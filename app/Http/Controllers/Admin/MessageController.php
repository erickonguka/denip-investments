<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isSuperAdmin = $user->roles()->where('name', 'super_admin')->exists();
        
        // Get all users as contacts
        $contacts = User::where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();
        
        // Get conversations
        if ($isSuperAdmin) {
            $conversations = Message::with(['sender', 'recipient', 'replies'])
                ->whereNull('parent_id')
                ->get()
                ->groupBy(function($message) {
                    // Handle anonymous messages (from landing page)
                    if (!$message->sender_id) {
                        return 'anonymous_' . $message->id;
                    }
                    $participants = collect([$message->sender_id, $message->recipient_id])->filter()->sort();
                    return $participants->implode('_');
                })
                ->map(function($group) {
                    return $group->sortByDesc('created_at')->first();
                })
                ->sortByDesc('created_at');
        } else {
            $conversations = Message::with(['sender', 'recipient', 'replies'])
                ->where(function($q) use ($user) {
                    $q->where('sender_id', $user->id)
                      ->orWhere('recipient_id', $user->id);
                })
                ->whereNull('parent_id')
                ->get()
                ->groupBy(function($message) use ($user) {
                    $otherUserId = $message->sender_id === $user->id ? $message->recipient_id : $message->sender_id;
                    return $otherUserId;
                })
                ->map(function($group) {
                    return $group->sortByDesc('created_at')->first();
                })
                ->sortByDesc('created_at');
        }
        
        $messages = new \Illuminate\Pagination\LengthAwarePaginator(
            $conversations->forPage(request()->get('page', 1), 15),
            $conversations->count(),
            15,
            request()->get('page', 1),
            ['path' => request()->url(), 'pageName' => 'page']
        );
        
        return view('admin.messages.index', compact('messages', 'contacts'));
    }
    
    public function startChat($userId)
    {
        $user = Auth::user();
        $recipient = User::findOrFail($userId);
        
        // Find existing conversation
        $existingMessage = Message::where(function($q) use ($user, $recipient) {
            $q->where('sender_id', $user->id)->where('recipient_id', $recipient->id);
        })->orWhere(function($q) use ($user, $recipient) {
            $q->where('sender_id', $recipient->id)->where('recipient_id', $user->id);
        })->whereNull('parent_id')->first();
        
        if ($existingMessage) {
            return redirect()->route('admin.messages.show', $existingMessage);
        }
        
        // Show new chat interface without creating message
        return view('admin.messages.new-chat', compact('recipient'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
            'document_ids' => 'nullable|array',
            'document_ids.*' => 'integer'
        ]);
        
        $validated['sender_id'] = Auth::id();
        
        // Handle file attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ];
            }
        }
        
        // Handle document attachments (exclude drafts)
        $documents = [];
        if ($request->document_ids) {
            $invoices = \App\Models\Invoice::whereIn('id', $request->document_ids)
                ->whereIn('status', ['sent', 'paid', 'overdue'])
                ->get();
            $proposals = \App\Models\Proposal::whereIn('id', $request->document_ids)
                ->whereIn('status', ['sent', 'accepted', 'rejected'])
                ->get();
            
            foreach ($invoices as $invoice) {
                $documents[] = [
                    'type' => 'invoice',
                    'id' => $invoice->id,
                    'name' => 'Invoice #' . $invoice->invoice_number,
                    'project' => $invoice->project ? $invoice->project->title : null
                ];
            }
            
            foreach ($proposals as $proposal) {
                $documents[] = [
                    'type' => 'proposal',
                    'id' => $proposal->id,
                    'name' => $proposal->title,
                    'project' => $proposal->project ? $proposal->project->title : null
                ];
            }
        }
        
        $validated['attachments'] = $attachments;
        $validated['documents'] = $documents;
        
        $message = Message::create($validated);
        
        // Send email notification
        $recipient = User::find($validated['recipient_id']);
        \Mail::send('emails.new-message', [
            'sender' => Auth::user(),
            'recipient' => $recipient,
            'messageData' => $message
        ], function ($mail) use ($recipient, $message) {
            $mail->to($recipient->email)
                 ->subject('New Message: ' . $message->subject);
        });
        
        return redirect()->route('admin.messages.index')
            ->with('success', 'Message sent successfully');
    }
    
    public function show(Message $message, Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->roles()->where('name', 'super_admin')->exists();
        
        if (!$isSuperAdmin && $message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            abort(403);
        }
        
        // Mark message and all replies as read if user is recipient
        if ($message->recipient_id === $user->id && !$message->read_at) {
            $message->update(['read_at' => now()]);
        }
        
        // Mark any unread replies as read
        $message->replies()->where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        // Handle AJAX request for message loading
        if ($request->ajax) {
            $offset = $request->get('offset', 0);
            $limit = $request->get('limit', 10);
            
            // Get all messages in conversation with reply relationships
            $allMessages = collect([$message->load('replyTo.sender')])->merge($message->replies()->with(['sender', 'recipient', 'replyTo.sender'])->get())
                ->sortByDesc('created_at')
                ->skip($offset)
                ->take($limit);
            
            $hasMore = $message->replies->count() > ($offset + $limit);
            
            $messagesHtml = '';
            foreach ($allMessages->reverse() as $msg) {
                $messagesHtml .= $this->renderMessage($msg, $user->id);
            }
            
            return response()->json([
                'messages' => $messagesHtml,
                'hasMore' => $hasMore
            ]);
        }
        
        $message->load(['sender', 'recipient', 'replies.sender', 'replies.recipient', 'replyTo.sender']);
        
        return view('admin.messages.show', compact('message'));
    }
    
    private function renderMessage($message, $currentUserId)
    {
        $isSent = $message->sender_id === $currentUserId;
        $flexDirection = $isSent ? 'flex-direction: row-reverse;' : '';
        $bgColor = $isSent ? 'background: var(--primary-blue); color: white;' : 'background: white; border: 1px solid #e9ecef;';
        $nameColor = $isSent ? 'color: rgba(255,255,255,0.9);' : '';
        
        $senderName = $message->sender ? $message->sender->name : ($message->sender_name ?? 'Anonymous');
        $avatar = ($message->sender && $message->sender->profile_photo) 
            ? '<img src="' . asset('storage/' . $message->sender->profile_photo) . '" alt="' . $senderName . '" style="width: 100%; height: 100%; object-fit: cover;">'
            : '<div style="width: 100%; height: 100%; background: var(--primary-blue); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1rem;">' . substr($senderName, 0, 1) . '</div>';
        
        $attachmentsHtml = '';
        if (($message->attachments && count($message->attachments) > 0) || ($message->documents && count($message->documents) > 0)) {
            $borderColor = $isSent ? 'rgba(255,255,255,0.2)' : '#e9ecef';
            $attachmentBg = $isSent ? 'rgba(255,255,255,0.2)' : '#f8f9fa';
            $attachmentsHtml = '<div style="margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid ' . $borderColor . ';"><div style="font-size: 0.8rem; margin-bottom: 0.5rem; opacity: 0.8;">Attachments:</div>';
            
            if ($message->attachments) {
                foreach ($message->attachments as $attachment) {
                    $attachmentsHtml .= '<a href="' . asset('storage/' . $attachment['path']) . '" download="' . $attachment['name'] . '" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.25rem 0.5rem; background: ' . $attachmentBg . '; border-radius: 4px; text-decoration: none; color: inherit; font-size: 0.8rem; margin-right: 0.5rem; margin-bottom: 0.25rem;"><i class="fas fa-paperclip"></i>' . $attachment['name'] . '</a>';
                }
            }
            
            if ($message->documents) {
                foreach ($message->documents as $document) {
                    $icon = $document['type'] === 'invoice' ? 'fas fa-file-invoice' : 'fas fa-file-contract';
                    $url = $document['type'] === 'invoice' ? route('documents.invoice.view', $document['id']) : route('documents.proposal.view', $document['id']);
                    $attachmentsHtml .= '<a href="' . $url . '" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.25rem 0.5rem; background: ' . $attachmentBg . '; border-radius: 4px; text-decoration: none; color: inherit; font-size: 0.8rem; margin-right: 0.5rem; margin-bottom: 0.25rem;"><i class="' . $icon . '"></i>' . $document['name'] . '</a>';
                }
            }
            
            $attachmentsHtml .= '</div>';
        }
        
        $readReceipt = '';
        if ($isSent) {
            $tickColor = $message->read_at ? '#007bff' : 'rgba(255,255,255,0.7)';
            $readReceipt = '<span style="margin-left: 0.5rem; font-size: 0.7rem; color: ' . $tickColor . ';"><i class="fas fa-check"></i><i class="fas fa-check" style="margin-left: -0.3rem;"></i></span>';
        }
        
        $replyButton = !$isSent ? '<button onclick="replyToMessage(' . $message->id . ')" style="background: none; border: none; color: #007bff; font-size: 0.7rem; cursor: pointer; margin-top: 0.5rem; opacity: 0.8;"><i class="fas fa-reply"></i> Reply</button>' : '';
        
        $replyIndicator = '';
        if ($message->reply_to_id) {
            $replyTo = \App\Models\Message::find($message->reply_to_id);
            if ($replyTo) {
                $replyBgColor = $isSent ? 'rgba(255,255,255,0.2)' : 'rgba(0,123,255,0.1)';
                $replyBorderColor = $isSent ? 'rgba(255,255,255,0.6)' : '#007bff';
                $replyTextColor = $isSent ? 'rgba(255,255,255,0.9)' : '#495057';
                $replyIconColor = $isSent ? 'rgba(255,255,255,0.8)' : '#007bff';
                $replyHoverBg = $isSent ? 'rgba(255,255,255,0.3)' : 'rgba(0,123,255,0.15)';
                $senderName = $replyTo->sender ? $replyTo->sender->name : 'Unknown';
                $adminBadge = $replyTo->sender && $replyTo->sender->isAdmin() ? '<span style="background: #f3e5f5; color: #7b1fa2; padding: 0.1rem 0.3rem; border-radius: 8px; font-size: 0.6rem; font-weight: 600; margin-right: 0.3rem;">ADMIN</span>' : '';
                $replyIndicator = '<div onclick="scrollToMessage(' . $message->reply_to_id . ')" style="margin-bottom: 0.5rem; padding: 0.5rem; background: ' . $replyBgColor . '; border-left: 3px solid ' . $replyBorderColor . '; border-radius: 4px; font-size: 0.7rem; cursor: pointer;" onmouseover="this.style.background=\"' . $replyHoverBg . '\"" onmouseout="this.style.background=\"' . $replyBgColor . '\""><i class="fas fa-reply" style="color: ' . $replyIconColor . '; margin-right: 0.5rem;"></i><span style="color: ' . $replyTextColor . ';">' . $adminBadge . '<strong>' . $senderName . ':</strong> ' . \Str::limit($replyTo->body, 60) . '</span></div>';
            }
        }
        
        return '<div style="display: flex; margin-bottom: 1rem; gap: 0.75rem; ' . $flexDirection . '" data-message-id="' . $message->id . '"><div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; flex-shrink: 0;">' . $avatar . '</div><div style="max-width: 70%; padding: 0.75rem 1rem; border-radius: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); ' . $bgColor . '"><div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;"><span style="font-weight: 600; font-size: 0.85rem; ' . $nameColor . '">' . $senderName . '</span><span style="font-size: 0.75rem; opacity: 0.7;">' . $message->created_at->format('M j, Y g:i A') . $readReceipt . '</span></div>' . $replyIndicator . '<div style="line-height: 1.5; white-space: pre-wrap;">' . $message->body . '</div>' . $attachmentsHtml . $replyButton . '</div></div>';
    }
    
    public function reply(Request $request, Message $message)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->roles()->where('name', 'super_admin')->exists();
        
        if (!$isSuperAdmin && $message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'body' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240',
            'document_ids' => 'nullable|array',
            'document_ids.*' => 'integer',
            'reply_to_id' => 'nullable|integer|exists:messages,id'
        ]);
        
        // Handle file attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getMimeType(),
                    'size' => $file->getSize()
                ];
            }
        }
        
        // Handle document attachments (exclude drafts)
        $documents = [];
        if ($request->document_ids) {
            $invoices = \App\Models\Invoice::whereIn('id', $request->document_ids)
                ->whereIn('status', ['sent', 'paid', 'overdue'])
                ->get();
            $proposals = \App\Models\Proposal::whereIn('id', $request->document_ids)
                ->whereIn('status', ['sent', 'accepted', 'rejected'])
                ->get();
            
            foreach ($invoices as $invoice) {
                $documents[] = [
                    'type' => 'invoice',
                    'id' => $invoice->id,
                    'name' => 'Invoice #' . $invoice->invoice_number,
                    'project' => $invoice->project ? $invoice->project->title : null
                ];
            }
            
            foreach ($proposals as $proposal) {
                $documents[] = [
                    'type' => 'proposal',
                    'id' => $proposal->id,
                    'name' => $proposal->title,
                    'project' => $proposal->project ? $proposal->project->title : null
                ];
            }
        }
        
        // Debug: Log the reply_to_id value
        \Log::info('Admin Reply Debug', [
            'reply_to_id' => $validated['reply_to_id'] ?? 'null',
            'request_data' => $request->all()
        ]);
        
        // Determine recipient
        $recipientId = null;
        $isAnonymousReply = false;
        
        if ($message->sender_id === $user->id) {
            $recipientId = $message->recipient_id;
        } elseif ($message->sender_id) {
            $recipientId = $message->sender_id;
        } else {
            // This is an anonymous message from landing page
            $isAnonymousReply = true;
            if (!$message->sender_email) {
                return back()->with('error', 'Cannot reply to this message - no email address provided.');
            }
        }
        
        if ($isAnonymousReply) {
            // For anonymous messages, just send email and create internal note
            $reply = Message::create([
                'sender_id' => $user->id,
                'recipient_id' => null,
                'subject' => 'Reply sent to: ' . $message->subject,
                'body' => 'Email reply sent to ' . $message->sender_email . ":\n\n" . $validated['body'],
                'parent_id' => $message->id,
                'attachments' => $attachments,
                'documents' => $documents,
                'reply_to_id' => $validated['reply_to_id'] ?? null
            ]);
        } else {
            $reply = Message::create([
                'sender_id' => $user->id,
                'recipient_id' => $recipientId,
                'subject' => 'Re: ' . $message->subject,
                'body' => $validated['body'],
                'parent_id' => $message->id,
                'attachments' => $attachments,
                'documents' => $documents,
                'reply_to_id' => $validated['reply_to_id'] ?? null
            ]);
        }
        
        // Send email notification
        if ($isAnonymousReply) {
            // Send email to original landing page sender
            \Mail::send('emails.landing-reply', [
                'adminName' => $user->name,
                'originalMessage' => $message,
                'replyMessage' => $reply,
                'companyName' => \App\Models\Setting::get('company_name', 'Denip Investments Ltd')
            ], function ($mail) use ($message, $reply) {
                $mail->to($message->sender_email, $message->sender_name)
                     ->subject('Re: ' . str_replace('Contact Form: ', '', str_replace('Quote Request: ', '', $message->subject)));
            });
        } elseif ($recipientId) {
            $recipient = User::find($reply->recipient_id);
            \Mail::send('emails.new-message', [
                'sender' => $user,
                'recipient' => $recipient,
                'messageData' => $reply
            ], function ($mail) use ($recipient, $reply) {
                $mail->to($recipient->email)
                     ->subject('New Reply: ' . $reply->subject);
            });
        }
        
        return back()->with('success', 'Reply sent successfully');
    }
    
    public function markAsRead(Message $message)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->roles()->where('name', 'super_admin')->exists();
        
        if (!$isSuperAdmin && $message->recipient_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $message->update(['read_at' => now()]);
        
        return response()->json(['success' => true]);
    }
    
    public function markAsUnread(Message $message)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->roles()->where('name', 'super_admin')->exists();
        
        if (!$isSuperAdmin && $message->recipient_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $message->update(['read_at' => null]);
        
        return response()->json(['success' => true]);
    }
    
    public function destroy(Message $message)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->roles()->where('name', 'super_admin')->exists();
        
        // Only allow deletion if user is super admin or involved in the message
        if (!$isSuperAdmin && $message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Delete all replies first to maintain referential integrity
        $message->replies()->delete();
        
        // Delete the main message
        $message->delete();
        
        return response()->json(['success' => true]);
    }
}
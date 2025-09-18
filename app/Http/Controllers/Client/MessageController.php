<?php

namespace App\Http\Controllers\Client;

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
        // Get conversation threads (group by participants)
        $conversations = Message::where(function($q) use ($user) {
                $q->where('recipient_id', $user->id)
                  ->orWhere('sender_id', $user->id);
            })
            ->whereNull('parent_id')
            ->with(['sender', 'recipient', 'replies'])
            ->get()
            ->groupBy(function($message) use ($user) {
                $otherUserId = $message->sender_id === $user->id ? $message->recipient_id : $message->sender_id;
                return $otherUserId;
            })
            ->map(function($group) {
                return $group->sortByDesc('created_at')->first();
            })
            ->sortByDesc('created_at');
            
        $messages = new \Illuminate\Pagination\LengthAwarePaginator(
            $conversations->forPage(request()->get('page', 1), 10),
            $conversations->count(),
            10,
            request()->get('page', 1),
            ['path' => request()->url(), 'pageName' => 'page']
        );
            
        return view('client.messages.index', compact('messages'));
    }
    
    public function create()
    {
        $staff = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['admin', 'super_admin']);
        })->get();
        
        return view('client.messages.create', compact('staff'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string'
        ]);
        
        $validated['sender_id'] = Auth::id();
        
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
        
        return redirect()->route('client.messages.index')
            ->with('success', 'Message sent successfully');
    }
    
    public function show(Message $message, Request $request)
    {
        $user = Auth::user();
        
        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
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
            
            // Get all messages in conversation
            $allMessages = collect([$message])->merge($message->replies)
                ->sortByDesc('created_at')
                ->skip($offset)
                ->take($limit);
            
            $hasMore = $message->replies->count() > ($offset + $limit);
            
            $messagesHtml = '';
            foreach ($allMessages->reverse() as $msg) {
                $messagesHtml .= $this->renderClientMessage($msg, $user->id);
            }
            
            return response()->json([
                'messages' => $messagesHtml,
                'hasMore' => $hasMore
            ]);
        }
        
        $message->load(['sender', 'recipient', 'replies.sender', 'replies.recipient']);
        
        return view('client.messages.show', compact('message'));
    }
    
    private function renderClientMessage($message, $currentUserId)
    {
        $isSent = $message->sender_id === $currentUserId;
        $bubbleClass = $isSent ? 'sent' : 'received';
        
        $avatar = $message->sender->profile_photo 
            ? '<img src="' . $message->sender->profile_photo_url . '" alt="' . $message->sender->name . '">'
            : '<div class="avatar-placeholder">' . substr($message->sender->name, 0, 1) . '</div>';
        
        $attachmentsHtml = '';
        if (($message->attachments && count($message->attachments) > 0) || ($message->documents && count($message->documents) > 0)) {
            $attachmentsHtml = '<div class="message-attachments"><div class="attachments-label">Attachments:</div>';
            
            if ($message->attachments) {
                foreach ($message->attachments as $attachment) {
                    $attachmentsHtml .= '<a href="' . asset('storage/' . $attachment['path']) . '" download="' . $attachment['name'] . '" class="attachment-link"><i class="fas fa-paperclip"></i>' . $attachment['name'] . '</a>';
                }
            }
            
            if ($message->documents) {
                foreach ($message->documents as $document) {
                    $icon = $document['type'] === 'invoice' ? 'fas fa-file-invoice' : 'fas fa-file-contract';
                    $url = $document['type'] === 'invoice' ? route('documents.invoice.view', $document['id']) : route('documents.proposal.view', $document['id']);
                    $attachmentsHtml .= '<a href="' . $url . '" target="_blank" class="attachment-link"><i class="' . $icon . '"></i>' . $document['name'] . '</a>';
                }
            }
            
            $attachmentsHtml .= '</div>';
        }
        
        $readReceipt = '';
        if ($isSent) {
            $tickColor = $message->read_at ? '#007bff' : '#6c757d';
            $readReceipt = '<span class="read-receipt" style="margin-left: 0.5rem; font-size: 0.8rem; color: ' . $tickColor . ';"><i class="fas fa-check"></i><i class="fas fa-check" style="margin-left: -0.3rem;"></i></span>';
        }
        
        $replyButton = !$isSent ? '<button onclick="replyToMessage(' . $message->id . ')" class="reply-btn" style="background: none; border: none; color: #007bff; font-size: 0.8rem; cursor: pointer; margin-top: 0.5rem;"><i class="fas fa-reply"></i> Reply</button>' : '';
        
        $replyIndicator = '';
        if ($message->reply_to_id) {
            $replyTo = \App\Models\Message::find($message->reply_to_id);
            if ($replyTo) {
                $adminBadge = $replyTo->sender && $replyTo->sender->isAdmin() ? '<span style="background: #f3e5f5; color: #7b1fa2; padding: 0.1rem 0.3rem; border-radius: 8px; font-size: 0.6rem; font-weight: 600; margin-right: 0.3rem;">ADMIN</span>' : '';
                $replyIndicator = '<div onclick="scrollToMessage(' . $message->reply_to_id . ')" style="margin-bottom: 0.5rem; padding: 0.5rem; background: rgba(0,123,255,0.1); border-left: 3px solid #007bff; border-radius: 4px; font-size: 0.75rem; cursor: pointer;" onmouseover="this.style.background=\"rgba(0,123,255,0.15)\"" onmouseout="this.style.background=\"rgba(0,123,255,0.1)\""><i class="fas fa-reply" style="color: #007bff; margin-right: 0.5rem;"></i>' . $adminBadge . '<strong>' . $replyTo->sender->name . ':</strong> ' . \Str::limit($replyTo->body, 60) . '</div>';
            }
        }
        
        $senderBadge = $message->sender && $message->sender->isAdmin() ? '<span style="background: #f3e5f5; color: #7b1fa2; padding: 0.1rem 0.3rem; border-radius: 8px; font-size: 0.6rem; font-weight: 600; margin-left: 0.3rem;">ADMIN</span>' : '';
        
        return '<div class="message-bubble ' . $bubbleClass . '" data-message-id="' . $message->id . '"><div class="message-avatar">' . $avatar . '</div><div class="message-content"><div class="message-header"><span class="sender-name">' . $message->sender->name . $senderBadge . '</span><span class="message-time">' . $message->created_at->format('M j, Y g:i A') . $readReceipt . '</span></div>' . $replyIndicator . '<div class="message-text">' . $message->body . '</div>' . $attachmentsHtml . $replyButton . '</div></div>';
    }
    
    public function reply(Request $request, Message $message)
    {
        $user = Auth::user();
        
        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            abort(403);
        }
        
        $validated = $request->validate([
            'body' => 'required|string',
            'reply_to_id' => 'nullable|integer|exists:messages,id'
        ]);
        
        $reply = Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $message->sender_id === $user->id ? $message->recipient_id : $message->sender_id,
            'subject' => 'Re: ' . $message->subject,
            'body' => $validated['body'],
            'parent_id' => $message->id,
            'reply_to_id' => $validated['reply_to_id'] ?? null
        ]);
        
        // Send email notification
        $recipient = User::find($reply->recipient_id);
        \Mail::send('emails.new-message', [
            'sender' => $user,
            'recipient' => $recipient,
            'messageData' => $reply
        ], function ($mail) use ($recipient, $reply) {
            $mail->to($recipient->email)
                 ->subject('New Reply: ' . $reply->subject);
        });
        
        return back()->with('success', 'Reply sent successfully');
    }
    
    public function unreadCount()
    {
        $user = Auth::user();
        $count = Message::where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->count();
            
        return response()->json(['count' => $count]);
    }
    
    public function notifications()
    {
        $user = Auth::user();
        $messages = Message::where('recipient_id', $user->id)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        $notifications = $messages->map(function($message) {
            return [
                'id' => $message->id,
                'content' => $message->sender->name . ': ' . \Str::limit($message->subject, 50),
                'time' => $message->created_at->diffForHumans(),
                'read_at' => $message->read_at
            ];
        });
        
        return response()->json(['notifications' => $notifications]);
    }
    
    public function clearNotifications()
    {
        $user = Auth::user();
        Message::where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return response()->json(['success' => true]);
    }
    
    public function markAsRead($id)
    {
        $user = Auth::user();
        $message = Message::where('id', $id)
            ->where('recipient_id', $user->id)
            ->first();
            
        if ($message && !$message->read_at) {
            $message->update(['read_at' => now()]);
        }
        
        return response()->json(['success' => true]);
    }
}
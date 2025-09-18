@extends('layouts.app')

@section('title', $message->subject)

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; height: calc(100vh - 120px); display: flex; flex-direction: column;">
        
        <!-- Header -->
        <div style="padding: 1.5rem; background: linear-gradient(135deg, var(--primary-blue), var(--deep-blue)); color: white; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden;">
                    @if($message->sender_id === auth()->id())
                        @if($message->recipient->profile_photo)
                            <img src="{{ asset('storage/' . $message->recipient->profile_photo) }}" alt="{{ $message->recipient->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: rgba(255,255,255,0.2); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1.2rem;">
                                {{ substr($message->recipient->name, 0, 1) }}
                            </div>
                        @endif
                    @else
                        @if($message->sender && $message->sender->profile_photo)
                            <img src="{{ asset('storage/' . $message->sender->profile_photo) }}" alt="{{ $message->sender->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: rgba(255,255,255,0.2); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1.2rem;">
                                {{ $message->sender ? substr($message->sender->name, 0, 1) : substr($message->sender_name ?? 'A', 0, 1) }}
                            </div>
                        @endif
                    @endif
                </div>
                <div>
                    <h3 style="margin: 0 0 0.25rem 0; font-size: 1.2rem;">{{ $message->subject }}</h3>
                    <p style="margin: 0; opacity: 0.8; font-size: 0.9rem;">
                        @if($message->sender_id === auth()->id())
                            Conversation with {{ $message->recipient->name }}
                        @else
                            Conversation with {{ $message->sender ? $message->sender->name : ($message->sender_name ?? 'Anonymous') }}
                            @if(!$message->sender)
                                <span style="background: rgba(255,255,255,0.2); padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.7rem; margin-left: 0.5rem;">Landing Page</span>
                            @endif
                        @endif
                    </p>
                </div>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('admin.messages.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none;">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button onclick="openDeleteModal({{ $message->id }}, 'message', '{{ addslashes($message->subject) }}', '{{ route('admin.messages.destroy', $message) }}')" style="background: rgba(220,53,69,0.8); color: white; padding: 0.5rem 1rem; border-radius: 6px; border: none; cursor: pointer;" title="Delete Message">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>

        <!-- Messages -->
        <div id="messagesContainer" style="flex: 1; padding: 1rem; overflow-y: auto; background: #f8f9fa;">
            <div id="loadMoreBtn" style="text-align: center; margin-bottom: 1rem; display: none;">
                <button onclick="loadMoreMessages()" style="background: var(--primary-blue); color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer;">
                    Load More Messages
                </button>
            </div>
            
            <div id="messagesList">
                <!-- Messages will be loaded here -->
            </div>
        </div>

        <!-- Reply Input -->
        <div style="padding: 1rem; background: white; border-top: 1px solid #e9ecef;">
            @if(!$message->sender_id && !$message->sender_email)
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; text-align: center; color: #6c757d;">
                    <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                    This message has no email address - cannot send reply.
                </div>
            @else
                @if(!$message->sender_id)
                    <div style="background: #e3f2fd; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem; font-size: 0.9rem; color: #1565c0;">
                        <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i>
                        Your reply will be sent via email to: <strong>{{ $message->sender_email }}</strong>
                    </div>
                @endif
            <form action="{{ route('admin.messages.reply', $message) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Attachment Options -->
                <div id="attachmentOptions" style="display: none; margin-bottom: 1rem; background: white; border: 1px solid #e9ecef; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; max-height: 400px; flex-direction: column;">
                    <div style="background: linear-gradient(135deg, var(--primary-blue), var(--deep-blue)); color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                        <h4 style="margin: 0; font-size: 1rem;"><i class="fas fa-paperclip" style="margin-right: 0.5rem;"></i>Attach Files & Documents</h4>
                        <button type="button" onclick="toggleAttachments()" style="background: rgba(255,255,255,0.2); border: none; color: white; cursor: pointer; padding: 0.5rem; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div style="padding: 1.5rem; overflow-y: auto; flex: 1;">
                        <div style="margin-bottom: 2rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                                <i class="fas fa-upload" style="color: var(--primary-blue);"></i>
                                <label style="font-weight: 600; color: var(--primary-blue); font-size: 0.9rem; margin: 0;">Upload Files</label>
                            </div>
                            <x-upload-dropbox 
                                name="attachments[]" 
                                accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.pptx,.txt,.csv,.xls,.xlsx" 
                                :multiple="true" 
                                maxSize="10"
                                text="Upload files or drag and drop here"
                            />
                            <small style="color: #6c757d; font-size: 0.75rem; margin-top: 0.5rem; display: block;">Max 10MB per file</small>
                        </div>
                        
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                                <i class="fas fa-folder" style="color: var(--primary-blue);"></i>
                                <label style="font-weight: 600; color: var(--primary-blue); font-size: 0.9rem; margin: 0;">Internal Documents</label>
                            </div>
                            <div style="border: 1px solid #e9ecef; border-radius: 8px; background: white;">
                            @php
                                $invoices = \App\Models\Invoice::whereIn('status', ['sent', 'paid', 'overdue'])->with('project')->orderBy('created_at', 'desc')->take(20)->get();
                                $proposals = \App\Models\Proposal::whereIn('status', ['sent', 'accepted', 'rejected'])->with('project')->orderBy('created_at', 'desc')->take(20)->get();
                            @endphp
                            
                                @if($invoices->count() > 0)
                                <div style="border-bottom: 1px solid #f0f0f0;">
                                    <div style="padding: 0.75rem; background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-file-invoice" style="color: #28a745; font-size: 0.9rem;"></i>
                                            <strong style="font-size: 0.85rem; color: #28a745;">Invoices ({{ $invoices->count() }})</strong>
                                        </div>
                                    </div>
                                    <div style="padding: 0.5rem;">
                                        @foreach($invoices as $invoice)
                                        <label style="display: flex; align-items: start; gap: 0.75rem; padding: 0.75rem; margin: 0.25rem; cursor: pointer; border-radius: 6px; transition: all 0.3s ease; border: 1px solid transparent;" onmouseover="this.style.background='#f8f9fa'; this.style.borderColor='#e9ecef'" onmouseout="this.style.background='transparent'; this.style.borderColor='transparent'">
                                            <input type="checkbox" name="document_ids[]" value="{{ $invoice->id }}" style="margin-top: 0.25rem;">
                                            <div style="flex: 1; min-width: 0;">
                                                <div style="font-weight: 600; font-size: 0.8rem; color: var(--primary-blue); margin-bottom: 0.25rem;">Invoice #{{ $invoice->invoice_number }}</div>
                                                @if($invoice->project)
                                                    <div style="color: #6c757d; font-size: 0.75rem; margin-bottom: 0.25rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $invoice->project->title }}</div>
                                                @endif
                                                <div style="color: #6c757d; font-size: 0.7rem;">{{ $invoice->created_at->format('M j, Y') }}</div>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if($proposals->count() > 0)
                                <div>
                                    <div style="padding: 0.75rem; background: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-file-contract" style="color: #007bff; font-size: 0.9rem;"></i>
                                            <strong style="font-size: 0.85rem; color: #007bff;">Proposals ({{ $proposals->count() }})</strong>
                                        </div>
                                    </div>
                                    <div style="padding: 0.5rem;">
                                        @foreach($proposals as $proposal)
                                        <label style="display: flex; align-items: start; gap: 0.75rem; padding: 0.75rem; margin: 0.25rem; cursor: pointer; border-radius: 6px; transition: all 0.3s ease; border: 1px solid transparent;" onmouseover="this.style.background='#f8f9fa'; this.style.borderColor='#e9ecef'" onmouseout="this.style.background='transparent'; this.style.borderColor='transparent'">
                                            <input type="checkbox" name="document_ids[]" value="{{ $proposal->id }}" style="margin-top: 0.25rem;">
                                            <div style="flex: 1; min-width: 0;">
                                                <div style="font-weight: 600; font-size: 0.8rem; color: var(--primary-blue); margin-bottom: 0.25rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $proposal->title }}</div>
                                                @if($proposal->project)
                                                    <div style="color: #6c757d; font-size: 0.75rem; margin-bottom: 0.25rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $proposal->project->title }}</div>
                                                @endif
                                                <div style="color: #6c757d; font-size: 0.7rem;">{{ $proposal->created_at->format('M j, Y') }}</div>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Attachment Preview -->
                <div id="attachmentPreview" style="display: none; margin-bottom: 1rem; padding: 0.75rem; background: #f8f9fa; border-radius: 8px; border: 1px solid #e9ecef;">
                    <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 0.5rem;">
                        <small style="font-weight: 600; color: var(--primary-blue);">Attachments:</small>
                        <button type="button" onclick="clearAttachments()" style="background: none; border: none; color: #6c757d; cursor: pointer; font-size: 0.8rem;">Clear All</button>
                    </div>
                    <div id="attachmentList" style="display: flex; flex-wrap: wrap; gap: 0.5rem;"></div>
                </div>
                
                <!-- Reply Preview -->
                <div id="replyPreview" style="display: none; margin-bottom: 1rem; padding: 0.75rem; background: #f8f9fa; border-left: 3px solid var(--primary-blue); border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                        <small style="color: var(--primary-blue); font-weight: 600;">Replying to:</small>
                        <button type="button" onclick="cancelReply()" style="background: none; border: none; color: #6c757d; cursor: pointer; padding: 0;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="replyPreviewContent" style="font-size: 0.85rem; color: #6c757d; max-height: 60px; overflow: hidden;"></div>
                </div>
                
                <div style="display: flex; gap: 0.75rem; align-items: flex-end;">
                    <textarea name="body" placeholder="Type your reply..." rows="3" required style="flex: 1; padding: 0.75rem; border: 1px solid #e9ecef; border-radius: 20px; resize: none; font-family: inherit; font-size: 0.9rem; outline: none;" onfocus="this.style.borderColor='var(--primary-blue)'" onblur="this.style.borderColor='#e9ecef'"></textarea>
                    <button type="button" onclick="toggleAttachments()" style="width: 45px; height: 45px; border-radius: 50%; background: #6c757d; color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; margin-right: 0.5rem;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fas fa-paperclip"></i>
                    </button>
                    <button type="submit" style="width: 45px; height: 45px; border-radius: 50%; background: var(--primary-blue); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
            @endif
        </div>
        
        <script>
        function toggleAttachments() {
            const options = document.getElementById('attachmentOptions');
            if (options.style.display === 'none' || options.style.display === '') {
                options.style.display = 'flex';
                // Re-initialize upload components when shown
                setTimeout(() => {
                    document.querySelectorAll('.upload-dropbox:not([data-initialized])').forEach(function(dropbox) {
                        dropbox.setAttribute('data-initialized', 'true');
                        // Initialize upload functionality here if needed
                    });
                }, 100);
            } else {
                options.style.display = 'none';
            }
        }
        
        function updateAttachmentPreview() {
            const fileInput = document.querySelector('input[name="attachments[]"]');
            const documentCheckboxes = document.querySelectorAll('input[name="document_ids[]"]:checked');
            const preview = document.getElementById('attachmentPreview');
            const list = document.getElementById('attachmentList');
            
            let hasAttachments = false;
            list.innerHTML = '';
            
            // Show selected files
            if (fileInput && fileInput.files.length > 0) {
                Array.from(fileInput.files).forEach(file => {
                    const item = document.createElement('div');
                    item.style.cssText = 'display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: white; border: 1px solid #e9ecef; border-radius: 4px; font-size: 0.8rem;';
                    item.innerHTML = `<i class="fas fa-file"></i> ${file.name}`;
                    list.appendChild(item);
                    hasAttachments = true;
                });
            }
            
            // Show selected documents
            documentCheckboxes.forEach(checkbox => {
                const label = checkbox.closest('label');
                const text = label.textContent.trim();
                const item = document.createElement('div');
                item.style.cssText = 'display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem; background: white; border: 1px solid #e9ecef; border-radius: 4px; font-size: 0.8rem;';
                item.innerHTML = `<i class="fas fa-file-alt"></i> ${text}`;
                list.appendChild(item);
                hasAttachments = true;
            });
            
            preview.style.display = hasAttachments ? 'block' : 'none';
        }
        
        function clearAttachments() {
            // Clear file input
            const fileInput = document.querySelector('input[name="attachments[]"]');
            if (fileInput) fileInput.value = '';
            
            // Uncheck document checkboxes
            document.querySelectorAll('input[name="document_ids[]"]:checked').forEach(cb => cb.checked = false);
            
            // Hide preview
            document.getElementById('attachmentPreview').style.display = 'none';
        }
        
        // Monitor changes to update preview
        document.addEventListener('change', function(e) {
            if (e.target.name === 'attachments[]' || e.target.name === 'document_ids[]') {
                updateAttachmentPreview();
            }
        });
        
        // Message loading functionality
        let currentOffset = 0;
        let loadCount = 10;
        
        async function loadMessages(offset = 0, limit = 10) {
            try {
                const response = await fetch(`{{ route('admin.messages.show', $message) }}?offset=${offset}&limit=${limit}&ajax=1`);
                const data = await response.json();
                
                if (data.messages) {
                    const messagesList = document.getElementById('messagesList');
                    const loadMoreBtn = document.getElementById('loadMoreBtn');
                    
                    if (offset === 0) {
                        messagesList.innerHTML = data.messages;
                        scrollToBottom();
                    } else {
                        messagesList.insertAdjacentHTML('afterbegin', data.messages);
                    }
                    
                    loadMoreBtn.style.display = data.hasMore ? 'block' : 'none';
                }
            } catch (error) {
                console.error('Failed to load messages:', error);
            }
        }
        
        async function loadMoreMessages() {
            currentOffset += loadCount;
            loadCount += 10; // Increase by 10 each time
            await loadMessages(currentOffset, loadCount);
        }
        
        function scrollToBottom() {
            const container = document.getElementById('messagesContainer');
            container.scrollTop = container.scrollHeight;
        }
        
        // Load initial messages
        document.addEventListener('DOMContentLoaded', function() {
            loadMessages(0, 10);
        });
        
        // Reply to specific message
        function replyToMessage(messageId) {
            console.log('Replying to message ID:', messageId);
            const messageBubble = document.querySelector(`[data-message-id="${messageId}"]`);
            const messageText = messageBubble.querySelector('div[style*="line-height: 1.5"]').textContent;
            const senderName = messageBubble.querySelector('span[style*="font-weight: 600"]').textContent;
            
            // Show reply preview
            const preview = document.getElementById('replyPreview');
            const content = document.getElementById('replyPreviewContent');
            content.innerHTML = `<strong>${senderName}:</strong> ${messageText.substring(0, 100)}${messageText.length > 100 ? '...' : ''}`;
            preview.style.display = 'block';
            
            const textarea = document.querySelector('textarea[name="body"]');
            textarea.focus();
            textarea.placeholder = 'Type your reply...';
            
            // Add hidden input for reply_to_id
            let replyInput = document.querySelector('input[name="reply_to_id"]');
            if (!replyInput) {
                replyInput = document.createElement('input');
                replyInput.type = 'hidden';
                replyInput.name = 'reply_to_id';
                document.querySelector('form').appendChild(replyInput);
            }
            replyInput.value = messageId;
            console.log('Set reply_to_id to:', messageId);
        }
        
        function cancelReply() {
            document.getElementById('replyPreview').style.display = 'none';
            const replyInput = document.querySelector('input[name="reply_to_id"]');
            if (replyInput) replyInput.remove();
            document.querySelector('textarea[name="body"]').placeholder = 'Type your reply...';
        }
        
        function scrollToMessage(messageId) {
            const message = document.querySelector(`[data-message-id="${messageId}"]`);
            if (message) {
                message.scrollIntoView({ behavior: 'smooth', block: 'center' });
                message.style.background = 'rgba(0,123,255,0.1)';
                setTimeout(() => { message.style.background = ''; }, 2000);
            }
        }
        
        // Scroll to bottom after form submission
        document.querySelector('form').addEventListener('submit', function() {
            setTimeout(() => {
                loadMessages(0, 10);
            }, 1000);
        });
        

        </script>
        
        <style>
        .documents-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .documents-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .documents-scroll::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        .documents-scroll::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        </script>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .chat-container {
        height: calc(100vh - 150px);
        margin: 0;
        border-radius: 0;
    }
    
    .chat-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
        padding: 1rem;
    }
    
    .message-content {
        max-width: 85%;
    }
    
    .input-group {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .input-group textarea {
        border-radius: 8px;
    }
    
    .send-btn {
        align-self: flex-end;
        width: auto;
        height: auto;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
    }
}
</style>
<x-delete-modal />

@endsection
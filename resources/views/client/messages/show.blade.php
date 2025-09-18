@extends('layouts.client')

@section('title', $message->subject . ' - Denip Investments Ltd')
@section('page-title', 'Message')

@section('content')
<div class="chat-container">
    <div class="chat-header">
        <div class="chat-info">
            <div class="chat-avatar">
                @if($message->sender_id === auth()->id())
                    @if($message->recipient->profile_photo)
                        <img src="{{ $message->recipient->profile_photo_url }}" alt="{{ $message->recipient->name }}">
                    @else
                        <div class="avatar-placeholder">{{ substr($message->recipient->name, 0, 1) }}</div>
                    @endif
                @else
                    @if($message->sender->profile_photo)
                        <img src="{{ $message->sender->profile_photo_url }}" alt="{{ $message->sender->name }}">
                    @else
                        <div class="avatar-placeholder">{{ substr($message->sender->name, 0, 1) }}</div>
                    @endif
                @endif
            </div>
            <div class="chat-details">
                <h3>{{ $message->subject }}</h3>
                <p>
                    @if($message->sender_id === auth()->id())
                        Conversation with {{ $message->recipient->name }}
                    @else
                        Conversation with {{ $message->sender->name }}
                    @endif
                </p>
            </div>
        </div>
        <a href="{{ route('client.messages.index') }}" class="btn btn-outline" style="color: white; border-color: white;">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="chat-messages" id="messagesContainer">
        <div id="loadMoreBtn" style="text-align: center; margin-bottom: 1rem; display: none;">
            <button onclick="loadMoreMessages()" class="btn btn-outline">
                Load More Messages
            </button>
        </div>
        
        <div id="messagesList">
            <!-- Messages will be loaded here -->
        </div>
    </div>

    <div class="chat-input">
        <!-- Reply Preview -->
        <div id="replyPreview" style="display: none; margin-bottom: 1rem; padding: 0.75rem; background: #f8f9fa; border-left: 3px solid var(--secondary); border-radius: 8px;">
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                <small style="color: var(--secondary); font-weight: 600;">Replying to:</small>
                <button type="button" onclick="cancelReply()" style="background: none; border: none; color: #6c757d; cursor: pointer; padding: 0;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="replyPreviewContent" style="font-size: 0.85rem; color: #6c757d; max-height: 60px; overflow: hidden;"></div>
        </div>
        
        <form action="{{ route('client.messages.reply', $message) }}" method="POST">
            @csrf
            <div class="input-group">
                <textarea name="body" placeholder="Type your reply..." rows="3" required></textarea>
                <button type="submit" class="send-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.chat-container {
    max-width: 1200px;
    margin: 0 auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    overflow: hidden;
    height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}

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
    }
    
    .message-content {
        max-width: 85%;
    }
    
    .chat-input {
        padding: 0.75rem;
        width: 100%;
        box-sizing: border-box;
    }
    
    .input-group {
        flex-direction: column;
        gap: 0.75rem;
        align-items: stretch;
        width: 100%;
    }
    
    .input-group textarea {
        border-radius: 12px;
        padding: 1rem;
        min-height: 80px;
        font-size: 1rem;
        width: 100%;
        box-sizing: border-box;
        resize: vertical;
    }
    
    .send-btn {
        align-self: center;
        width: 100%;
        height: 48px;
        border-radius: 12px;
        padding: 0;
        font-size: 1rem;
    }
}

.chat-header {
    padding: 1.5rem;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.chat-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
}

.chat-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
}

.chat-details h3 {
    margin: 0 0 0.25rem 0;
    font-size: 1.2rem;
}

.chat-details p {
    margin: 0;
    opacity: 0.8;
    font-size: 0.9rem;
}

.chat-messages {
    flex: 1;
    padding: 1rem;
    overflow-y: auto;
    background: #f8f9fa;
}

.message-bubble {
    display: flex;
    margin-bottom: 1rem;
    gap: 0.75rem;
}

.message-bubble.sent {
    flex-direction: row-reverse;
}

.message-bubble.sent .message-content {
    background: var(--secondary);
    color: white;
}

.message-bubble.received .message-content {
    background: white;
    border: 1px solid #e9ecef;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.message-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.message-avatar .avatar-placeholder {
    width: 40px;
    height: 40px;
    background: var(--primary);
    color: white;
    font-size: 1rem;
}

.message-content {
    max-width: 70%;
    padding: 0.75rem 1rem;
    border-radius: 18px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.sender-name {
    font-weight: 600;
    font-size: 0.85rem;
}

.message-bubble.sent .sender-name {
    color: rgba(255,255,255,0.9);
}

.message-time {
    font-size: 0.75rem;
    opacity: 0.7;
}

.message-text {
    line-height: 1.5;
    white-space: pre-wrap;
}

.chat-input {
    padding: 1rem;
    background: white;
    border-top: 1px solid #e9ecef;
}

.input-group {
    display: flex;
    gap: 0.75rem;
    align-items: flex-end;
}

.input-group textarea {
    flex: 1;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
    border-radius: 20px;
    resize: vertical;
    font-family: inherit;
    font-size: 0.9rem;
    outline: none;
    min-height: 45px;
    max-height: 120px;
}

.input-group textarea:focus {
    border-color: var(--secondary);
    box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1);
}

.send-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: var(--secondary);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.send-btn:hover {
    background: var(--dark);
    transform: scale(1.05);
}

.message-attachments {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(0,0,0,0.1);
}

.message-bubble.sent .message-attachments {
    border-top-color: rgba(255,255,255,0.2);
}

.attachments-label {
    font-size: 0.8rem;
    margin-bottom: 0.5rem;
    opacity: 0.8;
    font-weight: 600;
}

.attachment-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.25rem 0.5rem;
    background: rgba(0,0,0,0.1);
    border-radius: 4px;
    text-decoration: none;
    color: inherit;
    font-size: 0.8rem;
    margin-right: 0.5rem;
    margin-bottom: 0.25rem;
    transition: background 0.3s ease;
}

.message-bubble.sent .attachment-link {
    background: rgba(255,255,255,0.2);
    color: white;
}

.attachment-link:hover {
    background: rgba(0,0,0,0.2);
}

.message-bubble.sent .attachment-link:hover {
    background: rgba(255,255,255,0.3);
}

.attachment-link small {
    opacity: 0.7;
}

@media (max-width: 768px) {
    .chat-container {
        height: calc(100vh - 150px);
        margin: 0;
        border-radius: 0;
    }
    
    .message-content {
        max-width: 85%;
    }
    
    .chat-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Message loading functionality
let currentOffset = 0;
let loadCount = 10;

async function loadMessages(offset = 0, limit = 10) {
    try {
        const response = await fetch(`{{ route('client.messages.show', $message) }}?offset=${offset}&limit=${limit}&ajax=1`);
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
    const messageBubble = document.querySelector(`[data-message-id="${messageId}"]`);
    const messageText = messageBubble.querySelector('.message-text').textContent;
    const senderName = messageBubble.querySelector('.sender-name').textContent;
    
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
@endpush
@endsection
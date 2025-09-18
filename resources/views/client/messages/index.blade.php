@extends('layouts.client')

@section('title', 'Messages - Denip Investments Ltd')
@section('page-title', 'Messages')

@section('content')
<div class="dashboard-header">
    <h1>Messages</h1>
    <p>Communicate with our team</p>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2>Conversations</h2>
        <a href="{{ route('client.messages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Message
        </a>
    </div>
    
    @forelse($messages as $message)
    <div class="message-item {{ $message->recipient_id === auth()->id() && !$message->read_at ? 'unread' : '' }}" onclick="window.location.href='{{ route('client.messages.show', $message) }}'" style="cursor: pointer;">
        <div class="message-avatar">
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
        
        <div class="message-content">
            <div class="message-header">
                <h4><a href="{{ route('client.messages.show', $message) }}" style="color: var(--primary); text-decoration: none;">{{ $message->subject }}</a></h4>
                <span class="message-time">{{ $message->created_at->diffForHumans() }}</span>
            </div>
            <div class="message-participants">
                @if($message->sender_id === auth()->id())
                    To: {{ $message->recipient->name }}
                @else
                    From: {{ $message->sender->name }}
                @endif
                @php
                    $totalMessages = 1 + $message->replies->count();
                    $lastMessage = $message->replies->last() ?? $message;
                @endphp
                <span class="reply-count">{{ $totalMessages }} message{{ $totalMessages > 1 ? 's' : '' }}</span>
                @if($lastMessage->created_at != $message->created_at)
                    <span style="margin-left: 0.5rem; font-size: 0.8rem; opacity: 0.7;">Last: {{ $lastMessage->created_at->diffForHumans() }}</span>
                @endif
            </div>
        </div>
        
        <div class="message-actions">
            <a href="{{ route('client.messages.show', $message) }}" class="btn btn-sm btn-outline">
                <i class="fas fa-eye"></i> View
            </a>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-comments"></i>
        <h3>No messages yet</h3>
        <p>Start a conversation with our team</p>
        <a href="{{ route('client.messages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Send Message
        </a>
    </div>
    @endforelse
    
    @if($messages->hasPages())
        <div class="pagination-wrapper">
            {{ $messages->links() }}
        </div>
    @endif
</div>

@push('styles')
<style>
.message-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
    border-left: 4px solid transparent;
}

.message-item:hover {
    transform: translateY(-2px);
}

.message-item.unread {
    border-left-color: var(--secondary);
    background: #fffbf0;
}

.message-avatar {
    flex-shrink: 0;
}

.avatar-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
}

.message-avatar img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.message-content {
    flex: 1;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.message-header h4 {
    margin: 0;
    color: var(--primary);
    font-size: 1.1rem;
}

.message-time {
    font-size: 0.85rem;
    color: var(--dark);
    opacity: 0.7;
}

.message-participants {
    font-size: 0.9rem;
    color: var(--dark);
    opacity: 0.8;
}

.reply-count {
    margin-left: 1rem;
    background: var(--light);
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.8rem;
}

.message-actions {
    flex-shrink: 0;
    display: flex;
    align-items: center;
}

@media (max-width: 768px) {
    .message-item {
        padding: 1rem;
        cursor: pointer;
    }
    
    .message-item:hover {
        background: #f8f9fa;
    }
    
    .message-actions {
        display: none;
    }
    
    .message-header {
        flex-direction: row;
        justify-content: space-between;
    }
    
    .message-participants {
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    
    .reply-count {
        margin-left: 0;
        margin-top: 0.25rem;
        display: inline-block;
    }
}
</style>
@endpush
@endsection
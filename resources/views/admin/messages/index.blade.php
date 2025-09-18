@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: var(--primary-blue); font-size: 2rem; font-weight: 700;">Messages</h1>
        <div style="position: relative;">
            <input type="text" id="contactSearch" placeholder="Search contacts..." style="padding: 0.75rem; border: 1px solid #e9ecef; border-radius: 8px; width: 300px;" oninput="filterContacts()">
            <div id="contactDropdown" style="display: none; position: absolute; top: 100%; right: 0; background: white; border: 1px solid #e9ecef; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); max-height: 300px; overflow-y: auto; z-index: 1000; width: 300px;">
                @foreach($contacts as $contact)
                <div class="contact-item" data-name="{{ strtolower($contact->name) }}" onclick="startChat({{ $contact->id }})" style="padding: 0.75rem; border-bottom: 1px solid #f0f0f0; cursor: pointer; display: flex; align-items: center; gap: 0.75rem;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                    <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;">
                        @if($contact->profile_photo)
                            <img src="{{ asset('storage/' . $contact->profile_photo) }}" alt="{{ $contact->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: var(--primary-blue); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem;">
                                {{ substr($contact->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div style="font-weight: 600; color: var(--primary-blue);">{{ $contact->name }}</div>
                        <div style="font-size: 0.8rem; color: #6c757d;">{{ $contact->email }}</div>
                        @if($contact->roles()->where('name', 'client')->exists())
                            <span style="background: var(--warning); color: white; padding: 0.125rem 0.375rem; border-radius: 8px; font-size: 0.7rem;">Client</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden;">
        @forelse($messages as $message)
        <div style="display: flex; align-items: center; gap: 1rem; padding: 1.5rem; border-bottom: 1px solid #f0f0f0; transition: background 0.3s ease;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
            <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; flex-shrink: 0;">
                @if($message->sender_id === auth()->id())
                    @if($message->recipient->profile_photo)
                        <img src="{{ asset('storage/' . $message->recipient->profile_photo) }}" alt="{{ $message->recipient->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; background: var(--primary-blue); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            {{ substr($message->recipient->name, 0, 1) }}
                        </div>
                    @endif
                @else
                    @if($message->sender && $message->sender->profile_photo)
                        <img src="{{ asset('storage/' . $message->sender->profile_photo) }}" alt="{{ $message->sender->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; background: var(--primary-blue); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                            {{ $message->sender ? substr($message->sender->name, 0, 1) : substr($message->sender_name ?? 'A', 0, 1) }}
                        </div>
                    @endif
                @endif
            </div>
            
            <div style="flex: 1;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <h4 style="margin: 0; color: var(--primary-blue); font-size: 1.1rem;"><a href="{{ route('admin.messages.show', $message) }}" style="color: var(--primary-blue); text-decoration: none; cursor: pointer;">{{ $message->subject }}</a></h4>
                    <span style="font-size: 0.85rem; color: #6c757d;">{{ $message->created_at->diffForHumans() }}</span>
                </div>
                <div style="font-size: 0.9rem; color: #6c757d; margin-bottom: 0.5rem;">
                    @if($message->sender_id === auth()->id())
                        To: {{ $message->recipient->name }}
                        @if($message->recipient->roles()->where('name', 'client')->exists())
                            <span style="background: var(--warning); color: white; padding: 0.125rem 0.375rem; border-radius: 8px; font-size: 0.7rem; margin-left: 0.5rem;">Client</span>
                        @endif
                    @else
                        From: {{ $message->sender ? $message->sender->name : ($message->sender_name ?? 'Anonymous') }}
                        @if($message->sender && $message->sender->roles()->where('name', 'client')->exists())
                            <span style="background: var(--warning); color: white; padding: 0.125rem 0.375rem; border-radius: 8px; font-size: 0.7rem; margin-left: 0.5rem;">Client</span>
                        @elseif(!$message->sender)
                            <span style="background: #6c757d; color: white; padding: 0.125rem 0.375rem; border-radius: 8px; font-size: 0.7rem; margin-left: 0.5rem;">Landing Page</span>
                        @endif
                    @endif
                    @php
                        $totalMessages = 1 + $message->replies->count();
                        $lastMessage = $message->replies->last() ?? $message;
                    @endphp
                    <span style="margin-left: 1rem; background: #e9ecef; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem;">{{ $totalMessages }} message{{ $totalMessages > 1 ? 's' : '' }}</span>
                    @if($lastMessage->created_at != $message->created_at)
                        <span style="margin-left: 0.5rem; font-size: 0.8rem; opacity: 0.7;">Last: {{ $lastMessage->created_at->diffForHumans() }}</span>
                    @endif
                </div>
                @if($message->recipient_id === auth()->id() && !$message->read_at)
                    <span style="background: var(--warning); color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">UNREAD</span>
                @endif
            </div>
            
            <div style="display: flex; gap: 0.5rem;">
                @if($message->recipient_id === auth()->id() && !$message->read_at)
                    <button onclick="markAsRead({{ $message->id }})" style="background: #28a745; color: white; padding: 0.5rem; border-radius: 6px; border: none; cursor: pointer; font-size: 0.9rem;" title="Mark as read">
                        <i class="fas fa-check"></i>
                    </button>
                @elseif($message->recipient_id === auth()->id() && $message->read_at)
                    <button onclick="markAsUnread({{ $message->id }})" style="background: #6c757d; color: white; padding: 0.5rem; border-radius: 6px; border: none; cursor: pointer; font-size: 0.9rem;" title="Mark as unread">
                        <i class="fas fa-envelope"></i>
                    </button>
                @endif
                <button onclick="openDeleteModal({{ $message->id }}, 'message', '{{ addslashes($message->subject) }}', '{{ route('admin.messages.destroy', $message) }}')" style="background: #dc3545; color: white; padding: 0.5rem; border-radius: 6px; border: none; cursor: pointer; font-size: 0.9rem;" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 3rem; color: #6c757d;">
            <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3>No messages yet</h3>
            <p>Start a conversation with team members or clients</p>
            <button onclick="document.getElementById('contactSearch').focus(); document.getElementById('contactDropdown').style.display='block';" style="background: var(--primary-blue); color: white; padding: 0.75rem 1.5rem; border-radius: 8px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; margin-top: 1rem;">
                <i class="fas fa-plus"></i> Start Conversation
            </button>
        </div>
        @endforelse
    </div>
    
    @if($messages->hasPages())
        <div style="margin-top: 2rem; display: flex; justify-content: center;">
            {{ $messages->links() }}
        </div>
    @endif
</div>

<style>
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
}
</style>

<script>
function filterContacts() {
    const search = document.getElementById('contactSearch').value.toLowerCase();
    const dropdown = document.getElementById('contactDropdown');
    const contacts = dropdown.querySelectorAll('.contact-item');
    
    if (search.length > 0) {
        dropdown.style.display = 'block';
        contacts.forEach(contact => {
            const name = contact.getAttribute('data-name');
            if (name.includes(search)) {
                contact.style.display = 'flex';
            } else {
                contact.style.display = 'none';
            }
        });
    } else {
        dropdown.style.display = 'none';
    }
}

function startChat(userId) {
    window.location.href = `/admin/messages/chat/${userId}`;
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('#contactSearch') && !e.target.closest('#contactDropdown')) {
        document.getElementById('contactDropdown').style.display = 'none';
    }
});

// Show dropdown when focusing search
document.getElementById('contactSearch').addEventListener('focus', function() {
    if (this.value.length > 0) {
        document.getElementById('contactDropdown').style.display = 'block';
    }
});

// Message actions
async function markAsRead(messageId) {
    try {
        const response = await fetch(`/admin/messages/${messageId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        if (response.ok) location.reload();
    } catch (error) {
        console.error('Failed to mark as read:', error);
    }
}

async function markAsUnread(messageId) {
    try {
        const response = await fetch(`/admin/messages/${messageId}/mark-unread`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        if (response.ok) location.reload();
    } catch (error) {
        console.error('Failed to mark as unread:', error);
    }
}


</script>
<x-delete-modal />

@endsection
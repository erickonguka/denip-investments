@extends('layouts.app')

@section('title', 'New Chat with ' . $recipient->name)

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; height: calc(100vh - 200px); display: flex; flex-direction: column;">
        
        <!-- Header -->
        <div style="padding: 1.5rem; background: linear-gradient(135deg, var(--primary-blue), var(--deep-blue)); color: white; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden;">
                    @if($recipient->profile_photo)
                        <img src="{{ asset('storage/' . $recipient->profile_photo) }}" alt="{{ $recipient->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 100%; background: rgba(255,255,255,0.2); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1.2rem;">
                            {{ substr($recipient->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h3 style="margin: 0 0 0.25rem 0; font-size: 1.2rem;">{{ $recipient->name }}</h3>
                    <p style="margin: 0; opacity: 0.8; font-size: 0.9rem;">
                        Start a new conversation
                        @if($recipient->roles()->where('name', 'client')->exists())
                            <span style="background: rgba(255,255,255,0.2); padding: 0.125rem 0.375rem; border-radius: 8px; font-size: 0.7rem; margin-left: 0.5rem;">Client</span>
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.messages.index') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <!-- Empty Messages Area -->
        <div style="flex: 1; padding: 2rem; overflow-y: auto; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
            <div style="text-align: center; color: #6c757d;">
                <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                <h3>Start your conversation</h3>
                <p>Send your first message to {{ $recipient->name }}</p>
            </div>
        </div>

        <!-- Message Input -->
        <div style="padding: 1rem; background: white; border-top: 1px solid #e9ecef;">
            <form action="{{ route('admin.messages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="recipient_id" value="{{ $recipient->id }}">
                <input type="hidden" name="subject" value="Conversation with {{ $recipient->name }}">
                
                <!-- Attachment Options -->
                <div id="attachmentOptions" style="display: none; margin-bottom: 1rem; background: white; border: 1px solid #e9ecef; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; max-height: 400px; flex-direction: column;">
                    <div style="background: linear-gradient(135deg, var(--primary-blue), var(--deep-blue)); color: white; padding: 1rem; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                        <h4 style="margin: 0; font-size: 1rem;"><i class="fas fa-paperclip" style="margin-right: 0.5rem;"></i>Attach Files & Documents</h4>
                        <button type="button" onclick="toggleAttachments()" style="background: rgba(255,255,255,0.2); border: none; color: white; cursor: pointer; padding: 0.5rem; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div style="padding: 1.5rem; overflow-y: auto; flex: 1;">
                        <!-- File Upload Section -->
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
                        
                        <!-- Internal Documents Section -->
                        <div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                                <i class="fas fa-folder" style="color: var(--primary-blue);"></i>
                                <label style="font-weight: 600; color: var(--primary-blue); font-size: 0.9rem; margin: 0;">Internal Documents</label>
                            </div>
                            <div style="border: 1px solid #e9ecef; border-radius: 8px; background: white;">
                                @php
                                    if($recipient->roles()->where('name', 'client')->exists()) {
                                        $clientId = $recipient->client ? $recipient->client->id : null;
                                        $invoices = $clientId ? \App\Models\Invoice::where('client_id', $clientId)->whereIn('status', ['sent', 'paid', 'overdue'])->with('project')->orderBy('created_at', 'desc')->take(20)->get() : collect();
                                        $proposals = $clientId ? \App\Models\Proposal::where('client_id', $clientId)->whereIn('status', ['sent', 'accepted', 'rejected'])->with('project')->orderBy('created_at', 'desc')->take(20)->get() : collect();
                                    } else {
                                        $invoices = \App\Models\Invoice::whereIn('status', ['sent', 'paid', 'overdue'])->with('project')->orderBy('created_at', 'desc')->take(20)->get();
                                        $proposals = \App\Models\Proposal::whereIn('status', ['sent', 'accepted', 'rejected'])->with('project')->orderBy('created_at', 'desc')->take(20)->get();
                                    }
                                @endphp
                                
                                @if($invoices->isEmpty() && $proposals->isEmpty())
                                    <div style="text-align: center; padding: 2rem; color: #6c757d;">
                                        <i class="fas fa-file-alt" style="font-size: 2rem; opacity: 0.3; margin-bottom: 0.5rem; display: block;"></i>
                                        <div style="font-size: 0.9rem;">
                                            @if($recipient->roles()->where('name', 'client')->exists())
                                                No documents for this client
                                            @else
                                                No documents available
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
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
                
                <div style="display: flex; gap: 0.75rem; align-items: flex-end;">
                    <textarea name="body" placeholder="Type your message..." rows="3" required style="flex: 1; padding: 0.75rem; border: 1px solid #e9ecef; border-radius: 20px; resize: none; font-family: inherit; font-size: 0.9rem; outline: none;" onfocus="this.style.borderColor='var(--primary-blue)'" onblur="this.style.borderColor='#e9ecef'"></textarea>
                    <button type="button" onclick="toggleAttachments()" style="width: 45px; height: 45px; border-radius: 50%; background: #6c757d; color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; margin-right: 0.5rem;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fas fa-paperclip"></i>
                    </button>
                    <button type="submit" style="width: 45px; height: 45px; border-radius: 50%; background: var(--primary-blue); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
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
</style>
@endsection
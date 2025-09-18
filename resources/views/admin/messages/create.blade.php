@extends('layouts.app')

@section('title', 'New Message')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1 style="color: var(--primary-blue); font-size: 1.5rem; font-weight: 700; margin: 0;">New Message</h1>
            <a href="{{ route('admin.messages.index') }}" style="color: var(--primary-blue); text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <form action="{{ route('admin.messages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--primary-blue);">To:</label>
                <select name="recipient_id" required style="width: 100%; padding: 0.75rem; border: 1px solid #e9ecef; border-radius: 8px; font-size: 1rem;" onfocus="this.style.borderColor='var(--primary-blue)'" onblur="this.style.borderColor='#e9ecef'">
                    <option value="">Select recipient</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} - {{ $user->email }}
                            @if($user->roles()->where('name', 'client')->exists())
                                (Client)
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--primary-blue);">Subject:</label>
                <input type="text" name="subject" required style="width: 100%; padding: 0.75rem; border: 1px solid #e9ecef; border-radius: 8px; font-size: 1rem;" onfocus="this.style.borderColor='var(--primary-blue)'" onblur="this.style.borderColor='#e9ecef'">
            </div>
            
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--primary-blue);">Message:</label>
                <textarea name="body" rows="8" required style="width: 100%; padding: 0.75rem; border: 1px solid #e9ecef; border-radius: 8px; font-size: 1rem; resize: vertical;" onfocus="this.style.borderColor='var(--primary-blue)'" onblur="this.style.borderColor='#e9ecef'"></textarea>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--primary-blue);">Attach Documents:</label>
                <div style="max-height: 200px; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 8px; padding: 1rem;">
                    <div style="margin-bottom: 1rem;">
                        <strong>Invoices:</strong>
                        @foreach($invoices as $invoice)
                        <label style="display: block; margin: 0.5rem 0; cursor: pointer;">
                            <input type="checkbox" name="document_ids[]" value="{{ $invoice->id }}" style="margin-right: 0.5rem;">
                            Invoice #{{ $invoice->invoice_number }}
                            @if($invoice->project)
                                <small style="color: #6c757d;"> - {{ $invoice->project->title }}</small>
                            @endif
                        </label>
                        @endforeach
                    </div>
                    <div>
                        <strong>Proposals:</strong>
                        @foreach($proposals as $proposal)
                        <label style="display: block; margin: 0.5rem 0; cursor: pointer;">
                            <input type="checkbox" name="document_ids[]" value="{{ $proposal->id }}" style="margin-right: 0.5rem;">
                            {{ $proposal->title }}
                            @if($proposal->project)
                                <small style="color: #6c757d;"> - {{ $proposal->project->title }}</small>
                            @endif
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--primary-blue);">File Attachments:</label>
                <input type="file" name="attachments[]" multiple style="width: 100%; padding: 0.75rem; border: 1px solid #e9ecef; border-radius: 8px;">
                <small style="color: #6c757d;">Max 10MB per file</small>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" style="background: var(--primary-blue); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
                <a href="{{ route('admin.messages.index') }}" style="background: #6c757d; color: white; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; display: flex; align-items: center; gap: 0.5rem;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
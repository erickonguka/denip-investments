@extends('layouts.client')

@section('title', 'New Message - Denip Investments Ltd')
@section('page-title', 'New Message')

@section('content')
<div class="dashboard-header">
    <h1>New Message</h1>
    <p>Send a message to our team</p>
</div>

<div class="dashboard-section">
    <form action="{{ route('client.messages.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="recipient_id">To:</label>
            <select name="recipient_id" id="recipient_id" class="form-control" required>
                <option value="">Select team member</option>
                @foreach($staff as $member)
                    <option value="{{ $member->id }}">{{ $member->name }} - {{ $member->job_title ?? 'Team Member' }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="subject">Subject:</label>
            <input type="text" name="subject" id="subject" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="body">Message:</label>
            <textarea name="body" id="body" class="form-control" rows="8" required></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Send Message
            </button>
            <a href="{{ route('client.messages.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>

@push('styles')
<style>
.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--primary);
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--light);
    border-radius: 8px;
    font-size: 1rem;
}

.form-control:focus {
    outline: none;
    border-color: var(--secondary);
    box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1);
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}
</style>
@endpush
@endsection
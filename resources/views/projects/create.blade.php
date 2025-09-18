@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
<h1 class="page-title">Create New Project</h1>
<p class="page-subtitle">Start a new project for your client.</p>

<div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
    <form id="projectForm">
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Project Title</label>
            <input type="text" name="title" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none;" placeholder="Enter project title" required>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Client</label>
            <select name="client_id" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none; background: var(--white);" required>
                <option value="">Select client</option>
                @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->name }} - {{ $client->company }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Description</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none;" placeholder="Enter project description"></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 1.5rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Start Date</label>
                <input type="date" name="start_date" value="{{ now()->format('Y-m-d') }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none;" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">End Date</label>
                <input type="date" name="end_date" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none;">
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Budget ({{ \App\Helpers\SettingsHelper::currencySymbol() }})</label>
            <input type="number" name="budget" step="0.01" min="0" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none;" placeholder="0.00">
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                Create Project
            </button>
            <a href="{{ route('projects.index') }}" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('projectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('{{ route("projects.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route("projects.index") }}';
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>
@endpush
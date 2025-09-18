@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<h1 class="page-title">Client Management</h1>
<p class="page-subtitle">Manage your clients and their information.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    <button class="btn btn-primary" onclick="openModal('client-modal')">
        <i class="fas fa-plus"></i>
        Add New Client
    </button>
</div>

<x-data-table 
    title="All Clients" 
    :headers="['Name', 'Company', 'Contact', 'Projects', 'Status']"
    searchPlaceholder="Search clients..."
    :pagination="$clients">
    
    @forelse($clients as $client)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <a href="{{ route('clients.show', $client) }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">
                {{ $client->name }}
            </a>
        </td>
        <td style="padding: 1rem;">{{ $client->company ?? '-' }}</td>
        <td style="padding: 1rem;">{{ $client->email }}</td>
        <td style="padding: 1rem;">{{ $client->projects->count() }}</td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; background: {{ $client->status === 'active' ? 'var(--light-yellow)' : '#fef2f2' }}; color: {{ $client->status === 'active' ? 'var(--dark-yellow)' : 'var(--error)' }};">
                {{ ucfirst($client->status) }}
            </span>
        </td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editClient({{ $client->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                <a href="{{ route('admin.messages.startChat', $client->user_id ?? $client->id) }}" class="btn" style="background: var(--success); color: white; padding: 0.5rem; text-decoration: none;">
                    <i class="fas fa-envelope"></i>
                </a>
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="deleteClient({{ $client->id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--gray-600);">No clients found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="client-modal" title="Add New Client">
    <form id="clientForm">
        <x-form-field label="Client Name" name="name" :required="true" placeholder="Enter client name" />
        <x-form-field label="Company" name="company" placeholder="Enter company name" />
        <x-form-field label="Email" name="email" type="email" :required="true" placeholder="Enter email address" />
        <x-form-field label="Phone" name="phone" type="tel" placeholder="Enter phone number" />
        <x-form-field label="Client Type" name="type" type="select" :required="true" 
            :options="['corporate' => 'Corporate', 'individual' => 'Individual']" 
            placeholder="Select type" />
        <x-form-field label="Status" name="status" type="select" 
            :options="['active' => 'Active', 'inactive' => 'Inactive']" 
            value="active" />
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Create Client</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('client-modal')">Cancel</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
// Form submission
document.getElementById('clientForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = editId ? 'Updating...' : 'Saving...';
    
    handleFormSubmit(this, '{{ route("clients.store") }}')
        .then(response => {
            if (response.success) {
                showNotification(response.message, 'success');
                closeModal('client-modal');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Failed to save client', 'error');
            }
        })
        .catch(() => {
            showNotification('An error occurred', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btnText.textContent = editId ? 'Update Client' : 'Create Client';
        });
});

function editClient(clientId) {
    fetch(`{{ route('clients.index') }}/${clientId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            editRecord(clientId, 'client', response.data);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteClient(clientId) {
    const clientRow = event.target.closest('tr');
    const clientName = clientRow.querySelector('a').textContent.trim();
    const deleteUrl = `{{ route('clients.index') }}/${clientId}`;
    
    openDeleteModal(clientId, 'client', clientName, deleteUrl);
}


</script>
@endpush
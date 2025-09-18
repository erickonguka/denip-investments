@extends('layouts.app')

@section('title', 'Proposals')

@section('content')
<h1 class="page-title">Proposal Management</h1>
<p class="page-subtitle">Create and track project proposals.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    <button class="btn btn-primary" onclick="openModal('proposal-modal')">
        <i class="fas fa-plus"></i>
        Create New Proposal
    </button>
</div>

<x-data-table 
    title="All Proposals" 
    :headers="['Proposal #', 'Client', 'Title', 'Value', 'Valid Until', 'Status']"
    searchPlaceholder="Search proposals..."
    :pagination="$proposals">
    
    @forelse($proposals as $proposal)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <a href="{{ route('proposals.show', $proposal) }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">
                {{ $proposal->proposal_number }}
            </a>
        </td>
        <td style="padding: 1rem;">{{ $proposal->client->name }}</td>
        <td style="padding: 1rem;">{{ $proposal->title }}</td>
        <td style="padding: 1rem;">{{ \App\Helpers\CurrencyHelper::format($proposal->estimated_value) }}</td>
        <td style="padding: 1rem;">{{ $proposal->valid_until->format('Y-m-d') }}</td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $proposal->status === 'accepted' ? '#dcfce7' : ($proposal->status === 'sent' ? 'var(--light-yellow)' : '#fef2f2') }}; 
                color: {{ $proposal->status === 'accepted' ? 'var(--success)' : ($proposal->status === 'sent' ? 'var(--dark-yellow)' : 'var(--error)') }};">
                {{ ucfirst($proposal->status) }}
            </span>
        </td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editProposal({{ $proposal->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="deleteProposal({{ $proposal->id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" style="padding: 2rem; text-align: center; color: var(--gray-600);">No proposals found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="proposal-modal" title="Add New Proposal">
    <form id="proposalForm">
        <x-form-field label="Client" name="client_id" type="select" :required="true" 
            :options="$clients->pluck('name', 'id')->toArray()" 
            placeholder="Select client" />
        <x-form-field label="Project" name="project_id" type="select" 
            :options="$projects->pluck('title', 'id')->toArray()" 
            placeholder="Select project (optional)" />
        <x-form-field label="Title" name="title" :required="true" placeholder="Enter proposal title" />
        <x-form-field label="Description" name="description" type="textarea" :required="true" placeholder="Enter detailed proposal description" />
        <x-form-field label="Estimated Value" name="estimated_value" type="number" step="0.01" placeholder="0.00" />
        <x-form-field label="Valid Until" name="valid_until" type="date" :required="true" />
        <x-form-field label="Status" name="status" type="select" 
            :options="['draft' => 'Draft', 'sent' => 'Sent', 'accepted' => 'Accepted', 'rejected' => 'Rejected']" 
            value="draft" />
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Create Proposal</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('proposal-modal')">Cancel</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
document.getElementById('proposalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = editId ? 'Updating...' : 'Saving...';
    
    handleFormSubmit(this, '{{ route("proposals.store") }}')
        .then(response => {
            if (response.success) {
                showNotification(response.message, 'success');
                closeModal('proposal-modal');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Failed to save proposal', 'error');
            }
        })
        .catch(() => {
            showNotification('An error occurred', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btnText.textContent = editId ? 'Update Proposal' : 'Create Proposal';
        });
});

function editProposal(proposalId) {
    fetch(`{{ route('proposals.index') }}/${proposalId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            editRecord(proposalId, 'proposal', response.data);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteProposal(proposalId) {
    const proposalRow = event.target.closest('tr');
    const proposalNumber = proposalRow.querySelector('a').textContent.trim();
    const deleteUrl = `{{ route('proposals.index') }}/${proposalId}`;
    
    openDeleteModal(proposalId, 'proposal', proposalNumber, deleteUrl);
}

// Handle client change to filter projects
document.querySelector('[name="client_id"]').addEventListener('change', function() {
    const clientId = this.value;
    const projectSelect = document.querySelector('[name="project_id"]');
    
    projectSelect.innerHTML = '<option value="">Select project (optional)</option>';
    
    if (clientId) {
        fetch(`/clients/${clientId}/projects`)
            .then(response => response.json())
            .then(projects => {
                projects.forEach(project => {
                    const option = document.createElement('option');
                    option.value = project.id;
                    option.textContent = project.title;
                    projectSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading projects:', error));
    }
});
</script>
@endpush
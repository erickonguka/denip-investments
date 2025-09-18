@extends('layouts.app')

@section('title', 'Proposal Details')

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">{{ $proposal->proposal_number }}</h1>
        <p class="page-subtitle">{{ $proposal->title }}</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="editProposal({{ $proposal->id }})">
            <i class="fas fa-edit"></i>
            <span class="btn-text">Edit</span>
        </button>
        <a href="{{ route('proposals.pdf', $proposal) }}" class="btn btn-secondary">
            <i class="fas fa-download"></i>
            <span class="btn-text">PDF</span>
        </a>
        <button class="btn btn-danger" onclick="deleteProposal({{ $proposal->id }})">
            <i class="fas fa-trash"></i>
            <span class="btn-text">Delete</span>
        </button>
        <a href="{{ route('proposals.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i>
            <span class="btn-text">Back</span>
        </a>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
    gap: 1rem;
    width: 100%;
    max-width: 100%;
    overflow: hidden;
}

.page-header-content {
    flex: 1;
    min-width: 0;
    overflow: hidden;
}

.page-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    align-items: center;
    flex-shrink: 0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    box-sizing: border-box;
}

.btn-primary { background: var(--primary-blue); color: white; }
.btn-secondary { background: var(--success); color: white; }
.btn-danger { background: var(--error); color: white; }
.btn-outline { background: transparent; color: var(--gray-700); border: 2px solid var(--gray-300); }

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .page-actions {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
        width: 100%;
    }
    
    .btn {
        padding: 0.75rem 0.25rem;
        font-size: 0.9rem;
        min-width: 0;
        justify-content: center;
    }
    
    .btn-text { display: none; }
    .btn i { font-size: 1.1rem; }
    
    div[style*="grid-template-columns: 2fr 1fr"] {
        display: block !important;
    }
    
    div[style*="grid-template-columns: 2fr 1fr"] > div {
        margin-bottom: 1rem;
    }
    
    div[style*="grid-template-columns: 1fr 1fr"] {
        display: block !important;
    }
    
    div[style*="grid-template-columns: 1fr 1fr"] > div {
        margin-bottom: 1rem;
    }
}
</style>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Proposal Content -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 2rem;">
            <h3 style="color: var(--deep-blue);">{{ $proposal->title }}</h3>
            <span style="padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; 
                background: {{ $proposal->status === 'accepted' ? '#dcfce7' : ($proposal->status === 'sent' ? 'var(--light-yellow)' : '#fef2f2') }}; 
                color: {{ $proposal->status === 'accepted' ? 'var(--success)' : ($proposal->status === 'sent' ? 'var(--dark-yellow)' : 'var(--error)') }};">
                {{ ucfirst($proposal->status) }}
            </span>
        </div>

        <div style="margin-bottom: 2rem;">
            <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Description</h4>
            <div style="color: var(--gray-600); line-height: 1.6;">
                {!! nl2br(e($proposal->description)) !!}
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div>
                <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Proposal Details</h4>
                <div style="display: grid; gap: 0.5rem;">
                    <div><strong>Client:</strong> {{ $proposal->client->name }}</div>
                    <div><strong>Project:</strong> {{ $proposal->project->title ?? 'No project assigned' }}</div>
                    <div><strong>Valid Until:</strong> {{ $proposal->valid_until->format('M j, Y') }}</div>
                    <div><strong>Created:</strong> {{ $proposal->created_at->format('M j, Y') }}</div>
                </div>
            </div>
            
            <div>
                <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Financial</h4>
                <div style="display: grid; gap: 0.5rem;">
                    <div><strong>Estimated Value:</strong> KSh {{ number_format($proposal->estimated_value, 2) }}</div>
                    <div><strong>Status:</strong> {{ ucfirst($proposal->status) }}</div>
                    @if($proposal->valid_until->isPast())
                    <div style="color: var(--error);"><strong>Expired:</strong> {{ $proposal->valid_until->diffForHumans() }}</div>
                    @else
                    <div style="color: var(--success);"><strong>Valid for:</strong> {{ $proposal->valid_until->diffForHumans() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Proposal Summary -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
            Proposal Summary
        </h3>
        <div style="display: grid; gap: 1rem;">
            <div style="display: flex; justify-content: space-between;">
                <span>Value:</span>
                <strong>KSh {{ number_format($proposal->estimated_value, 2) }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Status:</span>
                <strong>{{ ucfirst($proposal->status) }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Days Left:</span>
                <strong style="color: {{ $proposal->valid_until->isPast() ? 'var(--error)' : 'var(--success)' }};">
                    {{ $proposal->valid_until->isPast() ? 'Expired' : $proposal->valid_until->diffInDays(now()) }}
                </strong>
            </div>
        </div>

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--gray-200);">
            <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Client Info</h4>
            <div style="display: grid; gap: 0.5rem; font-size: 0.9rem;">
                <div><strong>{{ $proposal->client->name }}</strong></div>
                <div>{{ $proposal->client->email }}</div>
                <div>{{ $proposal->client->phone }}</div>
                @if($proposal->client->company)
                <div>{{ $proposal->client->company }}</div>
                @endif
            </div>
        </div>

        @if($proposal->status === 'accepted')
        <div style="margin-top: 2rem; padding: 1rem; background: #dcfce7; border-radius: 8px; text-align: center;">
            <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
            <div style="font-weight: 600; color: var(--success);">Proposal Accepted</div>
        </div>
        @endif
    </div>
</div>

<x-modal id="proposal-modal" title="Add New Proposal">
    <form id="proposalForm">
        @php
            $clientOptions = [$proposal->client->id => $proposal->client->name];
            $projectOptions = $proposal->project ? [$proposal->project->id => $proposal->project->title] : [];
        @endphp
        
        <x-form-field label="Client" name="client_id" type="select" :required="true" 
            :options="$clientOptions" 
            placeholder="Select client" />
        <x-form-field label="Project" name="project_id" type="select" 
            :options="$projectOptions" 
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
    const proposalNumber = '{{ $proposal->proposal_number }}';
    const deleteUrl = `{{ route('proposals.index') }}/${proposalId}`;
    
    openDeleteModal(proposalId, 'proposal', proposalNumber, deleteUrl);
}
</script>
@endpush
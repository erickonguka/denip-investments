@extends('layouts.client')

@section('title', 'Proposal - ' . $proposal->title)
@section('page-title', 'Proposal Details')

@section('content')
<div class="dashboard-header">
    <div>
        <h1>{{ $proposal->title }}</h1>
        <p>{{ $proposal->proposal_number }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('client.proposals.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Proposals
        </a>
        <a href="{{ route('client.proposals.pdf', $proposal) }}" class="btn btn-outline" target="_blank">
            <i class="fas fa-eye"></i> View PDF
        </a>
        <a href="{{ route('client.proposals.download', $proposal) }}" class="btn btn-outline">
            <i class="fas fa-download"></i> Download PDF
        </a>
        <span class="status status-{{ $proposal->status }}">
            @if($proposal->status === 'sent')
                Pending Review
            @elseif($proposal->status === 'accepted')
                Accepted
            @else
                Rejected
            @endif
        </span>
    </div>
</div>

<div class="dashboard-section">
    <div class="proposal-details">
        <div class="detail-card">
            <h3>Proposal Information</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Proposal Number</label>
                    <span>{{ $proposal->proposal_number }}</span>
                </div>
                @if($proposal->estimated_value)
                <div class="detail-item">
                    <label>Estimated Value</label>
                    <span>{{ \App\Helpers\CurrencyHelper::format($proposal->estimated_value) }}</span>
                </div>
                @endif
                <div class="detail-item">
                    <label>Valid Until</label>
                    <span>{{ $proposal->valid_until->format('F j, Y') }}</span>
                </div>
                <div class="detail-item">
                    <label>Created</label>
                    <span>{{ $proposal->created_at->format('F j, Y') }}</span>
                </div>
                @if($proposal->project)
                <div class="detail-item">
                    <label>Related Project</label>
                    <span>{{ $proposal->project->title }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <div class="detail-card">
            <h3>Description</h3>
            <div class="proposal-description">
                {{ $proposal->description }}
            </div>
        </div>
        
        @if($proposal->status === 'sent')
        <div class="detail-card">
            <h3>Action Required</h3>
            <p>Please review this proposal carefully. You can request changes or accept if everything looks good.</p>
            <div class="proposal-actions">
                <button onclick="updateProposalStatus({{ $proposal->id }}, 'accepted')" class="btn btn-success">
                    <i class="fas fa-check"></i> Accept as is
                </button>
                <button onclick="requestChanges({{ $proposal->id }})" class="btn btn-secondary">
                    <i class="fas fa-edit"></i> Request Changes
                </button>
                <button onclick="updateProposalStatus({{ $proposal->id }}, 'rejected')" class="btn btn-danger">
                    <i class="fas fa-times"></i> Reject
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.proposal-details {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.detail-card {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.detail-card h3 {
    margin: 0 0 1.5rem 0;
    color: var(--primary);
    font-size: 1.2rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item label {
    font-weight: 600;
    color: var(--dark);
    font-size: 0.9rem;
}

.detail-item span {
    color: #6c757d;
}

.proposal-description {
    line-height: 1.6;
    color: var(--dark);
}

.proposal-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

.status {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-sent {
    background: #fff3cd;
    color: #856404;
}

.status-accepted {
    background: #d1fae5;
    color: #065f46;
}

.status-rejected {
    background: #fee2e2;
    color: #dc2626;
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .proposal-actions {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script>
function updateProposalStatus(proposalId, status) {
    const action = status === 'accepted' ? 'accept' : 'reject';
    showConfirmation(
        `${action.charAt(0).toUpperCase() + action.slice(1)} Proposal`,
        `Are you sure you want to ${action} this proposal?`,
        async () => {
            try {
                const response = await fetch(`/client/proposals/${proposalId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification(`Proposal ${status} successfully`, 'success');
                    location.reload();
                } else {
                    showNotification('Failed to update proposal', 'error');
                }
            } catch (error) {
                showNotification('An error occurred', 'error');
            }
        }
    );
}

function requestChanges(proposalId) {
    showNotification('Please contact us directly to discuss changes to this proposal', 'info');
}
</script>
@endpush
@endsection
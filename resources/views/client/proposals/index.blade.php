@extends('layouts.client')

@section('title', 'Proposals - Denip Investments Ltd')
@section('page-title', 'Proposals')

@section('content')
<div class="dashboard-header">
    <h1>Your Proposals</h1>
    <p>Review project proposals and estimates</p>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2>All Proposals</h2>
    </div>
    
    @forelse($proposals as $proposal)
    <div class="proposal-item" onclick="window.location.href='{{ route('client.proposals.show', $proposal) }}'">
        <div class="proposal-main">
            <div class="proposal-title">
                <h4><a href="{{ route('client.proposals.show', $proposal) }}" style="color: var(--primary); text-decoration: none;">{{ $proposal->title }}</a></h4>
                <span class="proposal-number">{{ $proposal->proposal_number }}</span>
            </div>
            <p class="proposal-desc">{{ Str::limit($proposal->description, 100) }}</p>
            <div class="proposal-details">
                @if($proposal->estimated_value)
                    <span class="detail-item">{{ \App\Helpers\CurrencyHelper::format($proposal->estimated_value) }}</span>
                @endif
                <span class="detail-item">Valid until {{ $proposal->valid_until->format('M j, Y') }}</span>
                <span class="detail-item">{{ $proposal->created_at->diffForHumans() }}</span>
            </div>
        </div>
        <div class="proposal-status">
            <span class="status status-{{ $proposal->status }}">
                @if($proposal->status === 'sent')
                    Pending
                @elseif($proposal->status === 'accepted')
                    Accepted
                @else
                    Rejected
                @endif
            </span>
            <div class="proposal-actions" onclick="event.stopPropagation()">
                <a href="{{ route('client.proposals.pdf', $proposal) }}" class="action-btn download" target="_blank" title="View PDF">
                    <i class="fas fa-eye"></i>
                </a>
                @if($proposal->status === 'sent')
                <button onclick="updateProposalStatus({{ $proposal->id }}, 'accepted')" class="action-btn accept">
                    <i class="fas fa-check"></i>
                </button>
                <button onclick="updateProposalStatus({{ $proposal->id }}, 'rejected')" class="action-btn reject">
                    <i class="fas fa-times"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-handshake"></i>
        <h3>No proposals yet</h3>
        <p>Project proposals will appear here when available</p>
    </div>
    @endforelse
    
    <div class="pagination-wrapper">
        <x-pagination :paginator="$proposals" />
    </div>
</div>
@push('styles')
<style>
.proposal-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 1rem;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.proposal-item:hover {
    transform: translateY(-2px);
}

.proposal-main {
    flex: 1;
}

.proposal-title {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.proposal-title h4 {
    margin: 0;
    color: var(--primary);
    font-size: 1.1rem;
}

.proposal-number {
    font-size: 0.8rem;
    color: #6c757d;
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.proposal-desc {
    margin: 0 0 0.75rem 0;
    color: var(--dark);
    font-size: 0.9rem;
    line-height: 1.4;
}

.proposal-details {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.detail-item {
    font-size: 0.8rem;
    color: #6c757d;
}

.proposal-status {
    text-align: right;
}

.proposal-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.action-btn.accept {
    background: #28a745;
    color: white;
}

.action-btn.reject {
    background: #dc3545;
    color: white;
}

.action-btn:hover {
    transform: scale(1.1);
}

.action-btn.download {
    background: #6c757d;
    color: white;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
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

.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .proposal-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .proposal-meta {
        flex-direction: column;
        gap: 0.5rem;
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
</script>
@endpush
@endsection
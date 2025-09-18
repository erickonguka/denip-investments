@extends('layouts.client')

@section('title', 'Invoices - Denip Investments Ltd')
@section('page-title', 'Invoices')

@section('content')
<div class="dashboard-header">
    <h1>Your Invoices</h1>
    <p>View and manage your billing history</p>
</div>

<div class="dashboard-section">
    <div class="section-header">
        <h2>All Invoices</h2>
    </div>
    
    @forelse($invoices as $invoice)
    <div class="invoice-item" onclick="window.location.href='{{ route('client.invoices.show', $invoice) }}'">
        <div class="invoice-main">
            <div class="invoice-title">
                <h4><a href="{{ route('client.invoices.show', $invoice) }}" style="color: var(--primary); text-decoration: none;">#{{ $invoice->invoice_number }}</a></h4>
                <span class="invoice-date">{{ $invoice->created_at->format('M j, Y') }}</span>
            </div>
            <p class="invoice-desc">{{ $invoice->description ?? 'Invoice for services' }}</p>
            <div class="invoice-details">
                <span class="detail-item">{{ \App\Helpers\CurrencyHelper::format($invoice->total) }}</span>
                <span class="detail-item">Due {{ $invoice->due_date->format('M j, Y') }}</span>
                <span class="detail-item">{{ $invoice->created_at->diffForHumans() }}</span>
            </div>
        </div>
        <div class="invoice-status">
            <span class="status status-{{ $invoice->status }}">
                {{ ucfirst($invoice->status) }}
            </span>
            <div class="invoice-actions" onclick="event.stopPropagation()">
                <a href="{{ route('client.invoices.pdf', $invoice) }}" class="action-btn download" target="_blank" title="View PDF">
                    <i class="fas fa-eye"></i>
                </a>
                @if($invoice->status === 'sent')
                <button onclick="markInvoicePaid({{ $invoice->id }})" class="action-btn accept">
                    <i class="fas fa-check"></i>
                </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-file-invoice"></i>
        <h3>No invoices yet</h3>
        <p>Your invoices will appear here once they are generated</p>
    </div>
    @endforelse
    
    <div class="pagination-wrapper">
        <x-pagination :paginator="$invoices" />
    </div>
</div>
@push('styles')
<style>
.invoice-item {
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

.invoice-item:hover {
    transform: translateY(-2px);
}

.invoice-main {
    flex: 1;
}

.invoice-title {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.invoice-title h4 {
    margin: 0;
    color: var(--primary);
    font-size: 1.1rem;
}

.invoice-date {
    font-size: 0.8rem;
    color: #6c757d;
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
}

.invoice-desc {
    margin: 0 0 0.75rem 0;
    color: var(--dark);
    font-size: 0.9rem;
    line-height: 1.4;
}

.invoice-details {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.detail-item {
    font-size: 0.8rem;
    color: #6c757d;
}

.invoice-status {
    text-align: right;
}

.invoice-actions {
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
    text-decoration: none;
}

.action-btn.accept {
    background: #28a745;
    color: white;
}

.action-btn.download {
    background: #6c757d;
    color: white;
}

.action-btn:hover {
    transform: scale(1.1);
}

.status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.status-sent {
    background: #fff3cd;
    color: #856404;
}

.status-paid {
    background: #d1fae5;
    color: #065f46;
}

.status-overdue {
    background: #fee2e2;
    color: #dc2626;
}

.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .invoice-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .invoice-status {
        text-align: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
function markInvoicePaid(invoiceId) {
    showConfirmation(
        'Mark Invoice as Paid',
        'Are you sure you want to mark this invoice as paid?',
        async () => {
            try {
                const response = await fetch(`/client/invoices/${invoiceId}/mark-paid`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Invoice marked as paid successfully', 'success');
                    location.reload();
                } else {
                    showNotification('Failed to mark invoice as paid', 'error');
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
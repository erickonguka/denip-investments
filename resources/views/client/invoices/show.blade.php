@extends('layouts.client')

@section('title', 'Invoice - #' . $invoice->invoice_number)
@section('page-title', 'Invoice Details')

@section('content')
<div class="dashboard-header">
    <div>
        <h1>Invoice #{{ $invoice->invoice_number }}</h1>
        <p>{{ $invoice->created_at->format('F j, Y') }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('client.invoices.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Invoices
        </a>
        <a href="{{ route('client.invoices.pdf', $invoice) }}" class="btn btn-outline" target="_blank">
            <i class="fas fa-eye"></i> View PDF
        </a>
        <a href="{{ route('client.invoices.download', $invoice) }}" class="btn btn-outline">
            <i class="fas fa-download"></i> Download PDF
        </a>
        <span class="status status-{{ $invoice->status }}">
            {{ ucfirst($invoice->status) }}
        </span>
    </div>
</div>

<div class="dashboard-section">
    <div class="invoice-details">
        <div class="detail-card">
            <h3>Invoice Information</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Invoice Number</label>
                    <span>#{{ $invoice->invoice_number }}</span>
                </div>
                <div class="detail-item">
                    <label>Issue Date</label>
                    <span>{{ $invoice->issue_date->format('F j, Y') }}</span>
                </div>
                <div class="detail-item">
                    <label>Due Date</label>
                    <span>{{ $invoice->due_date->format('F j, Y') }}</span>
                </div>
                <div class="detail-item">
                    <label>Status</label>
                    <span class="status status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
                </div>
                @if($invoice->project)
                <div class="detail-item">
                    <label>Related Project</label>
                    <span>{{ $invoice->project->title }}</span>
                </div>
                @endif
            </div>
        </div>
        
        @if($invoice->items && count($invoice->items) > 0)
        <div class="detail-card">
            <h3>Invoice Items</h3>
            <div class="invoice-items">
                @foreach($invoice->items as $item)
                <div class="invoice-item">
                    <div class="item-description">{{ $item->description }}</div>
                    <div class="item-details">
                        <span>{{ $item->quantity }} × {{ \App\Helpers\CurrencyHelper::format($item->price) }}</span>
                        <span class="item-total">{{ \App\Helpers\CurrencyHelper::format($item->quantity * $item->price) }}</span>
                    </div>
                </div>
                @endforeach
                
                <div class="invoice-totals">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>{{ \App\Helpers\CurrencyHelper::format($invoice->subtotal) }}</span>
                    </div>
                    @if($invoice->tax_amount > 0)
                    <div class="total-row">
                        <span>Tax ({{ $invoice->tax_rate }}%):</span>
                        <span>{{ \App\Helpers\CurrencyHelper::format($invoice->tax_amount) }}</span>
                    </div>
                    @endif
                    <div class="total-row final">
                        <span>Total:</span>
                        <span>{{ \App\Helpers\CurrencyHelper::format($invoice->total) }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if($invoice->notes)
        <div class="detail-card">
            <h3>Notes</h3>
            <div class="invoice-notes">
                {{ $invoice->notes }}
            </div>
        </div>
        @endif
        
        @if($invoice->status === 'sent')
        <div class="detail-card">
            <h3>Payment Action</h3>
            <p>Please confirm when you have made the payment for this invoice.</p>
            <div class="invoice-actions">
                <button onclick="markInvoicePaid({{ $invoice->id }})" class="btn btn-success">
                    <i class="fas fa-check"></i> Mark as Paid
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

.invoice-details {
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

.invoice-items {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.invoice-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.item-description {
    font-weight: 600;
    color: var(--dark);
}

.item-details {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
}

.item-total {
    font-weight: 600;
    color: var(--primary);
}

.invoice-totals {
    border-top: 2px solid #dee2e6;
    padding-top: 1rem;
    margin-top: 1rem;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}

.total-row.final {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary);
    border-top: 1px solid #dee2e6;
    margin-top: 0.5rem;
    padding-top: 1rem;
}

.invoice-notes {
    line-height: 1.6;
    color: var(--dark);
}

.invoice-actions {
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

.status-paid {
    background: #d1fae5;
    color: #065f46;
}

.status-overdue {
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
    
    .invoice-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .item-details {
        align-items: flex-start;
    }
    
    .invoice-actions {
        flex-direction: column;
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
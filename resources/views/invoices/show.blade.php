@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">{{ $invoice->invoice_number }}</h1>
        <p class="page-subtitle">{{ $invoice->client->name }}</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="editInvoice({{ $invoice->id }})">
            <i class="fas fa-edit"></i>
            <span class="btn-text">Edit</span>
        </button>
        <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-secondary">
            <i class="fas fa-download"></i>
            <span class="btn-text">PDF</span>
        </a>
        <button class="btn btn-danger" onclick="deleteInvoice({{ $invoice->id }})">
            <i class="fas fa-trash"></i>
            <span class="btn-text">Delete</span>
        </button>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline">
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
    
    div[style*="overflow-x: auto"] table {
        font-size: 0.9rem;
    }
    
    div[style*="overflow-x: auto"] th,
    div[style*="overflow-x: auto"] td {
        padding: 0.5rem !important;
        white-space: nowrap;
    }
}
</style>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Invoice Details -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; margin-bottom: 2rem;">
            <div>
                <h3 style="color: var(--deep-blue); margin-bottom: 1rem;">Invoice Details</h3>
                <div style="display: grid; gap: 0.5rem;">
                    <div><strong>Issue Date:</strong> {{ $invoice->issue_date->format('M j, Y') }}</div>
                    <div><strong>Due Date:</strong> {{ $invoice->due_date->format('M j, Y') }}</div>
                    <div><strong>Project:</strong> {{ $invoice->project->title ?? 'No project' }}</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.9rem; color: var(--gray-600); margin-bottom: 0.5rem;">Status</div>
                <span style="padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; 
                    background: {{ $invoice->status === 'paid' ? '#dcfce7' : ($invoice->status === 'sent' ? 'var(--light-yellow)' : '#fef2f2') }}; 
                    color: {{ $invoice->status === 'paid' ? 'var(--success)' : ($invoice->status === 'sent' ? 'var(--dark-yellow)' : 'var(--error)') }};">
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>
        </div>

        <!-- Invoice Items -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--gray-50);">
                        <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--gray-200);">Description</th>
                        <th style="padding: 1rem; text-align: center; border-bottom: 1px solid var(--gray-200);">Qty</th>
                        <th style="padding: 1rem; text-align: right; border-bottom: 1px solid var(--gray-200);">Rate</th>
                        <th style="padding: 1rem; text-align: right; border-bottom: 1px solid var(--gray-200);">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">{{ $item->description }}</td>
                        <td style="padding: 1rem; text-align: center; border-bottom: 1px solid var(--gray-200);">{{ $item->quantity }}</td>
                        <td style="padding: 1rem; text-align: right; border-bottom: 1px solid var(--gray-200);">{{ \App\Helpers\CurrencyHelper::format($item->price) }}</td>
                        <td style="padding: 1rem; text-align: right; border-bottom: 1px solid var(--gray-200);">{{ \App\Helpers\CurrencyHelper::format($item->quantity * $item->price) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div style="margin-top: 2rem; text-align: right;">
            <div style="display: grid; gap: 0.5rem; max-width: 300px; margin-left: auto;">
                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                    <span>Subtotal:</span>
                    <span>{{ \App\Helpers\CurrencyHelper::format($invoice->subtotal) }}</span>
                </div>
                @if($invoice->tax_rate > 0)
                <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                    <span>Tax ({{ $invoice->tax_rate }}%):</span>
                    <span>{{ \App\Helpers\CurrencyHelper::format($invoice->tax_amount) }}</span>
                </div>
                @endif
                <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-top: 2px solid var(--gray-300); font-size: 1.2rem; font-weight: bold;">
                    <span>Total:</span>
                    <span>{{ \App\Helpers\CurrencyHelper::format($invoice->total) }}</span>
                </div>
            </div>
        </div>

        @if($invoice->notes)
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--gray-200);">
            <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Notes</h4>
            <p style="color: var(--gray-600);">{{ $invoice->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Invoice Summary -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
            Invoice Summary
        </h3>
        <div style="display: grid; gap: 1rem;">
            <div style="display: flex; justify-content: space-between;">
                <span>Items:</span>
                <strong>{{ $invoice->items->count() }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Amount:</span>
                <strong>{{ \App\Helpers\CurrencyHelper::format($invoice->total) }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Days Overdue:</span>
                <strong style="color: {{ $invoice->due_date->isPast() && $invoice->status !== 'paid' ? 'var(--error)' : 'var(--success)' }};">
                    {{ $invoice->due_date->isPast() && $invoice->status !== 'paid' ? $invoice->due_date->diffInDays(now()) : '0' }}
                </strong>
            </div>
        </div>

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--gray-200);">
            <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Client Info</h4>
            <div style="display: grid; gap: 0.5rem; font-size: 0.9rem;">
                <div><strong>{{ $invoice->client->name }}</strong></div>
                <div>{{ $invoice->client->email }}</div>
                <div>{{ $invoice->client->phone }}</div>
            </div>
        </div>
    </div>
</div>

<x-modal id="invoice-modal" title="Add New Invoice" size="large">
    <form id="invoiceForm">
        @php
            $clientOptions = [$invoice->client->id => $invoice->client->name];
            $projectOptions = $invoice->project ? [$invoice->project->id => $invoice->project->title] : [];
        @endphp
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <x-form-field label="Client" name="client_id" type="select" :required="true" 
                :options="$clientOptions" 
                placeholder="Select client" />
            <x-form-field label="Project" name="project_id" type="select" 
                :options="$projectOptions" 
                placeholder="Select project (optional)" />
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <x-form-field label="Issue Date" name="issue_date" type="date" :required="true" :value="date('Y-m-d')" />
            <x-form-field label="Due Date" name="due_date" type="date" :required="true" value="{{ now()->addDays(\App\Helpers\SettingsHelper::paymentTerms())->format('Y-m-d') }}" />
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Invoice Items</label>
            <div class="invoice-items-container" style="overflow-x: auto;">
                <div id="invoice-items">
                    <div class="invoice-item" style="display: grid; grid-template-columns: 2fr 80px 80px 80px 40px; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem; padding: 0.5rem; background: var(--gray-50); border-radius: 8px; min-width: 500px;">
                        <input type="text" name="items[0][description]" placeholder="Service Description" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; min-width: 150px;" required>
                        <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" value="1" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
                        <input type="number" name="items[0][price]" placeholder="Rate" step="0.01" min="0" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
                        <input type="number" class="item-total" placeholder="Total" readonly style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; background: var(--gray-100);">
                        <button type="button" class="btn" style="background: var(--error); color: white; padding: 0.5rem; min-width: 40px;" onclick="removeInvoiceItem(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue); margin-top: 0.5rem;" onclick="addInvoiceItem()">
                <i class="fas fa-plus"></i>
                Add Item
            </button>
        </div>
        
        <div class="invoice-summary" style="display: grid; grid-template-columns: 1fr auto; gap: 2rem;">
            <div>
                <x-form-field label="Tax Rate (%)" name="tax_rate" type="number" step="0.01" value="{{ \App\Helpers\SettingsHelper::taxRate() }}" />
                <x-form-field label="Notes" name="notes" type="textarea" placeholder="{{ \App\Helpers\SettingsHelper::get('invoice_footer', 'Thank you for your business!') }}" />
                <x-form-field label="Status" name="status" type="select" 
                    :options="['draft' => 'Draft', 'sent' => 'Sent', 'paid' => 'Paid', 'overdue' => 'Overdue']" 
                    value="draft" />
            </div>
            <div style="background: var(--gray-50); padding: 1rem; border-radius: 8px; min-width: 200px;">
                <div style="font-size: 1.2rem; font-weight: bold; color: var(--deep-blue);">
                    Total: {{ \App\Helpers\SettingsHelper::currencySymbol() }}<span id="invoice-total">0.00</span>
                </div>
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Create Invoice</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('invoice-modal')">Cancel</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
let invoiceItemIndex = 1;

function addInvoiceItem() {
    const container = document.getElementById('invoice-items');
    const itemHtml = `
        <div class="invoice-item" style="display: grid; grid-template-columns: 2fr 80px 80px 80px 40px; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem; padding: 0.5rem; background: var(--gray-50); border-radius: 8px; min-width: 500px;">
            <input type="text" name="items[${invoiceItemIndex}][description]" placeholder="Service Description" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; min-width: 150px;" required>
            <input type="number" name="items[${invoiceItemIndex}][quantity]" placeholder="Qty" min="1" value="1" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
            <input type="number" name="items[${invoiceItemIndex}][price]" placeholder="Rate" step="0.01" min="0" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
            <input type="number" class="item-total" placeholder="Total" readonly style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; background: var(--gray-100);">
            <button type="button" class="btn" style="background: var(--error); color: white; padding: 0.5rem; min-width: 40px;" onclick="removeInvoiceItem(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', itemHtml);
    invoiceItemIndex++;
}

function removeInvoiceItem(button) {
    if (document.querySelectorAll('.invoice-item').length > 1) {
        button.closest('.invoice-item').remove();
        updateInvoiceTotal();
    }
}

function updateInvoiceTotal() {
    let subtotal = 0;
    document.querySelectorAll('.invoice-item').forEach(item => {
        const quantity = parseFloat(item.querySelector('input[name*="[quantity]"]').value) || 0;
        const price = parseFloat(item.querySelector('input[name*="[price]"]').value) || 0;
        const itemTotal = quantity * price;
        
        item.querySelector('.item-total').value = itemTotal.toFixed(2);
        subtotal += itemTotal;
    });
    
    const taxRate = parseFloat(document.querySelector('input[name="tax_rate"]').value) || 0;
    const tax = subtotal * (taxRate / 100);
    const total = subtotal + tax;
    
    document.getElementById('invoice-total').textContent = total.toFixed(2);
}

document.addEventListener('input', function(e) {
    if (e.target.matches('input[name*="[quantity]"], input[name*="[price]"], input[name="tax_rate"]')) {
        updateInvoiceTotal();
    }
});

function editInvoice(invoiceId) {
    fetch(`{{ route('invoices.index') }}/${invoiceId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            const form = document.getElementById('invoiceForm');
            const invoice = response.data;
            
            // Populate basic fields
            Object.keys(invoice).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && key !== 'items') {
                    field.value = invoice[key] || '';
                }
            });
            
            // Clear and populate items
            document.getElementById('invoice-items').innerHTML = '';
            invoiceItemIndex = 0;
            
            if (invoice.items && invoice.items.length > 0) {
                invoice.items.forEach(() => {
                    addInvoiceItem();
                });
                
                invoice.items.forEach((item, index) => {
                    const itemDiv = document.querySelectorAll('.invoice-item')[index];
                    itemDiv.querySelector('input[name*="[description]"]').value = item.description;
                    itemDiv.querySelector('input[name*="[quantity]"]').value = item.quantity;
                    itemDiv.querySelector('input[name*="[price]"]').value = item.price;
                });
            } else {
                addInvoiceItem();
            }
            
            openModal('invoice-modal');
        }
    })
    .catch(error => console.error('Error:', error));
}

document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    
    btn.disabled = true;
    btnText.textContent = 'Updating...';
    
    const formData = new FormData(this);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        if (key.includes('[')) {
            const matches = key.match(/(\\w+)\\[(\\d+)\\]\\[(\\w+)\\]/);
            if (matches) {
                const [, field, index, subfield] = matches;
                if (!data[field]) data[field] = [];
                if (!data[field][index]) data[field][index] = {};
                data[field][index][subfield] = value;
            }
        } else {
            data[key] = value;
        }
    }
    
    fetch('{{ route("invoices.update", $invoice) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({...data, _method: 'PUT'})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal('invoice-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to update invoice', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Update Invoice';
    });
});

function deleteInvoice(invoiceId) {
    const invoiceNumber = '{{ $invoice->invoice_number }}';
    const deleteUrl = `{{ route('invoices.index') }}/${invoiceId}`;
    
    openDeleteModal(invoiceId, 'invoice', invoiceNumber, deleteUrl);
}


</script>
@endpush
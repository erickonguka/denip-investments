@extends('layouts.app')

@section('title', 'Quotation Details')

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">{{ $quotation->quotation_number }}</h1>
        <p class="page-subtitle">{{ $quotation->client->name }}</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="editQuotation({{ $quotation->id }})">
            <i class="fas fa-edit"></i>
            <span class="btn-text">Edit</span>
        </button>
        @if($quotation->status === 'active')
        <button class="btn btn-info" onclick="convertToInvoice({{ $quotation->id }})">
            <i class="fas fa-file-invoice"></i>
            <span class="btn-text">Convert</span>
        </button>
        @endif
        <a href="{{ route('quotations.pdf', $quotation) }}" class="btn btn-secondary">
            <i class="fas fa-download"></i>
            <span class="btn-text">PDF</span>
        </a>
        <button class="btn btn-danger" onclick="deleteQuotation({{ $quotation->id }})">
            <i class="fas fa-trash"></i>
            <span class="btn-text">Delete</span>
        </button>
        <a href="{{ route('quotations.index') }}" class="btn btn-outline">
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
.btn-info { background: #17a2b8; color: white; }
.btn-danger { background: var(--error); color: white; }
.btn-outline { background: transparent; color: var(--gray-700); border: 2px solid var(--gray-300); }

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
    }
    
    .page-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
        width: 100%;
    }
    
    .btn {
        padding: 0.75rem 0.25rem;
        font-size: 0.9rem;
        min-width: 0;
        justify-content: center;
    }
    
    .btn:nth-child(4),
    .btn:nth-child(5) {
        grid-column: span 1;
    }
    
    .btn:last-child {
        grid-column: 1 / -1;
        margin-top: 0.5rem;
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
    <!-- Quotation Details -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; margin-bottom: 2rem;">
            <div>
                <h3 style="color: var(--deep-blue); margin-bottom: 1rem;">Quotation Details</h3>
                <div style="display: grid; gap: 0.5rem;">
                    <div><strong>Client:</strong> {{ $quotation->client->name }}</div>
                    <div><strong>Project:</strong> {{ $quotation->project->title ?? 'No project' }}</div>
                    <div><strong>Valid Until:</strong> {{ $quotation->valid_until->format('M j, Y') }}</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 0.9rem; color: var(--gray-600); margin-bottom: 0.5rem;">Status</div>
                <span style="padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; 
                    background: {{ $quotation->status === 'converted' ? '#dcfce7' : ($quotation->status === 'active' ? 'var(--light-yellow)' : '#fef2f2') }}; 
                    color: {{ $quotation->status === 'converted' ? 'var(--success)' : ($quotation->status === 'active' ? 'var(--dark-yellow)' : 'var(--error)') }};">
                    {{ ucfirst($quotation->status) }}
                </span>
            </div>
        </div>

        <!-- Quotation Items -->
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
                    @foreach($quotation->items as $item)
                    <tr>
                        <td style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">{{ $item->description }}</td>
                        <td style="padding: 1rem; text-align: center; border-bottom: 1px solid var(--gray-200);">{{ $item->quantity }}</td>
                        <td style="padding: 1rem; text-align: right; border-bottom: 1px solid var(--gray-200);">KSh {{ number_format($item->price, 2) }}</td>
                        <td style="padding: 1rem; text-align: right; border-bottom: 1px solid var(--gray-200);">KSh {{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div style="margin-top: 2rem; text-align: right;">
            <div style="display: flex; justify-content: space-between; padding: 1rem 0; border-top: 2px solid var(--gray-300); font-size: 1.2rem; font-weight: bold; max-width: 300px; margin-left: auto;">
                <span>Total:</span>
                <span>KSh {{ number_format($quotation->total, 2) }}</span>
            </div>
        </div>

        @if($quotation->notes)
        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--gray-200);">
            <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Notes</h4>
            <p style="color: var(--gray-600);">{{ $quotation->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Quotation Summary -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
            Quotation Summary
        </h3>
        <div style="display: grid; gap: 1rem;">
            <div style="display: flex; justify-content: space-between;">
                <span>Items:</span>
                <strong>{{ $quotation->items->count() }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Amount:</span>
                <strong>KSh {{ number_format($quotation->total, 2) }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Valid Days:</span>
                <strong style="color: {{ $quotation->valid_until->isPast() ? 'var(--error)' : 'var(--success)' }};">
                    {{ $quotation->valid_until->isPast() ? 'Expired' : $quotation->valid_until->diffInDays(now()) }}
                </strong>
            </div>
        </div>

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--gray-200);">
            <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Client Info</h4>
            <div style="display: grid; gap: 0.5rem; font-size: 0.9rem;">
                <div><strong>{{ $quotation->client->name }}</strong></div>
                <div>{{ $quotation->client->email }}</div>
                <div>{{ $quotation->client->phone }}</div>
            </div>
        </div>

        @if($quotation->status === 'converted')
        <div style="margin-top: 2rem; padding: 1rem; background: #dcfce7; border-radius: 8px; text-align: center;">
            <i class="fas fa-check-circle" style="color: var(--success); font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
            <div style="font-weight: 600; color: var(--success);">Converted to Invoice</div>
        </div>
        @endif
    </div>
</div>

@php
    $clientOptions = [$quotation->client->id => $quotation->client->name];
    $projectOptions = $quotation->project ? [$quotation->project->id => $quotation->project->title] : [];
@endphp

<x-modal id="quotation-modal" title="Add New Quotation" size="large">
    <form id="quotationForm">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <x-form-field label="Client" name="client_id" type="select" :required="true" 
                :options="$clientOptions" 
                placeholder="Select client" />
            <x-form-field label="Project" name="project_id" type="select" 
                :options="$projectOptions" 
                placeholder="Select project (optional)" />
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Quotation Items</label>
            <div class="quotation-items-container" style="overflow-x: auto;">
                <div id="quotation-items">
                    <div class="quotation-item" style="display: grid; grid-template-columns: 2fr 80px 80px 80px 40px; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem; padding: 0.5rem; background: var(--gray-50); border-radius: 8px; min-width: 500px;">
                        <input type="text" name="items[0][description]" placeholder="Service Description" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; min-width: 150px;" required>
                        <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" value="1" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
                        <input type="number" name="items[0][price]" placeholder="Rate" step="0.01" min="0" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
                        <input type="number" class="item-total" placeholder="Total" readonly style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; background: var(--gray-100);">
                        <button type="button" class="btn" style="background: var(--error); color: white; padding: 0.5rem; min-width: 40px;" onclick="removeQuotationItem(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue); margin-top: 0.5rem;" onclick="addQuotationItem()">
                <i class="fas fa-plus"></i>
                Add Item
            </button>
        </div>
        
        <div class="quotation-summary" style="display: grid; grid-template-columns: 1fr auto; gap: 2rem;">
            <div>
                <x-form-field label="Notes" name="notes" type="textarea" placeholder="Additional notes..." />
                <x-form-field label="Valid Until" name="valid_until" type="date" :required="true" value="{{ now()->addDays(\App\Helpers\SettingsHelper::quotationValidity())->format('Y-m-d') }}" />
                <x-form-field label="Status" name="status" type="select" 
                    :options="['active' => 'Active', 'expired' => 'Expired', 'converted' => 'Converted', 'cancelled' => 'Cancelled']" 
                    value="active" />
            </div>
            <div style="background: var(--gray-50); padding: 1rem; border-radius: 8px; min-width: 200px;">
                <div style="font-size: 1.2rem; font-weight: bold; color: var(--deep-blue);">
                    Total: {{ \App\Helpers\SettingsHelper::currencySymbol() }}<span id="quotation-total">0.00</span>
                </div>
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Create Quotation</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('quotation-modal')">Cancel</button>
        </div>
    </form>
</x-modal>

<x-modal id="convert-modal" title="Convert to Invoice">
    <div style="text-align: center; padding: 1rem;">
        <i class="fas fa-file-invoice" style="font-size: 3rem; color: var(--primary-blue); margin-bottom: 1rem;"></i>
        <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Convert Quotation to Invoice?</h4>
        <p style="color: var(--gray-600); margin-bottom: 2rem;">This will create a new invoice based on this quotation and mark the quotation as converted.</p>
        
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button class="btn btn-primary" onclick="confirmConvert()">
                <i class="fas fa-check"></i>
                Yes, Convert
            </button>
            <button class="btn" style="background: var(--gray-300); color: var(--gray-700);" onclick="closeModal('convert-modal')">
                Cancel
            </button>
        </div>
    </div>
</x-modal>
@endsection

@push('scripts')
<script>
let quotationItemIndex = 1;

function addQuotationItem() {
    const container = document.getElementById('quotation-items');
    const itemHtml = `
        <div class="quotation-item" style="display: grid; grid-template-columns: 2fr 80px 80px 80px 40px; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem; padding: 0.5rem; background: var(--gray-50); border-radius: 8px; min-width: 500px;">
            <input type="text" name="items[${quotationItemIndex}][description]" placeholder="Service Description" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; min-width: 150px;" required>
            <input type="number" name="items[${quotationItemIndex}][quantity]" placeholder="Qty" min="1" value="1" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
            <input type="number" name="items[${quotationItemIndex}][price]" placeholder="Rate" step="0.01" min="0" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
            <input type="number" class="item-total" placeholder="Total" readonly style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; background: var(--gray-100);">
            <button type="button" class="btn" style="background: var(--error); color: white; padding: 0.5rem; min-width: 40px;" onclick="removeQuotationItem(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', itemHtml);
    quotationItemIndex++;
}

function removeQuotationItem(button) {
    if (document.querySelectorAll('.quotation-item').length > 1) {
        button.closest('.quotation-item').remove();
        updateQuotationTotal();
    }
}

function updateQuotationTotal() {
    let total = 0;
    document.querySelectorAll('.quotation-item').forEach(item => {
        const quantity = parseFloat(item.querySelector('input[name*="[quantity]"]').value) || 0;
        const price = parseFloat(item.querySelector('input[name*="[price]"]').value) || 0;
        const itemTotal = quantity * price;
        
        item.querySelector('.item-total').value = itemTotal.toFixed(2);
        total += itemTotal;
    });
    
    document.getElementById('quotation-total').textContent = total.toFixed(2);
}

document.addEventListener('input', function(e) {
    if (e.target.matches('input[name*="[quantity]"], input[name*="[price]"]')) {
        updateQuotationTotal();
    }
});

function editQuotation(quotationId) {
    fetch(`{{ route('quotations.index') }}/${quotationId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            const form = document.getElementById('quotationForm');
            const quotation = response.data;
            
            // Populate basic fields
            Object.keys(quotation).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && key !== 'items') {
                    field.value = quotation[key] || '';
                }
            });
            
            // Clear and populate items
            document.getElementById('quotation-items').innerHTML = '';
            quotationItemIndex = 0;
            
            if (quotation.items && quotation.items.length > 0) {
                quotation.items.forEach(() => {
                    addQuotationItem();
                });
                
                quotation.items.forEach((item, index) => {
                    const itemDiv = document.querySelectorAll('.quotation-item')[index];
                    itemDiv.querySelector('input[name*="[description]"]').value = item.description;
                    itemDiv.querySelector('input[name*="[quantity]"]').value = item.quantity;
                    itemDiv.querySelector('input[name*="[price]"]').value = item.price;
                });
            } else {
                addQuotationItem();
            }
            
            openModal('quotation-modal');
        }
    })
    .catch(error => console.error('Error:', error));
}

document.getElementById('quotationForm').addEventListener('submit', function(e) {
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
    
    fetch('{{ route("quotations.update", $quotation) }}', {
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
            closeModal('quotation-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to update quotation', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Update Quotation';
    });
});

function convertToInvoice(quotationId) {
    window.currentConvertQuotation = quotationId;
    openModal('convert-modal');
}

function confirmConvert() {
    const quotationId = window.currentConvertQuotation;
    
    fetch(`/quotations/${quotationId}/convert-to-invoice`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Quotation converted to invoice successfully', 'success');
            closeModal('convert-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to convert quotation', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred', 'error');
    });
}

function deleteQuotation(quotationId) {
    const quotationNumber = '{{ $quotation->quotation_number }}';
    const deleteUrl = `{{ route('quotations.index') }}/${quotationId}`;
    
    openDeleteModal(quotationId, 'quotation', quotationNumber, deleteUrl);
}
</script>
@endpush
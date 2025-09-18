@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<h1 class="page-title">Create New Invoice</h1>
<p class="page-subtitle">Generate a new invoice for your client.</p>

<div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
    <form id="invoiceForm">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Invoice Number</label>
                <input type="text" name="invoice_number" value="{{ $invoiceNumber }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none; background: var(--gray-50);" readonly>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Due Date</label>
                <input type="date" name="due_date" value="{{ now()->addDays(\App\Helpers\SettingsHelper::paymentTerms())->format('Y-m-d') }}" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none;" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Client</label>
                <select name="client_id" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none; background: var(--white);" required>
                    <option value="">Select client</option>
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }} - {{ $client->company }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Project (Optional)</label>
                <select name="project_id" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none; background: var(--white);">
                    <option value="">Select project</option>
                    @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Invoice Items</label>
            <div id="invoice-items">
                <div class="invoice-item" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem; padding: 0.5rem; background: var(--gray-50); border-radius: 8px;">
                    <input type="text" name="items[0][description]" placeholder="Description" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
                    <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" value="1" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
                    <input type="number" name="items[0][price]" placeholder="Price" step="0.01" min="0" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
                    <input type="number" class="item-total" placeholder="Total" readonly style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; background: var(--gray-100);">
                    <button type="button" class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="removeItem(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue); margin-top: 0.5rem;" onclick="addItem()">
                <i class="fas fa-plus"></i>
                Add Item
            </button>
        </div>

        <div style="display: grid; grid-template-columns: 1fr auto; gap: 2rem; margin-bottom: 2rem;">
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Notes</label>
                <textarea name="notes" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; outline: none;" placeholder="Additional notes..."></textarea>
            </div>
            <div style="background: var(--gray-50); padding: 1rem; border-radius: 8px; min-width: 200px;">
                <div style="margin-bottom: 0.5rem;">
                    <span>Subtotal: {{ \App\Helpers\SettingsHelper::currencySymbol() }}</span><span id="subtotal">0.00</span>
                </div>
                <div style="margin-bottom: 0.5rem;">
                    <label>Tax Rate (%): </label>
                    <input type="number" name="tax_rate" value="{{ \App\Helpers\SettingsHelper::taxRate() }}" min="0" max="100" step="0.01" style="width: 60px; padding: 0.25rem; border: 1px solid var(--gray-300); border-radius: 4px;">
                </div>
                <div style="margin-bottom: 0.5rem;">
                    <span>Tax: {{ \App\Helpers\SettingsHelper::currencySymbol() }}</span><span id="tax-amount">0.00</span>
                </div>
                <div style="font-size: 1.2rem; font-weight: bold; color: var(--deep-blue); border-top: 1px solid var(--gray-300); padding-top: 0.5rem;">
                    Total: {{ \App\Helpers\SettingsHelper::currencySymbol() }}<span id="total">0.00</span>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                Create Invoice
            </button>
            <a href="{{ route('invoices.index') }}" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = 1;

function addItem() {
    const container = document.getElementById('invoice-items');
    const itemHtml = `
        <div class="invoice-item" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr auto; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem; padding: 0.5rem; background: var(--gray-50); border-radius: 8px;">
            <input type="text" name="items[${itemIndex}][description]" placeholder="Description" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
            <input type="number" name="items[${itemIndex}][quantity]" placeholder="Qty" min="1" value="1" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
            <input type="number" name="items[${itemIndex}][price]" placeholder="Price" step="0.01" min="0" style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;" required>
            <input type="number" class="item-total" placeholder="Total" readonly style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; background: var(--gray-100);">
            <button type="button" class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="removeItem(this)">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', itemHtml);
    itemIndex++;
    updateTotals();
}

function removeItem(button) {
    if (document.querySelectorAll('.invoice-item').length > 1) {
        button.closest('.invoice-item').remove();
        updateTotals();
    }
}

function updateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.invoice-item').forEach(item => {
        const quantity = parseFloat(item.querySelector('input[name*="[quantity]"]').value) || 0;
        const price = parseFloat(item.querySelector('input[name*="[price]"]').value) || 0;
        const total = quantity * price;
        
        item.querySelector('.item-total').value = total.toFixed(2);
        subtotal += total;
    });
    
    const taxRate = parseFloat(document.querySelector('input[name="tax_rate"]').value) || 0;
    const taxAmount = subtotal * (taxRate / 100);
    const total = subtotal + taxAmount;
    
    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('tax-amount').textContent = taxAmount.toFixed(2);
    document.getElementById('total').textContent = total.toFixed(2);
}

// Event listeners
document.addEventListener('input', function(e) {
    if (e.target.matches('input[name*="[quantity]"], input[name*="[price]"], input[name="tax_rate"]')) {
        updateTotals();
    }
});

document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {};
    
    // Convert FormData to object
    for (let [key, value] of formData.entries()) {
        if (key.includes('[')) {
            // Handle array fields
            const matches = key.match(/(\w+)\[(\d+)\]\[(\w+)\]/);
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
    
    fetch('{{ route("invoices.store") }}', {
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
            window.location.href = '{{ route("invoices.index") }}';
        }
    })
    .catch(error => console.error('Error:', error));
});

// Initialize
updateTotals();
</script>
@endpush
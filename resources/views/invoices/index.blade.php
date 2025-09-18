@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
<h1 class="page-title">Invoice Management</h1>
<p class="page-subtitle">Create and manage invoices for your clients.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    <button class="btn btn-primary" onclick="openModal('invoice-modal')">
        <i class="fas fa-plus"></i>
        Create New Invoice
    </button>
</div>

<x-data-table 
    title="All Invoices" 
    :headers="['Invoice #', 'Client', 'Project', 'Date', 'Amount', 'Status']"
    searchPlaceholder="Search invoices..."
    :pagination="$invoices">
    
    @forelse($invoices as $invoice)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <a href="{{ route('invoices.show', $invoice) }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">
                {{ $invoice->invoice_number }}
            </a>
        </td>
        <td style="padding: 1rem;">{{ $invoice->client->name }}</td>
        <td style="padding: 1rem;">{{ $invoice->project->title ?? '-' }}</td>
        <td style="padding: 1rem;">{{ $invoice->issue_date->format('Y-m-d') }}</td>
        <td style="padding: 1rem;">{{ \App\Helpers\CurrencyHelper::format($invoice->total) }}</td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $invoice->status === 'paid' ? '#dcfce7' : ($invoice->status === 'sent' ? 'var(--light-yellow)' : '#fef2f2') }}; 
                color: {{ $invoice->status === 'paid' ? 'var(--success)' : ($invoice->status === 'sent' ? 'var(--dark-yellow)' : 'var(--error)') }};">
                {{ ucfirst($invoice->status) }}
            </span>
        </td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editInvoice({{ $invoice->id }})">
                    <i class="fas fa-edit"></i>
                </button>

                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="deleteInvoice({{ $invoice->id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" style="padding: 2rem; text-align: center; color: var(--gray-600);">No invoices found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="invoice-modal" title="Add New Invoice" size="large">
    <form id="invoiceForm">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <x-form-field label="Client" name="client_id" type="select" :required="true" 
                :options="$clients->pluck('name', 'id')->toArray()" 
                placeholder="Select client" />
            <x-form-field label="Project" name="project_id" type="select" 
                :options="$projects->pluck('title', 'id')->toArray()" 
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
        
        <div class="invoice-summary" style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <x-form-field label="Tax Rate (%)" name="tax_rate" type="number" step="0.01" value="{{ \App\Helpers\SettingsHelper::taxRate() }}" />
                <x-form-field label="Status" name="status" type="select" 
                    :options="['draft' => 'Draft', 'sent' => 'Sent', 'paid' => 'Paid', 'overdue' => 'Overdue']" 
                    value="draft" />
            </div>
            <x-form-field label="Notes" name="notes" type="textarea" placeholder="{{ \App\Helpers\SettingsHelper::get('invoice_footer', 'Thank you for your business!') }}" />
            <div style="background: var(--gray-50); padding: 1rem; border-radius: 8px; text-align: center;">
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

document.getElementById('invoiceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = editId ? 'Updating...' : 'Saving...';
    
    const formData = new FormData(this);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        if (key.includes('[')) {
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
    
    let url = '{{ route("invoices.store") }}';
    let method = 'POST';
    
    if (editId) {
        const baseUrl = url.replace('/store', '');
        url = `${baseUrl}/${editId}`;
        data._method = 'PUT';
    }
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal('invoice-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to save invoice', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = editId ? 'Update Invoice' : 'Create Invoice';
    });
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
            
            form.setAttribute('data-edit-id', invoiceId);
            
            // Populate basic fields
            Object.keys(invoice).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && key !== 'items') {
                    field.value = invoice[key] || '';
                }
            });
            
            // Trigger client change to load projects
            if (invoice.client_id) {
                document.querySelector('[name="client_id"]').dispatchEvent(new Event('change'));
                setTimeout(() => {
                    if (invoice.project_id) {
                        document.querySelector('[name="project_id"]').value = invoice.project_id;
                    }
                }, 100);
            }
            
            // Clear existing items
            document.getElementById('invoice-items').innerHTML = '';
            invoiceItemIndex = 0;
            
            // Add invoice items
            if (invoice.items && invoice.items.length > 0) {
                invoice.items.forEach((item, index) => {
                    if (index === 0) {
                        // Update first item
                        const firstItem = document.querySelector('.invoice-item');
                        if (firstItem) {
                            firstItem.querySelector('input[name*="[description]"]').value = item.description;
                            firstItem.querySelector('input[name*="[quantity]"]').value = item.quantity;
                            firstItem.querySelector('input[name*="[price]"]').value = item.price;
                        }
                    } else {
                        addInvoiceItem();
                        const newItem = document.querySelectorAll('.invoice-item')[index];
                        newItem.querySelector('input[name*="[description]"]').value = item.description;
                        newItem.querySelector('input[name*="[quantity]"]').value = item.quantity;
                        newItem.querySelector('input[name*="[price]"]').value = item.price;
                    }
                });
            } else {
                addInvoiceItem();
            }
            
            // Update modal title and button
            document.querySelector('#invoice-modal h3').textContent = 'Edit Invoice';
            document.querySelector('#invoice-modal .btn-text').textContent = 'Update Invoice';
            
            updateInvoiceTotal();
            openModal('invoice-modal');
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteInvoice(invoiceId) {
    const invoiceRow = event.target.closest('tr');
    const invoiceNumber = invoiceRow.querySelector('a').textContent.trim();
    const deleteUrl = `{{ route('invoices.index') }}/${invoiceId}`;
    
    openDeleteModal(invoiceId, 'invoice', invoiceNumber, deleteUrl);
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

updateInvoiceTotal();
</script>
@endpush
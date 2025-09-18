@extends('layouts.app')

@section('title', 'Quotations')

@section('content')
<h1 class="page-title">Quotation Management</h1>
<p class="page-subtitle">Generate and manage project quotations.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    <button class="btn btn-primary" onclick="openModal('quotation-modal')">
        <i class="fas fa-plus"></i>
        Create New Quotation
    </button>
</div>

<x-data-table 
    title="All Quotations" 
    :headers="['Quote #', 'Client', 'Project', 'Total', 'Valid Until', 'Status']"
    searchPlaceholder="Search quotations..."
    :pagination="$quotations">
    
    @forelse($quotations as $quotation)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <a href="{{ route('quotations.show', $quotation) }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">
                {{ $quotation->quotation_number }}
            </a>
        </td>
        <td style="padding: 1rem;">{{ $quotation->client->name }}</td>
        <td style="padding: 1rem;">{{ $quotation->project->title ?? '-' }}</td>
        <td style="padding: 1rem;">{{ \App\Helpers\CurrencyHelper::format($quotation->total) }}</td>
        <td style="padding: 1rem;">{{ $quotation->valid_until->format('Y-m-d') }}</td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $quotation->status === 'converted' ? '#dcfce7' : ($quotation->status === 'active' ? 'var(--light-yellow)' : '#fef2f2') }}; 
                color: {{ $quotation->status === 'converted' ? 'var(--success)' : ($quotation->status === 'active' ? 'var(--dark-yellow)' : 'var(--error)') }};">
                {{ ucfirst($quotation->status) }}
            </span>
        </td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editQuotation({{ $quotation->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="deleteQuotation({{ $quotation->id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" style="padding: 2rem; text-align: center; color: var(--gray-600);">No quotations found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="quotation-modal" title="Add New Quotation" size="large">
    <form id="quotationForm">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <x-form-field label="Client" name="client_id" type="select" :required="true" 
                :options="$clients->pluck('name', 'id')->toArray()" 
                placeholder="Select client" />
            <x-form-field label="Project" name="project_id" type="select" 
                :options="$projects->pluck('title', 'id')->toArray()" 
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
        
        <div class="quotation-summary" style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <x-form-field label="Valid Until" name="valid_until" type="date" :required="true" value="{{ now()->addDays(\App\Helpers\SettingsHelper::quotationValidity())->format('Y-m-d') }}" />
                <x-form-field label="Status" name="status" type="select" 
                    :options="['active' => 'Active', 'expired' => 'Expired', 'converted' => 'Converted', 'cancelled' => 'Cancelled']" 
                    value="active" />
            </div>
            <x-form-field label="Notes" name="notes" type="textarea" placeholder="Additional notes..." />
            <div style="background: var(--gray-50); padding: 1rem; border-radius: 8px; text-align: center;">
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

document.getElementById('quotationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    
    btn.disabled = true;
    btnText.textContent = 'Saving...';
    
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
    
    const editId = this.getAttribute('data-edit-id');
    let url = '{{ route("quotations.store") }}';
    let method = 'POST';
    
    if (editId) {
        url = url.replace('/store', `/${editId}`);
        method = 'PUT';
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
            closeModal('quotation-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to save quotation', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = editId ? 'Update Quotation' : 'Create Quotation';
    });
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
            editRecord(quotationId, 'quotation', response.data);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteQuotation(quotationId) {
    const quotationRow = event.target.closest('tr');
    const quotationNumber = quotationRow.querySelector('a').textContent.trim();
    const deleteUrl = `{{ route('quotations.index') }}/${quotationId}`;
    
    openDeleteModal(quotationId, 'quotation', quotationNumber, deleteUrl);
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

updateQuotationTotal();
</script>
@endpush
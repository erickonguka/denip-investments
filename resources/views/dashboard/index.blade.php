@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="page-title">Dashboard Overview</h1>
    <p class="page-subtitle">Welcome back! Here's what's happening with your business today.</p>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value">{{ $stats['total_clients'] }}</div>
                    <div class="stat-label">Total Clients</div>
                </div>
                <div class="stat-icon blue">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value">{{ $stats['active_projects'] }}</div>
                    <div class="stat-label">Active Projects</div>
                </div>
                <div class="stat-icon yellow">
                    <i class="fas fa-project-diagram"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value">{{ \App\Helpers\CurrencyHelper::format($stats['pending_invoices_amount']) }}
                    </div>
                    <div class="stat-label">Pending Invoices</div>
                </div>
                <div class="stat-icon blue">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value">{{ $stats['proposals_in_progress'] }}</div>
                    <div class="stat-label">Proposals in Progress</div>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-file-contract"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value">{{ $stats['pending_bookings'] }}</div>
                    <div class="stat-label">Pending Bookings</div>
                </div>
                <div class="stat-icon warning">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-value">{{ $stats['approved_bookings'] }}</div>
                    <div class="stat-label">Approved Bookings</div>
                </div>
                <div class="stat-icon success">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
        <button class="btn btn-primary" onclick="openModal('invoice-modal')">
            <i class="fas fa-plus"></i>
            Create Invoice
        </button>
        <button class="btn btn-secondary" onclick="openModal('project-modal')">
            <i class="fas fa-plus"></i>
            New Project
        </button>
        <button class="btn"
            style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);"
            onclick="openModal('client-modal')">
            <i class="fas fa-plus"></i>
            Add Client
        </button>
    </div>

    <!-- Recent Activity -->
    <x-data-table title="Recent Activity" :headers="[]" :actions="false">
        @foreach ($recent_activities as $activity)
            <tr style="border-bottom: 1px solid var(--gray-200);">
                <td style="padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                    @if ($activity->user && $activity->user->profile_photo)
                        <img src="{{ asset('storage/' . $activity->user->profile_photo) }}"
                            alt="{{ $activity->user->name }}"
                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div
                            style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: var(--primary-blue); color: white;">
                            @if ($activity->user)
                                {{ strtoupper(substr($activity->user->name, 0, 2)) }}
                            @else
                                <i
                                    class="fas fa-{{ $activity->action === 'login' ? 'sign-in-alt' : ($activity->action === 'created' ? 'plus' : 'edit') }}"></i>
                            @endif
                        </div>
                    @endif
                    <div style="flex: 1;">
                        <div style="margin-bottom: 0.25rem;">{{ $activity->description }}</div>
                        <div style="font-size: 0.8rem; color: var(--gray-600);">
                            @if ($activity->user && $activity->user->isClient())
                                <span
                                    style="background: var(--warning); color: white; padding: 0.125rem 0.375rem; border-radius: 8px; font-size: 0.65rem; margin-right: 0.5rem;">Client</span>
                            @elseif($activity->user)
                                <span
                                    style="background: var(--primary-blue); color: white; padding: 0.125rem 0.375rem; border-radius: 8px; font-size: 0.65rem; margin-right: 0.5rem;">Team</span>
                            @endif
                            {{ $activity->user?->name ?? 'System' }} • {{ $activity->created_at->diffForHumans() }}
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </x-data-table>

    <!-- Client Modal -->
    <x-modal id="client-modal" title="Add New Client">
        <form id="clientForm">
            <x-form-field label="Client Name" name="name" :required="true" placeholder="Enter client name" />
            <x-form-field label="Company" name="company" placeholder="Enter company name" />
            <x-form-field label="Email" name="email" type="email" :required="true"
                placeholder="Enter email address" />
            <x-form-field label="Phone" name="phone" type="tel" placeholder="Enter phone number" />
            <x-form-field label="Client Type" name="type" type="select" :required="true" :options="['corporate' => 'Corporate', 'individual' => 'Individual']"
                placeholder="Select type" />
            <x-form-field label="Status" name="status" type="select" :options="['active' => 'Active', 'inactive' => 'Inactive']" value="active" />

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <span class="btn-text">Create Client</span>
                </button>
                <button type="button" class="btn"
                    style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);"
                    onclick="closeModal('client-modal')">Cancel</button>
            </div>
        </form>
    </x-modal>

    <!-- Project Modal -->
    <x-modal id="project-modal" title="Add New Project">
        <form id="projectForm">
            <x-form-field label="Project Title" name="title" :required="true" placeholder="Enter project title" />
            <x-form-field label="Client" name="client_id" type="select" :required="true" :options="$clients->pluck('name', 'id')->toArray()"
                placeholder="Select client" />
            <x-form-field label="Category" name="category_id" type="select" :required="true" :options="$categories->pluck('name', 'id')->toArray()"
                placeholder="Select category" />
            <div style="margin-bottom: 1.5rem;">
                <label
                    style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Description</label>
                <x-rich-text-editor name="description" id="project-description-editor" height="300px" />
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <x-form-field label="Start Date" name="start_date" type="date" :required="true" />
                <x-form-field label="End Date" name="end_date" type="date" />
            </div>

            <x-form-field label="Budget" name="budget" type="number" step="0.01" placeholder="0.00" />
            <x-form-field label="Status" name="status" type="select" :options="[
                'planning' => 'Planning',
                'active' => 'Active',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ]" value="planning" />
            <x-form-field label="Progress (%)" name="progress" type="number" min="0" max="100"
                value="0" />

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Project
                    Media</label>
                <x-upload-dropbox name="media[]" :multiple="true" maxSize="10" />
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Assigned
                    Users</label>
                <div
                    style="max-height: 150px; overflow-y: auto; border: 1px solid var(--gray-300); border-radius: 4px; padding: 0.5rem;">
                    @foreach ($users as $user)
                        <label style="display: flex; align-items: center; margin-bottom: 0.5rem; cursor: pointer;">
                            <input type="checkbox" name="assigned_users[]" value="{{ $user->id }}"
                                style="margin-right: 0.5rem;">
                            <span>{{ $user->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                <input type="checkbox" name="is_public" id="is_public" style="margin: 0;">
                <label for="is_public" style="margin: 0; font-weight: 600; color: var(--deep-blue);">Create Public
                    Link</label>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">
                    <span class="btn-text">Create Project</span>
                </button>
                <button type="button" class="btn"
                    style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);"
                    onclick="closeModal('project-modal')">Cancel</button>
            </div>
        </form>
    </x-modal>

    <!-- Invoice Modal -->
    <x-modal id="invoice-modal" title="Create New Invoice" size="large">
        <form id="invoiceForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <x-form-field label="Client" name="client_id" type="select" :required="true" :options="$clients->pluck('name', 'id')->toArray()"
                    placeholder="Select client" />
                <x-form-field label="Project" name="project_id" type="select" :options="$projects->pluck('title', 'id')->toArray()"
                    placeholder="Select project (optional)" />
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <x-form-field label="Issue Date" name="issue_date" type="date" :required="true"
                    :value="date('Y-m-d')" />
                <x-form-field label="Due Date" name="due_date" type="date" :required="true"
                    value="{{ now()->addDays((int) \App\Helpers\SettingsHelper::get('payment_terms', 30))->format('Y-m-d') }}" />
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Invoice
                    Items</label>
                <div class="invoice-items-container" style="overflow-x: auto;">
                    <div id="invoice-items">
                        <div class="invoice-item"
                            style="display: grid; grid-template-columns: 2fr 80px 80px 80px 40px; gap: 0.5rem; align-items: center; margin-bottom: 0.5rem; padding: 0.5rem; background: var(--gray-50); border-radius: 8px; min-width: 500px;">
                            <input type="text" name="items[0][description]" placeholder="Service Description"
                                style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; min-width: 150px;"
                                required>
                            <input type="number" name="items[0][quantity]" placeholder="Qty" min="1"
                                value="1"
                                style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;"
                                required>
                            <input type="number" name="items[0][price]" placeholder="Rate" step="0.01"
                                min="0"
                                style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none;"
                                required>
                            <input type="number" class="item-total" placeholder="Total" readonly
                                style="padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; outline: none; background: var(--gray-100);">
                            <button type="button" class="btn"
                                style="background: var(--error); color: white; padding: 0.5rem; min-width: 40px;"
                                onclick="removeInvoiceItem(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn"
                    style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue); margin-top: 0.5rem;"
                    onclick="addInvoiceItem()">
                    <i class="fas fa-plus"></i>
                    Add Item
                </button>
            </div>

            <div class="invoice-summary" style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <x-form-field label="Tax Rate (%)" name="tax_rate" type="number" step="0.01"
                        value="{{ \App\Helpers\SettingsHelper::taxRate() }}" />
                    <x-form-field label="Status" name="status" type="select" :options="['draft' => 'Draft', 'sent' => 'Sent', 'paid' => 'Paid', 'overdue' => 'Overdue']" value="draft" />
                </div>
                <x-form-field label="Notes" name="notes" type="textarea"
                    placeholder="{{ \App\Helpers\SettingsHelper::get('invoice_footer', 'Thank you for your business!') }}" />
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
                <button type="button" class="btn"
                    style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);"
                    onclick="closeModal('invoice-modal')">Cancel</button>
            </div>
        </form>
    </x-modal>
@endsection

@push('scripts')
    <script>
        let invoiceItemIndex = 1;

        // Form submission
        document.getElementById('clientForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = this.querySelector('button[type="submit"]');
            const btnText = btn.querySelector('.btn-text');
            const editId = this.getAttribute('data-edit-id');

            btn.disabled = true;
            btnText.textContent = editId ? 'Updating...' : 'Saving...';

            handleFormSubmit(this, '{{ route('clients.store') }}')
                .then(response => {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        closeModal('client-modal');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification('Failed to save client', 'error');
                    }
                })
                .catch(() => {
                    showNotification('An error occurred', 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    btnText.textContent = editId ? 'Update Client' : 'Create Client';
                });
        });


        // Project Form
        document.getElementById('projectForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = this.querySelector('button[type="submit"]');
            const btnText = btn.querySelector('.btn-text');

            btn.disabled = true;
            btnText.textContent = 'Saving...';

            // Handle form data with files
            const formData = new FormData(this);

            // Ensure is_public is properly set as boolean
            const isPublicChecked = document.querySelector('[name="is_public"]').checked;
            formData.set('is_public', isPublicChecked ? '1' : '0');

            // Handle assigned users checkboxes
            const assignedUsersCheckboxes = document.querySelectorAll('[name="assigned_users[]"]');
            formData.delete('assigned_users[]');
            assignedUsersCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    formData.append('assigned_users[]', checkbox.value);
                }
            });

            fetch('/projects', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(response => {
                    if (response.success) {
                        showNotification(response.message, 'success');
                        closeModal('project-modal');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(response.message || 'Failed to save project', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('An error occurred: ' + error.message, 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    btnText.textContent = 'Create Project';
                });
        });

        // Invoice Form
        document.getElementById('invoiceForm').addEventListener('submit', function(e) {
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

            fetch('{{ route('invoices.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeModal('invoice-modal');
                        showNotification('Invoice created successfully!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification('Error creating invoice. Please try again.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error creating invoice. Please try again.', 'error');
                })
                .finally(() => {
                    btn.disabled = false;
                    btnText.textContent = 'Create Invoice';
                });
        });

        // Invoice Items Management
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

            const taxRateField = document.querySelector('input[name="tax_rate"]');
            const taxRate = taxRateField ? parseFloat(taxRateField.value) || 0 : 0;
            const tax = subtotal * (taxRate / 100);
            const total = subtotal + tax;

            document.getElementById('invoice-total').textContent = total.toFixed(2);
        }

        // Update totals on input change
        document.addEventListener('input', function(e) {
            if (e.target.matches('input[name*="[quantity]"], input[name*="[price]"], input[name="tax_rate"]')) {
                updateInvoiceTotal();
            }
        });

        // Initialize invoice total
        updateInvoiceTotal();
    </script>
@endpush

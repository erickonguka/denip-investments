@extends('layouts.app')

@section('title', 'Client Details')

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">{{ $client->name }}</h1>
        <p class="page-subtitle">{{ $client->company ?? 'Individual Client' }}</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="editClient({{ $client->id }})">
            <i class="fas fa-edit"></i>
            <span class="btn-text">Edit</span>
        </button>
        <button class="btn btn-secondary">
            <i class="fas fa-download"></i>
            <span class="btn-text">PDF</span>
        </button>
        <a href="{{ route('admin.messages.startChat', $client->user_id ?? $client->id) }}" class="btn btn-info">
            <i class="fas fa-envelope"></i>
            <span class="btn-text">Message</span>
        </a>
        <button class="btn btn-danger" onclick="deleteClient({{ $client->id }})">
            <i class="fas fa-trash"></i>
            <span class="btn-text">Delete</span>
        </button>
        <a href="{{ route('clients.index') }}" class="btn btn-outline">
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
    
    div[style*="grid-template-columns: 1fr 1fr"] {
        display: block !important;
    }
    
    div[style*="grid-template-columns: 1fr 1fr"] > div {
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

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Client Information -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
            Client Information
        </h3>
        <div style="display: grid; gap: 1rem;">
            <div>
                <strong>Name:</strong> {{ $client->name }}
            </div>
            <div>
                <strong>Email:</strong> {{ $client->email }}
            </div>
            <div>
                <strong>Phone:</strong> {{ $client->phone ?? 'Not provided' }}
            </div>
            <div>
                <strong>Type:</strong> {{ ucfirst($client->type) }}
            </div>
            <div>
                <strong>Status:</strong>
                <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                    background: {{ $client->status === 'active' ? 'var(--light-yellow)' : '#fef2f2' }}; 
                    color: {{ $client->status === 'active' ? 'var(--dark-yellow)' : 'var(--error)' }};">
                    {{ ucfirst($client->status) }}
                </span>
            </div>
            @if($client->user)
            <div>
                <strong>Company:</strong> {{ $client->user->company ?? 'Not provided' }}
            </div>
            <div>
                <strong>Job Title:</strong> {{ $client->user->job_title ?? 'Not provided' }}
            </div>
            <div>
                <strong>Industry:</strong> {{ $client->user->industry ?? 'Not provided' }}
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Stats -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
            Quick Stats
        </h3>
        <div style="display: grid; gap: 1rem;">
            <div style="display: flex; justify-content: space-between;">
                <span>Total Projects:</span>
                <strong>{{ $client->projects->count() }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Total Invoices:</span>
                <strong>{{ $client->invoices->count() }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Total Proposals:</span>
                <strong>{{ $client->proposals->count() }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Total Revenue:</span>
                <strong>{{ \App\Helpers\CurrencyHelper::format($client->invoices->where('status', 'paid')->sum('total')) }}</strong>
            </div>
        </div>
    </div>
</div>

@if($client->user)
<!-- Onboarding Details -->
<div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); margin-top: 2rem;">
    <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
        Onboarding Details
    </h3>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div style="display: grid; gap: 1rem;">
            <div>
                <strong>Project Type:</strong> {{ $client->user->project_type ?? 'Not specified' }}
            </div>
            <div>
                <strong>Project Scale:</strong> {{ $client->user->project_scale ?? 'Not specified' }}
            </div>
            <div>
                <strong>Project Timeline:</strong> {{ $client->user->project_timeline ?? 'Not specified' }}
            </div>
            <div>
                <strong>Contact Preference:</strong> {{ ucfirst($client->user->contact_preference ?? 'Not specified') }}
            </div>
            @if($client->user->company_size)
            <div>
                <strong>Company Size:</strong> {{ $client->user->company_size }}
            </div>
            @endif
        </div>
        <div style="display: grid; gap: 1rem;">
            @if($client->user->project_location)
            <div>
                <strong>Project Location:</strong> {{ $client->user->project_location }}
            </div>
            @endif
            @if($client->user->years_in_business)
            <div>
                <strong>Years in Business:</strong> {{ $client->user->years_in_business }}
            </div>
            @endif
            @if($client->user->registration_number)
            <div>
                <strong>Registration Number:</strong> {{ $client->user->registration_number }}
            </div>
            @endif
            @if($client->user->country)
            <div>
                <strong>Country:</strong> {{ strtoupper($client->user->country) }}
            </div>
            @endif
        </div>
    </div>
    @if($client->user->project_description)
    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
        <strong>Project Description:</strong>
        <p style="margin-top: 0.5rem; color: var(--gray-600); line-height: 1.6;">{{ $client->user->project_description }}</p>
    </div>
    @endif
</div>
@endif

<!-- Projects -->
@if($client->projects->count() > 0)
<div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); margin-top: 2rem;">
    <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
        Projects
    </h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: var(--gray-50);">
                    <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--gray-200);">Project</th>
                    <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--gray-200);">Status</th>
                    <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--gray-200);">Progress</th>
                    <th style="padding: 1rem; text-align: left; border-bottom: 1px solid var(--gray-200);">Budget</th>
                </tr>
            </thead>
            <tbody>
                @foreach($client->projects as $project)
                <tr>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">
                        <a href="{{ route('projects.show', $project) }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">
                            {{ $project->title }}
                        </a>
                    </td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">{{ ucfirst($project->status) }}</td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">{{ $project->progress }}%</td>
                    <td style="padding: 1rem; border-bottom: 1px solid var(--gray-200);">{{ \App\Helpers\CurrencyHelper::format($project->budget) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<x-modal id="client-modal" title="Edit Client" size="large">
    <form id="clientForm">
        <!-- Basic Information -->
        <div class="form-section">
            <h4 style="color: var(--primary); margin-bottom: 1rem; border-bottom: 2px solid var(--secondary); padding-bottom: 0.5rem; display: inline-block;">Basic Information</h4>
            <div class="form-grid">
                <x-form-field label="Client Name" name="name" :required="true" placeholder="Enter client name" />
                <x-form-field label="Email" name="email" type="email" :required="true" placeholder="Enter email address" />
                <x-form-field label="Phone" name="phone" type="tel" placeholder="Enter phone number" />
                <x-form-field label="Client Type" name="type" type="select" :required="true" 
                    :options="['corporate' => 'Corporate', 'individual' => 'Individual']" 
                    placeholder="Select type" />
                <x-form-field label="Status" name="status" type="select" 
                    :options="['active' => 'Active', 'inactive' => 'Inactive']" 
                    value="active" />
            </div>
        </div>
        
        <!-- Company Information (for self-registered clients) -->
        <div class="form-section" id="company-section">
            <h4 style="color: var(--primary); margin-bottom: 1rem; border-bottom: 2px solid var(--secondary); padding-bottom: 0.5rem; display: inline-block;">Company Information</h4>
            <div class="form-grid">
                <x-form-field label="Company" name="company" placeholder="Enter company name" />
                <x-form-field label="Job Title" name="job_title" placeholder="Enter job title" />
                <x-form-field label="Industry" name="industry" type="select" 
                    :options="[
                        'real-estate' => 'Real Estate Development',
                        'manufacturing' => 'Manufacturing',
                        'hospitality' => 'Hospitality & Tourism',
                        'retail' => 'Retail & Commercial',
                        'government' => 'Government/Public Sector',
                        'education' => 'Education',
                        'healthcare' => 'Healthcare',
                        'other' => 'Other'
                    ]" />
                <x-form-field label="Company Size" name="company_size" type="select" 
                    :options="[
                        'startup' => 'Startup (1-10 employees)',
                        'small' => 'Small (11-50 employees)',
                        'medium' => 'Medium (51-200 employees)',
                        'large' => 'Large (200+ employees)'
                    ]" />
                <x-form-field label="Years in Business" name="years_in_business" type="select" 
                    :options="[
                        'new' => 'New Business (0-1 years)',
                        'emerging' => 'Emerging (2-5 years)',
                        'established' => 'Established (6-15 years)',
                        'mature' => 'Mature (15+ years)'
                    ]" />
                <x-form-field label="Registration Number" name="registration_number" placeholder="Enter registration number" />
            </div>
        </div>
        
        <!-- Contact Preferences -->
        <div class="form-section" id="preferences-section">
            <h4 style="color: var(--primary); margin-bottom: 1rem; border-bottom: 2px solid var(--secondary); padding-bottom: 0.5rem; display: inline-block;">Contact Preferences</h4>
            <div class="form-grid">
                <x-form-field label="Contact Preference" name="contact_preference" type="select" 
                    :options="[
                        'email' => 'Email',
                        'phone' => 'Phone Call',
                        'whatsapp' => 'WhatsApp',
                        'meeting' => 'In-Person Meeting'
                    ]" />
                <x-form-field label="Country" name="country" placeholder="Country code (e.g., KE)" />
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary" style="flex: 1;">
                <span class="btn-text">Update Client</span>
            </button>
            <button type="button" class="btn btn-outline" style="flex: 1;" onclick="closeModal('client-modal')">Cancel</button>
        </div>
    </form>
</x-modal>

<style>
.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--light);
}

.form-section:last-of-type {
    border-bottom: none;
    margin-bottom: 1rem;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@push('scripts')
<script>
document.getElementById('clientForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    
    btn.disabled = true;
    btnText.textContent = 'Updating...';
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('{{ route("clients.update", $client) }}', {
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
            closeModal('client-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to update client', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Update Client';
    });
});

function editClient(clientId) {
    fetch(`{{ route('clients.index') }}/${clientId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            const form = document.getElementById('clientForm');
            const client = response.data;
            
            form.setAttribute('data-edit-id', clientId);
            
            // Populate basic client fields
            Object.keys(client).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field) field.value = client[key] || '';
            });
            
            // Populate user fields if client has associated user (self-registered)
            if (client.user) {
                Object.keys(client.user).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field) field.value = client.user[key] || '';
                });
                
                // Show additional sections for self-registered clients
                document.getElementById('company-section').style.display = 'block';
                document.getElementById('preferences-section').style.display = 'block';
            } else {
                // Hide additional sections for admin-created clients
                document.getElementById('company-section').style.display = 'none';
                document.getElementById('preferences-section').style.display = 'none';
            }
            
            openModal('client-modal');
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteClient(clientId) {
    const clientName = '{{ $client->name }}';
    const deleteUrl = `{{ route('clients.index') }}/${clientId}`;
    
    openDeleteModal(clientId, 'client', clientName, deleteUrl);
}


</script>
@endpush
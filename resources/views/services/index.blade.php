@extends('layouts.app')

@section('title', 'Services')

@section('content')
<h1 class="page-title">Service Management</h1>
<p class="page-subtitle">Manage services displayed on the website.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    <button class="btn btn-primary" onclick="openServiceModal()">
        <i class="fas fa-plus"></i>
        Add Service
    </button>
</div>

<x-data-table 
    title="All Services" 
    :headers="['Image', 'Name', 'Description', 'Features', 'Order', 'Status']"
    searchPlaceholder="Search services..."
    :pagination="$services">
    
    @forelse($services as $service)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            @if($service->image)
                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" style="width: 60px; height: 40px; border-radius: 4px; object-fit: cover;">
            @elseif($service->icon)
                <i class="{{ $service->icon }}" style="font-size: 2rem; color: var(--primary);"></i>
            @else
                <div style="width: 60px; height: 40px; background: var(--gray-200); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: var(--gray-500);">
                    <i class="fas fa-image"></i>
                </div>
            @endif
        </td>
        <td style="padding: 1rem;">
            <div style="font-weight: 600; color: var(--primary-blue);">{{ $service->name }}</div>
            @if($service->icon)
                <div style="font-size: 0.8rem; color: var(--gray); margin-top: 0.25rem;">{{ $service->icon }}</div>
            @endif
        </td>
        <td style="padding: 1rem;">{{ Str::limit($service->description, 80) }}</td>
        <td style="padding: 1rem;">
            @if($service->features && count($service->features) > 0)
                <div style="font-size: 0.8rem;">
                    @foreach(array_slice($service->features, 0, 2) as $feature)
                        <div style="margin-bottom: 0.25rem;">• {{ $feature }}</div>
                    @endforeach
                    @if(count($service->features) > 2)
                        <div style="color: var(--gray);">+{{ count($service->features) - 2 }} more</div>
                    @endif
                </div>
            @else
                <span style="color: var(--gray-400);">-</span>
            @endif
        </td>
        <td style="padding: 1rem;">{{ $service->order }}</td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $service->is_active ? '#dcfce7' : '#fef2f2' }}; 
                color: {{ $service->is_active ? 'var(--success)' : 'var(--error)' }};">
                {{ $service->is_active ? 'Active' : 'Inactive' }}
            </span>
        </td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editService({{ $service->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="deleteService({{ $service->id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" style="padding: 2rem; text-align: center; color: var(--gray-600);">No services found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="service-modal" title="Add Service">
    <form id="serviceForm" enctype="multipart/form-data">
        <x-form-field label="Service Name" name="name" :required="true" placeholder="Enter service name" />
        <x-form-field label="Description" name="description" type="textarea" :required="true" placeholder="Service description" />
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Icon (FontAwesome class)</label>
            <input type="text" name="icon" placeholder="e.g. fas fa-hammer, fas fa-building, fas fa-tools" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px;">
            <small style="color: var(--gray-600); font-size: 0.8rem;">Use FontAwesome icon classes</small>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Service Image</label>
            <input type="file" name="image" accept="image/*" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px;">
            <small style="color: var(--gray-600); font-size: 0.8rem;">Max size: 2MB. Formats: JPG, PNG, GIF</small>
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Features</label>
            <div id="features-container">
                <div class="feature-input" style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <input type="text" name="features[]" placeholder="Enter feature" style="flex: 1; padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px;">
                    <button type="button" onclick="removeFeature(this)" style="background: var(--error); color: white; border: none; padding: 0.5rem; border-radius: 4px; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <button type="button" onclick="addFeature()" style="background: var(--success); color: white; border: none; padding: 0.5rem 1rem; border-radius: 4px; cursor: pointer; font-size: 0.8rem;">
                <i class="fas fa-plus"></i> Add Feature
            </button>
        </div>
        
        <x-form-field label="Display Order" name="order" type="number" min="0" placeholder="0" />
        
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
            <input type="checkbox" name="is_active" id="is_active" checked style="margin: 0;">
            <label for="is_active" style="margin: 0; font-weight: 600; color: var(--deep-blue);">Active</label>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Add Service</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('service-modal')">Cancel</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
document.getElementById('serviceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = editId ? 'Updating...' : 'Adding...';
    
    const formData = new FormData(this);
    const url = editId ? `/services/${editId}` : '{{ route("services.store") }}';
    if (editId) formData.append('_method', 'PUT');
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            showNotification(response.message, 'success');
            closeModal('service-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(response.message || 'Failed to save service', 'error');
        }
    })
    .catch(error => showNotification('An error occurred', 'error'))
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = editId ? 'Update Service' : 'Add Service';
    });
});

function openServiceModal() {
    const form = document.querySelector('#service-modal form');
    form.reset();
    form.removeAttribute('data-edit-id');
    document.querySelector('#service-modal h3').textContent = 'Add Service';
    document.querySelector('#service-modal .btn-text').textContent = 'Add Service';
    document.querySelector('#is_active').checked = true;
    
    // Reset features
    const container = document.getElementById('features-container');
    container.innerHTML = `
        <div class="feature-input" style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
            <input type="text" name="features[]" placeholder="Enter feature" style="flex: 1; padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px;">
            <button type="button" onclick="removeFeature(this)" style="background: var(--error); color: white; border: none; padding: 0.5rem; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    openModal('service-modal');
}

function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'feature-input';
    div.style.cssText = 'display: flex; gap: 0.5rem; margin-bottom: 0.5rem;';
    div.innerHTML = `
        <input type="text" name="features[]" placeholder="Enter feature" style="flex: 1; padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px;">
        <button type="button" onclick="removeFeature(this)" style="background: var(--error); color: white; border: none; padding: 0.5rem; border-radius: 4px; cursor: pointer;">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeFeature(button) {
    const container = document.getElementById('features-container');
    if (container.children.length > 1) {
        button.parentElement.remove();
    }
}

function editService(serviceId) {
    fetch(`/services/${serviceId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            const form = document.getElementById('serviceForm');
            const service = response.data;
            
            form.setAttribute('data-edit-id', serviceId);
            document.querySelector('#service-modal h3').textContent = 'Edit Service';
            document.querySelector('#service-modal .btn-text').textContent = 'Update Service';
            
            // Populate basic fields
            Object.keys(service).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && key !== 'features') {
                    if (field.type === 'checkbox') {
                        field.checked = service[key];
                    } else if (field.type !== 'file') {
                        field.value = service[key] || '';
                    }
                }
            });
            
            // Populate features
            const container = document.getElementById('features-container');
            container.innerHTML = '';
            const features = service.features || [''];
            if (features.length === 0) features.push('');
            
            features.forEach(feature => {
                const div = document.createElement('div');
                div.className = 'feature-input';
                div.style.cssText = 'display: flex; gap: 0.5rem; margin-bottom: 0.5rem;';
                div.innerHTML = `
                    <input type="text" name="features[]" value="${feature}" placeholder="Enter feature" style="flex: 1; padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px;">
                    <button type="button" onclick="removeFeature(this)" style="background: var(--error); color: white; border: none; padding: 0.5rem; border-radius: 4px; cursor: pointer;">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                container.appendChild(div);
            });
            
            openModal('service-modal');
        }
    });
}

function deleteService(serviceId) {
    const serviceRow = event.target.closest('tr');
    const serviceName = serviceRow.querySelector('div[style*="font-weight: 600"]').textContent.trim();
    const deleteUrl = `{{ route('services.index') }}/${serviceId}`;
    openDeleteModal(serviceId, 'service', serviceName, deleteUrl);
}
</script>
@endpush
@extends('layouts.app')

@section('title', 'Careers')

@section('content')
<h1 class="page-title">Career Management</h1>
<p class="page-subtitle">Manage job postings and applications.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    <button class="btn btn-primary" onclick="openCareerModal()">
        <i class="fas fa-plus"></i>
        Create New Position
    </button>
</div>

<x-data-table 
    title="All Career Positions" 
    :headers="['Position', 'Location', 'Type', 'Salary Range', 'Applications', 'Status', 'Created']"
    searchPlaceholder="Search positions..."
    :pagination="$careers">
    
    @forelse($careers as $career)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <div>
                <a href="{{ route('careers.applications', $career) }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">
                    {{ $career->title }}
                </a>
                <div style="font-size: 0.8rem; color: var(--gray-500); margin-top: 0.25rem;">
                    {{ Str::limit($career->description, 60) }}
                </div>
            </div>
        </td>
        <td style="padding: 1rem;">{{ $career->location }}</td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $career->type === 'full-time' ? '#dcfce7' : ($career->type === 'part-time' ? '#fef3c7' : ($career->type === 'contract' ? '#dbeafe' : '#f3e8ff')) }}; 
                color: {{ $career->type === 'full-time' ? '#16a34a' : ($career->type === 'part-time' ? '#d97706' : ($career->type === 'contract' ? '#2563eb' : '#9333ea')) }};">
                {{ ucfirst(str_replace('-', ' ', $career->type)) }}
            </span>
        </td>
        <td style="padding: 1rem;">
            @if($career->salary_min && $career->salary_max)
                KSh {{ number_format($career->salary_min) }} - {{ number_format($career->salary_max) }}
            @else
                <span style="color: var(--gray-500); font-style: italic;">Not specified</span>
            @endif
        </td>
        <td style="padding: 1rem;">
            <a href="{{ route('careers.applications', $career) }}" style="color: var(--primary-blue); text-decoration: none;">
                {{ $career->applications_count }} applications
            </a>
        </td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $career->is_active ? '#dcfce7' : '#fef2f2' }}; 
                color: {{ $career->is_active ? 'var(--success)' : 'var(--error)' }};">
                {{ $career->is_active ? 'Active' : 'Inactive' }}
            </span>
        </td>
        <td style="padding: 1rem;">{{ $career->created_at->format('M j, Y') }}</td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editCareer({{ $career->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="openDeleteModal({{ $career->id }}, 'career', '{{ addslashes($career->title) }}', '/admin/careers/{{ $career->id }}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" style="padding: 2rem; text-align: center; color: var(--gray-600);">No career positions found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="career-modal" title="Add New Position">
    <form id="careerForm">
        <x-form-field label="Position Title" name="title" :required="true" placeholder="e.g. Senior Project Manager" />
        <x-form-field label="Location" name="location" :required="true" placeholder="e.g. Nairobi, Kenya" />
        <x-form-field label="Employment Type" name="type" type="select" :required="true" 
            :options="['full-time' => 'Full Time', 'part-time' => 'Part Time', 'contract' => 'Contract', 'internship' => 'Internship']" />
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <x-form-field label="Minimum Salary (KSh)" name="salary_min" type="number" step="1000" placeholder="50000" />
            <x-form-field label="Maximum Salary (KSh)" name="salary_max" type="number" step="1000" placeholder="100000" />
        </div>
        
        <x-form-field label="Job Description" name="description" type="textarea" :required="true" 
            placeholder="Describe the role, responsibilities, and what the candidate will be doing..." rows="4" />
        <x-form-field label="Requirements" name="requirements" type="textarea" :required="true" 
            placeholder="List the required qualifications, skills, and experience..." rows="4" />
        <x-form-field label="Benefits" name="benefits" type="textarea" 
            placeholder="Health insurance, performance bonuses, professional development..." rows="3" />
        
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
            <input type="checkbox" name="is_active" id="is_active" checked style="margin: 0;">
            <label for="is_active" style="margin: 0; font-weight: 600; color: var(--deep-blue);">Active (visible on website)</label>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Create Position</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('career-modal')">Cancel</button>
        </div>
    </form>
</x-modal>

<x-delete-modal />
@endsection

@push('scripts')
<script>
document.getElementById('careerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = editId ? 'Updating...' : 'Creating...';
    
    const formData = new FormData(this);
    formData.set('is_active', document.querySelector('[name="is_active"]').checked ? '1' : '0');
    
    const url = editId ? `/admin/careers/${editId}` : '{{ route("careers.store") }}';
    
    if (editId) {
        formData.append('_method', 'PUT');
    }
    
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
            closeModal('career-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(response.message || 'Failed to save position', 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = editId ? 'Update Position' : 'Create Position';
    });
});

function openCareerModal() {
    const form = document.querySelector('#career-modal form');
    const title = document.querySelector('#career-modal h3');
    const submitBtn = document.querySelector('#career-modal .btn-text');
    
    form.reset();
    form.removeAttribute('data-edit-id');
    document.querySelector('[name="is_active"]').checked = true;
    
    if (title) title.textContent = 'Add New Position';
    if (submitBtn) submitBtn.textContent = 'Create Position';
    
    openModal('career-modal');
}

function editCareer(careerId) {
    fetch(`/admin/careers/${careerId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            const form = document.getElementById('careerForm');
            const career = response.data;
            
            form.setAttribute('data-edit-id', careerId);
            document.querySelector('#career-modal h3').textContent = 'Edit Position';
            document.querySelector('#career-modal .btn-text').textContent = 'Update Position';
            
            Object.keys(career).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && field.type !== 'checkbox') {
                    field.value = career[key] || '';
                }
            });
            
            document.querySelector('[name="is_active"]').checked = career.is_active || false;
            
            openModal('career-modal');
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endpush
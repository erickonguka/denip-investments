@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<h1 class="page-title">Project Management</h1>
<p class="page-subtitle">Track and manage all your projects.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    <button class="btn btn-primary" onclick="openProjectModal()">
        <i class="fas fa-plus"></i>
        Create New Project
    </button>
</div>

<x-data-table 
    title="All Projects" 
    :headers="['Project Name', 'Client', 'Category', 'Start Date', 'Budget', 'Status', 'Progress', 'Created By']"
    searchPlaceholder="Search projects..."
    :pagination="$projects">
    
    @forelse($projects as $project)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <a href="{{ route('projects.show', $project) }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">
                {{ $project->title }}
            </a>
        </td>
        <td style="padding: 1rem;">{{ $project->client->name }}</td>
        <td style="padding: 1rem;">
            @if($project->category)
                <span style="background: #3b82f6; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">{{ $project->category->name }}</span>
            @else
                <span style="color: var(--gray-500); font-style: italic;">No Category</span>
            @endif
        </td>
        <td style="padding: 1rem;">{{ $project->start_date->format('Y-m-d') }}</td>
        <td style="padding: 1rem;">{{ \App\Helpers\CurrencyHelper::format($project->budget ?? 0) }}</td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $project->status === 'completed' ? '#dcfce7' : ($project->status === 'active' ? 'var(--light-yellow)' : '#fef2f2') }}; 
                color: {{ $project->status === 'completed' ? 'var(--success)' : ($project->status === 'active' ? 'var(--dark-yellow)' : 'var(--error)') }};">
                {{ ucfirst($project->status) }}
            </span>
        </td>
        <td style="padding: 1rem;">{{ $project->progress }}%</td>
        <td style="padding: 1rem;">
            @if($project->creator)
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    @if($project->creator->isClient())
                        <span style="padding: 0.2rem 0.5rem; background: #e3f2fd; color: #1976d2; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                            CLIENT
                        </span>
                    @else
                        <span style="padding: 0.2rem 0.5rem; background: #f3e5f5; color: #7b1fa2; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">
                            ADMIN
                        </span>
                    @endif
                    <span style="font-size: 0.9rem;">{{ $project->creator->name }}</span>
                </div>
            @else
                <span style="color: var(--gray-500); font-style: italic;">Unknown</span>
            @endif
        </td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editProject({{ $project->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                @if($project->is_public)
                <button class="btn" style="background: var(--success); color: white; padding: 0.5rem;" onclick="copyPublicLink('{{ route('projects.public.token', $project->public_token) }}')" title="Copy Public Link">
                    <i class="fas fa-link"></i>
                </button>
                @endif
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="deleteProject({{ $project->id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="9" style="padding: 2rem; text-align: center; color: var(--gray-600);">No projects found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="project-modal" title="Add New Project">
    <form id="projectForm">
        <x-form-field label="Project Title" name="title" :required="true" placeholder="Enter project title" />
        <x-form-field label="Client" name="client_id" type="select" :required="true" 
            :options="$clients->pluck('name', 'id')->toArray()" 
            placeholder="Select client" />
        <x-form-field label="Category" name="category_id" type="select" :required="true" 
            :options="$categories->pluck('name', 'id')->toArray()" 
            placeholder="Select category" />
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Description</label>
            <x-rich-text-editor name="description" id="project-description-editor" height="300px" />
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <x-form-field label="Start Date" name="start_date" type="date" :required="true" />
            <x-form-field label="End Date" name="end_date" type="date" />
        </div>
        
        <x-form-field label="Budget" name="budget" type="number" step="0.01" placeholder="0.00" />
        <x-form-field label="Status" name="status" type="select" 
            :options="['planning' => 'Planning', 'active' => 'Active', 'completed' => 'Completed', 'cancelled' => 'Cancelled']" 
            value="planning" />
        <x-form-field label="Progress (%)" name="progress" type="number" min="0" max="100" value="0" />
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Project Media</label>
            <x-upload-dropbox name="media[]" :multiple="true" maxSize="10" id="project-media-upload" :existingMedia="[]" />
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Assigned Users</label>
            <div style="max-height: 150px; overflow-y: auto; border: 1px solid var(--gray-300); border-radius: 4px; padding: 0.5rem;">
                @foreach($users as $user)
                <label style="display: flex; align-items: center; margin-bottom: 0.5rem; cursor: pointer;">
                    <input type="checkbox" name="assigned_users[]" value="{{ $user->id }}" style="margin-right: 0.5rem;">
                    <span>{{ $user->name }}</span>
                </label>
                @endforeach
            </div>
        </div>
        
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
            <input type="checkbox" name="is_public" id="is_public" style="margin: 0;">
            <label for="is_public" style="margin: 0; font-weight: 600; color: var(--deep-blue);">Create Public Link</label>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Create Project</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('project-modal')">Cancel</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
document.getElementById('projectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = editId ? 'Updating...' : 'Saving...';
    
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
    
    // Send existing media data for edit mode
    if (editId && window.currentProjectMedia) {
        formData.append('existing_media', JSON.stringify(window.currentProjectMedia));
    }
    
    const url = editId ? `/projects/${editId}` : '{{ route("projects.store") }}';
    
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
    .then(response => {
        console.log('Response status:', response.status);
        return response.json().then(data => ({ status: response.status, data }));
    })
    .then(({ status, data }) => {
        console.log('Response data:', data);
        if (status === 422) {
            // Validation errors
            if (data.errors) {
                const errorMessages = Object.values(data.errors).flat();
                showNotification(errorMessages.join('\n'), 'error');
            } else {
                showNotification(data.message || 'Validation failed', 'error');
            }
        } else if (data.success) {
            showNotification(data.message, 'success');
            closeModal('project-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Failed to save project', 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showNotification('An error occurred: ' + error.message, 'error');
    })
        .finally(() => {
            btn.disabled = false;
            btnText.textContent = editId ? 'Update Project' : 'Create Project';
        });
});

function openProjectModal() {
    // Reset form for create mode
    const form = document.querySelector('#project-modal form');
    const title = document.querySelector('#project-modal h3');
    const submitBtn = document.querySelector('#project-modal .btn-text');
    
    form.reset();
    form.removeAttribute('data-edit-id');
    
    // Hide existing media display
    const existingMedia = document.querySelector('#project-media-upload .existing-media');
    if (existingMedia) {
        existingMedia.style.display = 'none';
    }
    
    if (title) title.textContent = 'Add New Project';
    if (submitBtn) submitBtn.textContent = 'Create Project';
    
    // Reset Quill editor
    setTimeout(() => {
        if (window.project_description_editor) {
            window.project_description_editor.root.innerHTML = '';
        }
    }, 100);
    
    openModal('project-modal');
}

function editProject(projectId) {
    fetch(`/projects/${projectId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            const form = document.getElementById('projectForm');
            const project = response.data;
            
            // Set form to edit mode
            form.setAttribute('data-edit-id', projectId);
            document.querySelector('#project-modal h3').textContent = 'Edit Project';
            document.querySelector('#project-modal .btn-text').textContent = 'Update Project';
            
            // Populate basic fields
            Object.keys(project).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && field.type !== 'checkbox') {
                    field.value = project[key] || '';
                }
            });
            
            // Handle dates specifically
            if (project.start_date) {
                const startDate = form.querySelector('[name="start_date"]');
                if (startDate) startDate.value = project.start_date.split('T')[0];
            }
            if (project.end_date) {
                const endDate = form.querySelector('[name="end_date"]');
                if (endDate) endDate.value = project.end_date.split('T')[0];
            }
            
            // Handle public checkbox
            const publicCheckbox = form.querySelector('[name="is_public"]');
            if (publicCheckbox) {
                publicCheckbox.checked = project.is_public || false;
            }
            
            // Handle assigned users checkboxes
            const assignedUsersCheckboxes = form.querySelectorAll('[name="assigned_users[]"]');
            assignedUsersCheckboxes.forEach(checkbox => checkbox.checked = false);
            if (project.assigned_users) {
                project.assigned_users.forEach(userId => {
                    const checkbox = form.querySelector(`[name="assigned_users[]"][value="${userId}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }
            
            // Store current media globally for form submission
            window.currentProjectMedia = project.media || [];
            
            // The existing media will be handled by the component itself
            
            openModal('project-modal');
            
            // Load content into Quill editor after modal opens
            setTimeout(() => {
                if (window.project_description_editor && project.description) {
                    window.project_description_editor.root.innerHTML = project.description;
                }
            }, 500);
        }
    })
    .catch(error => console.error('Error:', error));
}

function copyPublicLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: var(--success);
            color: white;
            border-radius: 8px;
            z-index: 10000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        `;
        notification.textContent = 'Public link copied to clipboard!';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 3000);
    });
}

function deleteProject(projectId) {
    const projectRow = event.target.closest('tr');
    const projectTitle = projectRow.querySelector('a').textContent.trim();
    const deleteUrl = `{{ route('projects.index') }}/${projectId}`;
    
    openDeleteModal(projectId, 'project', projectTitle, deleteUrl);
}
</script>
@endpush
@extends('layouts.app')

@section('title', 'Project Details')

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">{{ $project->title }}</h1>
        <p class="page-subtitle">{{ $project->client->name }}</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="editProject({{ $project->id }})">
            <i class="fas fa-edit"></i>
            <span class="btn-text">Edit</span>
        </button>
        <a href="{{ route('projects.pdf', $project) }}" class="btn btn-secondary">
            <i class="fas fa-download"></i>
            <span class="btn-text">PDF</span>
        </a>
        <button class="btn btn-danger" onclick="deleteProject({{ $project->id }})">
            <i class="fas fa-trash"></i>
            <span class="btn-text">Delete</span>
        </button>
        <a href="{{ route('projects.index') }}" class="btn btn-outline">
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
    
    div[style*="grid-template-columns: 1fr 1fr"] {
        display: block !important;
    }
    
    div[style*="grid-template-columns: 1fr 1fr"] > div {
        margin-bottom: 1rem;
    }
    
    div[style*="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr))"] {
        display: block !important;
    }
    
    div[style*="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr))"] {
        display: block !important;
    }
}
</style>

<!-- Project Hero Image -->
@if($project->media && count($project->media) > 0)
    @php $firstImage = collect($project->media)->first(fn($m) => str_starts_with($m['type'], 'image/')) @endphp
    @if($firstImage)
    <div style="background: var(--white); border-radius: 12px; padding: 0; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); margin-bottom: 2rem; overflow: hidden;">
        <img src="{{ asset('storage/' . $firstImage['path']) }}" alt="{{ $project->title }}" style="width: 100%; height: 300px; object-fit: cover;">
    </div>
    @endif
@endif

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Project Details -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
            Project Details
        </h3>
        <div style="display: grid; gap: 1.5rem;">
            <div>
                <strong>Description:</strong>
                <div style="margin-top: 0.5rem; color: var(--gray-600);">{!! $project->description ?? 'No description provided' !!}</div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <strong>Start Date:</strong> {{ $project->start_date->format('M j, Y') }}
                </div>
                <div>
                    <strong>End Date:</strong> {{ $project->end_date?->format('M j, Y') ?? 'Not set' }}
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <strong>Budget:</strong> {{ \App\Helpers\CurrencyHelper::format($project->budget) }}
                </div>
                <div>
                    <strong>Progress:</strong> {{ $project->progress }}%
                </div>
            </div>
            <div>
                <strong>Status:</strong>
                <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                    background: {{ $project->status === 'completed' ? '#dcfce7' : ($project->status === 'active' ? 'var(--light-yellow)' : '#fef2f2') }}; 
                    color: {{ $project->status === 'completed' ? 'var(--success)' : ($project->status === 'active' ? 'var(--dark-yellow)' : 'var(--error)') }};">
                    {{ ucfirst($project->status) }}
                </span>
            </div>
            <div>
                <strong>Assigned Team:</strong>
                @if($project->assigned_users && count($project->assigned_users) > 0)
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
                    @foreach($assignedUsers as $user)
                    <div style="display: flex; align-items: center; gap: 0.5rem; background: var(--gray-100); padding: 0.25rem 0.75rem; border-radius: 15px; font-size: 0.9rem;">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--primary-blue); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.8rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <span>{{ $user->name }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="color: var(--gray-600); font-style: italic; margin-top: 0.5rem;">No users assigned</div>
                @endif
            </div>
            @if($project->is_public && $project->public_token)
            <div>
                <strong>Public Links:</strong>
                <div style="margin-top: 0.5rem;">
                    <div style="margin-bottom: 0.5rem;">
                        <label style="font-size: 0.9rem; color: var(--gray-600);">SEO-Friendly URL:</label>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="text" value="{{ route('landing.project.show', $project->slug) }}" readonly style="flex: 1; padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; background: var(--gray-50); font-size: 0.9rem;">
                            <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="copyPublicLink('{{ route('landing.project.show', $project->slug) }}')" title="Copy SEO Link">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 0.9rem; color: var(--gray-600);">Admin Share Token:</label>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <input type="text" value="{{ route('projects.public.token', $project->public_token) }}" readonly style="flex: 1; padding: 0.5rem; border: 1px solid var(--gray-300); border-radius: 4px; background: var(--gray-50); font-size: 0.9rem;">
                            <button class="btn" style="background: var(--secondary); color: white; padding: 0.5rem;" onclick="copyPublicLink('{{ route('projects.public.token', $project->public_token) }}')" title="Copy Token Link">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Project Stats -->
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
            Project Stats
        </h3>
        <div style="display: grid; gap: 1rem;">
            <div style="display: flex; justify-content: space-between;">
                <span>Invoices:</span>
                <strong>{{ $project->invoices->count() }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Proposals:</span>
                <strong>{{ $project->proposals->count() }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Quotations:</span>
                <strong>{{ $project->quotations->count() }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Total Billed:</span>
                <strong>{{ \App\Helpers\CurrencyHelper::format($project->invoices->sum('total')) }}</strong>
            </div>
        </div>
    </div>
</div>

<!-- Related Items -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem;">
    <!-- Invoices -->
    @if($project->invoices->count() > 0)
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Recent Invoices</h4>
        @foreach($project->invoices->take(3) as $invoice)
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--gray-200);">
            <a href="{{ route('invoices.show', $invoice) }}" style="color: var(--primary-blue); text-decoration: none;">{{ $invoice->invoice_number }}</a>
            <span>{{ \App\Helpers\CurrencyHelper::format($invoice->total) }}</span>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Proposals -->
    @if($project->proposals->count() > 0)
    <div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Proposals</h4>
        @foreach($project->proposals->take(3) as $proposal)
        <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--gray-200);">
            <a href="{{ route('proposals.show', $proposal) }}" style="color: var(--primary-blue); text-decoration: none;">{{ $proposal->title }}</a>
            <span style="font-size: 0.8rem; color: var(--gray-600);">{{ ucfirst($proposal->status) }}</span>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Project Media -->
<div style="background: var(--white); border-radius: 12px; padding: 2rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); margin-top: 2rem;">
    <h3 style="color: var(--deep-blue); margin-bottom: 1.5rem; border-bottom: 2px solid var(--yellow); padding-bottom: 0.5rem;">
        Project Media
    </h3>
    @if($project->media && count($project->media) > 0)
        @php 
            $firstImage = collect($project->media)->first(fn($m) => str_starts_with($m['type'], 'image/'));
            $otherMedia = collect($project->media)->filter(function($m) use ($firstImage) {
                return !$firstImage || $m['path'] !== $firstImage['path'];
            });
        @endphp
        @if($otherMedia->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
            @foreach($otherMedia as $media)
            <div style="background: var(--gray-50); border-radius: 8px; padding: 1rem; text-align: center; border: 1px solid var(--gray-200);">
                @if(str_starts_with($media['type'], 'image/'))
                    <img src="{{ asset('storage/' . $media['path']) }}" alt="{{ $media['name'] }}" style="width: 100%; height: 120px; object-fit: cover; border-radius: 6px; margin-bottom: 0.5rem;">
                @else
                    <div style="font-size: 3rem; color: var(--primary-blue); margin-bottom: 0.5rem;">
                        @if($media['type'] === 'application/pdf')
                            <i class="fas fa-file-pdf"></i>
                        @elseif(str_contains($media['type'], 'word'))
                            <i class="fas fa-file-word"></i>
                        @elseif(str_contains($media['type'], 'excel') || str_contains($media['type'], 'sheet'))
                            <i class="fas fa-file-excel"></i>
                        @else
                            <i class="fas fa-file"></i>
                        @endif
                    </div>
                @endif
                <p style="font-size: 0.9rem; font-weight: 600; margin-bottom: 0.25rem;">{{ $media['name'] }}</p>
                <p style="font-size: 0.8rem; color: var(--gray-600); margin-bottom: 0.5rem;">{{ number_format($media['size'] / 1024, 1) }} KB</p>
                <a href="{{ asset('storage/' . $media['path']) }}" download="{{ $media['name'] }}" class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem 1rem; text-decoration: none; border-radius: 4px; font-size: 0.8rem;">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align: center; color: var(--gray-600); font-style: italic; padding: 2rem;">
            No additional media files available for this project
        </div>
        @endif
    @else
    <div style="text-align: center; color: var(--gray-600); font-style: italic; padding: 2rem;">
        No media files available for this project
    </div>
    @endif
</div>

<x-modal id="project-modal" title="Edit Project">
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
            <x-rich-text-editor name="description" id="project-show-description-editor" height="300px" />
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
            <x-upload-dropbox name="media[]" :multiple="true" maxSize="10" id="project-media-upload" :existingMedia="$project->media ?? []" />
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
                <span class="btn-text">Update Project</span>
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
            
            // The existing media is already shown via the component prop
            
            openModal('project-modal');
            
            // Load content into Quill editor after modal opens
            setTimeout(() => {
                if (window.project_show_description_editor && project.description) {
                    window.project_show_description_editor.root.innerHTML = project.description;
                }
            }, 500);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteProject(projectId) {
    const projectTitle = '{{ $project->title }}';
    const deleteUrl = `{{ route('projects.index') }}/${projectId}`;
    
    openDeleteModal(projectId, 'project', projectTitle, deleteUrl);
}

function copyPublicLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        showNotification('Public link copied to clipboard!', 'success');
    }).catch(() => {
        showNotification('Failed to copy link', 'error');
    });
}
</script>
@endpush
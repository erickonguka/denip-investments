@extends('layouts.app')

@section('title', $category->name . ' - Categories')

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">{{ $category->name }}</h1>
        <p class="page-subtitle">{{ $category->description ?: 'Projects in this category' }}</p>
        <span style="padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600; 
            background: {{ $category->is_active ? 'var(--success-light)' : 'var(--gray-200)' }}; 
            color: {{ $category->is_active ? 'var(--success)' : 'var(--gray-600)' }}; display: inline-block; margin-top: 0.5rem;">
            {{ $category->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>
    <div class="page-actions">
        <button class="btn" style="background: var(--primary-blue); color: white;" 
                onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description) }}')">
            <i class="fas fa-edit"></i>
            <span class="btn-text">Edit</span>
        </button>
        <button class="btn" style="background: {{ $category->is_active ? 'var(--warning)' : 'var(--success)' }}; color: white;" 
                onclick="toggleCategory({{ $category->id }})">
            <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }}"></i>
            <span class="btn-text">{{ $category->is_active ? 'Deactivate' : 'Activate' }}</span>
        </button>
        <button class="btn" style="background: var(--error); color: white;" 
                onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->projects()->count() }})">
            <i class="fas fa-trash"></i>
            <span class="btn-text">Delete</span>
        </button>
        <a href="{{ route('categories.index') }}" class="btn" style="background: transparent; color: var(--gray-700); border: 2px solid var(--gray-300);">
            <i class="fas fa-arrow-left"></i>
            <span class="btn-text">Back</span>
        </a>
    </div>
</div>

<x-data-table 
    title="Projects in {{ $category->name }}" 
    :headers="['Project Name', 'Client', 'Start Date', 'Budget', 'Status', 'Progress', 'Created By']"
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
                <a href="{{ route('projects.show', $project) }}" class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;">
                    <i class="fas fa-eye"></i>
                </a>
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editProject({{ $project->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                @if($project->is_public)
                <button class="btn" style="background: var(--success); color: white; padding: 0.5rem;" onclick="copyPublicLink('{{ route('projects.public.token', $project->public_token) }}')" title="Copy Public Link">
                    <i class="fas fa-link"></i>
                </button>
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="8" style="padding: 2rem; text-align: center; color: var(--gray-600);">
            <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                <i class="fas fa-project-diagram" style="font-size: 3rem; opacity: 0.3;"></i>
                <p>No projects found in this category</p>
            </div>
        </td>
    </tr>
    @endforelse
</x-data-table>

@push('styles')
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
    
    .btn:last-child {
        grid-column: 1 / -1;
        margin-top: 0.5rem;
    }
    
    .btn-text { display: none; }
    .btn i { font-size: 1.1rem; }
}
</style>
@endpush

<!-- Edit Category Modal -->
<x-modal id="editCategoryModal" title="Edit Category">
    <form id="editCategoryForm">
        <x-form-field label="Category Name" name="name" :required="true" placeholder="Enter category name" />
        <x-form-field label="Description" name="description" type="textarea" placeholder="Enter category description" />
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Update Category</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('editCategoryModal')">Cancel</button>
        </div>
    </form>
</x-modal>

@push('scripts')
<script>
document.getElementById('editCategoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const categoryId = this.getAttribute('data-category-id');
    
    btn.disabled = true;
    btnText.textContent = 'Updating...';
    
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    
    fetch(`/categories/${categoryId}`, {
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
            showNotification('Category updated successfully', 'success');
            closeModal('editCategoryModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(response.message || 'Failed to update category', 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Update Category';
    });
});

function editCategory(id, name, description) {
    const form = document.getElementById('editCategoryForm');
    form.setAttribute('data-category-id', id);
    form.querySelector('[name="name"]').value = name;
    form.querySelector('[name="description"]').value = description || '';
    openModal('editCategoryModal');
}

function toggleCategory(categoryId) {
    fetch(`/categories/${categoryId}/toggle`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            showNotification('Category status updated', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to update category', 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred', 'error');
    });
}

function deleteCategory(categoryId, categoryName, projectCount) {
    let message = `Are you sure you want to delete the category "${categoryName}"?`;
    
    if (projectCount > 0) {
        message += `\n\nWarning: This will also delete ${projectCount} project(s) associated with this category. This action cannot be undone.`;
    }
    
    showConfirmation(
        'Delete Category',
        message,
        async () => {
            try {
                const response = await fetch(`/categories/${categoryId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showNotification('Category deleted successfully', 'success');
                    setTimeout(() => window.location.href = '{{ route("categories.index") }}', 1000);
                } else {
                    showNotification(result.message || 'Failed to delete category', 'error');
                }
            } catch (error) {
                showNotification('An error occurred', 'error');
            }
        }
    );
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

function editProject(projectId) {
    window.location.href = `/projects/${projectId}`;
}
</script>
@endpush
@endsection
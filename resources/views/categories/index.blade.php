@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<h1 class="page-title">Category Management</h1>
<p class="page-subtitle">Organize projects by categories for better classification.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    <button class="btn btn-primary" onclick="openModal('createCategoryModal')">
        <i class="fas fa-plus"></i>
        Add Category
    </button>
</div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

<x-data-table 
    title="All Categories" 
    :headers="['Name', 'Icon', 'Description', 'Projects', 'Status']"
    searchPlaceholder="Search categories..."
    :pagination="null">
    
    @forelse($categories as $category)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <a href="{{ route('categories.show', $category) }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">
                {{ $category->name }}
            </a>
        </td>
        <td style="padding: 1rem; text-align: center;">
            @if($category->icon)
                <i class="{{ $category->icon }}" style="font-size: 1.5rem; color: var(--primary-blue);"></i>
            @else
                <span style="color: var(--gray-400);">-</span>
            @endif
        </td>
        <td style="padding: 1rem;">{{ $category->description ?? '-' }}</td>
        <td style="padding: 1rem;">
            <span style="background: var(--primary-blue); color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">{{ $category->projects_count ?? $category->projects()->count() }}</span>
        </td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $category->is_active ? 'var(--success-light)' : 'var(--gray-200)' }}; 
                color: {{ $category->is_active ? 'var(--success)' : 'var(--gray-600)' }};">
                {{ $category->is_active ? 'Active' : 'Inactive' }}
            </span>
        </td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" 
                        onclick="editCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description) }}', '{{ addslashes($category->icon) }}')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn" style="background: {{ $category->is_active ? 'var(--warning)' : 'var(--success)' }}; color: white; padding: 0.5rem;" 
                        onclick="toggleCategory({{ $category->id }})">
                    <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }}"></i>
                </button>
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" 
                        onclick="deleteCategory({{ $category->id }}, '{{ addslashes($category->name) }}', {{ $category->projects()->count() }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--gray-600);">
            <div style="display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                <i class="fas fa-tags" style="font-size: 3rem; opacity: 0.3;"></i>
                <p>No categories found</p>
            </div>
        </td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="createCategoryModal" title="Add New Category">
    <form id="createCategoryForm">
        <x-form-field label="Category Name" name="name" :required="true" placeholder="Enter category name" />
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark);">Icon (FontAwesome class)</label>
            <input type="text" name="icon" placeholder="e.g. fas fa-home, fas fa-building, fas fa-tools" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; font-size: 0.9rem;">
            <small style="color: var(--gray-600); font-size: 0.8rem; margin-top: 0.25rem; display: block;">Use FontAwesome icon classes (e.g., fas fa-home)</small>
        </div>
        <x-form-field label="Description" name="description" type="textarea" placeholder="Enter category description" />
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Create Category</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('createCategoryModal')">Cancel</button>
        </div>
    </form>
</x-modal>

<x-modal id="editCategoryModal" title="Edit Category">
    <form id="editCategoryForm">
        <x-form-field label="Category Name" name="name" :required="true" placeholder="Enter category name" />
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--dark);">Icon (FontAwesome class)</label>
            <input type="text" name="icon" placeholder="e.g. fas fa-home, fas fa-building, fas fa-tools" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px; font-size: 0.9rem;">
            <small style="color: var(--gray-600); font-size: 0.8rem; margin-top: 0.25rem; display: block;">Use FontAwesome icon classes (e.g., fas fa-home)</small>
        </div>
        <x-form-field label="Description" name="description" type="textarea" placeholder="Enter category description" />
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Update Category</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('editCategoryModal')">Cancel</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
document.getElementById('createCategoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    
    btn.disabled = true;
    btnText.textContent = 'Creating...';
    
    const formData = new FormData(this);
    
    fetch('{{ route("categories.store") }}', {
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
            showNotification('Category created successfully', 'success');
            closeModal('createCategoryModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(response.message || 'Failed to create category', 'error');
        }
    })
    .catch(error => {
        showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Create Category';
    });
});

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

function editCategory(id, name, description, icon) {
    const form = document.getElementById('editCategoryForm');
    form.setAttribute('data-category-id', id);
    form.querySelector('[name="name"]').value = name;
    form.querySelector('[name="description"]').value = description || '';
    form.querySelector('[name="icon"]').value = icon || '';
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
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showNotification(result.message || 'Failed to delete category', 'error');
                }
            } catch (error) {
                showNotification('An error occurred', 'error');
            }
        }
    );
}
</script>
@endpush
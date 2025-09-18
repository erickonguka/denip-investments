@extends('layouts.app')

@section('title', 'Blog Categories')

@section('content')
<h1 class="page-title">Blog Categories</h1>
<p class="page-subtitle">Manage blog post categories.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    <button class="btn btn-primary" onclick="openCategoryModal()">
        <i class="fas fa-plus"></i>
        Create New Category
    </button>
    <a href="{{ route('blogs.index') }}" class="btn" style="background: var(--secondary); color: white;">
        <i class="fas fa-arrow-left"></i>
        Back to Blog Posts
    </a>
</div>

<x-data-table 
    title="All Categories" 
    :headers="['Name', 'Description', 'Color', 'Posts', 'Status']"
    searchPlaceholder="Search categories..."
    :pagination="$categories">
    
    @forelse($categories as $category)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <span style="background: {{ $category->color }}; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                {{ $category->name }}
            </span>
        </td>
        <td style="padding: 1rem;">{{ Str::limit($category->description, 60) ?: '-' }}</td>
        <td style="padding: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 20px; height: 20px; background: {{ $category->color }}; border-radius: 4px;"></div>
                <span style="font-family: monospace;">{{ $category->color }}</span>
            </div>
        </td>
        <td style="padding: 1rem;">{{ $category->blogs_count }}</td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $category->is_active ? '#dcfce7' : '#fef2f2' }}; 
                color: {{ $category->is_active ? 'var(--success)' : 'var(--error)' }};">
                {{ $category->is_active ? 'Active' : 'Inactive' }}
            </span>
        </td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editCategory({{ $category->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="deleteCategory({{ $category->id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" style="padding: 2rem; text-align: center; color: var(--gray-600);">No categories found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="category-modal" title="Add New Category">
    <form id="categoryForm">
        <x-form-field label="Name" name="name" :required="true" placeholder="Enter category name" />
        <x-form-field label="Description" name="description" type="textarea" placeholder="Category description" />
        <x-form-field label="Color" name="color" type="color" value="#3b82f6" />
        
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
            <input type="checkbox" name="is_active" id="is_active" checked style="margin: 0;">
            <label for="is_active" style="margin: 0; font-weight: 600; color: var(--deep-blue);">Active</label>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Create Category</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('category-modal')">Cancel</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = editId ? 'Updating...' : 'Creating...';
    
    const formData = new FormData(this);
    const url = editId ? `/blog-categories/${editId}` : '{{ route("blog-categories.store") }}';
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
            closeModal('category-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(response.message || 'Failed to save category', 'error');
        }
    })
    .catch(error => showNotification('An error occurred: ' + error.message, 'error'))
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = editId ? 'Update Category' : 'Create Category';
    });
});

function openCategoryModal() {
    const form = document.querySelector('#category-modal form');
    form.reset();
    form.removeAttribute('data-edit-id');
    document.querySelector('#category-modal h3').textContent = 'Add New Category';
    document.querySelector('#category-modal .btn-text').textContent = 'Create Category';
    document.querySelector('#is_active').checked = true;
    openModal('category-modal');
}

function editCategory(categoryId) {
    fetch(`/blog-categories/${categoryId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            const form = document.getElementById('categoryForm');
            const category = response.data;
            
            form.setAttribute('data-edit-id', categoryId);
            document.querySelector('#category-modal h3').textContent = 'Edit Category';
            document.querySelector('#category-modal .btn-text').textContent = 'Update Category';
            
            Object.keys(category).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = category[key];
                    } else {
                        field.value = category[key] || '';
                    }
                }
            });
            
            openModal('category-modal');
        }
    });
}

function deleteCategory(categoryId) {
    const categoryRow = event.target.closest('tr');
    const categoryName = categoryRow.querySelector('span').textContent.trim();
    const deleteUrl = `{{ route('blog-categories.index') }}/${categoryId}`;
    openDeleteModal(categoryId, 'category', categoryName, deleteUrl);
}
</script>
@endpush
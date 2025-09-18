@extends('layouts.app')

@section('title', 'Blog Management')

@section('content')
<h1 class="page-title">Blog Management</h1>
<p class="page-subtitle">Create and manage blog posts.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    <button class="btn btn-primary" onclick="openBlogModal()">
        <i class="fas fa-plus"></i>
        Create New Post
    </button>
    <a href="{{ route('blog-categories.index') }}" class="btn" style="background: var(--secondary); color: white;">
        <i class="fas fa-tags"></i>
        Manage Categories
    </a>
</div>

<x-data-table 
    title="All Blog Posts" 
    :headers="['Title', 'Category', 'Author', 'Status', 'Views', 'Published']"
    searchPlaceholder="Search blog posts..."
    :pagination="$blogs">
    
    @forelse($blogs as $blog)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <a href="{{ route('blogs.show', $blog) }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">
                {{ $blog->title }}
            </a>
            <div style="font-size: 0.8rem; color: var(--gray-500); margin-top: 0.25rem;">
                {{ Str::limit($blog->excerpt, 60) }}
            </div>
        </td>
        <td style="padding: 1rem;">
            <span style="background: {{ $blog->category->color }}; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                {{ $blog->category->name }}
            </span>
        </td>
        <td style="padding: 1rem;">{{ $blog->author->name }}</td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $blog->status === 'published' ? '#dcfce7' : '#fef2f2' }}; 
                color: {{ $blog->status === 'published' ? 'var(--success)' : 'var(--error)' }};">
                {{ ucfirst($blog->status) }}
            </span>
        </td>
        <td style="padding: 1rem;">{{ number_format($blog->views) }}</td>
        <td style="padding: 1rem;">
            {{ $blog->published_at ? $blog->published_at->format('M j, Y') : '-' }}
        </td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editBlog('{{ $blog->slug }}', {{ $blog->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="deleteBlog('{{ $blog->slug }}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" style="padding: 2rem; text-align: center; color: var(--gray-600);">No blog posts found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="blog-modal" title="Add New Blog Post" size="large">
    <form id="blogForm" enctype="multipart/form-data">
        <x-form-field label="Title" name="title" :required="true" placeholder="Enter blog title" />
        <x-form-field label="Category" name="category_id" type="select" :required="true" 
            :options="App\Models\BlogCategory::where('is_active', true)->pluck('name', 'id')->toArray()" 
            placeholder="Select category" />
        <x-form-field label="Excerpt" name="excerpt" type="textarea" :required="true" 
            placeholder="Brief description (max 500 characters)" maxlength="500" />
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Content *</label>
            <x-rich-text-editor name="content" id="blog-content-editor" height="400px" />
        </div>
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Featured Image</label>
            <x-upload-dropbox name="featured_image" accept="image/*" maxSize="5" text="Drop featured image here or click to upload" id="blog-featured-image" />
        </div>
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Meta Keywords</label>
            <div id="keywords-container" style="border: 1px solid #ddd; border-radius: 4px; padding: 0.5rem; min-height: 40px; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                <input type="text" id="keyword-input" placeholder="Type keyword and press Enter" style="border: none; outline: none; flex: 1; min-width: 150px;">
            </div>
            <input type="hidden" name="meta_keywords" id="meta_keywords_hidden">
        </div>
        <x-form-field label="Meta Description" name="meta_description" type="textarea" 
            placeholder="SEO description (max 160 characters)" maxlength="160" />
        <x-form-field label="Status" name="status" type="select" 
            :options="['draft' => 'Draft', 'published' => 'Published']" 
            value="draft" />
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Create Post</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('blog-modal')">Cancel</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
let keywords = [];

function addKeyword(keyword) {
    if (keyword && !keywords.includes(keyword)) {
        keywords.push(keyword);
        renderKeywords();
    }
}

function removeKeyword(keyword) {
    keywords = keywords.filter(k => k !== keyword);
    renderKeywords();
}

function renderKeywords() {
    const container = document.getElementById('keywords-container');
    const input = document.getElementById('keyword-input');
    const hidden = document.getElementById('meta_keywords_hidden');
    
    container.innerHTML = '';
    
    keywords.forEach(keyword => {
        const tag = document.createElement('span');
        tag.style.cssText = 'background: var(--primary-blue); color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem; display: flex; align-items: center; gap: 0.25rem;';
        tag.innerHTML = `${keyword} <button type="button" onclick="removeKeyword('${keyword}')" style="background: none; border: none; color: white; cursor: pointer; font-size: 0.9rem;">×</button>`;
        container.appendChild(tag);
    });
    
    container.appendChild(input);
    hidden.value = keywords.join(',');
}

document.addEventListener('DOMContentLoaded', function() {
    const keywordInput = document.getElementById('keyword-input');
    if (keywordInput) {
        keywordInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const keyword = this.value.trim();
                if (keyword) {
                    addKeyword(keyword);
                    this.value = '';
                }
            }
        });
    }
});



document.addEventListener('DOMContentLoaded', function() {
    
    // Check if edit parameter is in URL
    const urlParams = new URLSearchParams(window.location.search);
    const editSlug = urlParams.get('edit');
    if (editSlug) {
        // Find the blog ID from the table
        const blogRows = document.querySelectorAll('tr');
        for (let row of blogRows) {
            const editBtn = row.querySelector('button[onclick*="editBlog"]');
            if (editBtn && editBtn.getAttribute('onclick').includes(editSlug)) {
                // Extract blog ID from onclick attribute
                const onclickAttr = editBtn.getAttribute('onclick');
                const match = onclickAttr.match(/editBlog\('([^']+)',\s*(\d+)\)/);
                if (match) {
                    const blogSlug = match[1];
                    const blogId = parseInt(match[2]);
                    setTimeout(() => editBlog(blogSlug, blogId), 500);
                    break;
                }
            }
        }
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

document.getElementById('blogForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = editId ? 'Updating...' : 'Creating...';
    

    
    const formData = new FormData(this);
    
    // Send existing media data for edit mode
    if (editId && window.currentBlogMedia) {
        formData.append('existing_media', JSON.stringify(window.currentBlogMedia));
    }
    
    const url = editId ? `/blogs/${editId}` : '{{ route("blogs.store") }}';
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
            closeModal('blog-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(response.message || 'Failed to save blog post', 'error');
        }
    })
    .catch(error => showNotification('An error occurred: ' + error.message, 'error'))
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = editId ? 'Update Post' : 'Create Post';
    });
});

function openBlogModal() {
    const form = document.querySelector('#blog-modal form');
    form.reset();
    form.removeAttribute('data-edit-id');
    
    // Remove any existing media display
    const existingMedia = document.querySelector('#blog-featured-image .existing-media');
    if (existingMedia) {
        existingMedia.remove();
    }
    
    document.querySelector('#blog-modal h3').textContent = 'Add New Blog Post';
    document.querySelector('#blog-modal .btn-text').textContent = 'Create Post';
    openModal('blog-modal');
}

function editBlog(blogSlug, blogId) {
    fetch(`/blogs/${blogSlug}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            const form = document.getElementById('blogForm');
            const blog = response.data;
            
            if (!form) {
                console.error('Blog form not found');
                showNotification('Blog form not found on this page', 'error');
                return;
            }
            
            form.setAttribute('data-edit-id', blogSlug);
            form.setAttribute('data-blog-id', blogId);
            
            const modalTitle = document.querySelector('#blog-modal h3');
            const modalBtn = document.querySelector('#blog-modal .btn-text');
            if (modalTitle) modalTitle.textContent = 'Edit Blog Post';
            if (modalBtn) modalBtn.textContent = 'Update Post';
            
            Object.keys(blog).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field && key !== 'content' && field.type !== 'file') {
                    if (key === 'meta_keywords' && Array.isArray(blog[key])) {
                        field.value = blog[key].join(', ');
                    } else {
                        field.value = blog[key] || '';
                    }
                }
            });
            
            // Load existing keywords
            if (blog.meta_keywords && Array.isArray(blog.meta_keywords)) {
                keywords = [...blog.meta_keywords];
                renderKeywords();
            }
            
            // Store current media globally for form submission
            window.currentBlogMedia = blog.featured_image || null;
            
            openModal('blog-modal');
            
            setTimeout(() => {
                if (window.blog_content_editor && blog.content) {
                    window.blog_content_editor.root.innerHTML = blog.content;
                }
            }, 500);
        }
    })
    .catch(error => {
        console.error('Error loading blog:', error);
        showNotification('Failed to load blog data', 'error');
    });
}

function deleteBlog(blogSlug) {
    const blogRow = event.target.closest('tr');
    const blogTitle = blogRow.querySelector('a').textContent.trim();
    const deleteUrl = `/blogs/${blogSlug}`;
    openDeleteModal(blogSlug, 'blog post', blogTitle, deleteUrl);
}
</script>
@endpush
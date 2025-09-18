@extends('layouts.app')

@section('title', 'View Blog Post')

@section('content')
<div class="page-header">
    <div class="page-header-content">
        <h1 class="page-title">{{ $blog->title }}</h1>
        <p class="page-subtitle">Published {{ $blog->published_at ? $blog->published_at->format('M j, Y') : 'Not published' }}</p>
    </div>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="editBlog('{{ $blog->slug }}', {{ $blog->id }})">
            <i class="fas fa-edit"></i>
            <span class="btn-text">Edit</span>
        </button>
        @if($blog->status === 'published')
        <a href="{{ route('landing.blog.show', $blog->slug) }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-external-link-alt"></i>
            <span class="btn-text">View Live</span>
        </a>
        @endif
        <button class="btn btn-danger" onclick="deleteBlog('{{ $blog->slug }}')">
            <i class="fas fa-trash"></i>
            <span class="btn-text">Delete</span>
        </button>
        <a href="{{ route('blogs.index') }}" class="btn btn-outline">
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
}
</style>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Content -->
    <div>
        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 20px var(--shadow);">
            <!-- Meta Info -->
            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--gray-200);">
                <span style="background: {{ $blog->category->color }}; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">
                    {{ $blog->category->name }}
                </span>
                <span style="color: var(--gray-600);">
                    <i class="fas fa-user"></i> {{ $blog->author->name }}
                </span>
                <span style="color: var(--gray-600);">
                    <i class="fas fa-eye"></i> {{ number_format($blog->views) }} views
                </span>
                <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                    background: {{ $blog->status === 'published' ? '#dcfce7' : '#fef2f2' }}; 
                    color: {{ $blog->status === 'published' ? 'var(--success)' : 'var(--error)' }};">
                    {{ ucfirst($blog->status) }}
                </span>
            </div>

            <!-- Featured Image -->
            @if($blog->featured_image)
            <div style="margin-bottom: 2rem;">
                <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" 
                    style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px;">
            </div>
            @endif

            <!-- Excerpt -->
            <div style="background: var(--light); padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; border-left: 4px solid var(--secondary);">
                <h4 style="color: var(--primary); margin-bottom: 0.5rem;">Excerpt</h4>
                <p style="color: var(--dark); line-height: 1.6; margin: 0;">{{ $blog->excerpt }}</p>
            </div>

            <!-- Content -->
            <div style="prose max-width: none; line-height: 1.8; color: #374151;">
                {!! $blog->content !!}
            </div>
        </div>

        <!-- Comments Section -->
        @if($blog->allComments->count() > 0)
        <div style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 20px var(--shadow); margin-top: 2rem;">
            <h3 style="color: var(--primary); margin-bottom: 1.5rem;">
                Comments ({{ $blog->allComments->count() }})
            </h3>
            
            @foreach($blog->approvedComments as $comment)
            <div style="border-bottom: 1px solid var(--gray-200); padding: 1rem 0;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                    <div>
                        <strong style="color: var(--primary);">{{ $comment->name }}</strong>
                        <span style="color: var(--gray-500); font-size: 0.85rem; margin-left: 0.5rem;">
                            {{ $comment->created_at->format('M j, Y \a\t g:i A') }}
                        </span>
                    </div>
                    <span style="padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.75rem; font-weight: 600; 
                        background: {{ $comment->status === 'approved' ? '#dcfce7' : ($comment->status === 'pending' ? '#fef3c7' : '#fef2f2') }}; 
                        color: {{ $comment->status === 'approved' ? 'var(--success)' : ($comment->status === 'pending' ? '#d97706' : 'var(--error)') }};">
                        {{ ucfirst($comment->status) }}
                    </span>
                </div>
                <p style="color: var(--dark); margin: 0;">{{ $comment->comment }}</p>
                
                @if($comment->replies->count() > 0)
                <div style="margin-left: 2rem; margin-top: 1rem; border-left: 2px solid var(--gray-200); padding-left: 1rem;">
                    @foreach($comment->replies as $reply)
                    <div style="margin-bottom: 1rem;">
                        <div style="margin-bottom: 0.5rem;">
                            <strong style="color: var(--primary); font-size: 0.9rem;">{{ $reply->name }}</strong>
                            <span style="color: var(--gray-500); font-size: 0.8rem; margin-left: 0.5rem;">
                                {{ $reply->created_at->format('M j, Y \a\t g:i A') }}
                            </span>
                        </div>
                        <p style="color: var(--dark); margin: 0; font-size: 0.9rem;">{{ $reply->comment }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div>
        <!-- SEO Info -->
        <div style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 20px var(--shadow); margin-bottom: 2rem;">
            <h4 style="color: var(--primary); margin-bottom: 1rem;">SEO Information</h4>
            
            @if($blog->meta_description)
            <div style="margin-bottom: 1rem;">
                <label style="font-weight: 600; color: var(--dark); font-size: 0.85rem;">Meta Description:</label>
                <p style="color: var(--gray-600); font-size: 0.9rem; margin: 0.25rem 0 0;">{{ $blog->meta_description }}</p>
            </div>
            @endif
            
            @if($blog->meta_keywords && count($blog->meta_keywords) > 0)
            <div>
                <label style="font-weight: 600; color: var(--dark); font-size: 0.85rem;">Keywords:</label>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.5rem;">
                    @foreach($blog->meta_keywords as $keyword)
                    <span style="background: var(--light); color: var(--primary); padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem;">
                        {{ $keyword }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>


    </div>
</div>

<style>
.prose h2 { font-size: 1.5rem; font-weight: 700; color: var(--primary); margin: 2rem 0 1rem; }
.prose h3 { font-size: 1.25rem; font-weight: 600; color: var(--primary); margin: 1.5rem 0 0.75rem; }
.prose p { margin-bottom: 1.5rem; }
.prose ul, .prose ol { margin: 1rem 0; padding-left: 2rem; }
.prose li { margin-bottom: 0.5rem; }
.prose blockquote { border-left: 4px solid var(--secondary); padding-left: 1rem; margin: 2rem 0; font-style: italic; color: #6b7280; }
.prose img { border-radius: 8px; margin: 2rem 0; }
</style>
@endsection





<x-modal id="blog-modal" title="Edit Blog Post" size="large">
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
            <x-upload-dropbox name="featured_image" accept="image/*" maxSize="5" text="Drop featured image here or click to upload" :existingMedia="$blog->featured_image" id="blog-featured-image" />
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
                <span class="btn-text">Update Post</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('blog-modal')">Cancel</button>
        </div>
    </form>
</x-modal>

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

document.getElementById('blogForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = 'Updating...';
    
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    
    if (editId && window.currentBlogMedia) {
        formData.append('existing_media', JSON.stringify(window.currentBlogMedia));
    }
    
    fetch(`/blogs/${editId}`, {
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
            showNotification(response.message || 'Failed to update blog post', 'error');
        }
    })
    .catch(error => showNotification('An error occurred: ' + error.message, 'error'))
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = 'Update Post';
    });
});

function editBlog(blogSlug, blogId) {
    const form = document.getElementById('blogForm');
    const blog = @json($blog);
    
    if (!form) {
        return;
    }
    
    form.setAttribute('data-edit-id', blogSlug);
    document.querySelector('#blog-modal h3').textContent = 'Edit Blog Post';
    document.querySelector('#blog-modal .btn-text').textContent = 'Update Post';
    
    Object.keys(blog).forEach(key => {
        const field = form.querySelector(`[name="${key}"]`);
        if (field && key !== 'content' && key !== 'meta_keywords' && field.type !== 'file') {
            field.value = blog[key] || '';
        }
    });
    
    // Load existing keywords
    if (blog.meta_keywords && Array.isArray(blog.meta_keywords)) {
        keywords = [...blog.meta_keywords];
        renderKeywords();
    }
    
    window.currentBlogMedia = blog.featured_image || null;
    
    const modal = document.getElementById('blog-modal');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        if (window.blog_content_editor && blog.content) {
            window.blog_content_editor.root.innerHTML = blog.content;
        }
    }, 500);
}

function deleteBlog(blogSlug) {
    const blogTitle = '{{ $blog->title }}';
    const deleteUrl = `/blogs/${blogSlug}`;
    openDeleteModal(blogSlug, 'blog post', blogTitle, deleteUrl);
}
</script>
@endpush
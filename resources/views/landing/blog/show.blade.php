@extends('layouts.landing')

@section('title', $seoData['title'])
@section('meta_description', $seoData['description'])
@section('meta_keywords', $seoData['keywords'])
@section('canonical', route('landing.blog.show', $blog->slug))

@section('og_title', $blog->title . ' - Construction Insights')
@section('og_description', $blog->excerpt)
@section('og_image', $blog->featured_image ? asset('storage/' . $blog->featured_image) : asset('img/seo/denip-blog-preview.jpg'))
@section('og_type', 'article')

@section('twitter_title', $blog->title)
@section('twitter_description', $blog->excerpt)
@section('twitter_image', $blog->featured_image ? asset('storage/' . $blog->featured_image) : asset('img/seo/denip-blog-preview.jpg'))

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BlogPosting",
  "headline": "{{ $blog->title }}",
  "description": "{{ $blog->excerpt }}",
  "image": "{{ $blog->featured_image ? asset('storage/' . $blog->featured_image) : asset('img/seo/denip-blog-preview.jpg') }}",
  "author": {
    "@@type": "Person",
    "name": "{{ $blog->author->name }}",
    "jobTitle": "Construction Expert"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "Denip Investments Ltd",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('img/denip-logo.svg') }}"
    }
  },
  "datePublished": "{{ $blog->published_at->format('Y-m-d\TH:i:s\Z') }}",
  "dateModified": "{{ $blog->updated_at->format('Y-m-d\TH:i:s\Z') }}",
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ route('landing.blog.show', $blog->slug) }}"
  },
  "articleSection": "{{ $blog->category->name }}",
  "keywords": [{{ $blog->meta_keywords ? '"' . implode('", "', $blog->meta_keywords) . '"' : '"construction", "building", "Kenya"' }}],
  "wordCount": "{{ str_word_count(strip_tags($blog->content)) }}",
  "timeRequired": "PT{{ $blog->reading_time ?? 5 }}M",
  "url": "{{ route('landing.blog.show', $blog->slug) }}",
  "isPartOf": {
    "@@type": "Blog",
    "name": "DENIP INVESTMENTS Construction Blog",
    "url": "{{ route('landing.blog.index') }}"
  },
  "about": {
    "@@type": "Thing",
    "name": "Construction Industry",
    "description": "Construction techniques, industry news, and building insights"
  },
  "audience": {
    "@@type": "Audience",
    "audienceType": "Construction professionals, property developers, building enthusiasts"
  }
}
</script>
@endpush

@section('content')
<!-- Reading Progress Bar -->
<div id="reading-progress" style="position: fixed; top: 70px; left: 0; width: 0%; height: 3px; background: var(--primary); z-index: 999; transition: width 0.3s ease;"></div>

<!-- Blog Hero -->
<section class="blog-hero" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 3rem 0;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                <a href="{{ route('landing.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">Home</a>
                <i class="fas fa-chevron-right" style="color: #9ca3af; font-size: 0.7rem;"></i>
                <a href="{{ route('landing.blog.index') }}" style="color: var(--primary); text-decoration: none; font-weight: 500;">Blog</a>
                <i class="fas fa-chevron-right" style="color: #9ca3af; font-size: 0.7rem;"></i>
                <span style="color: #6b7280;">{{ Str::limit($blog->title, 50) }}</span>
            </div>
        </nav>
        
        <!-- Article Meta -->
        <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <span style="background: {{ $blog->category->color }}; color: white; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                {{ $blog->category->name }}
            </span>
            <span style="color: #6b7280; font-size: 0.9rem; display: flex; align-items: center; gap: 0.3rem;">
                <i class="fas fa-calendar"></i> {{ $blog->published_at->format('F j, Y') }}
            </span>
            <span style="color: #6b7280; font-size: 0.9rem; display: flex; align-items: center; gap: 0.3rem;">
                <i class="fas fa-eye"></i> {{ number_format($blog->views) }} views
            </span>
            <span style="color: #6b7280; font-size: 0.9rem; display: flex; align-items: center; gap: 0.3rem;">
                <i class="fas fa-clock"></i> {{ $blog->reading_time ?? '5' }} min read
            </span>
        </div>
        
        <!-- Title -->
        <h1 style="font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 800; color: var(--secondary); line-height: 1.1; margin-bottom: 1.5rem; font-family: 'Playfair Display', serif;">
            {{ $blog->title }}
        </h1>
        
        <!-- Author -->
        <div style="display: flex; align-items: center; gap: 1rem;">
            @if($blog->author->profile_photo)
                <img src="{{ asset('storage/' . $blog->author->profile_photo) }}" alt="{{ $blog->author->name }}" 
                    style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            @else
                <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.1rem; border: 3px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    {{ strtoupper(substr($blog->author->name, 0, 2)) }}
                </div>
            @endif
            <div>
                <div style="font-weight: 700; color: var(--secondary); font-size: 1.1rem;">{{ $blog->author->name }}</div>
                <div style="font-size: 0.9rem; color: #6b7280;">Author & Construction Expert</div>
            </div>
        </div>
    </div>
</section>

<article style="padding: 3rem 0;">
    <div class="container">

        <div style="display: grid; grid-template-columns: 1fr 320px; gap: 4rem; align-items: start;" class="blog-layout">
            <!-- Main Content -->
            <div class="blog-content">

                <!-- Featured Image -->
                @if($blog->featured_image)
                <div style="margin-bottom: 3rem; position: relative;">
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" 
                        style="width: 100%; height: 450px; object-fit: cover; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
                    <div style="position: absolute; bottom: 1rem; right: 1rem; background: rgba(0,0,0,0.7); color: white; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.8rem;">
                        Featured Image
                    </div>
                </div>
                @endif

                <!-- Article Content -->
                <div class="article-content" style="max-width: none; line-height: 1.8; font-size: 1.1rem; color: #374151; margin-bottom: 4rem;">
                    {!! $blog->content !!}
                </div>

                <!-- Article Footer -->
                <footer style="background: #f8fafc; border-radius: 20px; padding: 2.5rem; margin-bottom: 4rem;">
                    @if($blog->meta_keywords && count($blog->meta_keywords) > 0)
                    <div style="margin-bottom: 2.5rem;">
                        <h4 style="color: var(--secondary); margin-bottom: 1.5rem; font-size: 1.2rem; font-weight: 700;">Related Topics</h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                            @foreach($blog->meta_keywords as $keyword)
                            <span style="background: white; color: var(--primary); padding: 0.6rem 1.2rem; border-radius: 25px; font-size: 0.9rem; font-weight: 600; border: 2px solid var(--primary); transition: all 0.3s ease;" 
                                onmouseover="this.style.background='var(--primary)'; this.style.color='white'" 
                                onmouseout="this.style.background='white'; this.style.color='var(--primary)'">
                                #{{ $keyword }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Share Section -->
                    <div style="text-align: center;">
                        <h4 style="color: var(--secondary); margin-bottom: 1.5rem; font-size: 1.2rem; font-weight: 700;">Share This Article</h4>
                        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(request()->url()) }}" 
                                target="_blank" style="background: #1da1f2; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(29,161,242,0.3)'" 
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                                target="_blank" style="background: #4267b2; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(66,103,178,0.3)'" 
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fab fa-facebook"></i> Facebook
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                                target="_blank" style="background: #0077b5; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: all 0.3s ease; display: flex; align-items: center; gap: 0.5rem;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,119,181,0.3)'" 
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fab fa-linkedin"></i> LinkedIn
                            </a>
                        </div>
                    </div>
                </footer>

                <!-- Comments Section -->
                <section class="comments-section">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2.5rem;">
                        <h3 style="color: var(--secondary); font-size: 2rem; font-weight: 800; margin: 0; font-family: 'Playfair Display', serif;">
                            Discussion
                        </h3>
                        <span style="background: var(--primary); color: white; padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                            {{ $blog->approvedComments->count() }} {{ $blog->approvedComments->count() === 1 ? 'Comment' : 'Comments' }}
                        </span>
                    </div>

                    <!-- Comment Form -->
                    <div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 2.5rem; border-radius: 20px; margin-bottom: 3rem; border: 1px solid #e2e8f0;">
                        <h4 style="color: var(--secondary); margin-bottom: 1.5rem; font-size: 1.3rem; font-weight: 700;">Join the Conversation</h4>
                        <form id="commentForm">
                            <div class="comment-form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                                <input type="text" name="name" placeholder="Your Name" required 
                                    style="padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; background: white; transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(243,156,18,0.1)'" 
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                <input type="email" name="email" placeholder="Your Email" required 
                                    style="padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; background: white; transition: all 0.3s ease;"
                                    onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(243,156,18,0.1)'" 
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            </div>
                            <textarea name="comment" rows="4" placeholder="Share your thoughts..." required 
                                style="width: 100%; padding: 1rem 1.25rem; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 1rem; margin-bottom: 1.5rem; resize: vertical; background: white; transition: all 0.3s ease;"
                                onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 3px rgba(243,156,18,0.1)'" 
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"></textarea>
                            <input type="hidden" name="parent_id" id="parent_id">
                            <div class="comment-form-buttons" style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                <button type="submit" class="btn btn-primary" style="padding: 0.875rem 2rem; font-size: 1rem; font-weight: 600;">
                                    <i class="fas fa-paper-plane"></i> Post Comment
                                </button>
                                <button type="button" id="cancelReply" class="btn btn-outline" style="display: none; padding: 0.875rem 2rem; font-size: 1rem; font-weight: 600;" onclick="cancelReply()">
                                    <i class="fas fa-times"></i> Cancel Reply
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Comments List -->
                    <div id="comments-list" style="space-y: 1.5rem;">
                        @if($blog->approvedComments->count() > 0)
                            @foreach($blog->approvedComments as $comment)
                            @include('landing.blog.partials.comment', ['comment' => $comment])
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 3rem; background: #f8fafc; border-radius: 16px; border: 2px dashed #e2e8f0;">
                                <i class="fas fa-comments" style="font-size: 3rem; color: #9ca3af; margin-bottom: 1rem;"></i>
                                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">No comments yet</h4>
                                <p style="color: #6b7280;">Be the first to share your thoughts!</p>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <aside class="blog-sidebar" style="position: sticky; top: 100px;">
                <!-- Table of Contents -->
                <div style="background: white; border-radius: 20px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9;">
                    <h3 style="color: var(--secondary); margin-bottom: 1.5rem; font-size: 1.3rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-list" style="color: var(--primary);"></i> Table of Contents
                    </h3>
                    <div id="toc" style="font-size: 0.9rem; line-height: 1.6;"></div>
                </div>

                <!-- Author Card -->
                <div style="background: linear-gradient(135deg, var(--primary) 0%, #e67e22 100%); border-radius: 20px; padding: 2rem; margin-bottom: 2rem; color: white; text-align: center;">
                    @if($blog->author->profile_photo)
                        <img src="{{ asset('storage/' . $blog->author->profile_photo) }}" alt="{{ $blog->author->name }}" 
                            style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem; border: 4px solid rgba(255,255,255,0.3);">
                    @else
                        <div style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.5rem; margin: 0 auto 1rem; border: 4px solid rgba(255,255,255,0.3);">
                            {{ strtoupper(substr($blog->author->name, 0, 2)) }}
                        </div>
                    @endif
                    <h4 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 0.5rem;">{{ $blog->author->name }}</h4>
                    <p style="opacity: 0.9; font-size: 0.9rem; margin-bottom: 1.5rem;">Construction Expert & Author</p>
                    <a href="{{ route('landing.blog.index') }}?author={{ $blog->author->id }}" 
                        style="background: rgba(255,255,255,0.2); color: white; padding: 0.6rem 1.2rem; border-radius: 25px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.3s ease; display: inline-block;"
                        onmouseover="this.style.background='rgba(255,255,255,0.3)'" 
                        onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                        View All Posts
                    </a>
                </div>

                <!-- Related Posts -->
                @if($relatedBlogs->count() > 0)
                <div style="background: white; border-radius: 20px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9;">
                    <h3 style="color: var(--secondary); margin-bottom: 1.5rem; font-size: 1.3rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-newspaper" style="color: var(--primary);"></i> Related Articles
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        @foreach($relatedBlogs as $related)
                        <a href="{{ route('landing.blog.show', $related->slug) }}" style="text-decoration: none; color: inherit; display: block; transition: all 0.3s ease; border-radius: 12px;"
                            onmouseover="this.style.background='#f8fafc'; this.style.transform='translateX(5px)'" 
                            onmouseout="this.style.background='transparent'; this.style.transform='translateX(0)'">
                            <article style="padding: 1rem;">
                                @if($related->featured_image)
                                <div style="height: 100px; overflow: hidden; border-radius: 10px; margin-bottom: 1rem;">
                                    <img src="{{ asset('storage/' . $related->featured_image) }}" alt="{{ $related->title }}" 
                                        style="width: 100%; height: 100%; object-fit: cover; transition: all 0.3s ease;"
                                        onmouseover="this.style.transform='scale(1.05)'" 
                                        onmouseout="this.style.transform='scale(1)'">
                                </div>
                                @endif
                                <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--secondary); margin-bottom: 0.5rem; line-height: 1.4;">
                                    {{ Str::limit($related->title, 50) }}
                                </h4>
                                <p style="font-size: 0.8rem; color: #6b7280; line-height: 1.5; margin-bottom: 0.5rem;">
                                    {{ Str::limit($related->excerpt, 60) }}
                                </p>
                                <div style="font-size: 0.75rem; color: var(--primary); font-weight: 600;">
                                    {{ $related->published_at->format('M j, Y') }}
                                </div>
                            </article>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Back to Blog -->
                <div style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9;">
                    <a href="{{ route('landing.blog.index') }}" class="btn btn-primary" style="width: 100%; text-align: center; padding: 1rem; font-size: 1rem; font-weight: 600;">
                        <i class="fas fa-arrow-left"></i> Back to All Articles
                    </a>
                </div>
            </aside>
        </div>
    </div>
</article>

<style>
/* Blog Specific Styles */
.blog-hero {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.article-content h2 { 
    font-size: 1.8rem; 
    font-weight: 700; 
    color: var(--secondary); 
    margin: 3rem 0 1.5rem; 
    font-family: 'Playfair Display', serif;
    border-bottom: 2px solid var(--primary);
    padding-bottom: 0.5rem;
}

.article-content h3 { 
    font-size: 1.4rem; 
    font-weight: 600; 
    color: var(--secondary); 
    margin: 2.5rem 0 1rem; 
    font-family: 'Playfair Display', serif;
}

.article-content h4 { 
    font-size: 1.2rem; 
    font-weight: 600; 
    color: var(--primary); 
    margin: 2rem 0 0.75rem; 
}

.article-content p { 
    margin-bottom: 1.5rem; 
    text-align: justify;
}

.article-content ul, .article-content ol { 
    margin: 1.5rem 0; 
    padding-left: 2rem; 
}

.article-content li { 
    margin-bottom: 0.75rem; 
    line-height: 1.7;
}

.article-content blockquote { 
    border-left: 4px solid var(--primary); 
    background: #f8fafc;
    padding: 1.5rem 2rem; 
    margin: 2.5rem 0; 
    font-style: italic; 
    color: #4a5568;
    border-radius: 0 12px 12px 0;
    position: relative;
}

.article-content blockquote::before {
    content: '"';
    position: absolute;
    top: -10px;
    left: 15px;
    font-size: 3rem;
    color: var(--primary);
    opacity: 0.3;
}

.article-content img { 
    border-radius: 12px; 
    margin: 2.5rem 0; 
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    width: 100%;
    height: auto;
}

.article-content a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
    border-bottom: 1px solid transparent;
    transition: all 0.3s ease;
}

.article-content a:hover {
    border-bottom-color: var(--primary);
}

/* Table of Contents */
#toc a {
    display: block;
    padding: 0.5rem 0;
    color: #6b7280;
    text-decoration: none;
    border-left: 2px solid transparent;
    padding-left: 1rem;
    transition: all 0.3s ease;
}

#toc a:hover, #toc a.active {
    color: var(--primary);
    border-left-color: var(--primary);
    background: #f8fafc;
    margin-left: -1rem;
    padding-left: 2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .blog-layout {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
    
    .blog-sidebar {
        position: static !important;
        order: 1;
    }
    
    .blog-content {
        order: 0;
    }
    
    .blog-hero {
        padding: 2rem 0 !important;
    }
    
    .article-content img {
        height: 250px !important;
        object-fit: cover !important;
    }
    
    .comment-form-grid {
        grid-template-columns: 1fr !important;
    }
    
    .comment-form-buttons {
        flex-direction: column;
    }
    
    .comment-form-buttons .btn {
        width: 100%;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .blog-hero {
        padding: 1.5rem 0 !important;
    }
    
    .blog-hero h1 {
        font-size: 2rem !important;
    }
    
    article {
        padding: 2rem 0 !important;
    }
    
    .container {
        padding: 0 1rem !important;
    }
    
    .article-content {
        font-size: 1rem !important;
    }
    
    .article-content h2 {
        font-size: 1.5rem !important;
    }
}
</style>

<script>
// Reading Progress Bar
function updateReadingProgress() {
    const article = document.querySelector('.article-content');
    const progressBar = document.getElementById('reading-progress');
    
    if (!article || !progressBar) return;
    
    const articleTop = article.offsetTop;
    const articleHeight = article.offsetHeight;
    const windowHeight = window.innerHeight;
    const scrollTop = window.pageYOffset;
    
    const progress = Math.min(100, Math.max(0, 
        ((scrollTop - articleTop + windowHeight) / articleHeight) * 100
    ));
    
    progressBar.style.width = progress + '%';
}

// Table of Contents Generation
function generateTableOfContents() {
    const toc = document.getElementById('toc');
    const headings = document.querySelectorAll('.article-content h2, .article-content h3');
    
    if (!toc || headings.length === 0) {
        toc.parentElement.style.display = 'none';
        return;
    }
    
    let tocHTML = '';
    headings.forEach((heading, index) => {
        const id = 'heading-' + index;
        heading.id = id;
        
        const level = heading.tagName === 'H2' ? 0 : 1;
        const indent = level * 1;
        
        tocHTML += `<a href="#${id}" style="margin-left: ${indent}rem; font-size: ${level === 0 ? '0.9rem' : '0.85rem'}; font-weight: ${level === 0 ? '600' : '500'};">${heading.textContent}</a>`;
    });
    
    toc.innerHTML = tocHTML;
    
    // Smooth scroll for TOC links
    toc.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

// Highlight active TOC item
function updateActiveTocItem() {
    const headings = document.querySelectorAll('.article-content h2, .article-content h3');
    const tocLinks = document.querySelectorAll('#toc a');
    
    let activeIndex = -1;
    
    headings.forEach((heading, index) => {
        const rect = heading.getBoundingClientRect();
        if (rect.top <= 100) {
            activeIndex = index;
        }
    });
    
    tocLinks.forEach((link, index) => {
        link.classList.toggle('active', index === activeIndex);
    });
}

// Comment Form Handler
document.getElementById('commentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Posting...';
    btn.disabled = true;
    
    try {
        const formData = new FormData(this);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
        
        const response = await fetch('{{ route("landing.blog.comment", $blog->slug) }}', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            btn.innerHTML = '<i class="fas fa-check"></i> Comment Posted!';
            btn.style.background = '#10b981';
            this.reset();
            document.getElementById('parent_id').value = '';
            document.getElementById('cancelReply').style.display = 'none';
            
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.style.cssText = 'background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46; padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 4px solid #10b981; box-shadow: 0 4px 12px rgba(16,185,129,0.2);';
            successDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + result.message;
            this.parentNode.insertBefore(successDiv, this);
            
            setTimeout(() => {
                successDiv.remove();
                btn.innerHTML = originalText;
                btn.style.background = '';
                btn.disabled = false;
            }, 4000);
        } else {
            throw new Error(result.message || 'Failed to post comment');
        }
    } catch (error) {
        btn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Failed to post';
        btn.style.background = '#ef4444';
        
        // Show error message
        const errorDiv = document.createElement('div');
        errorDiv.style.cssText = 'background: linear-gradient(135deg, #fecaca, #fca5a5); color: #991b1b; padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border-left: 4px solid #ef4444;';
        errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + error.message;
        this.parentNode.insertBefore(errorDiv, this);
        
        setTimeout(() => {
            errorDiv.remove();
            btn.innerHTML = originalText;
            btn.style.background = '';
            btn.disabled = false;
        }, 4000);
    }
});

function replyToComment(commentId, authorName) {
    document.getElementById('parent_id').value = commentId;
    document.querySelector('textarea[name="comment"]').placeholder = `Replying to ${authorName}...`;
    document.getElementById('cancelReply').style.display = 'inline-flex';
    document.querySelector('textarea[name="comment"]').focus();
    
    // Scroll to comment form
    document.getElementById('commentForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function cancelReply() {
    document.getElementById('parent_id').value = '';
    document.querySelector('textarea[name="comment"]').placeholder = 'Share your thoughts...';
    document.getElementById('cancelReply').style.display = 'none';
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    generateTableOfContents();
    updateReadingProgress();
    updateActiveTocItem();
});

// Update progress and active TOC item on scroll
window.addEventListener('scroll', function() {
    updateReadingProgress();
    updateActiveTocItem();
});

// Smooth scroll for all internal links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>
@endsection
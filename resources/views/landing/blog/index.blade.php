@extends('layouts.landing')

@section('title', 'Blog - Construction Insights | DENIP INVESTMENTS LTD | Kenya Construction News')
@section('meta_description', 'Stay updated with construction industry trends, project insights, and expert tips from DENIP INVESTMENTS. Read our latest blog posts about construction techniques, industry news, and project updates in Kenya.')
@section('meta_keywords', 'construction blog Kenya, building industry news, construction tips, project insights, construction techniques, building trends Kenya, construction industry updates')
@section('canonical', route('landing.blog.index'))

@section('og_title', 'Construction Blog - Industry Insights & Expert Tips')
@section('og_description', 'Stay updated with the latest construction industry trends, project insights, and expert tips from Kenya\'s premier construction company. Read our blog for construction news and updates.')
@section('og_image', asset('img/seo/denip-blog-preview.jpg'))
@section('og_type', 'website')

@section('twitter_title', 'Construction Blog - Expert Insights & Industry News')
@section('twitter_description', 'Latest construction industry trends, project insights, and expert tips from Kenya\'s leading construction company. Stay informed with our construction blog.')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Blog",
  "name": "DENIP INVESTMENTS Construction Blog",
  "description": "Construction industry insights, project updates, and expert tips from Kenya's premier construction company.",
  "url": "{{ route('landing.blog.index') }}",
  "publisher": {
    "@@type": "Organization",
    "name": "Denip Investments Ltd",
    "logo": {
      "@@type": "ImageObject",
      "url": "{{ asset('img/denip-logo.svg') }}"
    }
  },
  "mainEntityOfPage": {
    "@@type": "WebPage",
    "@@id": "{{ route('landing.blog.index') }}"
  },
  "blogPost": [
    @foreach($blogs->take(5) as $index => $blog)
    {
      "@@type": "BlogPosting",
      "headline": "{{ $blog->title }}",
      "description": "{{ $blog->excerpt }}",
      "url": "{{ route('landing.blog.show', $blog->slug) }}",
      "datePublished": "{{ $blog->published_at->format('Y-m-d') }}",
      "dateModified": "{{ $blog->updated_at->format('Y-m-d') }}",
      "author": {
        "@@type": "Person",
        "name": "{{ $blog->author->name }}"
      },
      "publisher": {
        "@@type": "Organization",
        "name": "Denip Investments Ltd"
      },
      "articleSection": "{{ $blog->category->name ?? 'Construction' }}",
      "wordCount": "{{ str_word_count(strip_tags($blog->content)) }}"
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
@endpush

@push('styles')
<style>


.blog-main {
    padding: var(--spacing-xl) 0;
    background: var(--light-gray);
}

.blog-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: var(--spacing-xl);
}

.search-bar {
    background: white;
    padding: var(--spacing-md);
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-bottom: var(--spacing-lg);
}

.search-form {
    display: flex;
    gap: var(--spacing-sm);
    align-items: center;
}

.search-input {
    flex: 1;
    padding: 0.875rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 1rem;
    transition: var(--transition);
}

.search-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(243, 156, 18, 0.1);
}

.blog-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-xl);
}

.blog-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: var(--transition);
    border: 1px solid #e5e7eb;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.blog-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    border-color: var(--primary);
}

.blog-image {
    height: 200px;
    overflow: hidden;
    position: relative;
}

.blog-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.blog-card:hover .blog-image img {
    transform: scale(1.05);
}

.blog-content {
    padding: var(--spacing-md);
    flex: 1;
    display: flex;
    flex-direction: column;
}

.blog-meta {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    margin-bottom: var(--spacing-sm);
}

.category-tag {
    padding: 0.3rem 0.8rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    color: white;
}

.blog-date {
    color: #6b7280;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

.blog-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--secondary);
    margin-bottom: var(--spacing-sm);
    line-height: 1.4;
    font-family: var(--font-serif);
}

.blog-title a {
    text-decoration: none;
    color: inherit;
    transition: var(--transition);
}

.blog-title a:hover {
    color: var(--primary);
}

.blog-excerpt {
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: var(--spacing-md);
    flex: 1;
}

.blog-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: var(--spacing-sm);
    border-top: 1px solid #e5e7eb;
}

.blog-author {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    color: #6b7280;
    font-size: 0.85rem;
}

.sidebar {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.sidebar-widget {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border: 1px solid #f0f0f0;
}

.widget-title {
    color: #2C3E50;
    margin-bottom: 1rem;
    font-size: 1.2rem;
    font-weight: 700;
    font-family: 'Playfair Display', serif;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #F39C12;
}

.category-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-xs);
}

.category-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
    color: #6b7280;
    border: 1px solid transparent;
    margin-bottom: 0.5rem;
}

.category-item:hover,
.category-item.active {
    background: #f8fafc;
    color: #F39C12;
    border-color: #F39C12;
    transform: translateX(3px);
}

.category-count {
    padding: 0.25rem 0.5rem;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
    min-width: 20px;
    text-align: center;
}

.recent-post {
    padding: 1rem;
    border-radius: 8px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s ease;
    display: block;
    border: 1px solid transparent;
    margin-bottom: 0.75rem;
}

.recent-post:hover {
    background: #f8fafc;
    border-color: #e5e7eb;
    transform: translateX(3px);
}

.recent-post-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #2C3E50;
    margin-bottom: 0.5rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.recent-post-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: #6b7280;
}

@media (max-width: 768px) {
    .blog-hero {
        height: 50vh;
    }
    
    .blog-layout {
        grid-template-columns: 1fr;
    }
    
    .search-form {
        flex-direction: column;
    }
    
    .blog-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
<x-landing.page-hero 
    title="Construction Insights"
    subtitle="Stay updated with industry trends, project insights, and expert construction tips"
    backgroundImage="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=75"
/>

<section class="blog-main" data-lazy>
    <div class="container">
        <div class="blog-layout">
            <!-- Main Content -->
            <div>
                <!-- Search & Filter Bar -->
                <div class="search-bar animate-fade-up">
                    <form method="GET" class="search-form">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search construction insights..." class="search-input">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request('search') || request('category'))
                        <a href="{{ route('landing.blog.index') }}" class="btn btn-outline">
                            <i class="fas fa-times"></i> Clear
                        </a>
                        @endif
                    </form>
                </div>

                <!-- Blog Posts Grid -->
                @if($blogs->count() > 0)
                <div class="blog-grid">
                    @foreach($blogs as $index => $blog)
                    <article class="blog-card animate-fade-up animate-delay-{{ ($index % 3) + 1 }}">
                        @if($blog->featured_image)
                        <div class="blog-image">
                            <img data-src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="lazy" style="width: 100%; height: 100%; object-fit: cover; background: #f3f4f6;">
                        </div>
                        @endif
                        
                        <div class="blog-content">
                            <div class="blog-meta">
                                <span class="category-tag" style="background: {{ $blog->category->color ?? '#F39C12' }}">
                                    {{ $blog->category->name ?? 'General' }}
                                </span>
                                <span class="blog-date">
                                    <i class="fas fa-calendar"></i> {{ $blog->published_at->format('M j, Y') }}
                                </span>
                            </div>
                            
                            <h2 class="blog-title">
                                <a href="{{ route('landing.blog.show', $blog->slug) }}">
                                    {{ $blog->title }}
                                </a>
                            </h2>
                            
                            <p class="blog-excerpt">
                                {{ $blog->excerpt }}
                            </p>
                            
                            <div class="blog-footer">
                                <div class="blog-author">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $blog->author->name }}</span>
                                    <span>•</span>
                                    <i class="fas fa-eye"></i>
                                    <span>{{ number_format($blog->views) }}</span>
                                </div>
                                <a href="{{ route('landing.blog.show', $blog->slug) }}" class="btn btn-outline btn-sm">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                {{ $blogs->links() }}
                @else
                <div style="text-align: center; padding: 4rem; color: var(--dark);">
                    <i class="fas fa-blog" style="font-size: 4rem; margin-bottom: 2rem; opacity: 0.3;"></i>
                    <h3>No Blog Posts Found</h3>
                    <p>{{ request('search') ? 'No posts match your search criteria.' : 'Check back soon for new content!' }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <aside class="sidebar">
                <!-- Categories -->
                <div class="sidebar-widget animate-slide-right">
                    <h3 class="widget-title">Categories</h3>
                    <div class="category-list">
                        <a href="{{ route('landing.blog.index') }}" class="category-item {{ !request('category') ? 'active' : '' }}">
                            <span>All Posts</span>
                            <span class="category-count" style="background: var(--secondary)">
                                {{ $blogs->total() }}
                            </span>
                        </a>
                        @foreach($categories as $category)
                        <a href="{{ route('landing.blog.index', ['category' => $category->slug]) }}" class="category-item {{ request('category') === $category->slug ? 'active' : '' }}">
                            <span>{{ $category->name }}</span>
                            <span class="category-count" style="background: {{ $category->color ?? '#F39C12' }}">
                                {{ $category->published_blogs_count }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Posts -->
                @php
                $recentPosts = App\Models\Blog::published()->with('category')->latest('published_at')->limit(5)->get();
                @endphp
                @if($recentPosts->count() > 0)
                <div class="sidebar-widget animate-slide-right animate-delay-1">
                    <h3 class="widget-title">Recent Posts</h3>
                    <div style="display: flex; flex-direction: column; gap: var(--spacing-sm);">
                        @foreach($recentPosts as $post)
                        <a href="{{ route('landing.blog.show', $post->slug) }}" class="recent-post">
                            <h4 class="recent-post-title">
                                {{ Str::limit($post->title, 50) }}
                            </h4>
                            <div class="recent-post-meta">
                                <span class="category-tag" style="background: {{ $post->category->color ?? '#F39C12' }}; font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                                    {{ $post->category->name ?? 'General' }}
                                </span>
                                <span>{{ $post->published_at->format('M j') }}</span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
        </div>
    </div>
</section>
@endsection
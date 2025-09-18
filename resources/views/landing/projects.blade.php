@extends('layouts.landing')

@section('title', 'Our Projects - DENIP INVESTMENTS LTD | Construction Portfolio Kenya')
@section('meta_description', 'Explore our exceptional portfolio of construction and infrastructure projects across Kenya. Residential, commercial, and infrastructure projects delivered with excellence and precision.')
@section('meta_keywords', 'construction projects Kenya, building portfolio, residential projects Kenya, commercial construction projects, infrastructure projects, construction company portfolio Nairobi')
@section('canonical', route('landing.projects'))

@section('og_title', 'Construction Projects Portfolio - DENIP INVESTMENTS LTD')
@section('og_description', 'Discover our exceptional construction and infrastructure projects delivered across Kenya. View our portfolio of residential, commercial, and infrastructure developments.')
@section('og_image', asset('img/seo/denip-projects-preview.jpg'))
@section('og_type', 'website')

@section('twitter_title', 'Construction Projects Portfolio - Quality Buildings in Kenya')
@section('twitter_description', 'Explore our portfolio of exceptional construction projects across Kenya. Residential, commercial, and infrastructure developments delivered with precision.')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "CollectionPage",
  "name": "Construction Projects Portfolio",
  "description": "Portfolio of construction and infrastructure projects delivered by Denip Investments Ltd across Kenya.",
  "url": "{{ route('landing.projects') }}",
  "mainEntity": {
    "@@type": "ItemList",
    "name": "Construction Projects",
    "description": "Completed construction projects including residential, commercial, and infrastructure developments.",
    "numberOfItems": "{{ $projects->total() ?? 0 }}",
    "itemListElement": [
      @foreach($projects->take(5) as $index => $project)
      {
        "@@type": "ListItem",
        "position": {{ $index + 1 }},
        "item": {
          "@@type": "CreativeWork",
          "name": "{{ $project->title }}",
          "description": "{{ Str::limit($project->description, 150) }}",
          "url": "{{ route('landing.project.show', $project->slug) }}",
          "creator": {
            "@@type": "Organization",
            "name": "Denip Investments Ltd"
          },
          "dateCreated": "{{ $project->created_at->format('Y-m-d') }}",
          "category": "{{ $project->category->name ?? 'Construction' }}"
        }
      }@if(!$loop->last),@endif
      @endforeach
    ]
  },
  "provider": {
    "@@type": "Organization",
    "name": "Denip Investments Ltd",
    "url": "{{ route('landing.index') }}"
  }
}
</script>
@endpush

@section('content')
<x-landing.page-hero 
    title="Our Project Portfolio"
    subtitle="Discover our exceptional construction and infrastructure projects delivered across Kenya with precision and excellence"
    backgroundImage="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=75"
/>

<!-- Projects Section -->
<section class="section" data-lazy>
    <div class="container">
        <div class="section-header">
            <h2>Our Projects</h2>
            <p>Showcasing our commitment to excellence through successful project deliveries across Kenya.</p>
        </div>
        
        <!-- Category Filter -->
        @if($categories->count() > 0)
        <div class="text-center mb-4">
            <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 0.5rem; margin-bottom: 2rem;">
                <a href="{{ route('landing.projects') }}" 
                   class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline' }} btn-sm">
                    All Projects
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('landing.projects', ['category' => $category->id]) }}" 
                       class="btn {{ request('category') == $category->id ? 'btn-primary' : 'btn-outline' }} btn-sm">
                        @if($category->icon)
                            <i class="{{ $category->icon }}"></i>
                        @endif
                        {{ $category->name }} ({{ $category->projects_count }})
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Projects Grid -->
        @if($projects->count() > 0)
            <div class="grid grid-2">
                @foreach($projects as $project)
                    <x-landing.project-card :project="$project" />
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($projects->hasPages())
                <div class="text-center mt-4">
                    {{ $projects->links() }}
                </div>
            @endif
        @else
            <div class="text-center" style="padding: 4rem 0;">
                <i class="fas fa-hard-hat" style="font-size: 4rem; color: var(--gray); opacity: 0.5; margin-bottom: 1rem;"></i>
                <h3 style="color: var(--secondary); margin-bottom: 1rem;">No Projects Available</h3>
                <p style="color: var(--gray);">We're working on exciting new projects. Check back soon!</p>
            </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="section" style="background: var(--secondary); color: white;" data-lazy>
    <div class="container text-center">
        <h2 style="color: white; margin-bottom: 1rem;">Ready to Start Your Project?</h2>
        <p style="font-size: 1.1rem; margin-bottom: 2rem; opacity: 0.9;">Let's discuss how we can bring your construction vision to life with our expertise and dedication.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <x-invest-button 
                text="Get Free Quote" 
                onclick="showQuoteModal()" 
                size="large" 
            />
            <x-invest-button 
                text="Contact Us" 
                href="{{ route('contact') }}" 
                size="large" 
                state="outline" 
            />
        </div>
    </div>
</section>
@endsection
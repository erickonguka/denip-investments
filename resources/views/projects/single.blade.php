@extends('layouts.landing')

@section('title', $project->title . ' - DENIP INVESTMENTS LTD | Construction Project')

@section('content')
<!-- Hero Section -->
<section class="project-hero" style="margin-top: -70px; padding-top: calc(2rem + 70px);">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb-nav">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                <a href="{{ route('landing.index') }}">Home</a>
                <i class="fas fa-chevron-right" style="color: rgba(255,255,255,0.5); font-size: 0.7rem;"></i>
                <a href="{{ route('landing.projects') }}">Projects</a>
                <i class="fas fa-chevron-right" style="color: rgba(255,255,255,0.5); font-size: 0.7rem;"></i>
                <span style="color: rgba(255,255,255,0.6);">{{ Str::limit($project->title, 30) }}</span>
            </div>
        </nav>
        
        <div style="position: relative; z-index: 2;">
            <div style="text-align: center; margin-bottom: 2rem;">
                @if($project->category)
                <span style="background: var(--primary); color: white; padding: 0.5rem 1.2rem; border-radius: 25px; font-size: 0.9rem; font-weight: 600; margin-bottom: 1.5rem; display: inline-block; box-shadow: 0 4px 15px rgba(243,156,18,0.3);">
                    @if($project->category->icon)<i class="{{ $project->category->icon }}"></i>@endif
                    {{ $project->category->name }}
                </span>
                @endif
                <h1>{{ $project->title }}</h1>
                <p style="font-size: 1.3rem; opacity: 0.9; margin-bottom: 1rem; color: var(--primary); font-weight: 600;">{{ $project->client->name }}</p>
                <div style="font-size: 1.1rem; opacity: 0.8; line-height: 1.6; margin-bottom: 2rem;">{!! Str::limit(strip_tags($project->description), 120) !!}</div>
            </div>
            <div style="text-align: center;">
                <span class="status-badge status-{{ strtolower($project->status) }}" style="font-size: 1rem; padding: 0.75rem 1.5rem;">{{ ucfirst($project->status) }}</span>
            </div>
        </div>
    </div>
</section>

<!-- Project Hero Image -->
@if($project->media && count($project->media) > 0)
    @php $firstImage = collect($project->media)->first(fn($m) => str_starts_with($m['type'], 'image/')) @endphp
    @if($firstImage)
    <section class="gallery-section" style="margin-top: 2rem;">
        <div class="container">
            <div class="gallery-container">
                <div class="gallery-main">
                    <div class="gallery-slide active">
                        <img src="{{ asset('storage/' . $firstImage['path']) }}" alt="{{ $project->title }} - Main Image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
@endif

<!-- Additional Project Gallery -->
@if($project->media && count($project->media) > 1)
    @php 
        $firstImage = collect($project->media)->first(fn($m) => str_starts_with($m['type'], 'image/'));
        $otherImages = collect($project->media)->filter(function($m) use ($firstImage) {
            return str_starts_with($m['type'], 'image/') && (!$firstImage || $m['path'] !== $firstImage['path']);
        });
    @endphp
    @if($otherImages->count() > 0)
    <section style="padding: 2rem 0;">
        <div class="container">
            <h3 style="color: var(--secondary); margin-bottom: 2rem; text-align: center;">Additional Images</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                @foreach($otherImages as $media)
                <div style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <img src="{{ asset('storage/' . $media['path']) }}" alt="{{ $project->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endif

<!-- Project Content -->
<section style="padding: 3rem 0;">
    <div class="container">
        <div class="content-layout">
            <!-- Main Content -->
            <div class="main-content">
                <!-- Project Overview -->
                <div class="content-card">
                    <h2>Project Overview</h2>
                    <div style="font-size: 1.2rem; line-height: 1.8; color: #4a5568; margin-bottom: 2.5rem;">{!! $project->description !!}</div>
                    
                    @if($project->features)
                    <h3 style="color: var(--secondary); font-size: 1.8rem; margin-bottom: 1.5rem; font-family: 'Playfair Display', serif;">Key Features & Highlights</h3>
                    <div class="features-grid">
                        @php 
                        $features = explode("\n", $project->features);
                        $icons = ['fas fa-check-circle', 'fas fa-star', 'fas fa-award', 'fas fa-shield-alt', 'fas fa-tools', 'fas fa-clock'];
                        @endphp
                        @foreach($features as $index => $feature)
                        @if(trim($feature))
                        <div class="feature-item">
                            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                                <i class="{{ $icons[$index % count($icons)] }}" style="color: var(--primary); font-size: 1.2rem; margin-top: 0.2rem;"></i>
                                <div style="color: #4a5568; line-height: 1.6;">{{ trim($feature) }}</div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Project Team -->
                @if($assignedUsers->count() > 0)
                <div class="content-card">
                    <h2>Expert Team</h2>
                    <p style="color: #6b7280; margin-bottom: 2rem; font-size: 1.1rem;">Our dedicated professionals ensuring project excellence at every stage.</p>
                    <div class="team-grid">
                        @foreach($assignedUsers as $user)
                        <div class="team-member">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" class="team-avatar" style="object-fit: cover;">
                            @else
                                <div class="team-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                            @endif
                            <h4 style="color: var(--secondary); margin-bottom: 0.5rem; font-size: 1.2rem;">{{ $user->name }}</h4>
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">Team Member</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Progress Chart -->
                <div class="sidebar-card" style="text-align: center;">
                    <h3 style="color: var(--secondary); margin-bottom: 2rem; font-size: 1.4rem; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                        <i class="fas fa-chart-pie" style="color: var(--primary);"></i> Project Progress
                    </h3>
                    <div class="progress-circle">
                        <svg width="150" height="150" style="transform: rotate(-90deg);">
                            <circle cx="75" cy="75" r="60" fill="none" stroke="#e5e7eb" stroke-width="12"></circle>
                            <circle cx="75" cy="75" r="60" fill="none" stroke="var(--primary)" stroke-width="12" 
                                    stroke-dasharray="{{ 2 * pi() * 60 }}" 
                                    stroke-dashoffset="{{ 2 * pi() * 60 * (1 - ($project->progress ?? 75) / 100) }}" 
                                    stroke-linecap="round" style="transition: stroke-dashoffset 1s ease;"></circle>
                        </svg>
                        <div class="progress-text">
                            <span class="progress-percentage">{{ $project->progress ?? 75 }}%</span>
                            <span style="font-size: 0.8rem; color: #6b7280; font-weight: 600;">Complete</span>
                        </div>
                    </div>
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; margin-top: 1rem;">
                        <p style="color: #6b7280; font-size: 0.9rem; margin: 0;">✅ Project is progressing on schedule</p>
                    </div>
                </div>

                <!-- Project Details -->
                <div class="sidebar-card">
                    <h3 style="color: var(--secondary); margin-bottom: 1.5rem; font-size: 1.2rem;">Project Details</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <strong>Client:</strong> {{ $project->client->name }}
                        </div>
                        @if($project->category)
                        <div>
                            <strong>Category:</strong> {{ $project->category->name }}
                        </div>
                        @endif
                        @if($project->start_date)
                        <div>
                            <strong>Started:</strong> {{ $project->start_date->format('M Y') }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- CTA Card -->
                <div class="sidebar-card" style="background: var(--secondary); color: white; text-align: center;">
                    <div>
                        <h4 style="color: white; margin-bottom: 1rem; font-size: 1.2rem;">Inspired by This Project?</h4>
                        <p style="opacity: 0.9; margin-bottom: 1.5rem; line-height: 1.5;">Let's discuss how we can bring your construction vision to life.</p>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <x-invest-button 
                                text="Get Free Quote" 
                                onclick="showQuoteModal()" 
                                size="normal" 
                            />
                            <x-invest-button 
                                text="Contact Us" 
                                href="{{ route('contact') }}" 
                                size="normal" 
                                state="outline" 
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="final-cta">
    <div class="container text-center">
        <div style="max-width: 600px; margin: 0 auto;">
            <div style="margin-bottom: 2rem;">
                <i class="fas fa-rocket" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"></i>
            </div>
            <h2 style="color: white; margin-bottom: 1.5rem; font-size: 2.5rem; font-family: 'Playfair Display', serif;">Ready to Start Your Project?</h2>
            <p style="font-size: 1.2rem; margin-bottom: 3rem; opacity: 0.9; line-height: 1.6;">Transform your vision into reality with Kenya's premier construction company. Let's build something extraordinary together.</p>
            <div style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap;">
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
    </div>
</section>

<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.gallery-slide');
const dots = document.querySelectorAll('.gallery-dot');

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });
    currentSlide = index;
}

function changeSlide(direction) {
    const newIndex = (currentSlide + direction + slides.length) % slides.length;
    showSlide(newIndex);
}

function goToSlide(index) {
    showSlide(index);
}

// Auto-advance slides
if (slides.length > 1) {
    setInterval(() => {
        changeSlide(1);
    }, 5000);
}

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') changeSlide(-1);
    if (e.key === 'ArrowRight') changeSlide(1);
});
</script>
@endsection
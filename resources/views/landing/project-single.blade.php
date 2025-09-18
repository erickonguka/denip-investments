@extends('layouts.landing')

@section('title', $project->title . ' - DENIP INVESTMENTS LTD | Construction Project')
@section('meta_description', Str::limit($project->description, 155) . ' - Quality construction project by DENIP INVESTMENTS LTD.')
@section('meta_keywords', 'construction project, {{ strtolower($project->category->name ?? "building") }} project Kenya, {{ strtolower($project->title) }}, construction portfolio, building project')
@section('canonical', route('landing.project.show', $project->slug))

@section('og_title', $project->title . ' - Construction Project')
@section('og_description', Str::limit($project->description, 155) . ' - Quality construction project delivered by Kenya\'s premier construction company.')
@section('og_image', $project->featured_image ? asset('storage/' . $project->featured_image) : asset('img/seo/denip-projects-preview.jpg'))
@section('og_type', 'article')

@section('twitter_title', $project->title . ' - Construction Project')
@section('twitter_description', Str::limit($project->description, 155) . ' - Quality construction by DENIP INVESTMENTS LTD.')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "CreativeWork",
  "name": "{{ $project->title }}",
  "description": "{{ $project->description }}",
  "url": "{{ route('landing.project.show', $project->slug) }}",
  "creator": {
    "@@type": "Organization",
    "name": "Denip Investments Ltd",
    "url": "{{ route('landing.index') }}"
  },
  "dateCreated": "{{ $project->start_date ? $project->start_date->format('Y-m-d') : $project->created_at->format('Y-m-d') }}",
  "category": "{{ $project->category->name ?? 'Construction' }}",
  "about": {
    "@@type": "Service",
    "name": "{{ $project->category->name ?? 'Construction' }} Services",
    "provider": {
      "@@type": "Organization",
      "name": "Denip Investments Ltd"
    }
  },
  "client": {
    "@@type": "Organization",
    "name": "{{ $project->client->name }}"
  },
  "workLocation": {
    "@@type": "Place",
    "name": "{{ $project->location ?? 'Kenya' }}"
  },
  "startDate": "{{ $project->start_date ? $project->start_date->format('Y-m-d') : '' }}",
  "endDate": "{{ $project->end_date ? $project->end_date->format('Y-m-d') : '' }}",
  "budget": {
    "@@type": "MonetaryAmount",
    "currency": "KES",
    "value": "{{ $project->budget ?? 0 }}"
  }
}
</script>
@endpush



@section('content')
<div style="background: red; color: white; padding: 1rem; text-align: center; font-size: 2rem;">TEST - NEW DESIGN LOADED</div>
<!-- Hero Section -->
<section class="project-hero">
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
        
        <div class="hero-content">
            <div class="hero-main">
                @if($project->category)
                <span style="background: var(--primary); color: white; padding: 0.5rem 1.2rem; border-radius: 25px; font-size: 0.9rem; font-weight: 600; margin-bottom: 1.5rem; display: inline-block; box-shadow: 0 4px 15px rgba(243,156,18,0.3);">
                    @if($project->category->icon)<i class="{{ $project->category->icon }}"></i>@endif
                    {{ $project->category->name }}
                </span>
                @endif
                <h1>{{ $project->title }}</h1>
                <p style="font-size: 1.3rem; opacity: 0.9; margin-bottom: 1rem; color: var(--primary); font-weight: 600;">{{ $project->client->name }}</p>
                <p style="font-size: 1.1rem; opacity: 0.8; line-height: 1.6;">{{ Str::limit($project->description, 120) }}</p>
                <div class="hero-actions">
                    <button class="btn btn-primary btn-lg" onclick="showQuoteModal()" style="box-shadow: 0 8px 25px rgba(243,156,18,0.3);">
                        <i class="fas fa-calculator"></i> Get Free Quote
                    </button>
                    <a href="{{ route('contact') }}" class="btn btn-outline btn-lg" style="color: white; border-color: white;">
                        <i class="fas fa-phone"></i> Contact Us
                    </a>
                </div>
            </div>
            <div class="status-display">
                <div style="margin-bottom: 1rem;">
                    <i class="fas fa-chart-line" style="font-size: 2rem; color: var(--primary); margin-bottom: 0.5rem;"></i>
                </div>
                <span class="status-badge status-{{ strtolower($project->status) }}" style="font-size: 1rem; padding: 0.75rem 1.5rem; display: block; margin-bottom: 1rem;">{{ ucfirst($project->status) }}</span>
                <div style="font-size: 0.9rem; opacity: 0.8;">Project Status</div>
            </div>
        </div>
    </div>
</section>

<!-- Project Gallery -->
@if($project->media && count($project->media) > 0)
<section class="gallery-section">
    <div class="container">
        <div class="gallery-container">
            <div class="gallery-main">
                @php $images = collect($project->media)->where('type', 'like', 'image/%') @endphp
                @foreach($images as $index => $media)
                <div class="gallery-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                    <img src="{{ asset('storage/' . $media['path']) }}" alt="{{ $project->title }} - Image {{ $index + 1 }}">
                </div>
                @endforeach
                
                @if($images->count() > 1)
                <button class="gallery-controls gallery-prev" onclick="changeSlide(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="gallery-controls gallery-next" onclick="changeSlide(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <div class="gallery-nav">
                    @foreach($images as $index => $media)
                    <div class="gallery-dot {{ $index === 0 ? 'active' : '' }}" onclick="goToSlide({{ $index }})"></div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
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
                    <p style="font-size: 1.2rem; line-height: 1.8; color: #4a5568; margin-bottom: 2.5rem;">{{ $project->description }}</p>
                    
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

                <!-- Project Specifications -->
                <div class="content-card">
                    <h2>Technical Specifications</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
                        <div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 2rem; border-radius: 15px; text-align: center; border: 2px solid transparent; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='transparent'; this.style.transform='translateY(0)'">
                            <i class="fas fa-ruler-combined" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;"></i>
                            <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Project Scale</h4>
                            <p style="color: #6b7280;">{{ $project->category->name ?? 'Large Scale' }} Construction</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 2rem; border-radius: 15px; text-align: center; border: 2px solid transparent; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='transparent'; this.style.transform='translateY(0)'">
                            <i class="fas fa-calendar-alt" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;"></i>
                            <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Timeline</h4>
                            <p style="color: #6b7280;">{{ $project->start_date && $project->end_date ? $project->start_date->diffInMonths($project->end_date) . ' Months' : 'On Schedule' }}</p>
                        </div>
                        <div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); padding: 2rem; border-radius: 15px; text-align: center; border: 2px solid transparent; transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.borderColor='transparent'; this.style.transform='translateY(0)'">
                            <i class="fas fa-certificate" style="font-size: 2.5rem; color: var(--primary); margin-bottom: 1rem;"></i>
                            <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Quality Standard</h4>
                            <p style="color: #6b7280;">ISO 9001 Certified</p>
                        </div>
                    </div>
                </div>

                <!-- Project Team -->
                <div class="content-card">
                    <h2>Expert Team</h2>
                    <p style="color: #6b7280; margin-bottom: 2rem; font-size: 1.1rem;">Our dedicated professionals ensuring project excellence at every stage.</p>
                    <div class="team-grid">
                        <div class="team-member">
                            <div class="team-avatar">PM</div>
                            <h4 style="color: var(--secondary); margin-bottom: 0.5rem; font-size: 1.2rem;">Project Manager</h4>
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">Leading project execution & coordination</p>
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <span style="background: var(--primary); color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.7rem;">Leadership</span>
                                <span style="background: #10b981; color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.7rem;">Planning</span>
                            </div>
                        </div>
                        <div class="team-member">
                            <div class="team-avatar" style="background: #10b981;">SE</div>
                            <h4 style="color: var(--secondary); margin-bottom: 0.5rem; font-size: 1.2rem;">Site Engineer</h4>
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">Technical supervision & implementation</p>
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <span style="background: #10b981; color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.7rem;">Technical</span>
                                <span style="background: #8b5cf6; color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.7rem;">Safety</span>
                            </div>
                        </div>
                        <div class="team-member">
                            <div class="team-avatar" style="background: #8b5cf6;">QC</div>
                            <h4 style="color: var(--secondary); margin-bottom: 0.5rem; font-size: 1.2rem;">Quality Control</h4>
                            <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1rem;">Ensuring standards & compliance</p>
                            <div style="display: flex; justify-content: center; gap: 0.5rem;">
                                <span style="background: #8b5cf6; color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.7rem;">Quality</span>
                                <span style="background: #ef4444; color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.7rem;">Inspection</span>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <h3 style="color: var(--secondary); margin-bottom: 2rem; font-size: 1.4rem; font-weight: 700; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-info-circle" style="color: var(--primary);"></i> Project Details
                    </h3>
                    <div class="details-list">
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-user" style="color: var(--primary); margin-right: 0.5rem;"></i>Client</span>
                            <span class="detail-value">{{ $project->client->name }}</span>
                        </div>
                        @if($project->category)
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-tag" style="color: var(--primary); margin-right: 0.5rem;"></i>Category</span>
                            <span class="detail-value">{{ $project->category->name }}</span>
                        </div>
                        @endif
                        @if($project->start_date)
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-play" style="color: var(--primary); margin-right: 0.5rem;"></i>Start Date</span>
                            <span class="detail-value">{{ $project->start_date->format('M j, Y') }}</span>
                        </div>
                        @endif
                        @if($project->end_date)
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-flag-checkered" style="color: var(--primary); margin-right: 0.5rem;"></i>End Date</span>
                            <span class="detail-value">{{ $project->end_date->format('M j, Y') }}</span>
                        </div>
                        @endif
                        @if($project->budget)
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-dollar-sign" style="color: var(--primary); margin-right: 0.5rem;"></i>Budget</span>
                            <span class="detail-value">{{ number_format($project->budget) }} KES</span>
                        </div>
                        @endif
                        @if($project->location)
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-map-marker-alt" style="color: var(--primary); margin-right: 0.5rem;"></i>Location</span>
                            <span class="detail-value">{{ $project->location }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- CTA Card -->
                <div class="sidebar-card cta-card">
                    <div class="cta-content">
                        <div style="margin-bottom: 1.5rem;">
                            <i class="fas fa-lightbulb" style="font-size: 2.5rem; margin-bottom: 1rem; opacity: 0.9;"></i>
                        </div>
                        <h4 style="color: white; margin-bottom: 1rem; font-size: 1.3rem; font-weight: 700;">Inspired by This Project?</h4>
                        <p style="opacity: 0.9; margin-bottom: 1.5rem; line-height: 1.5;">Let's discuss how we can bring your construction vision to life with similar quality and expertise.</p>
                        <div class="cta-buttons">
                            <button class="btn" onclick="showQuoteModal()" style="background: white; color: var(--primary); width: 100%; font-weight: 600; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                <i class="fas fa-calculator"></i> Get Free Quote
                            </button>
                            <a href="{{ route('contact') }}" class="btn" style="background: rgba(255,255,255,0.2); color: white; width: 100%; border: 2px solid rgba(255,255,255,0.3); backdrop-filter: blur(10px);">
                                <i class="fas fa-phone"></i> Contact Us
                            </a>
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
                <button class="btn btn-primary btn-lg" onclick="showQuoteModal()" style="box-shadow: 0 8px 25px rgba(243,156,18,0.4); transform: scale(1.05);">
                    <i class="fas fa-calculator"></i> Get Free Quote
                </button>
                <a href="{{ route('contact') }}" class="btn btn-outline btn-lg" style="color: white; border-color: white; border-width: 2px;">
                    <i class="fas fa-phone"></i> Contact Us
                </a>
            </div>
            <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.2);">
                <p style="font-size: 0.9rem; opacity: 0.7;">✅ Free consultation • ✅ Expert advice • ✅ Quality guaranteed</p>
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
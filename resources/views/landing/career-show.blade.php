@extends('layouts.landing')

@section('title', $seoData['title'])
@section('meta_description', $seoData['description'])
@section('meta_keywords', $seoData['keywords'])
@section('canonical', route('career.show', $career->slug))

@section('og_title', $career->title . ' - Career Opportunity at DENIP INVESTMENTS')
@section('og_description', Str::limit(strip_tags($career->description), 155) . ' - Join Kenya\'s premier construction company.')
@section('og_image', asset('img/seo/denip-careers-preview.jpg'))
@section('og_type', 'article')

@section('twitter_title', $career->title . ' - Construction Career Opportunity')
@section('twitter_description', Str::limit(strip_tags($career->description), 155) . ' - Join our construction team.')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "JobPosting",
  "title": "{{ $career->title }}",
  "description": "{{ strip_tags($career->description) }}",
  "datePosted": "{{ $career->created_at->format('Y-m-d') }}",
  "validThrough": "{{ $career->deadline ? $career->deadline->format('Y-m-d') : now()->addMonths(3)->format('Y-m-d') }}",
  "employmentType": "{{ strtoupper(str_replace('-', '_', $career->type)) }}",
  "hiringOrganization": {
    "@@type": "Organization",
    "name": "Denip Investments Ltd",
    "sameAs": "{{ route('landing.index') }}",
    "logo": "{{ asset('img/denip-logo.svg') }}"
  },
  "jobLocation": {
    "@@type": "Place",
    "address": {
      "@@type": "PostalAddress",
      "addressLocality": "{{ $career->location }}",
      "addressCountry": "KE"
    }
  },
  "baseSalary": {
    "@@type": "MonetaryAmount",
    "currency": "KES",
    "value": {
      "@@type": "QuantitativeValue",
      "minValue": {{ $career->salary_min ?? 0 }},
      "maxValue": {{ $career->salary_max ?? 0 }},
      "unitText": "MONTH"
    }
  },
  "qualifications": "{{ strip_tags($career->requirements) }}",
  "responsibilities": "{{ strip_tags($career->description) }}",
  "benefits": "{{ strip_tags($career->benefits ?? 'Competitive salary, health insurance, professional development') }}",
  "industry": "Construction",
  "occupationalCategory": "Construction and Building",
  "workHours": "Full-time",
  "url": "{{ route('career.show', $career->slug) }}"
}
</script>
@endpush

@section('content')
<!-- Hero Section -->
<section class="career-hero">
    <div class="container">
        <!-- Breadcrumb -->
        <nav style="margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem;">
                <a href="{{ route('landing.index') }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">Home</a>
                <i class="fas fa-chevron-right" style="color: rgba(255,255,255,0.5); font-size: 0.7rem;"></i>
                <a href="{{ route('careers') }}" style="color: rgba(255,255,255,0.8); text-decoration: none;">Careers</a>
                <i class="fas fa-chevron-right" style="color: rgba(255,255,255,0.5); font-size: 0.7rem;"></i>
                <span style="color: rgba(255,255,255,0.6);">{{ Str::limit($career->title, 30) }}</span>
            </div>
        </nav>
        
        <div class="career-header">
            <div class="career-main">
                <span class="job-type-badge">{{ ucfirst(str_replace('-', ' ', $career->type)) }}</span>
                <h1>{{ $career->title }}</h1>
                <div class="job-meta">
                    <span><i class="fas fa-map-marker-alt"></i> {{ $career->location }}</span>
                    <span><i class="fas fa-calendar"></i> Posted {{ $career->created_at->diffForHumans() }}</span>
                    @if($career->deadline)
                    <span><i class="fas fa-clock"></i> Apply by {{ $career->deadline->format('M j, Y') }}</span>
                    @endif
                </div>
                @if($career->salary_min && $career->salary_max)
                <div class="salary-range">
                    <i class="fas fa-money-bill-wave"></i>
                    KSh {{ number_format($career->salary_min) }} - {{ number_format($career->salary_max) }} /month
                </div>
                @endif
            </div>
            <div class="apply-quick">
                <a href="{{ route('landing.careers.apply', $career->slug) }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Job Content -->
<section style="padding: 4rem 0;">
    <div class="container">
        <div class="job-layout">
            <!-- Main Content -->
            <div class="job-content">
                <!-- Job Description -->
                <div class="content-section">
                    <h2><i class="fas fa-briefcase"></i> Job Description</h2>
                    <div class="content-text">{!! $career->description !!}</div>
                </div>

                <!-- Requirements -->
                <div class="content-section">
                    <h2><i class="fas fa-list-check"></i> Requirements</h2>
                    <div class="content-text">{!! $career->requirements !!}</div>
                </div>

                <!-- Benefits -->
                @if($career->benefits)
                <div class="content-section">
                    <h2><i class="fas fa-gift"></i> Benefits & Perks</h2>
                    <div class="content-text">{!! $career->benefits !!}</div>
                </div>
                @endif

                <!-- Company Culture -->
                <div class="content-section culture-section">
                    <h2><i class="fas fa-users"></i> Why Join DENIP INVESTMENTS?</h2>
                    <div class="culture-grid">
                        <div class="culture-item">
                            <i class="fas fa-trophy"></i>
                            <h4>Industry Leader</h4>
                            <p>Be part of Kenya's premier construction company with a proven track record of excellence.</p>
                        </div>
                        <div class="culture-item">
                            <i class="fas fa-chart-line"></i>
                            <h4>Career Growth</h4>
                            <p>Advance your career with continuous learning opportunities and professional development.</p>
                        </div>
                        <div class="culture-item">
                            <i class="fas fa-handshake"></i>
                            <h4>Team Collaboration</h4>
                            <p>Work with experienced professionals in a supportive and collaborative environment.</p>
                        </div>
                        <div class="culture-item">
                            <i class="fas fa-shield-alt"></i>
                            <h4>Job Security</h4>
                            <p>Enjoy stable employment with competitive benefits and long-term career prospects.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="job-sidebar">
                <!-- Apply Card -->
                <div class="sidebar-card apply-card">
                    <h3><i class="fas fa-rocket"></i> Ready to Apply?</h3>
                    <p>Join our team and help build Kenya's future infrastructure.</p>
                    <a href="{{ route('landing.careers.apply', $career->slug) }}" class="btn btn-primary btn-block">
                        <i class="fas fa-paper-plane"></i> Apply for this Position
                    </a>
                    <div class="apply-note">
                        <i class="fas fa-info-circle"></i>
                        <span>Application takes less than 5 minutes</span>
                    </div>
                </div>

                <!-- Job Details -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-info-circle"></i> Job Details</h3>
                    <div class="job-details">
                        <div class="detail-item">
                            <span class="label">Job Type</span>
                            <span class="value">{{ ucfirst(str_replace('-', ' ', $career->type)) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Location</span>
                            <span class="value">{{ $career->location }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Department</span>
                            <span class="value">Construction</span>
                        </div>
                        @if($career->deadline)
                        <div class="detail-item">
                            <span class="label">Deadline</span>
                            <span class="value">{{ $career->deadline->format('M j, Y') }}</span>
                        </div>
                        @endif
                        <div class="detail-item">
                            <span class="label">Posted</span>
                            <span class="value">{{ $career->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Share Job -->
                <div class="sidebar-card">
                    <h3><i class="fas fa-share-alt"></i> Share this Job</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="share-btn facebook">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($career->title . ' at DENIP INVESTMENTS') }}" target="_blank" class="share-btn twitter">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}" target="_blank" class="share-btn linkedin">
                            <i class="fab fa-linkedin-in"></i> LinkedIn
                        </a>
                    </div>
                </div>

                <!-- Contact -->
                <div class="sidebar-card contact-card">
                    <h3><i class="fas fa-question-circle"></i> Have Questions?</h3>
                    <p>Need more information about this position?</p>
                    <a href="{{ route('contact') }}" class="btn btn-outline btn-block">
                        <i class="fas fa-envelope"></i> Contact HR
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section style="background: var(--secondary); color: white; padding: 4rem 0;">
    <div class="container text-center">
        <h2 style="color: white; margin-bottom: 1rem; font-size: 2.5rem;">Start Your Career Journey</h2>
        <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.9;">Join Kenya's leading construction company and build your future with us.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('landing.careers.apply', $career->slug) }}" class="btn btn-primary btn-lg">
                <i class="fas fa-paper-plane"></i> Apply for {{ $career->title }}
            </a>
            <a href="{{ route('careers') }}" class="btn btn-outline btn-lg" style="color: white; border-color: white;">
                <i class="fas fa-search"></i> View All Jobs
            </a>
        </div>
    </div>
</section>

<style>
.career-hero {
    background: linear-gradient(135deg, #2C3E50 0%, #34495e 100%);
    color: white;
    padding: 2rem 0;
    position: relative;
    overflow: hidden;
}

.career-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
    opacity: 0.3;
}

.career-header {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2rem;
    align-items: start;
}

.job-type-badge {
    background: var(--primary);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: inline-block;
}

.career-main h1 {
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800;
    margin-bottom: 1rem;
    font-family: 'Playfair Display', serif;
}

.job-meta {
    display: flex;
    gap: 2rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.job-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    opacity: 0.9;
}

.salary-range {
    background: rgba(255,255,255,0.1);
    padding: 1rem 1.5rem;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    backdrop-filter: blur(10px);
}

.apply-quick {
    text-align: center;
}

.job-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 4rem;
    align-items: start;
}

.job-content {
    display: flex;
    flex-direction: column;
    gap: 3rem;
}

.content-section {
    background: white;
    border-radius: 15px;
    padding: 2.5rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 1px solid #f1f5f9;
}

.content-section h2 {
    color: var(--secondary);
    font-size: 1.8rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'Playfair Display', serif;
}

.content-section h2 i {
    color: var(--primary);
}

.content-text {
    line-height: 1.8;
    color: #4a5568;
    font-size: 1.1rem;
}

.content-text h1, .content-text h2, .content-text h3 {
    color: var(--secondary);
    margin: 1.5rem 0 1rem;
}

.content-text ul, .content-text ol {
    margin: 1rem 0;
    padding-left: 2rem;
    display: block;
}

.content-text li {
    margin-bottom: 0.5rem;
    display: list-item;
    list-style-position: outside;
}

.content-text ul li {
    list-style-type: disc;
}

.content-text ol li {
    list-style-type: decimal;
}

.content-text p {
    margin-bottom: 1rem;
}

.content-text strong {
    color: var(--secondary);
    font-weight: 600;
}

.culture-section {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.culture-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.culture-item {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
}

.culture-item:hover {
    transform: translateY(-5px);
}

.culture-item i {
    font-size: 2.5rem;
    color: var(--primary);
    margin-bottom: 1rem;
}

.culture-item h4 {
    color: var(--secondary);
    margin-bottom: 0.5rem;
    font-size: 1.2rem;
}

.culture-item p {
    color: #6b7280;
    font-size: 0.9rem;
    line-height: 1.5;
}

.job-sidebar {
    position: sticky;
    top: 100px;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.sidebar-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 1px solid #f1f5f9;
}

.sidebar-card h3 {
    color: var(--secondary);
    margin-bottom: 1rem;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sidebar-card h3 i {
    color: var(--primary);
}

.apply-card {
    background: linear-gradient(135deg, var(--primary) 0%, #e67e22 100%);
    color: white;
    text-align: center;
}

.apply-card h3,
.apply-card p {
    color: white;
}

.apply-note {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255,255,255,0.2);
    font-size: 0.85rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.job-details {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item .label {
    font-weight: 600;
    color: var(--secondary);
}

.detail-item .value {
    color: #6b7280;
}

.share-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.share-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.share-btn.facebook {
    background: #1877f2;
    color: white;
}

.share-btn.twitter {
    background: #1da1f2;
    color: white;
}

.share-btn.linkedin {
    background: #0077b5;
    color: white;
}

.share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.contact-card {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    text-align: center;
}

.btn-block {
    width: 100%;
    text-align: center;
}

@media (max-width: 768px) {
    .career-header {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 2rem;
    }
    
    .job-layout {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .job-sidebar {
        position: static;
        order: 1;
    }
    
    .job-meta {
        flex-direction: column;
        gap: 1rem;
    }
    
    .culture-grid {
        grid-template-columns: 1fr;
    }
    
    .content-section {
        padding: 2rem;
    }
}
</style>
@endsection
@extends('layouts.landing')

@section('title', 'Careers - Join Our Team | DENIP INVESTMENTS LTD | Construction Jobs Kenya')
@section('meta_description', 'Explore exciting career opportunities with Kenya\'s leading construction company. Join our team of construction professionals with competitive benefits, professional development, and growth opportunities.')
@section('meta_keywords', 'construction jobs Kenya, construction careers, building jobs Nairobi, construction employment, civil engineering jobs Kenya, project management careers, construction company jobs')
@section('canonical', route('careers'))

@section('og_title', 'Construction Careers - Join DENIP INVESTMENTS LTD Team')
@section('og_description', 'Build your career with Kenya\'s leading construction company. Competitive benefits, professional development, and exciting opportunities in construction and engineering.')
@section('og_image', asset('img/seo/denip-careers-preview.jpg'))
@section('og_type', 'website')

@section('twitter_title', 'Construction Careers - Build Your Future with Us')
@section('twitter_description', 'Join Kenya\'s premier construction company. Exciting career opportunities with professional development, competitive benefits, and growth potential.')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "JobPosting",
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
      "addressCountry": "KE",
      "addressLocality": "Nairobi",
      "addressRegion": "Nairobi County"
    }
  },
  "industry": "Construction",
  "employmentType": ["FULL_TIME", "PART_TIME", "CONTRACT"],
  "description": "Join Kenya's leading construction company. We offer exciting career opportunities in construction, engineering, project management, and related fields.",
  "benefits": [
    "Professional Development",
    "Comprehensive Benefits",
    "Career Growth Opportunities",
    "Team Environment",
    "Health Insurance",
    "Retirement Plans",
    "Competitive Compensation"
  ],
  "workEnvironment": "Construction sites, office environment, collaborative workplace",
  "qualifications": [
    "Relevant education in construction, engineering, or related field",
    "Experience in construction industry preferred",
    "Strong communication and teamwork skills",
    "Commitment to safety and quality"
  ]
}
</script>
@if($careers->count() > 0)
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "ItemList",
  "name": "Current Job Openings",
  "description": "Current career opportunities at Denip Investments Ltd",
  "numberOfItems": {{ $careers->count() }},
  "itemListElement": [
    @foreach($careers as $index => $career)
    {
      "@@type": "ListItem",
      "position": {{ $index + 1 }},
      "item": {
        "@@type": "JobPosting",
        "title": "{{ $career->title }}",
        "description": "{{ Str::limit(strip_tags($career->description), 150) }}",
        "datePosted": "{{ $career->created_at->format('Y-m-d') }}",
        "hiringOrganization": {
          "@@type": "Organization",
          "name": "Denip Investments Ltd"
        },
        "jobLocation": {
          "@@type": "Place",
          "address": {
            "@@type": "PostalAddress",
            "addressCountry": "KE",
            "addressLocality": "{{ $career->location ?? 'Nairobi' }}"
          }
        },
        "employmentType": "{{ $career->type ?? 'FULL_TIME' }}",
        "url": "{{ route('career.show', $career->slug) }}"
      }
    }@if(!$loop->last),@endif
    @endforeach
  ]
}
</script>
@endif
@endpush

@section('content')
<x-landing.page-hero 
    title="Join Our Team"
    subtitle="Build your career with Kenya's leading construction company. We offer competitive benefits, professional development, and exciting opportunities."
    backgroundImage="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?ixlib=rb-4.0.3&auto=format&fit=crop&w=2084&q=80"
/>

<!-- Careers Section -->
<section class="section">
    <div class="container">
        @if($careers->count() > 0)
            <div class="section-header">
                <h2>Current Openings</h2>
                <p>Discover exciting career opportunities and become part of our growing team of construction professionals.</p>
            </div>
            
            <div class="grid grid-2">
                @foreach($careers as $career)
                    <x-career-card :career="$career" />
                @endforeach
            </div>
        @else
            <div class="text-center" style="padding: 4rem 0;">
                <i class="fas fa-briefcase" style="font-size: 4rem; color: var(--gray); opacity: 0.5; margin-bottom: 1rem;"></i>
                <h3 style="color: var(--secondary); margin-bottom: 1rem;">No Current Openings</h3>
                <p style="color: var(--gray); margin-bottom: 2rem;">We're always looking for talented individuals. Send us your resume and we'll keep you in mind for future opportunities.</p>
                <x-invest-button 
                    text="Send Resume" 
                    href="{{ route('contact') }}" 
                    size="normal" 
                />
            </div>
        @endif
    </div>
</section>

<!-- Why Work With Us -->
<section class="section" style="background: var(--light-gray);">
    <div class="container">
        <div class="section-header">
            <h2>Why Choose DENIP INVESTMENTS</h2>
            <p>Join a company that values growth, innovation, and excellence in everything we do.</p>
        </div>
        
        <div class="grid grid-4">
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Professional Development</h4>
                <p style="color: var(--gray);">Continuous learning opportunities and skill development programs.</p>
            </div>
            
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-heart"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Comprehensive Benefits</h4>
                <p style="color: var(--gray);">Health insurance, retirement plans, and competitive compensation packages.</p>
            </div>
            
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-rocket"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Career Growth</h4>
                <p style="color: var(--gray);">Clear advancement paths and opportunities to take on leadership roles.</p>
            </div>
            
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-users"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Team Environment</h4>
                <p style="color: var(--gray);">Collaborative workplace with supportive colleagues and leadership.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="section" style="background: var(--secondary); color: white;">
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
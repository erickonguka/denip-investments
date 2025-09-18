@extends('layouts.landing')

@section('title', 'About Us - DENIP INVESTMENTS LTD | Kenya\'s Premier Construction Company')
@section('meta_description', 'Learn about DENIP INVESTMENTS LTD, Kenya\'s premier construction company with over 10 years of experience. Our mission, vision, values, and commitment to excellence in construction.')
@section('meta_keywords', 'about Denip Investments, construction company Kenya history, building contractors Nairobi, construction company mission vision, Kenya construction expertise, construction company values')
@section('canonical', route('about'))

@section('og_title', 'About DENIP INVESTMENTS LTD - Kenya\'s Construction Leaders')
@section('og_description', 'Discover the story behind Kenya\'s premier construction company. Learn about our mission, vision, values, and 10+ years of excellence in delivering quality construction projects.')
@section('og_image', asset('img/seo/denip-about-preview.jpg'))
@section('og_type', 'website')

@section('twitter_title', 'About DENIP INVESTMENTS LTD - Building Kenya\'s Future')
@section('twitter_description', 'Learn about Kenya\'s premier construction company with 10+ years of experience, 100+ completed projects, and unwavering commitment to excellence.')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "AboutPage",
  "mainEntity": {
    "@@type": "Organization",
    "name": "Denip Investments Ltd",
    "url": "{{ route('landing.index') }}",
    "logo": "{{ asset('img/denip-logo.svg') }}",
    "description": "Kenya's premier construction company with over 10 years of experience in residential, commercial, and infrastructure projects.",
    "foundingDate": "2013",
    "numberOfEmployees": "50-100",
    "address": {
      "@@type": "PostalAddress",
      "addressCountry": "KE",
      "addressLocality": "Nairobi",
      "addressRegion": "Nairobi County"
    },
    "contactPoint": {
      "@@type": "ContactPoint",
      "telephone": "{{ \App\Models\Setting::get('company_phone', '+254788225898') }}",
      "contactType": "customer service",
      "email": "{{ \App\Models\Setting::get('company_email', 'info@denipinvestments.com') }}"
    },
    "award": [
      "100+ Projects Completed",
      "50+ Happy Clients",
      "10+ Years Experience"
    ],
    "knowsAbout": [
      "Residential Construction",
      "Commercial Construction",
      "Infrastructure Development",
      "Project Management",
      "Construction Safety"
    ],
    "serviceArea": {
      "@@type": "Country",
      "name": "Kenya"
    }
  }
}
</script>
@endpush

@section('content')
<x-landing.page-hero 
    title="About DENIP INVESTMENTS"
    subtitle="Building Kenya's future with excellence, innovation, and unwavering commitment to quality construction solutions."
    backgroundImage="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80"
/>

<!-- About Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Our Story</h2>
            <p>DENIP INVESTMENTS LTD has been at the forefront of Kenya's construction industry, delivering exceptional projects that stand the test of time.</p>
        </div>
        
        <div class="grid grid-2">
            <div class="card">
                <div class="card-body">
                    <h3 style="color: var(--secondary); margin-bottom: 1rem;">Our Mission</h3>
                    <p style="color: var(--gray); line-height: 1.8;">To build infrastructure that transforms communities and drives economic growth through innovative construction solutions and unwavering commitment to quality.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h3 style="color: var(--secondary); margin-bottom: 1rem;">Our Vision</h3>
                    <p style="color: var(--gray); line-height: 1.8;">To be Kenya's leading construction company, recognized for excellence, innovation, and our contribution to building tomorrow's infrastructure.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="section" style="background: var(--light-gray);">
    <div class="container">
        <div class="section-header">
            <h2>Our Core Values</h2>
            <p>The principles that guide everything we do and define who we are as a company.</p>
        </div>
        
        <div class="grid grid-4">
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-award"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Excellence</h4>
                <p style="color: var(--gray);">Striving for the highest standards in every project we undertake.</p>
            </div>
            
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-handshake"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Integrity</h4>
                <p style="color: var(--gray);">Honest, transparent, and ethical business practices in all our dealings.</p>
            </div>
            
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Innovation</h4>
                <p style="color: var(--gray);">Embracing new technologies and methods to deliver better results.</p>
            </div>
            
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Safety</h4>
                <p style="color: var(--gray);">Prioritizing the safety of our workers and communities in every project.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Our Achievements</h2>
            <p>Numbers that reflect our commitment to excellence and customer satisfaction.</p>
        </div>
        
        <div class="grid grid-4">
            <div class="text-center">
                <div style="font-size: 3rem; font-weight: 800; color: var(--primary); margin-bottom: 0.5rem;">100+</div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Projects Completed</h4>
                <p style="color: var(--gray);">Successfully delivered across Kenya</p>
            </div>
            
            <div class="text-center">
                <div style="font-size: 3rem; font-weight: 800; color: var(--primary); margin-bottom: 0.5rem;">50+</div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Happy Clients</h4>
                <p style="color: var(--gray);">Satisfied customers nationwide</p>
            </div>
            
            <div class="text-center">
                <div style="font-size: 3rem; font-weight: 800; color: var(--primary); margin-bottom: 0.5rem;">10+</div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Years Experience</h4>
                <p style="color: var(--gray);">In the construction industry</p>
            </div>
            
            <div class="text-center">
                <div style="font-size: 3rem; font-weight: 800; color: var(--primary); margin-bottom: 0.5rem;">24/7</div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Support</h4>
                <p style="color: var(--gray);">Customer service availability</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Meet Our Team</h2>
            <p>The dedicated professionals behind our success, bringing expertise and passion to every project.</p>
        </div>
        
        <div class="grid grid-3">
            @forelse(\App\Models\TeamMember::where('is_active', true)->orderBy('order')->get() as $member)
            <div class="card text-center">
                <div class="card-body">
                    @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin: 0 auto 1rem; display: block;">
                    @else
                        <div style="width: 120px; height: 120px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 2rem; font-weight: 600;">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </div>
                    @endif
                    <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">{{ $member->name }}</h4>
                    <p style="color: var(--primary); font-weight: 600; margin-bottom: 0.5rem;">{{ $member->position }}</p>
                    @if($member->bio)
                        <p style="color: var(--gray); font-size: 0.9rem; line-height: 1.6;">{{ $member->bio }}</p>
                    @endif
                    @if($member->email || $member->phone)
                        <div style="display: flex; gap: 0.5rem; justify-content: center; margin-top: 1rem;">
                            @if($member->email)
                                <a href="mailto:{{ $member->email }}" style="color: var(--primary); font-size: 1.2rem;">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            @endif
                            @if($member->phone)
                                <a href="tel:{{ $member->phone }}" style="color: var(--primary); font-size: 1.2rem;">
                                    <i class="fas fa-phone"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--gray);">
                <i class="fas fa-users" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                <p>Our team information will be available soon.</p>
            </div>
            @endforelse
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
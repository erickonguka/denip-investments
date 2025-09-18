@extends('layouts.landing')

@section('title', 'Contact Us - DENIP INVESTMENTS LTD | Construction Services Kenya')
@section('meta_description', 'Contact DENIP INVESTMENTS LTD for your construction needs in Kenya. Get free quotes, consultations, and professional construction services. Call us today for residential, commercial, and infrastructure projects.')
@section('meta_keywords', 'contact construction company Kenya, construction quotes Kenya, building contractors contact, construction consultation Nairobi, construction services contact, Denip Investments contact')
@section('canonical', route('contact'))

@section('og_title', 'Contact DENIP INVESTMENTS LTD - Construction Services Kenya')
@section('og_description', 'Get in touch with Kenya\'s premier construction company. Free quotes, consultations, and professional construction services for residential, commercial, and infrastructure projects.')
@section('og_image', asset('img/seo/denip-contact-preview.jpg'))
@section('og_type', 'website')

@section('twitter_title', 'Contact Us - Professional Construction Services Kenya')
@section('twitter_description', 'Contact Kenya\'s leading construction company for quotes, consultations, and professional construction services. Available for residential, commercial, and infrastructure projects.')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "ContactPage",
  "mainEntity": {
    "@@type": "Organization",
    "name": "Denip Investments Ltd",
    "url": "{{ route('landing.index') }}",
    "logo": "{{ asset('img/denip-logo.svg') }}",
    "description": "Kenya's premier construction company providing residential, commercial, and infrastructure construction services.",
    "address": {
      "@@type": "PostalAddress",
      "streetAddress": "{{ \App\Models\Setting::get('company_address', 'Nairobi, Kenya') }}",
      "addressLocality": "Nairobi",
      "addressRegion": "Nairobi County",
      "postalCode": "{{ \App\Models\Setting::get('company_postal', '00100') }}",
      "addressCountry": "KE"
    },
    "contactPoint": [
      {
        "@@type": "ContactPoint",
        "telephone": "{{ \App\Models\Setting::get('company_phone', '+254788225898') }}",
        "contactType": "customer service",
        "areaServed": "KE",
        "availableLanguage": ["English", "Swahili"]
      },
      {
        "@@type": "ContactPoint",
        "email": "{{ \App\Models\Setting::get('company_email', 'info@denipinvestments.com') }}",
        "contactType": "customer service",
        "areaServed": "KE"
      }
    ],
    "openingHours": [
      "Mo-Fr 08:00-18:00",
      "Sa 09:00-16:00"
    ],
    "geo": {
      "@@type": "GeoCoordinates",
      "latitude": "-1.286389",
      "longitude": "36.817223"
    },
    "areaServed": {
      "@@type": "Country",
      "name": "Kenya"
    },
    "serviceType": [
      "Residential Construction",
      "Commercial Construction",
      "Infrastructure Development",
      "Construction Consultation",
      "Project Management"
    ]
  }
}
</script>
@endpush

@section('content')
<x-landing.page-hero 
    title="Contact Us"
    subtitle="Get in touch with our team for quotes, consultations, and professional construction services."
    backgroundImage="https://images.unsplash.com/photo-1423666639041-f56000c27a9a?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=80"
/>

<!-- Contact Section -->
<section class="section">
    <div class="container">
        <div class="grid grid-2">
            <!-- Contact Form -->
            <div class="card">
                <div class="card-header">
                    <h3>Send us a Message</h3>
                    <p style="color: var(--gray); margin: 0;">We'll get back to you within 24 hours</p>
                </div>
                <div class="card-body">
                    <form id="contactForm">
                        <div class="form-group">
                            <label>Full Name *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Email Address *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                        
                        <div class="form-group">
                            <label>Project Type</label>
                            <select class="form-control" name="project_type">
                                <option value="">Select project type</option>
                                <option value="residential">Residential Construction</option>
                                <option value="commercial">Commercial Construction</option>
                                <option value="infrastructure">Infrastructure Development</option>
                                <option value="renovation">Renovation & Remodeling</option>
                                <option value="consultation">Consultation</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Message *</label>
                            <textarea class="form-control" name="message" rows="5" required placeholder="Tell us about your project requirements..."></textarea>
                        </div>
                        
                        <div style="width: 100%;">
                            <x-invest-button 
                                text="Send Message" 
                                type="submit" 
                                size="normal" 
                            />
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div>
                <div class="card mb-3">
                    <div class="card-body">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4 style="color: var(--secondary); margin-bottom: 0.25rem;">Office Address</h4>
                                <p style="color: var(--gray); margin: 0;">{{ \App\Models\Setting::get('company_address', 'Nairobi, Kenya') }}<br>{{ \App\Models\Setting::get('company_location', '7557-40100 Kisumu') }}<br>{{ \App\Models\Setting::get('company_po_box', 'P.O. Box 12345-00100') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h4 style="color: var(--secondary); margin-bottom: 0.25rem;">Phone Numbers</h4>
                                <p style="color: var(--gray); margin: 0;">{{ \App\Models\Setting::get('company_phone', '(254) 788 225 898') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4 style="color: var(--secondary); margin-bottom: 0.25rem;">Email Addresses</h4>
                                <p style="color: var(--gray); margin: 0;">{{ \App\Models\Setting::get('company_email', 'info@denipinvestments.com') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 style="color: var(--secondary); margin-bottom: 0.25rem;">Business Hours</h4>
                                <p style="color: var(--gray); margin: 0;">{{ \App\Models\Setting::get('business_hours', 'Monday - Friday: 8:00 AM - 6:00 PM<br>Saturday: 9:00 AM - 4:00 PM<br>Sunday: Closed') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
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
                text="Call Now" 
                href="tel:{{ str_replace(['(', ')', ' ', '-'], '', \App\Models\Setting::get('company_phone', '+254788225898')) }}" 
                size="large" 
                state="outline" 
            />
        </div>
    </div>
</section>
@endsection
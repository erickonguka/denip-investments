@extends('layouts.landing')

@section('title', 'Terms of Service - DENIP INVESTMENTS LTD | Construction Service Terms')
@section('meta_description', 'Terms of Service for DENIP INVESTMENTS LTD. Read our terms and conditions for using our construction services, project agreements, payment terms, and warranties in Kenya.')
@section('meta_keywords', 'terms of service, construction terms, service agreement, construction contract terms, building service conditions, project terms Kenya')
@section('canonical', route('terms-of-service'))
@section('robots', 'index, follow')

@section('og_title', 'Terms of Service - DENIP INVESTMENTS LTD')
@section('og_description', 'Read our terms and conditions for construction services, project agreements, payment terms, and warranties. Legal terms for using DENIP INVESTMENTS services.')
@section('og_image', asset('img/seo/denip-default-preview.jpg'))
@section('og_type', 'website')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebPage",
  "name": "Terms of Service",
  "description": "Terms and conditions for using DENIP INVESTMENTS LTD construction services.",
  "url": "{{ route('terms-of-service') }}",
  "isPartOf": {
    "@@type": "WebSite",
    "name": "Denip Investments Ltd",
    "url": "{{ route('landing.index') }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "Denip Investments Ltd"
  },
  "dateModified": "{{ now()->format('Y-m-d') }}",
  "about": {
    "@@type": "Service",
    "name": "Construction Services",
    "provider": {
      "@@type": "Organization",
      "name": "Denip Investments Ltd"
    }
  }
}
</script>
@endpush

@section('content')
<x-landing.page-hero 
    title="Terms of Service"
    subtitle="Terms and conditions for using our construction services"
    backgroundImage="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=75"
/>

<section class="section">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="card">
                <div class="card-body" style="padding: 3rem;">
                    <p style="color: #6b7280; margin-bottom: 2rem;"><strong>Last updated:</strong> {{ date('F j, Y') }}</p>

                    <h3>1. Acceptance of Terms</h3>
                    <p>By accessing and using DENIP INVESTMENTS LTD services, you accept and agree to be bound by the terms and provision of this agreement.</p>

                    <h3>2. Services</h3>
                    <p>DENIP INVESTMENTS LTD provides construction and infrastructure development services including:</p>
                    <ul>
                        <li>Residential construction</li>
                        <li>Commercial building projects</li>
                        <li>Infrastructure development</li>
                        <li>Project management and consultation</li>
                    </ul>

                    <h3>3. Project Agreements</h3>
                    <p>All construction projects are subject to separate written agreements that will include:</p>
                    <ul>
                        <li>Project scope and specifications</li>
                        <li>Timeline and milestones</li>
                        <li>Payment terms and schedule</li>
                        <li>Quality standards and warranties</li>
                    </ul>

                    <h3>4. Payment Terms</h3>
                    <p>Payment terms will be specified in individual project contracts. Generally:</p>
                    <ul>
                        <li>Deposits may be required before project commencement</li>
                        <li>Progress payments are tied to project milestones</li>
                        <li>Final payment is due upon project completion and acceptance</li>
                    </ul>

                    <h3>5. Warranties</h3>
                    <p>We provide warranties on our construction work as specified in individual project contracts, typically covering structural integrity and workmanship for agreed periods.</p>

                    <h3>6. Limitation of Liability</h3>
                    <p>Our liability is limited to the terms specified in individual project contracts. We are not liable for delays caused by weather, permit issues, or other factors beyond our control.</p>

                    <h3>7. Governing Law</h3>
                    <p>These terms are governed by the laws of Kenya. Any disputes will be resolved through the Kenyan legal system.</p>

                    <h3>8. Contact Information</h3>
                    <p>For questions about these Terms of Service, contact us at:</p>
                    <p>
                        <strong>Email:</strong> {{ \App\Models\Setting::get('company_email', 'info@denipinvestments.com') }}<br>
                        <strong>Phone:</strong> {{ \App\Models\Setting::get('company_phone', '(254) 788 225 898') }}<br>
                        <strong>Address:</strong> {{ \App\Models\Setting::get('company_address', 'Nairobi, Kenya') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
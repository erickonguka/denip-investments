@extends('layouts.landing')

@section('title', 'Construction Services - DENIP INVESTMENTS LTD | Residential, Commercial & Infrastructure')
@section('meta_description', 'Comprehensive construction services in Kenya including residential construction, commercial buildings, infrastructure development, project management, design & planning, and construction consulting.')
@section('meta_keywords', 'construction services Kenya, residential construction, commercial construction, infrastructure development, project management Kenya, construction consulting, building contractors Nairobi')
@section('canonical', route('services'))

@section('og_title', 'Construction Services - DENIP INVESTMENTS LTD')
@section('og_description', 'Comprehensive construction services including residential, commercial, and infrastructure projects. Expert project management, design & planning, and construction consulting in Kenya.')
@section('og_image', asset('img/seo/denip-services-preview.jpg'))
@section('og_type', 'website')

@section('twitter_title', 'Construction Services - Quality Building Solutions in Kenya')
@section('twitter_description', 'Residential, commercial & infrastructure construction services. Expert project management and construction consulting by Kenya\'s premier construction company.')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Service",
  "provider": {
    "@@type": "Organization",
    "name": "Denip Investments Ltd",
    "url": "{{ route('landing.index') }}"
  },
  "serviceType": "Construction Services",
  "description": "Comprehensive construction services including residential, commercial, and infrastructure projects in Kenya.",
  "areaServed": {
    "@@type": "Country",
    "name": "Kenya"
  },
  "hasOfferCatalog": {
    "@@type": "OfferCatalog",
    "name": "Construction Services",
    "itemListElement": [
      {
        "@@type": "Offer",
        "itemOffered": {
          "@@type": "Service",
          "name": "Residential Construction",
          "description": "Custom homes, apartments, and residential complexes built with precision and attention to detail.",
          "category": "Construction"
        }
      },
      {
        "@@type": "Offer",
        "itemOffered": {
          "@@type": "Service",
          "name": "Commercial Construction",
          "description": "Office buildings, retail spaces, and commercial complexes designed for modern business needs.",
          "category": "Construction"
        }
      },
      {
        "@@type": "Offer",
        "itemOffered": {
          "@@type": "Service",
          "name": "Infrastructure Development",
          "description": "Roads, bridges, and public infrastructure projects that connect communities and drive growth.",
          "category": "Construction"
        }
      },
      {
        "@@type": "Offer",
        "itemOffered": {
          "@@type": "Service",
          "name": "Project Management",
          "description": "End-to-end project management services ensuring timely delivery and quality execution.",
          "category": "Consulting"
        }
      },
      {
        "@@type": "Offer",
        "itemOffered": {
          "@@type": "Service",
          "name": "Design & Planning",
          "description": "Architectural design and engineering services to bring your construction vision to life.",
          "category": "Design"
        }
      },
      {
        "@@type": "Offer",
        "itemOffered": {
          "@@type": "Service",
          "name": "Construction Consulting",
          "description": "Expert consulting services providing guidance on best practices and industry standards.",
          "category": "Consulting"
        }
      }
    ]
  }
}
</script>
@endpush

@section('content')
<x-landing.page-hero 
    title="Our Construction Services"
    subtitle="Comprehensive construction solutions delivered with precision, innovation, and unwavering commitment to excellence."
    backgroundImage="https://images.unsplash.com/photo-1504307651254-35680f356dfd?ixlib=rb-4.0.3&auto=format&fit=crop&w=2076&q=80"
/>

<!-- Services Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Our Services</h2>
            <p>Comprehensive construction solutions tailored to meet your specific needs and requirements.</p>
        </div>
        
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
            @forelse($services as $service)
                <div class="card">
                    @if($service->image)
                        <div style="height: 200px; background: url('{{ asset('storage/' . $service->image) }}') center/cover; border-radius: 15px 15px 0 0;"></div>
                    @endif
                    <div class="card-body">
                        @if($service->icon)
                            <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                                <i class="{{ $service->icon }}"></i>
                            </div>
                        @endif
                        <h3 style="color: var(--secondary); margin-bottom: 1rem; text-align: center;">{{ $service->name }}</h3>
                        <p style="color: var(--gray); line-height: 1.6; margin-bottom: 1.5rem;">{{ $service->description }}</p>
                        @if($service->features && count($service->features) > 0)
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                @foreach($service->features as $feature)
                                    <li style="display: flex; align-items: center; margin-bottom: 0.5rem; color: var(--gray);">
                                        <i class="fas fa-check" style="color: var(--primary); margin-right: 0.5rem;"></i>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--gray);">
                    <i class="fas fa-cogs" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                    <p>Our services information will be available soon.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section" style="background: var(--light-gray);">
    <div class="container">
        <div class="section-header">
            <h2>Why Choose DENIP INVESTMENTS</h2>
            <p>Experience the difference of working with Kenya's most trusted construction partner.</p>
        </div>
        
        <div class="grid grid-4">
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-award"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Quality Excellence</h4>
                <p style="color: var(--gray);">Uncompromising commitment to quality in every project we undertake.</p>
            </div>
            
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-clock"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Timely Delivery</h4>
                <p style="color: var(--gray);">Projects completed on schedule without compromising on quality.</p>
            </div>
            
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-users"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Expert Team</h4>
                <p style="color: var(--gray);">Skilled professionals with years of construction industry experience.</p>
            </div>
            
            <div class="text-center">
                <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 1.5rem;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4 style="color: var(--secondary); margin-bottom: 0.5rem;">Safety First</h4>
                <p style="color: var(--gray);">Strict adherence to safety protocols and industry standards.</p>
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
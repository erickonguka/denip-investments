@extends('layouts.landing')

@section('title', 'DENIP INVESTMENTS LTD - Building Tomorrow\'s Infrastructure')
@section('meta_description', 'Kenya\'s premier construction company specializing in residential, commercial, and infrastructure projects. Expert builders delivering quality construction solutions across Nairobi and East Africa.')
@section('meta_keywords', 'construction company Kenya, building contractors Nairobi, residential construction, commercial construction, infrastructure projects, construction services Kenya, Denip Investments, quality builders Kenya')
@section('canonical', route('landing.index'))

@section('og_title', 'DENIP INVESTMENTS LTD - Kenya\'s Premier Construction Company')
@section('og_description', 'Leading construction company in Kenya specializing in residential, commercial, and infrastructure projects. Quality construction solutions delivered with excellence and innovation.')
@section('og_image', asset('img/seo/denip-home-preview.jpg'))
@section('og_type', 'website')

@section('twitter_title', 'DENIP INVESTMENTS LTD - Building Tomorrow\'s Infrastructure')
@section('twitter_description', 'Kenya\'s premier construction company delivering quality residential, commercial, and infrastructure projects with excellence and innovation.')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Organization",
  "name": "Denip Investments Ltd",
  "url": "{{ route('landing.index') }}",
  "logo": "{{ asset('img/denip-logo.svg') }}",
  "description": "Kenya's premier construction company specializing in residential, commercial, and infrastructure projects.",
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
  "sameAs": [
    "{{ \App\Models\Setting::get('facebook_url', '#') }}",
    "{{ \App\Models\Setting::get('twitter_url', '#') }}",
    "{{ \App\Models\Setting::get('linkedin_url', '#') }}",
    "{{ \App\Models\Setting::get('instagram_url', '#') }}"
  ],
  "serviceArea": {
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
          "description": "Custom home building and residential construction services"
        }
      },
      {
        "@@type": "Offer",
        "itemOffered": {
          "@@type": "Service",
          "name": "Commercial Construction",
          "description": "Office buildings, retail spaces, and commercial construction"
        }
      },
      {
        "@@type": "Offer",
        "itemOffered": {
          "@@type": "Service",
          "name": "Infrastructure Projects",
          "description": "Roads, bridges, and infrastructure development projects"
        }
      }
    ]
  }
}
</script>
@endpush

@section('content')
<x-landing.hero />

<!-- Services Section -->
<section class="section" style="background: #f8fafc;" data-lazy>
    <div class="container">
        <div class="section-header text-center">
            <h2 style="color: var(--secondary); font-family: 'Playfair Display', serif;">Comprehensive Construction Solutions</h2>
            <p style="color: var(--gray); font-size: 1.1rem;">From residential homes to commercial complexes, we deliver excellence in every project</p>
        </div>
        <div class="grid grid-3" style="gap: 2rem;">
            @foreach($categories as $index => $category)
                <div style="background: white; border-radius: 20px; padding: 2.5rem; text-align: center; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; transition: all 0.3s ease; position: relative; overflow: hidden;"
                    onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.15)'; this.querySelector('.service-icon').style.transform='scale(1.1)'; this.querySelector('.service-icon').style.background='#e67e22'" 
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.08)'; this.querySelector('.service-icon').style.transform='scale(1)'; this.querySelector('.service-icon').style.background='var(--primary)'">
                    <div class="service-icon" style="width: 80px; height: 80px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 2rem; color: white; transition: all 0.3s ease; box-shadow: 0 8px 25px rgba(243,156,18,0.3);">
                        <i class="{{ $category->icon ?? 'fas fa-building' }}"></i>
                    </div>
                    <h3 style="color: var(--secondary); margin-bottom: 1rem; font-size: 1.4rem; font-weight: 700;">{{ $category->name }}</h3>
                    <p style="color: var(--gray); line-height: 1.6; margin-bottom: 2rem;">{{ $category->description }}</p>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                        <span style="color: var(--primary); font-weight: 600; font-size: 0.9rem;">{{ $category->projects_count }} Projects</span>
                        <a href="{{ route('landing.projects', ['category' => $category->id]) }}" style="background: var(--primary); color: white; padding: 0.6rem 1.2rem; border-radius: 25px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: all 0.3s ease;"
                            onmouseover="this.style.background='#e67e22'; this.style.transform='translateY(-2px)'" 
                            onmouseout="this.style.background='var(--primary)'; this.style.transform='translateY(0)'">
                            View Projects
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Projects -->
@if($projects->count() > 0)
<section class="section" id="projects" data-lazy>
    <div class="container">
        <div class="section-header text-center">
            <h2 style="color: var(--secondary); font-family: 'Playfair Display', serif;">Excellence in Construction</h2>
            <p style="color: var(--gray); font-size: 1.1rem;">Delivered with precision, innovation, and unwavering commitment to quality</p>
        </div>
        <div class="grid grid-2" style="gap: 2rem;">
            @foreach($projects as $project)
                <x-landing.project-card :project="$project" />
            @endforeach
        </div>
        
        <div class="text-center" style="margin-top: 3rem;">
            <x-invest-button 
                text="View All Projects" 
                href="{{ route('landing.projects') }}" 
                size="normal" 
            />
        </div>
    </div>
</section>
@endif

<!-- Testimonials Section -->
<section class="section" style="background: #f8fafc;" data-lazy>
    <div class="container">
        <div class="section-header text-center">
            <h2 style="color: var(--secondary); font-family: 'Playfair Display', serif;">Client Testimonials</h2>
            <p style="color: var(--gray); font-size: 1.1rem;">What our clients say about us</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div style="background: white; padding: 2.5rem; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; position: relative;">
                <div style="position: absolute; top: -10px; left: 30px; font-size: 4rem; color: var(--primary); opacity: 0.2;">"</div>
                <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem; font-style: italic; color: #4a5568;">Denip Investments delivered our office complex on time and within budget. Their attention to detail and professionalism is unmatched.</p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.2rem;">AK</div>
                    <div>
                        <h4 style="color: var(--secondary); margin-bottom: 0.25rem;">Alice Kimani</h4>
                        <p style="color: var(--gray); font-size: 0.9rem; margin: 0;">CEO, TechCorp Kenya</p>
                    </div>
                </div>
            </div>
            <div style="background: white; padding: 2.5rem; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; position: relative;">
                <div style="position: absolute; top: -10px; left: 30px; font-size: 4rem; color: var(--primary); opacity: 0.2;">"</div>
                <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 2rem; font-style: italic; color: #4a5568;">Outstanding work on our residential project. The team was professional, efficient, and delivered exceptional quality.</p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.2rem;">RO</div>
                    <div>
                        <h4 style="color: var(--secondary); margin-bottom: 0.25rem;">Robert Ochieng</h4>
                        <p style="color: var(--gray); font-size: 0.9rem; margin: 0;">Property Developer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Careers CTA Section -->
<section class="section" data-lazy>
    <div class="container">
        <div style="background: linear-gradient(135deg, var(--secondary) 0%, #34495e 100%); border-radius: 25px; padding: 4rem 2rem; text-align: center; color: white;">
            <h2 style="color: white; margin-bottom: 1rem; font-family: 'Playfair Display', serif;">Join Our Team</h2>
            <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.95;">Build your career with Kenya's leading construction company</p>
            <p style="margin-bottom: 2.5rem; opacity: 0.9;">We're always looking for talented individuals to join our growing team</p>
            <div style="display: inline-block;">
                <x-invest-button 
                    text="View Open Positions" 
                    href="{{ route('careers') }}" 
                    state="outline" 
                    size="normal" 
                />
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section" id="contact" style="background: linear-gradient(135deg, var(--secondary) 0%, #34495e 100%); color: white;" data-lazy>
    <div class="container">
        <div class="section-header text-center">
            <h2 style="color: white; font-family: 'Playfair Display', serif;">Get In Touch</h2>
            <p style="color: rgba(255,255,255,0.9); font-size: 1.1rem;">Ready to start your next project? Contact us today</p>
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4rem; align-items: start;" class="contact-grid">
            <div>
                <h3 style="color: var(--primary); margin-bottom: 2rem; font-size: 1.5rem; font-weight: 700;">Contact Information</h3>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.1); border-radius: 15px; transition: all 0.3s ease; backdrop-filter: blur(10px);"
                    onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateX(10px)'" 
                    onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateX(0)'">
                    <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Phone</div>
                        <div style="opacity: 0.9;">{{ \App\Models\Setting::get('company_phone', '(254) 788 225 898') }}</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.1); border-radius: 15px; transition: all 0.3s ease; backdrop-filter: blur(10px);"
                    onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateX(10px)'" 
                    onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateX(0)'">
                    <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Email</div>
                        <div style="opacity: 0.9;">{{ \App\Models\Setting::get('company_email', 'info@denipinvestments.com') }}</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.1); border-radius: 15px; transition: all 0.3s ease; backdrop-filter: blur(10px);"
                    onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateX(10px)'" 
                    onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateX(0)'">
                    <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Location</div>
                        <div style="opacity: 0.9;">{{ \App\Models\Setting::get('company_address', 'Nairobi, Kenya') }}</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1.5rem; background: rgba(255,255,255,0.1); border-radius: 15px; transition: all 0.3s ease; backdrop-filter: blur(10px);"
                    onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateX(10px)'" 
                    onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateX(0)'">
                    <div style="width: 60px; height: 60px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <div style="font-weight: 600; margin-bottom: 0.25rem;">Business Hours</div>
                        <div style="opacity: 0.9;">{!! \App\Models\Setting::get('business_hours', 'Monday - Friday: 8:00 AM - 6:00 PM') !!}</div>
                    </div>
                </div>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 2.5rem; border-radius: 20px; backdrop-filter: blur(10px);">
                <h3 style="color: var(--primary); margin-bottom: 2rem; font-size: 1.5rem; font-weight: 700;">Send us a Message</h3>
                <form id="contactForm" class="contact-form">
                    <div style="margin-bottom: 1.5rem;">
                        <input type="text" name="name" placeholder="Your Name" required 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid rgba(255,255,255,0.2); border-radius: 12px; font-size: 1rem; background: rgba(255,255,255,0.1); color: white; transition: all 0.3s ease;"
                            onfocus="this.style.borderColor='var(--primary)'; this.style.background='rgba(255,255,255,0.15)'" 
                            onblur="this.style.borderColor='rgba(255,255,255,0.2)'; this.style.background='rgba(255,255,255,0.1)'">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <input type="email" name="email" placeholder="Your Email" required 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid rgba(255,255,255,0.2); border-radius: 12px; font-size: 1rem; background: rgba(255,255,255,0.1); color: white; transition: all 0.3s ease;"
                            onfocus="this.style.borderColor='var(--primary)'; this.style.background='rgba(255,255,255,0.15)'" 
                            onblur="this.style.borderColor='rgba(255,255,255,0.2)'; this.style.background='rgba(255,255,255,0.1)'">
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <input type="tel" name="phone" placeholder="Your Phone" 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid rgba(255,255,255,0.2); border-radius: 12px; font-size: 1rem; background: rgba(255,255,255,0.1); color: white; transition: all 0.3s ease;"
                            onfocus="this.style.borderColor='var(--primary)'; this.style.background='rgba(255,255,255,0.15)'" 
                            onblur="this.style.borderColor='rgba(255,255,255,0.2)'; this.style.background='rgba(255,255,255,0.1)'">
                    </div>
                    <div style="margin-bottom: 2rem;">
                        <textarea name="message" rows="4" placeholder="Your Message" required 
                            style="width: 100%; padding: 1rem 1.25rem; border: 2px solid rgba(255,255,255,0.2); border-radius: 12px; font-size: 1rem; background: rgba(255,255,255,0.1); color: white; resize: vertical; transition: all 0.3s ease;"
                            onfocus="this.style.borderColor='var(--primary)'; this.style.background='rgba(255,255,255,0.15)'" 
                            onblur="this.style.borderColor='rgba(255,255,255,0.2)'; this.style.background='rgba(255,255,255,0.1)'"></textarea>
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
    </div>
</section>

<style>
@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
}

.contact-form input::placeholder,
.contact-form textarea::placeholder {
    color: rgba(255,255,255,0.7);
}
</style>
@endsection
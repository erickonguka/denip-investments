@extends('layouts.landing')

@section('title', 'Privacy Policy - DENIP INVESTMENTS LTD | Data Protection & Privacy')
@section('meta_description', 'Read DENIP INVESTMENTS LTD privacy policy. Learn how we collect, use, and protect your personal information when you use our construction services and website.')
@section('meta_keywords', 'privacy policy, data protection, personal information, construction company privacy, website privacy, data security, GDPR compliance')
@section('canonical', route('privacy-policy'))
@section('robots', 'index, follow')

@section('og_title', 'Privacy Policy - DENIP INVESTMENTS LTD')
@section('og_description', 'Learn about our privacy practices and how we protect your personal information when using our construction services and website.')
@section('og_image', asset('img/seo/denip-default-preview.jpg'))
@section('og_type', 'website')

@push('structured_data')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebPage",
  "name": "Privacy Policy",
  "description": "Privacy policy for Denip Investments Ltd construction services and website.",
  "url": "{{ route('privacy-policy') }}",
  "isPartOf": {
    "@@type": "WebSite",
    "name": "Denip Investments Ltd",
    "url": "{{ route('landing.index') }}"
  },
  "publisher": {
    "@@type": "Organization",
    "name": "Denip Investments Ltd"
  },
  "dateModified": "{{ now()->format('Y-m-d') }}"
}
</script>
@endpush

@section('content')
<x-landing.page-hero 
    title="Privacy Policy"
    subtitle="How we collect, use, and protect your personal information"
    backgroundImage="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=75"
/>

<section class="section">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="card">
                <div class="card-body" style="padding: 3rem;">
                    <p style="color: #6b7280; margin-bottom: 2rem;"><strong>Last updated:</strong> {{ date('F j, Y') }}</p>

                    <h3>1. Information We Collect</h3>
                    <p>We collect information you provide directly to us, such as when you:</p>
                    <ul>
                        <li>Request a quote or consultation</li>
                        <li>Create an account or register for our services</li>
                        <li>Contact us through our website or email</li>
                        <li>Subscribe to our newsletter</li>
                    </ul>

                    <h3>2. How We Use Your Information</h3>
                    <p>We use the information we collect to:</p>
                    <ul>
                        <li>Provide and improve our construction services</li>
                        <li>Respond to your inquiries and requests</li>
                        <li>Send you project updates and communications</li>
                        <li>Comply with legal obligations</li>
                    </ul>

                    <h3>3. Information Sharing</h3>
                    <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy or as required by law.</p>

                    <h3>4. Data Security</h3>
                    <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>

                    <h3>5. Your Rights</h3>
                    <p>You have the right to:</p>
                    <ul>
                        <li>Access your personal information</li>
                        <li>Correct inaccurate information</li>
                        <li>Request deletion of your information</li>
                        <li>Opt-out of marketing communications</li>
                    </ul>

                    <h3>6. Contact Us</h3>
                    <p>If you have questions about this Privacy Policy, please contact us at:</p>
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
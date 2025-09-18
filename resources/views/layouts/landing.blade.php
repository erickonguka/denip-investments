<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Denip Investments Ltd - Building Tomorrow\'s Infrastructure')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Denip Investments Ltd - Kenya\'s premier construction company specializing in residential, commercial, and infrastructure projects. Expert builders delivering quality construction solutions.')">
    <meta name="keywords" content="@yield('meta_keywords', 'construction Kenya, building contractors, residential construction, commercial construction, infrastructure projects, Denip Investments, construction company Nairobi')">
    <meta name="author" content="Denip Investments Ltd">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <link rel="canonical" href="@yield('canonical', url()->current())">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('og_title', 'Denip Investments Ltd - Building Tomorrow\'s Infrastructure')">
    <meta property="og:description" content="@yield('og_description', 'Kenya\'s premier construction company specializing in residential, commercial, and infrastructure projects.')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:image" content="@yield('og_image', asset('img/seo/denip-default-preview.jpg'))">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Denip Investments Ltd">
    <meta property="og:locale" content="en_KE">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@DenipInvestments">
    <meta name="twitter:creator" content="@DenipInvestments">
    <meta name="twitter:title" content="@yield('twitter_title', 'Denip Investments Ltd - Building Tomorrow\'s Infrastructure')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Kenya\'s premier construction company specializing in residential, commercial, and infrastructure projects.')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('img/seo/denip-default-preview.jpg'))">
    
    <!-- Additional SEO Meta Tags -->
    <meta name="geo.region" content="KE">
    <meta name="geo.placename" content="Nairobi, Kenya">
    <meta name="geo.position" content="-1.286389;36.817223">
    <meta name="ICBM" content="-1.286389, 36.817223">
    
    <!-- Schema.org structured data -->
    @stack('structured_data')
    
    @stack('head')
    
    <!-- Landing Page Loader - Execute Immediately -->
    <script src="{{ asset('js/landing-loader.js') }}?v={{ time() }}"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Stylesheets -->
    <link href="{{ asset('css/design-system.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/mobile-responsive.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/landing.css') }}?v={{ time() }}" rel="stylesheet">
    @stack('styles')
    
    <!-- Critical Navigation & Footer Styles -->
    <style>
    .navbar { position: fixed !important; top: 0; left: 0; right: 0; background: rgba(44, 62, 80, 0.95) !important; backdrop-filter: blur(10px); z-index: 1000; height: 70px; }
    .nav-container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; display: flex; align-items: center; justify-content: space-between; height: 100%; }
    .nav-right { display: flex; align-items: center; gap: 1rem; }
    .nav-links { display: flex !important; list-style: none; gap: 2rem; align-items: center; margin: 0; padding: 0; }
    .nav-links a { color: white !important; text-decoration: none; font-weight: 500; transition: all 0.3s ease; }
    .nav-links a:hover, .nav-links a.active { color: #F39C12 !important; }
    .dropdown-toggle.active { color: #F39C12 !important; }
    .dropdown-menu a.active { background: #f8fafc; color: #F39C12 !important; }
    .mobile-menu-items a.active { color: #F39C12 !important; font-weight: 600; }
    .dropdown { position: relative; }
    .dropdown-menu { position: absolute; top: 100%; left: 0; background: white; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); padding: 0.5rem 0; min-width: 200px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease; z-index: 1000; }
    .dropdown:hover .dropdown-menu, .dropdown.show .dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
    .dropdown-menu a { display: block; padding: 0.75rem 1rem; color: #2C3E50 !important; text-decoration: none; }
    .dropdown-menu a:hover { background: #f8fafc; color: #F39C12 !important; }
    .user-menu { position: relative; }
    .user-avatar { width: 35px; height: 35px; border-radius: 50%; background: #F39C12; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem; }
    .user-dropdown { position: absolute; top: 100%; right: 0; background: white; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); padding: 0.5rem 0; min-width: 180px; display: none; z-index: 1001; }
    .user-dropdown a { display: block; padding: 0.75rem 1rem; color: #2C3E50 !important; text-decoration: none; transition: all 0.2s ease; }
    .user-dropdown a:hover { background: #f8fafc; color: #F39C12 !important; }
    .user-dropdown a i { margin-right: 0.5rem; color: #6b7280; }
    .footer { background: linear-gradient(135deg, #2C3E50 0%, #34495e 100%) !important; color: white !important; padding: 4rem 0 2rem; position: relative; }
    .footer::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #F39C12, #e67e22); }
    .footer-content { display: grid; grid-template-columns: 1fr 2fr; gap: 4rem; margin-bottom: 3rem; }
    .footer-brand { text-align: center; }
    .footer-links-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 3rem; }
    .footer-section h4 { color: #F39C12 !important; font-family: 'Playfair Display', serif; font-size: 1.3rem; margin-bottom: 1rem; }
    .footer-section ul { list-style: none; margin: 0; padding: 0; }
    .footer-section ul li { margin-bottom: 0.5rem; }
    .footer-section ul li a { color: rgba(255,255,255,0.8) !important; text-decoration: none; }
    .footer-section ul li a:hover { color: #F39C12 !important; }
    .social-links { display: flex; justify-content: center; gap: 1rem; }
    .social-link { width: 45px; height: 45px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: white !important; font-size: 1.2rem; text-decoration: none; }
    .social-link:hover { background: #F39C12; }
    .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 2rem; }
    .footer-bottom-content { display: flex; justify-content: space-between; align-items: center; }
    .footer-bottom-links { display: flex; gap: 2rem; }
    .footer-bottom-links a { color: rgba(255,255,255,0.6) !important; text-decoration: none; }
    .contact-item { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; color: rgba(255,255,255,0.8); }
    .contact-item i { color: #F39C12; width: 20px; }
    .mobile-menu { position: fixed; top: 0; right: -100%; width: 300px; height: 100vh; background: white; z-index: 1002; transition: right 0.3s ease; padding: 2rem 1rem; overflow-y: auto; }
    .mobile-menu.active { right: 0; }
    .mobile-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1001; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
    .mobile-overlay.active { opacity: 1; visibility: visible; }
    .mobile-menu-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #e5e7eb; }
    .mobile-menu-title { font-size: 1.2rem; font-weight: 700; color: #2C3E50; }
    .mobile-menu-close { background: none; border: none; font-size: 1.5rem; color: #6b7280; cursor: pointer; }
    .mobile-menu-items { list-style: none; margin: 0; padding: 0; }
    .mobile-menu-items li { margin-bottom: 0.5rem; }
    .mobile-menu-items a { display: block; padding: 0.75rem 1rem; color: #2C3E50; text-decoration: none; border-radius: 8px; transition: all 0.2s ease; }
    .mobile-menu-items a:hover, .mobile-menu-items a.active { background: #f8fafc; color: #F39C12; }
    .mobile-user-section { margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb; }
    .mobile-user-info { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
    .mobile-user-info span { font-weight: 600; color: #2C3E50; }
    .mobile-controls { display: none; align-items: center; gap: 1rem; }
    
    /* Floating Buttons */
    .floating-btn { position: fixed; right: 20px; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; text-decoration: none; font-size: 1.2rem; z-index: 1000; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .scroll-top { bottom: 80px; background: #2C3E50; cursor: pointer; }
    .scroll-top:hover { background: #34495e; transform: translateY(-2px); }
    .whatsapp-btn { bottom: 20px; background: #25D366; }
    .whatsapp-btn:hover { background: #128C7E; transform: translateY(-2px); color: white; }
    
    /* Location Suggestions Styling */
    #quoteLocationSuggestions > div {
        padding: 12px 16px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s ease;
    }
    #quoteLocationSuggestions > div:hover {
        background: #f8f9fa;
    }
    #quoteLocationSuggestions > div:last-child {
        border-bottom: none;
    }
    
    @media (max-width: 768px) { .mobile-controls { display: flex; } }
    
    /* Page Loader Styles */
    .page-loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #2C3E50 0%, #34495e 100%); z-index: 9999; display: flex; align-items: center; justify-content: center; transition: opacity 0.5s ease, visibility 0.5s ease; }
    .page-loader.hidden { opacity: 0; visibility: hidden; }
    .loader-content { text-align: center; color: white; }
    .loader-logo { margin-bottom: 2rem; }
    .loader-logo-svg { width: 120px; height: auto; animation: pulse 2s infinite; }
    .loader-spinner { width: 50px; height: 50px; border: 4px solid rgba(243, 156, 18, 0.3); border-top: 4px solid #F39C12; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem; }
    .loader-text { font-size: 1.1rem; font-weight: 500; opacity: 0.9; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
    
    /* Image Loading Optimization */
    img { transition: opacity 0.3s ease; }
    img.loading { opacity: 0.5; }
    img.loaded { opacity: 1; }
    
    @media (max-width: 768px) { .nav-links { display: none !important; } .footer-brand { text-align: center !important; } .footer-brand .footer-logo { text-align: center !important; } .footer-brand .footer-tagline { text-align: center !important; } .footer-brand .social-links { justify-content: center !important; } .footer-content { grid-template-columns: 1fr; gap: 3rem; text-align: center; } .footer-links-grid { grid-template-columns: 1fr; gap: 2rem; } .footer-bottom-content { flex-direction: column; gap: 1rem; text-align: center; } .contact-item { justify-content: center !important; } .loader-logo-svg { width: 80px; } .loader-spinner { width: 40px; height: 40px; } }
    
    /* Project Single Page Critical Styles */
    .project-hero { background: linear-gradient(135deg, #2C3E50 0%, #34495e 100%); color: white; padding: 2rem 0; position: relative; overflow: hidden; }
    .project-hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat; opacity: 0.3; }
    .breadcrumb-nav { position: relative; z-index: 2; margin-bottom: 2rem; }
    .breadcrumb-nav a { color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s ease; }
    .breadcrumb-nav a:hover { color: #F39C12; }
    .hero-content { position: relative; z-index: 2; text-align: center; }
    .hero-main h1 { font-size: clamp(2rem, 4vw, 3.5rem); font-weight: 800; margin-bottom: 1rem; font-family: 'Playfair Display', serif; line-height: 1.1; }
    .hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 2rem; }
    .status-display { text-align: center; background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 15px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); }
    .gallery-section { padding: 0; margin-bottom: 3rem; position: relative; }
    .gallery-container { position: relative; height: 500px; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 80px rgba(0,0,0,0.15); }
    .gallery-main { position: relative; height: 100%; overflow: hidden; }
    .gallery-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 0.5s ease; }
    .gallery-slide.active { opacity: 1; }
    .gallery-slide img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-nav { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 0.5rem; z-index: 10; }
    .gallery-dot { width: 12px; height: 12px; border-radius: 50%; background: rgba(255,255,255,0.5); cursor: pointer; transition: all 0.3s ease; }
    .gallery-dot.active { background: #F39C12; transform: scale(1.2); }
    .gallery-controls { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); color: white; border: none; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; font-size: 1.2rem; transition: all 0.3s ease; z-index: 10; }
    .gallery-prev { left: 20px; }
    .gallery-next { right: 20px; }
    .gallery-controls:hover { background: #F39C12; transform: translateY(-50%) scale(1.1); }
    .content-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 4rem; align-items: start; }
    .main-content { display: flex; flex-direction: column; gap: 3rem; }
    .content-card { background: white; border-radius: 20px; padding: 3rem; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; }
    .content-card h2 { color: #2C3E50; font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 2rem; position: relative; padding-bottom: 1rem; }
    .content-card h2::after { content: ''; position: absolute; bottom: 0; left: 0; width: 60px; height: 3px; background: #F39C12; border-radius: 2px; }
    .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem; }
    .feature-item { background: #f8fafc; padding: 1.5rem; border-radius: 15px; border-left: 4px solid #F39C12; transition: all 0.3s ease; }
    .feature-item:hover { transform: translateX(5px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .team-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; }
    .team-member { text-align: center; padding: 2rem; background: #f8fafc; border-radius: 20px; transition: all 0.3s ease; border: 2px solid transparent; }
    .team-member:hover { transform: translateY(-8px); border-color: #F39C12; box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
    .team-avatar { width: 80px; height: 80px; background: #F39C12; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; font-size: 1.5rem; font-weight: 700; box-shadow: 0 8px 25px rgba(243,156,18,0.3); }
    .sidebar { position: sticky; top: 100px; display: flex; flex-direction: column; gap: 2rem; }
    .sidebar-card { background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; }
    .progress-circle { position: relative; width: 150px; height: 150px; margin: 0 auto 2rem; }
    .progress-text { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; flex-direction: column; }
    .progress-percentage { font-size: 2rem; font-weight: 800; color: #F39C12; }
    .details-list { display: flex; flex-direction: column; gap: 0; }
    .detail-item { display: flex; justify-content: space-between; align-items: center; padding: 1rem 0; border-bottom: 1px solid #f1f5f9; }
    .detail-item:last-child { border-bottom: none; }
    .detail-label { font-weight: 600; color: #2C3E50; }
    .detail-value { color: #6b7280; text-align: right; }
    .cta-card { background: linear-gradient(135deg, #F39C12 0%, #e67e22 100%); color: white; text-align: center; position: relative; overflow: hidden; }
    .cta-card::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); animation: shimmer 3s ease-in-out infinite; }
    @keyframes shimmer { 0%, 100% { transform: translateX(-100%) translateY(-100%); } 50% { transform: translateX(0) translateY(0); } }
    .cta-content { position: relative; z-index: 2; }
    .cta-buttons { display: flex; flex-direction: column; gap: 1rem; margin-top: 1.5rem; }
    .final-cta { background: #2C3E50; color: white; text-align: center; padding: 4rem 0; }
    @media (max-width: 768px) { .hero-content { grid-template-columns: 1fr; gap: 2rem; text-align: center; } .content-layout { grid-template-columns: 1fr; gap: 2rem; } .sidebar { position: static; order: -1; } .gallery-container { height: 300px; } .content-card { padding: 2rem; } .sidebar-card { padding: 2rem; } .hero-actions { justify-content: center; } }
    </style>
</head>

<body>
    <!-- Global Page Loader -->
    <div id="pageLoader" class="page-loader">
        <div class="loader-content">
            <div class="loader-logo">
                <svg viewBox="0 0 1605 502" xmlns="http://www.w3.org/2000/svg" class="loader-logo-svg">
                    <g transform="matrix(1,0,0,1,-698.678,-1249.19)">
                        <g transform="matrix(21.3645,0,0,21.3645,-449.868,-765.599)">
                            <g transform="matrix(0.818597,0,0,0.818597,15.8776,15.9463)">
                                <path d="M46.277,108.659L68.821,108.617L68.821,103.451L50.495,103.451L46.277,108.659Z" fill="#f39c12" />
                                <path d="M52.076,100.89L78.037,100.89L78.015,111.2L68.778,111.2L68.778,116.366L83.05,116.366L83.05,100.955L77.818,95.724L56.607,95.724L52.076,100.89Z" fill="#f39c12" />
                                <path d="M56.979,111.178L66.019,111.178L66.019,116.235L53.145,116.235L56.979,111.178Z" fill="#f39c12" />
                                <path d="M98.009,100.966L95.199,104.371L89.697,104.371L89.697,106.94L97.215,106.94L94.458,110.222L89.759,110.222L89.759,112.745L97.978,112.745L95.139,116.366L85.595,116.336L85.595,100.95L98.009,100.966Z" fill="white" />
                                <path d="M100.408,116.344L100.408,100.89L104.173,100.89L110.893,109.471L110.893,100.89L115.118,100.89L115.118,116.333L111.233,116.333L104.403,108.156L104.403,116.351L100.408,116.344Z" fill="white" />
                                <path d="M118.049,116.332L118.03,116.351L118.038,100.89L122.186,100.89L122.186,111.039L118.049,116.332Z" fill="white" />
                                <path d="M125.096,100.89L132.805,100.89C132.805,100.89 138.022,100.668 138.029,106.227C138.035,111.421 133.548,111.847 133.548,111.847L129.399,111.847L129.399,116.351L125.096,116.351L125.096,100.89ZM132.127,109.129C133.845,109.129 134.839,108.021 134.839,106.303C134.839,104.585 133.845,103.476 132.127,103.476L129.367,103.476L129.367,109.129L132.127,109.129Z" fill="white" />
                            </g>
                        </g>
                    </g>
                </svg>
            </div>
            <div class="loader-spinner"></div>
            <p class="loader-text">Loading...</p>
        </div>
    </div>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('landing.index') }}" class="logo">
                <svg viewBox="0 0 1605 502" xmlns="http://www.w3.org/2000/svg" class="logo-svg">
                    <g transform="matrix(1,0,0,1,-698.678,-1249.19)">
                        <g transform="matrix(21.3645,0,0,21.3645,-449.868,-765.599)">
                            <g transform="matrix(0.818597,0,0,0.818597,15.8776,15.9463)">
                                <path d="M46.277,108.659L68.821,108.617L68.821,103.451L50.495,103.451L46.277,108.659Z"
                                    fill="#f39c12" />
                                <path
                                    d="M52.076,100.89L78.037,100.89L78.015,111.2L68.778,111.2L68.778,116.366L83.05,116.366L83.05,100.955L77.818,95.724L56.607,95.724L52.076,100.89Z"
                                    fill="#f39c12" />
                                <path d="M56.979,111.178L66.019,111.178L66.019,116.235L53.145,116.235L56.979,111.178Z"
                                    fill="#f39c12" />
                                <path
                                    d="M98.009,100.966L95.199,104.371L89.697,104.371L89.697,106.94L97.215,106.94L94.458,110.222L89.759,110.222L89.759,112.745L97.978,112.745L95.139,116.366L85.595,116.336L85.595,100.95L98.009,100.966Z"
                                    fill="white" />
                                <path
                                    d="M100.408,116.344L100.408,100.89L104.173,100.89L110.893,109.471L110.893,100.89L115.118,100.89L115.118,116.333L111.233,116.333L104.403,108.156L104.403,116.351L100.408,116.344Z"
                                    fill="white" />
                                <path
                                    d="M118.049,116.332L118.03,116.351L118.038,100.89L122.186,100.89L122.186,111.039L118.049,116.332Z"
                                    fill="white" />
                                <path
                                    d="M125.096,100.89L132.805,100.89C132.805,100.89 138.022,100.668 138.029,106.227C138.035,111.421 133.548,111.847 133.548,111.847L129.399,111.847L129.399,116.351L125.096,116.351L125.096,100.89ZM132.127,109.129C133.845,109.129 134.839,108.021 134.839,106.303C134.839,104.585 133.845,103.476 132.127,103.476L129.367,103.476L129.367,109.129L132.127,109.129Z"
                                    fill="white" />
                            </g>
                        </g>
                    </g>
                </svg>
            </a>
            <div class="nav-right">
                <ul class="nav-links">
                    <li><a href="{{ route('landing.index') }}" class="{{ request()->routeIs('landing.index') ? 'active' : '' }}">Home</a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle {{ request()->routeIs('about', 'services', 'careers') ? 'active' : '' }}">About <i class="fas fa-chevron-down dropdown-icon"></i></a>
                        <div class="dropdown-menu">
                            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About Us</a>
                            <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">Services</a>
                            <a href="{{ route('careers') }}" class="{{ request()->routeIs('careers') ? 'active' : '' }}">Careers</a>
                        </div>
                    </li>
                    <li><a href="{{ route('landing.projects') }}" class="{{ request()->routeIs('landing.projects') ? 'active' : '' }}">Projects</a></li>
                    <li><a href="{{ route('landing.blog.index') }}" class="{{ request()->routeIs('landing.blog.*') ? 'active' : '' }}">Blog</a></li>
                    <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                    @auth
                        <li class="user-menu">
                            <div class="user-menu-trigger" onclick="toggleUserMenu()">
                                @if (auth()->user()->profile_photo)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                        alt="{{ auth()->user()->name }}" class="user-avatar user-photo">
                                @else
                                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                                @endif
                                <i class="fas fa-chevron-down user-chevron"></i>
                            </div>
                            <div id="userDropdown" class="user-dropdown">
                                <a href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    Dashboard
                                </a>
                                <a href="{{ route('client.logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('client-logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    @else
                        <li><a href="{{ route('client.login') }}" class="btn btn-outline" style="color: white; border-color: white; background: rgba(255,255,255,0.1);">Login</a></li>
                        <li><a href="{{ route('client.register') }}" class="btn btn-primary">Partner with Us</a></li>
                    @endauth
                </ul>
                <div class="mobile-controls">
                    @auth
                        <div class="mobile-user-avatar" onclick="toggleMobileMenu()">
                            @if (auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                    alt="{{ auth()->user()->name }}" class="user-avatar user-photo">
                            @else
                                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                            @endif
                        </div>
                    @endauth
                    <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobileOverlay" class="mobile-overlay" onclick="closeMobileMenu()"></div>
    <div id="mobileMenu" class="mobile-menu">
        <div class="mobile-menu-header">
            <span class="mobile-menu-title">Menu</span>
            <button class="mobile-menu-close" onclick="closeMobileMenu()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="mobile-menu-items">
            <li><a href="{{ route('landing.index') }}" class="{{ request()->routeIs('landing.index') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
            <li><a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">Services</a></li>
            <li><a href="{{ route('careers') }}" class="{{ request()->routeIs('careers') ? 'active' : '' }}">Careers</a></li>
            <li><a href="{{ route('landing.projects') }}" class="{{ request()->routeIs('landing.projects') ? 'active' : '' }}">Projects</a></li>
            <li><a href="{{ route('landing.blog.index') }}" class="{{ request()->routeIs('landing.blog.*') ? 'active' : '' }}">Blog</a></li>
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
        </ul>
        @auth
            <div class="mobile-user-section">
                <div class="mobile-user-info">
                    @if (auth()->user()->profile_photo)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                            alt="{{ auth()->user()->name }}" class="user-avatar user-photo">
                    @else
                        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    @endif
                    <span>{{ auth()->user()->name }}</span>
                </div>
                <ul class="mobile-menu-items">
                    <li><a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="{{ route('client.logout') }}"
                            onclick="event.preventDefault(); document.getElementById('client-logout-form').submit();"><i
                                class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        @else
            <div class="mobile-user-section">
                <a href="{{ route('client.login') }}" class="btn btn-outline mobile-auth-btn" style="color: #2C3E50; border-color: #2C3E50; margin-bottom: 0.5rem; display: block; text-align: center;">Login</a>
                <a href="{{ route('client.register') }}" class="btn btn-primary mobile-auth-btn" style="display: block; text-align: center;">Partner with Us</a>
            </div>
        @endauth
    </div>

    @yield('content')

    <!-- Floating Buttons -->
    <div id="scrollToTop" class="floating-btn scroll-top" onclick="scrollToTop()" style="display: none;">
        <i class="fas fa-chevron-up"></i>
    </div>
    
    <a href="https://wa.me/{{ str_replace(['(', ')', ' ', '-'], '', \App\Models\Setting::get('company_phone', '+254788225898')) }}" target="_blank" class="floating-btn whatsapp-btn">
        <i class="fab fa-whatsapp"></i>
    </a>

    <x-landing.footer />

    @auth
        <form id="client-logout-form" action="{{ route('client.logout') }}" method="POST" class="hidden-form">
            @csrf
        </form>
    @endauth

    <!-- Global Quote Modal -->
    <div id="quoteModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 2000; align-items: center; justify-content: center;">
        <div class="modal" style="background: white; border-radius: 15px; max-width: 500px; width: 90%; max-height: 80vh; overflow-y: auto;">
            <div class="modal-header" style="padding: 2rem 2rem 1rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="margin: 0; color: #2C3E50;">Request a Quote</h3>
                <button class="close-btn" onclick="closeModal('quoteModal')" style="background: none; border: none; font-size: 2rem; cursor: pointer; color: #6b7280;">&times;</button>
            </div>
            <div class="modal-content" style="padding: 2rem;">
                <form id="quoteForm">
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <input type="text" name="project_type" placeholder="Project Type (e.g., Residential, Commercial)" required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem; position: relative;">
                        <input type="text" id="quoteLocationInput" name="location" placeholder="Start typing location..." required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                        <div id="quoteLocationSuggestions" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 2001; max-height: 200px; overflow-y: auto; display: none;"></div>
                        <input type="hidden" name="latitude" id="quoteLatitude">
                        <input type="hidden" name="longitude" id="quoteLongitude">
                        <input type="hidden" name="formatted_address" id="quoteFormattedAddress">
                        <input type="hidden" name="place_id" id="quotePlaceId">
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <input type="text" name="budget" placeholder="Estimated Budget Range" required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <textarea name="description" rows="4" placeholder="Project Description" required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem; resize: vertical;"></textarea>
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <input type="text" name="name" placeholder="Your Name" required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <input type="email" name="email" placeholder="Your Email" required style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <input type="tel" name="phone" placeholder="Your Phone" style="width: 100%; padding: 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 1rem;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem 2rem; background: #F39C12; color: white; border: none; border-radius: 50px; font-size: 1rem; font-weight: 600; cursor: pointer;">Submit Request</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-menu')) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown) dropdown.style.display = 'none';
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.style.background = 'rgba(44,62,80,0.98)';
            } else {
                navbar.style.background = 'rgba(44,62,80,0.95)';
            }
        });

        // Mobile menu functions
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileOverlay');
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function closeMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const overlay = document.getElementById('mobileOverlay');
            menu.classList.remove('active');
            overlay.classList.remove('active');
        }

        window.toggleMobileMenu = toggleMobileMenu;
        window.closeMobileMenu = closeMobileMenu;
        
        // Scroll functions
        function scrollToNext() {
            const nextSection = document.querySelector('.section');
            if (nextSection) {
                nextSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
        
        function scrollToProjects() {
            const projectsSection = document.querySelector('#projects') || document.querySelector('.section');
            if (projectsSection) {
                projectsSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
        

        
        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
                document.documentElement.style.overflow = '';
            }
        }
        
        // Dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                const toggle = dropdown.querySelector('.dropdown-toggle');
                const menu = dropdown.querySelector('.dropdown-menu');
                let hideTimeout;
                
                // Show on hover
                dropdown.addEventListener('mouseenter', function() {
                    clearTimeout(hideTimeout);
                    dropdown.classList.add('show');
                });
                
                // Hide on mouse leave with delay
                dropdown.addEventListener('mouseleave', function() {
                    hideTimeout = setTimeout(() => {
                        dropdown.classList.remove('show');
                    }, 300);
                });
                
                // Prevent default click on toggle
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                });
            });
        });
        
        // Page Loader
        window.addEventListener('load', function() {
            const loader = document.getElementById('pageLoader');
            setTimeout(() => {
                loader.classList.add('hidden');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 500);
            }, 800);
        });
        
        // Image Loading Optimization
        function optimizeImages() {
            const images = document.querySelectorAll('img');
            images.forEach(img => {
                if (!img.complete) {
                    img.classList.add('loading');
                    img.addEventListener('load', function() {
                        this.classList.remove('loading');
                        this.classList.add('loaded');
                    });
                    img.addEventListener('error', function() {
                        this.classList.remove('loading');
                        this.style.opacity = '0.3';
                    });
                } else {
                    img.classList.add('loaded');
                }
            });
        }
        
        // Initialize optimizations
        document.addEventListener('DOMContentLoaded', optimizeImages);
        
        // Scroll to top functionality
        function scrollToTop() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
        
        // Show/hide scroll to top button
        window.addEventListener('scroll', function() {
            const scrollBtn = document.getElementById('scrollToTop');
            if (window.scrollY > 300) {
                scrollBtn.style.display = 'flex';
            } else {
                scrollBtn.style.display = 'none';
            }
        });
        
        // Location search for quote modal
        let quoteSearchTimeout;
        
        function initQuoteLocationSearch() {
            const locationInput = document.getElementById('quoteLocationInput');
            const suggestionsContainer = document.getElementById('quoteLocationSuggestions');
            
            if (!locationInput || !suggestionsContainer) return;
            
            locationInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(quoteSearchTimeout);
                
                if (query.length < 3) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }
                
                quoteSearchTimeout = setTimeout(async () => {
                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&countrycodes=ke,ug,tz,rw&limit=5`
                        );
                        const data = await response.json();
                        
                        displayQuoteSuggestions(data);
                    } catch (error) {
                        console.error('Location search error:', error);
                    }
                }, 300);
            });
            
            function displayQuoteSuggestions(suggestions) {
                if (suggestions.length === 0) {
                    suggestionsContainer.style.display = 'none';
                    return;
                }
                
                suggestionsContainer.innerHTML = '';
                suggestions.forEach(suggestion => {
                    const item = document.createElement('div');
                    item.innerHTML = `<strong>${suggestion.display_name.split(',')[0]}</strong><br><small style="color: #666;">${suggestion.display_name}</small>`;
                    
                    item.addEventListener('click', () => {
                        locationInput.value = suggestion.display_name;
                        document.getElementById('quoteLatitude').value = suggestion.lat;
                        document.getElementById('quoteLongitude').value = suggestion.lon;
                        document.getElementById('quoteFormattedAddress').value = suggestion.display_name;
                        document.getElementById('quotePlaceId').value = suggestion.place_id;
                        
                        locationInput.style.borderColor = '#4CAF50';
                        suggestionsContainer.style.display = 'none';
                    });
                    
                    suggestionsContainer.appendChild(item);
                });
                
                suggestionsContainer.style.display = 'block';
            }
            
            document.addEventListener('click', function(e) {
                if (!locationInput.parentNode.contains(e.target)) {
                    suggestionsContainer.style.display = 'none';
                }
            });
        }
        
        // Initialize location search when modal opens
        function showQuoteModal() {
            const modal = document.getElementById('quoteModal');
            if (modal) {
                modal.style.display = 'flex';
                initQuoteLocationSearch();
            }
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initQuoteLocationSearch();
        });
        
        // Global functions
        window.scrollToNext = scrollToNext;
        window.scrollToProjects = scrollToProjects;
        window.showQuoteModal = showQuoteModal;
        window.closeModal = closeModal;
        window.scrollToTop = scrollToTop;
    </script>
    <script src="{{ asset('js/animations.js') }}" defer></script>
    <script src="{{ asset('js/landing.js') }}" defer></script>
    @stack('scripts')
</body>

</html>
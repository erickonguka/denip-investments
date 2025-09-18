<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand animate-fade-up" style="text-align: left;">
                <div class="footer-logo" style="text-align: left;">
                    <svg viewBox="0 0 1605 502" xmlns="http://www.w3.org/2000/svg" class="logo-svg">
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
                <p class="footer-tagline" style="text-align: left;">Building Tomorrow's Infrastructure with Excellence</p>
                <div class="social-links" style="justify-content: flex-start;">
                    @if(\App\Models\Setting::get('social_facebook'))
                        <a href="{{ \App\Models\Setting::get('social_facebook') }}" target="_blank" class="social-link"><i class="fab fa-facebook"></i></a>
                    @endif
                    @if(\App\Models\Setting::get('social_twitter'))
                        <a href="{{ \App\Models\Setting::get('social_twitter') }}" target="_blank" class="social-link"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if(\App\Models\Setting::get('social_linkedin'))
                        <a href="{{ \App\Models\Setting::get('social_linkedin') }}" target="_blank" class="social-link"><i class="fab fa-linkedin"></i></a>
                    @endif
                    @if(\App\Models\Setting::get('social_instagram'))
                        <a href="{{ \App\Models\Setting::get('social_instagram') }}" target="_blank" class="social-link"><i class="fab fa-instagram"></i></a>
                    @endif
                </div>
            </div>
            
            <div class="footer-links-grid">
                <div class="footer-section animate-fade-up animate-delay-1">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('landing.projects') }}">Our Projects</a></li>
                        <li><a href="{{ route('careers') }}">Careers</a></li>
                        <li><a href="{{ route('landing.blog.index') }}">Blog</a></li>
                    </ul>
                </div>
                
                <div class="footer-section animate-fade-up animate-delay-2">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="{{ route('services') }}">Construction</a></li>
                        <li><a href="{{ route('services') }}">Infrastructure</a></li>
                        <li><a href="{{ route('services') }}">Project Management</a></li>
                        <li><a href="{{ route('services') }}">Consulting</a></li>
                    </ul>
                </div>
                
                <div class="footer-section animate-fade-up animate-delay-3">
                    <h4>Contact</h4>
                    <div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ \App\Models\Setting::get('company_phone', '(254) 788 225 898') }}</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ \App\Models\Setting::get('company_email', 'info@denipinvestments.com') }}</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ \App\Models\Setting::get('company_location', '7557-40100 Kisumu') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom animate-fade-up animate-delay-4">
            <div class="footer-bottom-content">
                <p>&copy; {{ date('Y') }} DENIP <span style="color: white;">INVESTMENTS LTD</span>. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
                    <a href="{{ route('terms-of-service') }}">Terms of Service</a>
                    <a href="{{ route('contact') }}">Contact</a>
                </div>
            </div>
        </div>
    </div>
</footer>


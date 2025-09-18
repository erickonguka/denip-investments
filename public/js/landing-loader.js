// Landing Page Loader and Performance Optimization
(function() {
    'use strict';

    // Show page loader immediately
    function showLoader() {
        const loader = document.getElementById('pageLoader');
        if (loader) {
            loader.style.display = 'flex';
            loader.classList.remove('hidden');
        }
    }

    // Hide page loader
    function hideLoader() {
        const loader = document.getElementById('pageLoader');
        if (loader) {
            setTimeout(() => {
                loader.classList.add('hidden');
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 500);
            }, 300);
        }
    }

    // Preload critical resources
    function preloadCritical() {
        const criticalResources = [
            '/css/design-system.css',
            '/css/landing.css',
            'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Montserrat:wght@300;400;500;600;700&display=swap'
        ];

        criticalResources.forEach(resource => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = resource.includes('.css') ? 'style' : 'font';
            link.href = resource;
            if (resource.includes('font')) {
                link.crossOrigin = 'anonymous';
            }
            document.head.appendChild(link);
        });
    }

    // Optimize images with intersection observer
    function setupImageOptimization() {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        img.classList.add('loading');
                        
                        img.onload = () => {
                            img.classList.remove('loading');
                            img.classList.add('loaded');
                        };
                        
                        img.onerror = () => {
                            img.classList.remove('loading');
                            img.classList.add('error');
                        };
                    }
                    
                    observer.unobserve(img);
                }
            });
        }, {
            rootMargin: '50px'
        });

        // Observe all lazy images
        document.querySelectorAll('img[data-src], img.lazy').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Optimize content loading
    function setupContentOptimization() {
        const contentObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('loaded');
                    
                    // Load any lazy content in this section
                    const lazyElements = entry.target.querySelectorAll('[data-lazy]');
                    lazyElements.forEach(el => {
                        el.classList.add('load-now');
                    });
                }
            });
        }, {
            rootMargin: '100px'
        });

        document.querySelectorAll('[data-lazy]').forEach(section => {
            contentObserver.observe(section);
        });
    }

    // Defer non-critical resources
    function deferNonCritical() {
        // Defer Font Awesome
        const fontAwesome = document.querySelector('link[href*="font-awesome"]');
        if (fontAwesome) {
            fontAwesome.media = 'print';
            fontAwesome.onload = function() {
                this.media = 'all';
            };
        }

        // Defer non-critical scripts
        const scripts = document.querySelectorAll('script[src*="animations"], script[src*="landing"]');
        scripts.forEach(script => {
            script.defer = true;
        });
    }

    // Network-aware optimizations
    function networkOptimizations() {
        if ('connection' in navigator) {
            const connection = navigator.connection;
            
            if (connection.effectiveType === 'slow-2g' || connection.effectiveType === '2g') {
                document.documentElement.classList.add('slow-connection');
                
                // Reduce animation duration
                document.documentElement.style.setProperty('--animation-duration', '0.1s');
                
                // Replace high-quality images
                document.querySelectorAll('img[src*="unsplash"]').forEach(img => {
                    if (img.src.includes('q=75') || img.src.includes('q=80')) {
                        img.src = img.src.replace(/q=\d+/, 'q=50');
                    }
                    if (img.src.includes('w=1200')) {
                        img.src = img.src.replace(/w=\d+/, 'w=800');
                    }
                });
            }
        }
    }

    // Initialize all optimizations
    function init() {
        // Show loader immediately
        showLoader();
        
        // Preload critical resources
        preloadCritical();
        
        // Network optimizations
        networkOptimizations();
        
        // Defer non-critical resources
        deferNonCritical();

        // When DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                setupImageOptimization();
                setupContentOptimization();
            });
        } else {
            setupImageOptimization();
            setupContentOptimization();
        }

        // When everything is loaded
        window.addEventListener('load', () => {
            hideLoader();
        });

        // Fallback to hide loader after 3 seconds
        setTimeout(hideLoader, 3000);
    }

    // Start optimization
    init();

})();

// Add performance CSS
const performanceCSS = `
    /* Loading states */
    .lazy { 
        opacity: 0.3; 
        transition: opacity 0.3s ease; 
        background: #f3f4f6;
    }
    .loading { 
        opacity: 0.7; 
        filter: blur(1px);
    }
    .loaded { 
        opacity: 1; 
        filter: none;
    }
    .error { 
        opacity: 0.2; 
        filter: grayscale(100%); 
    }
    
    /* Slow connection optimizations */
    .slow-connection * {
        animation-duration: 0.1s !important;
        transition-duration: 0.1s !important;
    }
    
    .slow-connection img {
        filter: blur(0.5px);
        transition: filter 0.3s;
    }
    
    .slow-connection img.loaded {
        filter: none;
    }
    
    /* Reduce motion for accessibility */
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
    
    /* Content loading optimization */
    [data-lazy] {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    
    [data-lazy].loaded {
        opacity: 1;
        transform: translateY(0);
    }
`;

// Inject performance CSS
const style = document.createElement('style');
style.textContent = performanceCSS;
document.head.appendChild(style);
// Performance Optimization Script
(function() {
    'use strict';

    // Lazy Loading for Images
    function initLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
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
                    
                    observer.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // Preload Critical Resources
    function preloadCriticalResources() {
        const criticalImages = [
            'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=75'
        ];

        criticalImages.forEach(src => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = src;
            document.head.appendChild(link);
        });
    }

    // Optimize Font Loading
    function optimizeFonts() {
        const fontLinks = document.querySelectorAll('link[href*="fonts.googleapis.com"]');
        fontLinks.forEach(link => {
            link.setAttribute('rel', 'preload');
            link.setAttribute('as', 'style');
            link.setAttribute('onload', "this.onload=null;this.rel='stylesheet'");
        });
    }

    // Defer Non-Critical CSS
    function deferNonCriticalCSS() {
        const nonCriticalCSS = document.querySelectorAll('link[rel="stylesheet"]:not([data-critical])');
        nonCriticalCSS.forEach(link => {
            if (!link.href.includes('design-system') && !link.href.includes('landing')) {
                link.media = 'print';
                link.onload = function() {
                    this.media = 'all';
                };
            }
        });
    }

    // Optimize Animations
    function optimizeAnimations() {
        // Reduce animations on slower devices
        if (navigator.hardwareConcurrency < 4) {
            document.documentElement.style.setProperty('--animation-duration', '0.2s');
        }

        // Pause animations when tab is not visible
        document.addEventListener('visibilitychange', () => {
            const animations = document.querySelectorAll('[class*="animate"]');
            if (document.hidden) {
                animations.forEach(el => el.style.animationPlayState = 'paused');
            } else {
                animations.forEach(el => el.style.animationPlayState = 'running');
            }
        });
    }

    // Content Loading Optimization
    function optimizeContentLoading() {
        // Defer loading of below-the-fold content
        const sections = document.querySelectorAll('.section');
        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('loaded');
                    // Trigger any lazy-loaded content in this section
                    const lazyElements = entry.target.querySelectorAll('[data-lazy]');
                    lazyElements.forEach(el => {
                        el.classList.add('load-now');
                    });
                }
            });
        }, { rootMargin: '50px' });

        sections.forEach(section => sectionObserver.observe(section));
    }

    // Network-aware loading
    function networkAwareLoading() {
        if ('connection' in navigator) {
            const connection = navigator.connection;
            
            // Reduce quality on slow connections
            if (connection.effectiveType === 'slow-2g' || connection.effectiveType === '2g') {
                document.documentElement.classList.add('slow-connection');
                
                // Replace high-quality images with lower quality versions
                const images = document.querySelectorAll('img[src*="unsplash"]');
                images.forEach(img => {
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
        // Run immediately
        preloadCriticalResources();
        optimizeFonts();
        networkAwareLoading();

        // Run when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                initLazyLoading();
                deferNonCriticalCSS();
                optimizeAnimations();
                optimizeContentLoading();
            });
        } else {
            initLazyLoading();
            deferNonCriticalCSS();
            optimizeAnimations();
            optimizeContentLoading();
        }
    }

    // Start optimization
    init();

    // Expose utilities globally
    window.performanceUtils = {
        initLazyLoading,
        preloadCriticalResources,
        optimizeAnimations
    };

})();

// CSS for performance optimizations
const performanceCSS = `
    .lazy { opacity: 0.3; transition: opacity 0.3s; }
    .loading { opacity: 0.7; }
    .loaded { opacity: 1; }
    .error { opacity: 0.2; filter: grayscale(100%); }
    
    .slow-connection img { 
        filter: blur(0.5px); 
        transition: filter 0.3s; 
    }
    .slow-connection img.loaded { 
        filter: none; 
    }
    
    @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
`;

// Inject performance CSS
const style = document.createElement('style');
style.textContent = performanceCSS;
document.head.appendChild(style);
// Lightweight Animation System
(function() {
    'use strict';

    // Animation observer for fade-up effects
    function initAnimations() {
        const animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '50px'
        });

        // Observe all animation elements
        document.querySelectorAll('[class*="animate-"]').forEach(el => {
            animationObserver.observe(el);
        });
    }

    // Smooth scroll for anchor links
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initAnimations();
            initSmoothScroll();
        });
    } else {
        initAnimations();
        initSmoothScroll();
    }

})();

// Add animation CSS
const animationCSS = `
    /* Animation base styles */
    [class*="animate-"] {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    
    [class*="animate-"].animate-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Delay classes */
    .animate-delay-1 { transition-delay: 0.1s; }
    .animate-delay-2 { transition-delay: 0.2s; }
    .animate-delay-3 { transition-delay: 0.3s; }
    .animate-delay-4 { transition-delay: 0.4s; }
    
    /* Slide animations */
    .animate-slide-right {
        transform: translateX(-30px);
    }
    
    .animate-slide-right.animate-in {
        transform: translateX(0);
    }
    
    /* Reduce animations on slow connections */
    .slow-connection [class*="animate-"] {
        transition-duration: 0.2s !important;
        transition-delay: 0s !important;
    }
`;

// Inject animation CSS
const animationStyle = document.createElement('style');
animationStyle.textContent = animationCSS;
document.head.appendChild(animationStyle);
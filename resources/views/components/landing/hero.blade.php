<section id="home" class="hero-slider">
    <div class="slider-container">
        <div class="slide active" data-bg="https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=75">
            <div class="slide-overlay"></div>
        </div>
        <div class="slide" data-bg="https://images.unsplash.com/photo-1504307651254-35680f356dfd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=75">
            <div class="slide-overlay"></div>
        </div>
        <div class="slide" data-bg="https://images.unsplash.com/photo-1590725175161-be908fcb4ce6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=75">
            <div class="slide-overlay"></div>
        </div>
    </div>
    
    <div class="container">
        <div class="hero-content animate-fade-up">
            <h1 class="hero-title animate-fade-up animate-delay-1">{{ $title ?? "Building Tomorrow's Infrastructure" }}</h1>
            <p class="hero-subtitle animate-fade-up animate-delay-2">{{ $subtitle ?? 'Leading construction and infrastructure development across Kenya with precision, innovation, and excellence' }}</p>
            <div class="cta-buttons animate-fade-up animate-delay-3">
                <x-invest-button 
                    text="Get Quote" 
                    onclick="showQuoteModal()" 
                    size="normal" 
                    state="bounce" 
                />
                <x-invest-button 
                    text="View Projects" 
                    href="#projects" 
                    size="normal" 
                    state="outline" 
                />
            </div>
        </div>
    </div>
    
    <div class="slider-dots">
        <span class="dot active" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
    </div>
    
    <div class="slider-nav">
        <button class="nav-btn prev" onclick="changeSlide(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="nav-btn next" onclick="changeSlide(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    
    <div class="scroll-indicator animate-fade-up animate-delay-4" onclick="scrollToNext()">
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<style>
.hero-slider {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    overflow: hidden;
}

.slider-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    background-color: #2C3E50;
}

.slide.active {
    opacity: 1;
}

.slide-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(44, 62, 80, 0.8) 0%, rgba(44, 62, 80, 0.6) 50%, rgba(243, 156, 18, 0.3) 100%);
}

.hero-content {
    position: relative;
    z-index: 3;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 2rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    display: flex;
    flex-direction: column;
    align-items: center;
}

.hero-title {
    font-size: clamp(2.5rem, 5vw, 4rem);
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.2;
    font-family: 'Playfair Display', serif;
    text-shadow: 3px 3px 6px rgba(0,0,0,0.7);
}

.hero-subtitle {
    font-size: clamp(1rem, 2vw, 1.3rem);
    margin-bottom: 2.5rem;
    opacity: 0.95;
    line-height: 1.6;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 3rem;
}

.cta-buttons .btn {
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    backdrop-filter: blur(10px);
}

.slider-dots {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 4;
    display: flex;
    gap: 1rem;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid rgba(255,255,255,0.8);
}

.dot.active,
.dot:hover {
    background: var(--primary);
    border-color: var(--primary);
    transform: scale(1.2);
}

.slider-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 4;
    width: 100%;
    display: flex;
    justify-content: space-between;
    padding: 0 2rem;
    pointer-events: none;
}

.nav-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    border: 2px solid rgba(255,255,255,0.5);
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    backdrop-filter: blur(10px);
    pointer-events: all;
}

.nav-btn:hover {
    background: var(--primary);
    border-color: var(--primary);
    transform: scale(1.1);
}

.scroll-indicator {
    position: absolute;
    bottom: 6rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 4;
    cursor: pointer;
    animation: bounce 2s infinite;
    color: white;
    font-size: 1.5rem;
    opacity: 0.8;
    transition: var(--transition);
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.scroll-indicator:hover {
    opacity: 1;
    transform: translateX(-50%) translateY(-5px);
}

@media (max-width: 768px) {
    .slide {
        background-attachment: scroll;
    }
    
    .slider-nav {
        padding: 0 1rem;
    }
    
    .nav-btn {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .slider-dots {
        bottom: 1.5rem;
    }
    
    .scroll-indicator {
        bottom: 5rem;
    }
}
</style>

<script>
let currentSlideIndex = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
let slideInterval;

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove('active'));
    dots.forEach(dot => dot.classList.remove('active'));
    
    slides[index].classList.add('active');
    dots[index].classList.add('active');
    
    currentSlideIndex = index;
}

function changeSlide(direction) {
    currentSlideIndex += direction;
    
    if (currentSlideIndex >= slides.length) {
        currentSlideIndex = 0;
    } else if (currentSlideIndex < 0) {
        currentSlideIndex = slides.length - 1;
    }
    
    showSlide(currentSlideIndex);
    resetAutoSlide();
}

function currentSlide(index) {
    showSlide(index - 1);
    resetAutoSlide();
}

function autoSlide() {
    currentSlideIndex++;
    if (currentSlideIndex >= slides.length) {
        currentSlideIndex = 0;
    }
    showSlide(currentSlideIndex);
}

function resetAutoSlide() {
    clearInterval(slideInterval);
    slideInterval = setInterval(autoSlide, 5000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Lazy load background images
    const slides = document.querySelectorAll('.slide');
    slides.forEach((slide, index) => {
        const bgUrl = slide.getAttribute('data-bg');
        if (index === 0) {
            // Load first image immediately
            slide.style.backgroundImage = `url('${bgUrl}')`;
        } else {
            // Preload other images
            const img = new Image();
            img.onload = () => {
                slide.style.backgroundImage = `url('${bgUrl}')`;
            };
            img.src = bgUrl;
        }
    });
    
    showSlide(0);
    slideInterval = setInterval(autoSlide, 5000);
    
    const heroSlider = document.querySelector('.hero-slider');
    if (heroSlider) {
        heroSlider.addEventListener('mouseenter', () => clearInterval(slideInterval));
        heroSlider.addEventListener('mouseleave', () => {
            slideInterval = setInterval(autoSlide, 5000);
        });
    }
});

window.changeSlide = changeSlide;
window.currentSlide = currentSlide;
</script>
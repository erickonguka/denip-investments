<section class="page-hero" @if($backgroundImage ?? false) data-bg="{{ $backgroundImage }}" @endif>
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1>{{ $title }}</h1>
            <p>{{ $subtitle }}</p>
            @if($showBreadcrumb ?? true)
            <nav class="breadcrumb">
                <a href="{{ route('landing.index') }}">Home</a>
                <span>/</span>
                <span>{{ $title }}</span>
            </nav>
            @endif
        </div>
    </div>
</section>

@push('styles')
<style>
.page-hero {
    height: 60vh;
    background: linear-gradient(135deg, rgba(44, 62, 80, 0.8), rgba(243, 156, 18, 0.6)), 
                #2C3E50 center/cover;
    display: flex;
    align-items: center;
    color: white;
    position: relative;
    background-size: cover;
    background-position: center;
}
.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(44, 62, 80, 0.7);
}
.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.hero-content h1 {
    font-size: clamp(2rem, 5vw, 3.5rem);
    margin-bottom: 1rem;
    font-family: 'Playfair Display', serif;
}
.hero-content p {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    opacity: 0.8;
}
.breadcrumb a {
    color: white;
    text-decoration: none;
}
.breadcrumb a:hover {
    color: #F39C12;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const pageHero = document.querySelector('.page-hero[data-bg]');
    if (pageHero) {
        const bgUrl = pageHero.getAttribute('data-bg');
        const img = new Image();
        img.onload = () => {
            pageHero.style.backgroundImage = `linear-gradient(135deg, rgba(44, 62, 80, 0.8), rgba(243, 156, 18, 0.6)), url('${bgUrl}')`;
        };
        img.src = bgUrl;
    }
});
</script>
@endpush
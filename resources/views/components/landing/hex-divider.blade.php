<div class="hex-divider" data-text="{{ $text }}">
    <h2>{{ $text }}</h2>
</div>

@push('styles')
<style>
.hex-divider {
    position: relative;
    background: var(--primary);
    color: var(--white);
    padding: 2rem 4rem;
    margin: 4rem auto;
    text-align: center;
    max-width: 400px;
    clip-path: polygon(0% 25%, 0% 75%, 50% 100%, 100% 75%, 100% 25%, 50% 0%);
    transform: translateY(50px);
    opacity: 0;
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.hex-divider.animate {
    transform: translateY(0);
    opacity: 1;
}

.hex-divider h2 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    font-family: var(--font-sans);
}

.hex-divider::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, var(--primary), #e67e22, var(--primary));
    clip-path: polygon(0% 25%, 0% 75%, 50% 100%, 100% 75%, 100% 25%, 50% 0%);
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.hex-divider:hover::before {
    opacity: 1;
}

@media (max-width: 768px) {
    .hex-divider {
        padding: 1.5rem 2rem;
        margin: 2rem auto;
        max-width: 300px;
    }
    
    .hex-divider h2 {
        font-size: 1.1rem;
        letter-spacing: 1px;
    }
}
</style>
@endpush
<div class="card service-card">
    <div class="service-icon">
        <i class="{{ $icon }}"></i>
    </div>
    <h3>{{ $title }}</h3>
    <p>{{ $description }}</p>
    @if(isset($features) && $features)
        <div style="margin-top: 1.5rem;">
            <ul style="list-style: none; padding: 0; text-align: left;">
                @foreach($features as $feature)
                    <li style="color: var(--gray); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-check" style="color: var(--primary); font-size: 0.8rem;"></i>
                        {{ $feature }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div style="margin-top: 1.5rem;">
        <button class="btn btn-primary" onclick="showQuoteModal()">Get Quote</button>
    </div>
</div>
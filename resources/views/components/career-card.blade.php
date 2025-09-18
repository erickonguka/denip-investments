<div class="simple-career-card" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border: 1px solid #e5e7eb; transition: all 0.2s ease;">
    <div class="career-header">
        <h3>{{ $career->title }}</h3>
        <div class="career-tags">
            <span class="location-tag"><i class="fas fa-map-marker-alt"></i> {{ $career->location }}</span>
            <span class="type-tag">{{ ucfirst($career->type) }}</span>
        </div>
    </div>
    <div class="career-body">
        <p>{{ Str::limit($career->description, 120) }}</p>
        @if($career->salary_min && $career->salary_max)
            <div class="salary-range">
                <i class="fas fa-money-bill-wave"></i> {{ number_format($career->salary_min) }} - {{ number_format($career->salary_max) }} KES
            </div>
        @endif
    </div>
    <div class="career-actions">
        <a href="{{ route('landing.careers.show', $career->slug) }}" class="btn-details">View Details</a>
        <a href="{{ route('landing.careers.apply', $career->slug) }}" class="btn-apply">Apply Now</a>
    </div>
</div>
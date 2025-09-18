<div class="card project-card">
    <div class="project-image">
        @if($project->media && count($project->media) > 0)
            @php $firstImage = collect($project->media)->first(fn($m) => str_starts_with($m['type'], 'image/')) @endphp
            @if($firstImage)
                <img src="{{ asset('storage/' . $firstImage['path']) }}" alt="{{ $project->title }}" loading="lazy">
            @else
                <div class="project-placeholder">
                    <i class="fas fa-building"></i>
                </div>
            @endif
        @else
            <div class="project-placeholder">
                <i class="fas fa-building"></i>
            </div>
        @endif
        <div class="project-status status-{{ strtolower($project->status) }}">
            {{ ucfirst($project->status) }}
        </div>
    </div>
    <div class="card-body project-content">
        <h3>{{ $project->title }}</h3>
        <p class="project-client">{{ $project->client->name }}</p>
        <p class="project-description">{!! Str::limit(strip_tags($project->description), 120) !!}</p>
        
        @if($project->category)
            <div class="mb-2">
                <span style="background: var(--light-gray); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                    @if($project->category->icon)
                        <i class="{{ $project->category->icon }}"></i>
                    @endif
                    {{ $project->category->name }}
                </span>
            </div>
        @endif
        
        <div class="project-actions">
            <a href="{{ route('landing.project.show', $project->slug) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-eye"></i> View Details
            </a>
            <button class="btn btn-outline btn-sm" onclick="showQuoteModal()">
                <i class="fas fa-calculator"></i> Get Quote
            </button>
        </div>
    </div>
</div>
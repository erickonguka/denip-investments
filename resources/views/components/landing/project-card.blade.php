<div class="simple-project-card" style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.2s ease, box-shadow 0.2s ease; border: 1px solid #e5e7eb; padding: 0;">
    <div class="project-img" style="height: 200px; position: relative; overflow: hidden;">
        @if($project->media && count($project->media) > 0)
            @php $firstImage = collect($project->media)->first(fn($m) => str_starts_with($m['type'], 'image/')) @endphp
            @if($firstImage)
                <img data-src="{{ asset('storage/' . $firstImage['path']) }}" alt="{{ $project->title }}" class="lazy" style="width: 100%; height: 100%; object-fit: cover; background: #f3f4f6;">
            @else
                <div class="img-placeholder" style="width: 100%; height: 100%; background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 2rem;">
                    <i class="fas fa-building"></i>
                </div>
            @endif
        @else
            <div class="img-placeholder" style="width: 100%; height: 100%; background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 2rem;">
                <i class="fas fa-building"></i>
            </div>
        @endif
        <span class="status-badge status-{{ strtolower($project->status) }}">{{ ucfirst($project->status) }}</span>
    </div>
    <div class="project-info" style="padding: 1.25rem;">
        @if($project->category)
            <span class="category-tag">
                @if($project->category->icon)<i class="{{ $project->category->icon }}"></i>@endif
                {{ $project->category->name }}
            </span>
        @endif
        <h3>{{ $project->title }}</h3>
        <p class="client-name">{{ $project->client->name }}</p>
        <p class="description">{!! Str::limit(strip_tags($project->description), 80) !!}</p>
        <div class="actions">
            <a href="{{ route('landing.project.show', $project->slug) }}" class="btn-view">View Details</a>
            <button onclick="showQuoteModal()" class="btn-quote">Get Quote</button>
        </div>
    </div>
</div>
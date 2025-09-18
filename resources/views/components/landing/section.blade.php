<section id="{{ $id ?? '' }}" class="section {{ $class ?? '' }}">
    <div class="container">
        @if(isset($title) || isset($subtitle))
        <div class="section-header">
            @if(isset($title))
            <h2>{{ $title }}</h2>
            @endif
            @if(isset($subtitle))
            <p>{{ $subtitle }}</p>
            @endif
        </div>
        @endif
        {{ $slot }}
    </div>
</section>
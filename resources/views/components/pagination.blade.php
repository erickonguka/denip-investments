@if ($paginator->hasPages())
<div class="custom-pagination">
    <div class="pagination-info">
        Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
    </div>
    <div class="pagination-controls">
        @if ($paginator->onFirstPage())
            <span class="pagination-btn disabled">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn">Previous</a>
        @endif
        
        <div class="pagination-numbers">
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                @if ($i == $paginator->currentPage())
                    <span class="pagination-number active">{{ $i }}</span>
                @else
                    <a href="{{ $paginator->url($i) }}" class="pagination-number">{{ $i }}</a>
                @endif
            @endfor
        </div>
        
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn">Next</a>
        @else
            <span class="pagination-btn disabled">Next</span>
        @endif
    </div>
</div>
@endif
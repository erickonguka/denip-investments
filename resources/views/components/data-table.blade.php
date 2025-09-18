@props(['title', 'searchPlaceholder' => 'Search...', 'headers', 'pagination' => null, 'actions' => true])

<div class="table-card">
    <div class="table-header">
        <h3>{{ $title }}</h3>
        <input type="text" placeholder="{{ $searchPlaceholder }}" class="table-search">
    </div>
    <div class="table-scroll">
        <table class="clean-table">
            <thead>
                <tr>
                    @foreach($headers as $header)
                    <th>{{ $header }}</th>
                    @endforeach
                    @if($actions)
                    <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
    @if($pagination)
    <div class="table-pagination pagination-wrapper">
        <x-pagination :paginator="$pagination" />
    </div>
    @endif
</div>



@push('styles')
<style>
.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

.custom-pagination {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.pagination-info {
    font-size: 0.9rem;
    color: var(--dark);
    opacity: 0.8;
}

.pagination-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.pagination-btn {
    padding: 0.5rem 1rem;
    background: var(--primary-blue);
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
    min-width: 80px;
    text-align: center;
}

.pagination-btn:hover {
    background: var(--deep-blue);
    color: white;
}

.pagination-btn.disabled {
    background: #e9ecef;
    color: #6c757d;
    cursor: not-allowed;
}

.pagination-numbers {
    display: flex;
    gap: 0.25rem;
    align-items: center;
}

.pagination-number {
    padding: 0.5rem 0.75rem;
    background: transparent;
    color: var(--dark);
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
    min-width: 40px;
    text-align: center;
}

.pagination-number:hover {
    background: var(--light);
    color: var(--primary-blue);
}

.pagination-number.active {
    background: var(--primary-blue);
    color: white;
    font-weight: 600;
}

.pagination-dots {
    padding: 0.5rem;
    color: #6c757d;
}

@media (max-width: 768px) {
    .custom-pagination {
        padding: 1rem;
    }
    
    .pagination-controls {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .pagination-btn {
        min-width: 120px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.table-search').forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.table-card').querySelector('tbody');
            const rows = table.querySelectorAll('tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });
});
</script>
@endpush
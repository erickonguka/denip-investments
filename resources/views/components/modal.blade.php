@props(['id', 'title', 'size' => 'default'])

<div class="modal" id="{{ $id }}" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 2000;">
    <div class="modal-content" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: var(--white); border-radius: 12px; padding: 2rem; 
        max-width: {{ $size === 'large' ? '800px' : ($size === 'small' ? '400px' : '500px') }}; width: 90%; max-height: 90vh; overflow-y: auto;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.5rem; font-weight: bold; color: var(--deep-blue);">{{ $title }}</h3>
            <button style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--gray-600);" onclick="closeModal('{{ $id }}')">&times;</button>
        </div>
        
        {{ $slot }}
    </div>
</div>

@push('styles')
<style>
@media (max-width: 768px) {
    .modal-content {
        width: 95% !important;
        padding: 1rem !important;
        max-height: 95vh !important;
        top: 2.5% !important;
        transform: translateX(-50%) !important;
    }
    
    .quotation-summary {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
    
    .quotation-items-container {
        margin: 0 -1rem;
        padding: 0 1rem;
    }
}
</style>
@endpush
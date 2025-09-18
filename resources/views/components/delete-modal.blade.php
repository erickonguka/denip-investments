<x-modal id="delete-modal" title="Confirm Delete">
    <div style="text-align: center; padding: 1rem;">
        <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: var(--error); margin-bottom: 1rem;"></i>
        <h4 style="color: var(--deep-blue); margin-bottom: 1rem;">Are you sure?</h4>
        <p id="delete-message" style="color: var(--gray-600); margin-bottom: 2rem;">This action cannot be undone.</p>
        
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <button class="btn" style="background: var(--error); color: white;" onclick="confirmDelete()">
                <i class="fas fa-trash"></i>
                Yes, Delete
            </button>
            <button class="btn" style="background: var(--gray-300); color: var(--gray-700);" onclick="closeModal('delete-modal')">
                Cancel
            </button>
        </div>
    </div>
</x-modal>

<script>
window.currentDeleteItem = null;

function openDeleteModal(id, type, name, url) {
    window.currentDeleteItem = { id, type, name, url };
    document.getElementById('delete-message').textContent = `This will permanently delete "${name}". This action cannot be undone.`;
    openModal('delete-modal');
}

function confirmDelete() {
    if (!window.currentDeleteItem) return;
    
    const { url } = window.currentDeleteItem;
    
    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal('delete-modal');
            
            // Redirect if on show page, reload if on index
            if (window.location.pathname.includes('/show') || window.location.pathname.match(/\/\d+$/)) {
                setTimeout(() => {
                    const indexUrl = window.location.pathname.split('/').slice(0, -1).join('/');
                    window.location.href = indexUrl;
                }, 1000);
            } else {
                setTimeout(() => location.reload(), 1000);
            }
        } else {
            showNotification('Failed to delete item', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred', 'error');
    });
}
</script>
// Mobile menu toggle
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const sidebar = document.getElementById('sidebar');
const mobileOverlay = document.getElementById('mobileOverlay');

if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('mobile-hidden');
        sidebar.classList.toggle('mobile-visible');
        mobileOverlay.classList.toggle('active');
    });
}

if (mobileOverlay) {
    mobileOverlay.addEventListener('click', () => {
        sidebar.classList.add('mobile-hidden');
        sidebar.classList.remove('mobile-visible');
        mobileOverlay.classList.remove('active');
    });
}

// Global Modal Functions
function openModal(modalId, data = null) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        if (data) {
            const form = modal.querySelector('form');
            const submitBtn = modal.querySelector('button[type="submit"] .btn-text');
            const title = modal.querySelector('h3');
            
            if (form) {
                Object.keys(data).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field) field.value = data[key];
                });
            }
            
            if (submitBtn) submitBtn.textContent = 'Update';
            if (title) {
                const originalTitle = title.textContent;
                title.textContent = originalTitle.replace('Add New', 'Edit').replace('Create New', 'Edit');
            }
        } else {
            const submitBtn = modal.querySelector('button[type="submit"] .btn-text');
            const title = modal.querySelector('h3');
            
            if (submitBtn) {
                const text = submitBtn.textContent;
                if (text === 'Update') {
                    submitBtn.textContent = text.includes('Client') ? 'Create Client' : 
                                           text.includes('Project') ? 'Create Project' : 
                                           text.includes('Invoice') ? 'Create Invoice' : 'Create';
                }
            }
        }
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        const form = modal.querySelector('form');
        const submitBtn = modal.querySelector('button[type="submit"] .btn-text');
        const title = modal.querySelector('h3');
        
        if (form) {
            form.reset();
            form.removeAttribute('data-edit-id');
        }
        
        if (submitBtn && submitBtn.textContent === 'Update') {
            const modalType = modalId.replace('-modal', '');
            submitBtn.textContent = `Create ${modalType.charAt(0).toUpperCase() + modalType.slice(1)}`;
        }
        
        if (title && title.textContent.includes('Edit')) {
            title.textContent = title.textContent.replace('Edit', 'Add New');
        }
    }
}

// Global Form Handler
function handleFormSubmit(form, url, method = 'POST') {
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    const editId = form.getAttribute('data-edit-id');
    
    if (editId) {
        const baseUrl = url.replace('/store', '');
        url = `${baseUrl}/${editId}`;
        data._method = 'PUT';
        method = 'POST'; // Laravel expects POST with _method field
    }
    
    return fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        return data;
    });
}

// Global Edit Function
function editRecord(id, type, data) {
    const modalId = `${type}-modal`;
    const form = document.querySelector(`#${modalId} form`);
    if (form) {
        form.setAttribute('data-edit-id', id);
        const title = document.querySelector(`#${modalId} h3`);
        if (title) title.textContent = `Edit ${type.charAt(0).toUpperCase() + type.slice(1)}`;
    }
    openModal(modalId, data);
}

// User menu functions
function toggleUserMenu() {
    const dropdown = document.getElementById('userDropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function switchAccount() {
    console.log('Switch account functionality');
}

// Close dropdowns when clicking outside
window.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        closeModal(e.target.id);
    }
    
    if (!e.target.closest('.user-menu')) {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown) dropdown.style.display = 'none';
    }
});

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--error)' : 'var(--primary-blue)'};
        color: white;
        border-radius: 8px;
        z-index: 10000;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        transform: translateX(100%);
        transition: transform 0.3s ease;
        font-weight: 600;
    `;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Confirmation dialog
function showConfirmation(title, message, onConfirm) {
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 10001;
        display: flex;
        align-items: center;
        justify-content: center;
    `;
    
    const confirmId = 'confirm_' + Date.now();
    
    modal.innerHTML = `
        <div style="background: white; border-radius: 12px; padding: 2rem; width: 90%; max-width: 400px;">
            <h3 style="margin: 0 0 1rem 0; color: var(--primary-blue);">${title}</h3>
            <p style="margin: 0 0 2rem 0; color: #6c757d;">${message}</p>
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button onclick="this.closest('.confirmation-modal').remove()" style="background: #6c757d; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer;">Cancel</button>
                <button id="${confirmId}" style="background: var(--primary-blue); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer;">Confirm</button>
            </div>
        </div>
    `;
    
    modal.className = 'confirmation-modal';
    document.body.appendChild(modal);
    
    document.getElementById(confirmId).onclick = function() {
        modal.remove();
        onConfirm();
    };
}
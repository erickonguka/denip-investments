@props(['id' => 'session-manager-modal'])

<x-modal id="{{ $id }}" title="Active Sessions" size="large">
    <div id="sessionsContent">
        <div style="margin-bottom: 1.5rem;">
            <p style="color: var(--gray-600); font-size: 0.9rem;">
                Manage your active sessions across different devices. You can revoke access to any device you don't recognize.
            </p>
        </div>

        <div id="sessionsList">
            <!-- Sessions will be loaded here -->
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
            <button type="button" onclick="revokeAllSessions()" class="btn" style="background: var(--error); color: var(--white); flex: 1;">
                <i class="fas fa-sign-out-alt"></i>
                Revoke All Other Sessions
            </button>
            <button type="button" onclick="closeModal('{{ $id }}')" class="btn" style="background: var(--gray-300); color: var(--gray-700); flex: 1;">
                Close
            </button>
        </div>
    </div>
</x-modal>

@push('styles')
<style>
.session-item {
    background: var(--white);
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.session-item.current {
    border-color: var(--success);
    background: #f0fdf4;
}

.session-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.session-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.session-icon.desktop {
    background: var(--light-blue);
    color: var(--white);
}

.session-icon.mobile {
    background: var(--yellow);
    color: var(--deep-blue);
}

.session-icon.tablet {
    background: var(--success);
    color: var(--white);
}

.session-details h4 {
    margin: 0 0 0.25rem 0;
    color: var(--deep-blue);
    font-size: 1rem;
}

.session-details p {
    margin: 0;
    color: var(--gray-600);
    font-size: 0.875rem;
}

.session-actions button {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.revoke-btn {
    background: var(--error);
    color: var(--white);
}

.revoke-btn:hover {
    background: #dc2626;
}

.current-badge {
    background: var(--success);
    color: var(--white);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}
</style>
@endpush

@push('scripts')
<script>
async function loadSessions() {
    try {
        const response = await fetch('/sessions', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            displaySessions(result.sessions);
        }
    } catch (error) {
        showNotification('Failed to load sessions', 'error');
    }
}

function displaySessions(sessions) {
    const container = document.getElementById('sessionsList');
    
    if (sessions.length === 0) {
        container.innerHTML = '<p style="text-align: center; color: var(--gray-600);">No active sessions found.</p>';
        return;
    }
    
    container.innerHTML = sessions.map(session => `
        <div class="session-item ${session.is_current ? 'current' : ''}">
            <div class="session-info">
                <div class="session-icon ${session.device_type.toLowerCase()}">
                    <i class="fas fa-${getDeviceIcon(session.device_type)}"></i>
                </div>
                <div class="session-details">
                    <h4>
                        ${session.browser} on ${session.device_type}
                        ${session.is_current ? '<span class="current-badge">Current</span>' : ''}
                    </h4>
                    <p>
                        ${session.ip_address} • ${session.location || 'Unknown location'}<br>
                        Last active: ${formatDate(session.last_activity)}
                    </p>
                </div>
            </div>
            <div class="session-actions">
                ${!session.is_current ? `
                    <button class="revoke-btn" onclick="revokeSession('${session.id}')">
                        <i class="fas fa-times"></i> Revoke
                    </button>
                ` : ''}
            </div>
        </div>
    `).join('');
}

function getDeviceIcon(deviceType) {
    switch (deviceType.toLowerCase()) {
        case 'mobile': return 'mobile-alt';
        case 'tablet': return 'tablet-alt';
        default: return 'desktop';
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInMinutes = Math.floor((now - date) / (1000 * 60));
    
    if (diffInMinutes < 1) return 'Just now';
    if (diffInMinutes < 60) return `${diffInMinutes} minutes ago`;
    if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)} hours ago`;
    return date.toLocaleDateString();
}

async function revokeSession(sessionId) {
    if (!confirm('Are you sure you want to revoke this session?')) return;
    
    try {
        const response = await fetch(`/sessions/${sessionId}/revoke`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Session revoked successfully', 'success');
            loadSessions(); // Reload sessions
        } else {
            showNotification('Failed to revoke session', 'error');
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
    }
}

async function revokeAllSessions() {
    if (!confirm('This will sign you out from all other devices. Continue?')) return;
    
    try {
        const response = await fetch('/sessions/revoke-all', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('All other sessions revoked successfully', 'success');
            loadSessions(); // Reload sessions
        } else {
            showNotification('Failed to revoke sessions', 'error');
        }
    } catch (error) {
        showNotification('An error occurred', 'error');
    }
}

// Load sessions when modal is opened
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('session-manager-modal');
    if (modal) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    if (modal.style.display === 'block') {
                        loadSessions();
                    }
                }
            });
        });
        observer.observe(modal, { attributes: true });
    }
});
</script>
@endpush
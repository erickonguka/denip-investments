@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<h1 class="page-title">User Management</h1>
<p class="page-subtitle">Manage users, roles, and permissions.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    @can('create', App\Models\User::class)
    <button class="btn btn-primary" onclick="openUserModal()">
        <i class="fas fa-user-plus"></i>
        Add New User
    </button>
    @endcan
</div>

<x-data-table 
    title="All Users" 
    :headers="['Name', 'Email', 'Role', 'Last Login', 'MFA', 'Status']"
    searchPlaceholder="Search users..."
    :pagination="$users">
    
    @forelse($users as $user)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                @else
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary-blue); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.9rem;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
                <a href="{{ route('users.show', $user) }}" style="color: var(--primary-blue); text-decoration: none; font-weight: 600;">
                    {{ $user->name }}
                </a>
            </div>
        </td>
        <td style="padding: 1rem;">{{ $user->email }}</td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; background: var(--light-yellow); color: var(--dark-yellow);">
                {{ $user->roles->first()?->display_name ?? 'No Role' }}
            </span>
        </td>
        <td style="padding: 1rem;">{{ $user->last_login_at?->format('Y-m-d H:i') ?? 'Never' }}</td>
        <td style="padding: 1rem;">
            <i class="fas {{ $user->mfa_enabled ? 'fa-check' : 'fa-times' }}" style="color: {{ $user->mfa_enabled ? 'var(--success)' : 'var(--error)' }};"></i>
        </td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $user->status === 'active' ? 'var(--light-yellow)' : '#fef2f2' }}; 
                color: {{ $user->status === 'active' ? 'var(--dark-yellow)' : 'var(--error)' }};">
                {{ ucfirst($user->status) }}
            </span>
        </td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                @can('update', $user)
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editUser({{ $user->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                @endcan
                @can('delete', $user)
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="deleteUser({{ $user->id }})">
                    <i class="fas fa-trash"></i>
                </button>
                @endcan
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" style="padding: 2rem; text-align: center; color: var(--gray-600);">No users found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="user-modal" title="Add New User">
    <form id="userForm">
        <x-form-field label="Full Name" name="name" :required="true" placeholder="Enter full name" />
        <x-form-field label="Email" name="email" type="email" :required="true" placeholder="Enter email address" />
        <x-form-field label="Phone" name="phone" type="tel" placeholder="Enter phone number" />
        <x-form-field label="Password" name="password" type="password" :required="true" placeholder="Enter password" />
        <x-form-field label="Role" name="role_id" type="select" :required="true" 
            :options="$roles->pluck('display_name', 'id')->toArray()" 
            placeholder="Select role" />
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="mfa_enabled" value="1" style="transform: scale(1.2);">
                <span style="font-weight: 600; color: var(--deep-blue);">Enable Multi-Factor Authentication</span>
            </label>
        </div>
        
        <x-form-field label="Status" name="status" type="select" 
            :options="['active' => 'Active', 'inactive' => 'Inactive']" 
            value="active" />
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Profile Photo</label>
            <x-upload-dropbox name="profile_photo" accept="image/*" :multiple="false" maxSize="5" text="Upload profile photo" id="profile-photo-upload" />
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Create User</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('user-modal')">Cancel</button>
        </div>
    </form>
</x-modal>


@endsection

@push('scripts')
<script>
document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = editId ? 'Updating...' : 'Saving...';
    
    // Handle form data with files
    const formData = new FormData(this);
    
    const url = editId ? `/users/${editId}` : '{{ route("users.store") }}';
    
    if (editId) {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            showNotification(response.message, 'success');
            closeModal('user-modal');
            location.reload();
        } else {
            showNotification('Failed to save user', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred', 'error');
    })
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = editId ? 'Update User' : 'Create User';
    });
});



function openUserModal() {
    // Reset form for create mode
    const form = document.querySelector('#user-modal form');
    const passwordField = document.querySelector('#user-modal input[name="password"]').closest('div');
    const title = document.querySelector('#user-modal h3');
    const submitBtn = document.querySelector('#user-modal .btn-text');
    
    form.reset();
    form.removeAttribute('data-edit-id');
    passwordField.style.display = 'block';
    
    // Hide existing media display
    const existingMedia = document.querySelector('#profile-photo-upload .existing-media');
    if (existingMedia) {
        existingMedia.style.display = 'none';
    }
    
    if (title) title.textContent = 'Add New User';
    if (submitBtn) submitBtn.textContent = 'Create User';
    
    openModal('user-modal');
}

function editUser(userId) {
    fetch(`{{ route('users.index') }}/${userId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            // Hide password field for edit mode
            const passwordField = document.querySelector('#user-modal input[name="password"]').closest('div');
            passwordField.style.display = 'none';
            
            // Set form to edit mode
            const form = document.querySelector('#user-modal form');
            form.setAttribute('data-edit-id', userId);
            
            // Populate form fields
            Object.keys(response.data).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = response.data[key];
                    } else {
                        field.value = response.data[key] || '';
                    }
                }
            });
            
            // Update upload dropbox with existing photo
            const uploadDropbox = document.querySelector('#profile-photo-upload');
            if (uploadDropbox && response.data.profile_photo) {
                // Create or update existing media display
                let existingMedia = uploadDropbox.querySelector('.existing-media');
                if (!existingMedia) {
                    existingMedia = document.createElement('div');
                    existingMedia.className = 'existing-media';
                    existingMedia.style.cssText = 'margin-top: 1rem;';
                    existingMedia.innerHTML = `
                        <h5 style="color: var(--deep-blue); margin-bottom: 0.5rem;">Current Files:</h5>
                        <div class="existing-file-list" style="display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center;"></div>
                    `;
                    uploadDropbox.querySelector('.upload-content').after(existingMedia);
                }
                
                const fileList = existingMedia.querySelector('.existing-file-list');
                fileList.innerHTML = `
                    <div class="existing-file-item" style="padding: 0.5rem; background: var(--light-yellow); border: 1px solid var(--yellow); border-radius: 6px; font-size: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                        <img src="/storage/${response.data.profile_photo}" alt="Profile Photo" style="width: 30px; height: 30px; object-fit: cover; border-radius: 4px;">
                        <span>Current Photo</span>
                    </div>
                `;
                existingMedia.style.display = 'block';
            }
            
            // Update modal title and button
            const title = document.querySelector('#user-modal h3');
            const submitBtn = document.querySelector('#user-modal .btn-text');
            if (title) title.textContent = 'Edit User';
            if (submitBtn) submitBtn.textContent = 'Update User';
            
            // Show modal
            openModal('user-modal');
        }
    })
    .catch(error => console.error('Error:', error));
}



function deleteUser(userId) {
    const userRow = event.target.closest('tr');
    const userName = userRow.querySelector('a').textContent.trim();
    const deleteUrl = `{{ route('users.index') }}/${userId}`;
    
    openDeleteModal(userId, 'user', userName, deleteUrl);
}
</script>
@endpush
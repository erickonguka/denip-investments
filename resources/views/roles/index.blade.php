@extends('layouts.app')

@section('title', 'Roles & Permissions')

@section('content')
<h1 class="page-title">Roles & Permissions</h1>
<p class="page-subtitle">Manage user roles and their permissions.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    @can('create', App\Models\Role::class)
    <button class="btn btn-primary" onclick="openModal('role-modal')">
        <i class="fas fa-plus"></i>
        Add New Role
    </button>
    @endcan
</div>

<x-data-table 
    title="All Roles" 
    :headers="['Role Name', 'Description', 'Permissions', 'Users']"
    searchPlaceholder="Search roles..."
    :pagination="$roles">
    
    @forelse($roles as $role)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            <div style="font-weight: 600; color: var(--deep-blue);">{{ $role->display_name }}</div>
            <small style="color: var(--gray-600);">{{ $role->name }}</small>
        </td>
        <td style="padding: 1rem;">{{ $role->description ?? 'No description' }}</td>
        <td style="padding: 1rem;">{{ $role->permissions->count() }}</td>
        <td style="padding: 1rem;">{{ $role->users->count() }}</td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                @can('update', $role)
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editRole({{ $role->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                @endcan
                @can('delete', $role)
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="deleteRole({{ $role->id }})">
                    <i class="fas fa-trash"></i>
                </button>
                @endcan
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" style="padding: 2rem; text-align: center; color: var(--gray-600);">No roles found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="role-modal" title="Role Management">
    <form id="roleForm">
        <x-form-field label="Role Name" name="name" :required="true" placeholder="e.g., content_manager" />
        <x-form-field label="Display Name" name="display_name" :required="true" placeholder="e.g., Content Manager" />
        <x-form-field label="Description" name="description" placeholder="Brief description of role responsibilities" />
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Permissions</label>
            <div style="max-height: 300px; overflow-y: auto; border: 1px solid var(--gray-300); border-radius: 8px; padding: 1rem;">
                @foreach($permissions as $module => $modulePermissions)
                <div style="margin-bottom: 1rem;">
                    <h4 style="color: var(--deep-blue); margin-bottom: 0.5rem; text-transform: capitalize;">{{ $module }}</h4>
                    @foreach($modulePermissions as $permission)
                    <label style="display: flex; align-items: center; margin-bottom: 0.25rem; cursor: pointer;">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" style="margin-right: 0.5rem;">
                        <span>{{ $permission->display_name }}</span>
                        <small style="color: var(--gray-600); margin-left: 0.5rem;">({{ $permission->name }})</small>
                    </label>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Save Role</span>
            </button>
            <button type="button" class="btn" style="background: var(--gray-300); color: var(--gray-700);" onclick="closeModal('role-modal')">Cancel</button>
        </div>
    </form>
</x-modal>

@push('scripts')
<script>
// Form submission
document.getElementById('roleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = editId ? 'Updating...' : 'Saving...';
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    const permissions = Array.from(document.querySelectorAll('[name="permissions[]"]:checked')).map(cb => cb.value);
    data.permissions = permissions;
    
    handleFormSubmit(this, '{{ route("roles.store") }}')
        .then(response => {
            if (response.success) {
                showNotification(response.message, 'success');
                closeModal('role-modal');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Failed to save role', 'error');
            }
        })
        .catch(() => {
            showNotification('An error occurred', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btnText.textContent = editId ? 'Update Role' : 'Create Role';
        });
});

function editRole(roleId) {
    fetch(`{{ route('roles.index') }}/${roleId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            editRecord(roleId, 'role', response.data);
            
            // Handle permissions checkboxes
            document.querySelectorAll('[name="permissions[]"]').forEach(cb => cb.checked = false);
            // Populate form fields
            document.querySelector('[name="display_name"]').value = response.data.display_name || '';
            
            if (response.data.permissions) {
                response.data.permissions.forEach(permId => {
                    const checkbox = document.querySelector(`[name="permissions[]"][value="${permId}"]`);
                    if (checkbox) checkbox.checked = true;
                });
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteRole(roleId) {
    const roleRow = event.target.closest('tr');
    const roleName = roleRow.querySelector('div').textContent.trim();
    const deleteUrl = `{{ route('roles.index') }}/${roleId}`;
    
    openDeleteModal(roleId, 'role', roleName, deleteUrl);
}
</script>
@endpush
@endsection
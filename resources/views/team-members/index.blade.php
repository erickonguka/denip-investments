@extends('layouts.app')

@section('title', 'Team Members')

@section('content')
<h1 class="page-title">Team Management</h1>
<p class="page-subtitle">Manage team members displayed on the about page.</p>

<div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
    <button class="btn btn-primary" onclick="openTeamMemberModal()">
        <i class="fas fa-plus"></i>
        Add Team Member
    </button>
</div>

<x-data-table 
    title="All Team Members" 
    :headers="['Photo', 'Name', 'Position', 'Contact', 'Order', 'Status']"
    searchPlaceholder="Search team members..."
    :pagination="$teamMembers">
    
    @forelse($teamMembers as $member)
    <tr style="border-bottom: 1px solid var(--gray-200);">
        <td style="padding: 1rem;">
            @if($member->photo)
                <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
            @else
                <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                    {{ strtoupper(substr($member->name, 0, 2)) }}
                </div>
            @endif
        </td>
        <td style="padding: 1rem;">
            <div style="font-weight: 600; color: var(--primary-blue);">{{ $member->name }}</div>
            @if($member->bio)
                <div style="font-size: 0.8rem; color: var(--gray); margin-top: 0.25rem;">{{ Str::limit($member->bio, 50) }}</div>
            @endif
        </td>
        <td style="padding: 1rem;">{{ $member->position }}</td>
        <td style="padding: 1rem;">
            @if($member->email || $member->phone)
                <div style="font-size: 0.8rem;">
                    @if($member->email)
                        <div><i class="fas fa-envelope" style="margin-right: 0.25rem;"></i>{{ $member->email }}</div>
                    @endif
                    @if($member->phone)
                        <div><i class="fas fa-phone" style="margin-right: 0.25rem;"></i>{{ $member->phone }}</div>
                    @endif
                </div>
            @else
                <span style="color: var(--gray-400);">-</span>
            @endif
        </td>
        <td style="padding: 1rem;">{{ $member->order }}</td>
        <td style="padding: 1rem;">
            <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; 
                background: {{ $member->is_active ? '#dcfce7' : '#fef2f2' }}; 
                color: {{ $member->is_active ? 'var(--success)' : 'var(--error)' }};">
                {{ $member->is_active ? 'Active' : 'Inactive' }}
            </span>
        </td>
        <td style="padding: 1rem;">
            <div style="display: flex; gap: 0.5rem;">
                <button class="btn" style="background: var(--primary-blue); color: white; padding: 0.5rem;" onclick="editTeamMember({{ $member->id }})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn" style="background: var(--error); color: white; padding: 0.5rem;" onclick="deleteTeamMember({{ $member->id }})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" style="padding: 2rem; text-align: center; color: var(--gray-600);">No team members found</td>
    </tr>
    @endforelse
</x-data-table>

<x-modal id="team-member-modal" title="Add Team Member">
    <form id="teamMemberForm" enctype="multipart/form-data">
        <x-form-field label="Name" name="name" :required="true" placeholder="Enter full name" />
        <x-form-field label="Position" name="position" :required="true" placeholder="Enter job position" />
        <x-form-field label="Bio" name="bio" type="textarea" placeholder="Brief biography" />
        
        <div style="margin-bottom: 1.5rem;">
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--deep-blue);">Photo</label>
            <input type="file" name="photo" accept="image/*" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: 8px;">
            <small style="color: var(--gray-600); font-size: 0.8rem;">Max size: 2MB. Formats: JPG, PNG, GIF</small>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <x-form-field label="Email" name="email" type="email" placeholder="email@example.com" />
            <x-form-field label="Phone" name="phone" placeholder="+254 700 000 000" />
        </div>
        
        <x-form-field label="Display Order" name="order" type="number" min="0" placeholder="0" />
        
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
            <input type="checkbox" name="is_active" id="is_active" checked style="margin: 0;">
            <label for="is_active" style="margin: 0; font-weight: 600; color: var(--deep-blue);">Active</label>
        </div>
        
        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn btn-primary">
                <span class="btn-text">Add Team Member</span>
            </button>
            <button type="button" class="btn" style="background: transparent; color: var(--primary-blue); border: 2px solid var(--primary-blue);" onclick="closeModal('team-member-modal')">Cancel</button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
document.getElementById('teamMemberForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button[type="submit"]');
    const btnText = btn.querySelector('.btn-text');
    const editId = this.getAttribute('data-edit-id');
    
    btn.disabled = true;
    btnText.textContent = editId ? 'Updating...' : 'Adding...';
    
    const formData = new FormData(this);
    const url = editId ? `/team-members/${editId}` : '{{ route("team-members.store") }}';
    if (editId) formData.append('_method', 'PUT');
    
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
            closeModal('team-member-modal');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(response.message || 'Failed to save team member', 'error');
        }
    })
    .catch(error => showNotification('An error occurred', 'error'))
    .finally(() => {
        btn.disabled = false;
        btnText.textContent = editId ? 'Update Team Member' : 'Add Team Member';
    });
});

function openTeamMemberModal() {
    const form = document.querySelector('#team-member-modal form');
    form.reset();
    form.removeAttribute('data-edit-id');
    document.querySelector('#team-member-modal h3').textContent = 'Add Team Member';
    document.querySelector('#team-member-modal .btn-text').textContent = 'Add Team Member';
    document.querySelector('#is_active').checked = true;
    openModal('team-member-modal');
}

function editTeamMember(memberId) {
    fetch(`/team-members/${memberId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(response => {
        if (response.success) {
            const form = document.getElementById('teamMemberForm');
            const member = response.data;
            
            form.setAttribute('data-edit-id', memberId);
            document.querySelector('#team-member-modal h3').textContent = 'Edit Team Member';
            document.querySelector('#team-member-modal .btn-text').textContent = 'Update Team Member';
            
            Object.keys(member).forEach(key => {
                const field = form.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = member[key];
                    } else if (field.type !== 'file') {
                        field.value = member[key] || '';
                    }
                }
            });
            
            openModal('team-member-modal');
        }
    });
}

function deleteTeamMember(memberId) {
    const memberRow = event.target.closest('tr');
    const memberName = memberRow.querySelector('div[style*="font-weight: 600"]').textContent.trim();
    const deleteUrl = `{{ route('team-members.index') }}/${memberId}`;
    openDeleteModal(memberId, 'team member', memberName, deleteUrl);
}
</script>
@endpush
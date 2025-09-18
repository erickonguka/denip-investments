<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('roles.read');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasPermission('roles.read');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('roles.create');
    }

    public function update(User $user, Role $role): bool
    {
        // Prevent modification of super_admin role by non-super_admins
        if ($role->name === 'super_admin' && !$user->hasRole('super_admin')) {
            return false;
        }
        return $user->hasPermission('roles.update');
    }

    public function delete(User $user, Role $role): bool
    {
        // Prevent deletion of system roles
        if (in_array($role->name, ['super_admin', 'admin', 'client'])) {
            return false;
        }
        return $user->hasPermission('roles.delete');
    }
}
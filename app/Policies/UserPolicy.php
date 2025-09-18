<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users.read');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasPermission('users.read');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('users.create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermission('users.update');
    }

    public function delete(User $user, User $model): bool
    {
        // Prevent self-deletion and super admin deletion
        if ($user->id === $model->id || $model->hasRole('super_admin')) {
            return false;
        }
        return $user->hasPermission('users.delete');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use App\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Assign admin roles to existing users who have role = 'admin'
        $adminUsers = User::where('role', 'admin')->get();
        
        $roles = [
            'super_admin' => Role::where('name', 'super_admin')->first(),
            'admin' => Role::where('name', 'admin')->first(),
            'content_manager' => Role::where('name', 'content_manager')->first(),
            'finance' => Role::where('name', 'finance')->first(),
        ];
        
        foreach ($adminUsers as $user) {
            // Assign super_admin role to first admin user, others get admin role
            $roleToAssign = $adminUsers->first()->id === $user->id ? $roles['super_admin'] : $roles['admin'];
            
            if ($roleToAssign && !$user->roles()->where('name', $roleToAssign->name)->exists()) {
                $user->roles()->attach($roleToAssign->id);
            }
        }
    }

    public function down(): void
    {
        $adminRoleNames = ['super_admin', 'admin', 'content_manager', 'finance'];
        User::whereHas('roles', function($query) use ($adminRoleNames) {
            $query->whereIn('name', $adminRoleNames);
        })->get()->each(function($user) use ($adminRoleNames) {
            $user->roles()->whereIn('name', $adminRoleNames)->detach();
        });
    }
};
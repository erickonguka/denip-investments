<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionTestController extends Controller
{
    public function testPermissions()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Not authenticated']);
        }
        
        $user = auth()->user();
        
        return response()->json([
            'user_info' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
            ],
            'roles' => $user->roles->pluck('name')->toArray(),
            'permissions' => PermissionService::getUserPermissions($user),
            'accessible_modules' => PermissionService::getAccessibleModules($user),
            'permission_checks' => [
                'can_view_users' => $user->hasPermission('users.read'),
                'can_create_users' => $user->hasPermission('users.create'),
                'can_delete_users' => $user->hasPermission('users.delete'),
                'can_view_roles' => $user->hasPermission('roles.read'),
                'can_create_roles' => $user->hasPermission('roles.create'),
                'can_view_settings' => $user->hasPermission('system.settings'),
                'can_view_activity_logs' => $user->hasPermission('activity_logs.view'),
            ],
            'helper_methods' => [
                'is_admin' => $user->isAdmin(),
                'is_client' => $user->isClient(),
                'is_super_admin' => $user->isSuperAdmin(),
                'can_manage_users' => $user->canManageUsers(),
                'can_manage_roles' => $user->canManageRoles(),
            ]
        ]);
    }
}
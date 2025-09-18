<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class PermissionService
{
    public static function getUserPermissions(User $user): array
    {
        if ($user->hasRole('super_admin')) {
            return Permission::all()->pluck('name')->toArray();
        }
        
        return $user->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->toArray();
    }
    
    public static function canAccessModule(User $user, string $module): bool
    {
        $modulePermissions = Permission::where('module', $module)->pluck('name')->toArray();
        
        foreach ($modulePermissions as $permission) {
            if ($user->hasPermission($permission)) {
                return true;
            }
        }
        
        return false;
    }
    
    public static function getAccessibleModules(User $user): array
    {
        if ($user->hasRole('super_admin')) {
            return Permission::distinct('module')->pluck('module')->toArray();
        }
        
        $userPermissions = self::getUserPermissions($user);
        $modules = [];
        
        foreach ($userPermissions as $permission) {
            $permissionModel = Permission::where('name', $permission)->first();
            if ($permissionModel && !in_array($permissionModel->module, $modules)) {
                $modules[] = $permissionModel->module;
            }
        }
        
        return $modules;
    }
    
    public static function validateRolePermissions(array $permissionIds): bool
    {
        return Permission::whereIn('id', $permissionIds)->count() === count($permissionIds);
    }
    
    public static function getSystemRoles(): array
    {
        return ['super_admin', 'admin', 'client'];
    }
    
    public static function canDeleteRole(Role $role): bool
    {
        return !in_array($role->name, self::getSystemRoles()) && $role->users()->count() === 0;
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Role::class);
        
        $roles = Role::with('permissions')->latest()->paginate(15);
        $permissions = Permission::all()->groupBy('module');
        return view('roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name|regex:/^[a-z_]+$/',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description']
        ]);

        if (isset($validated['permissions'])) {
            if (!PermissionService::validateRolePermissions($validated['permissions'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid permissions provided'
                ], 422);
            }
            $role->permissions()->sync($validated['permissions']);
        }

        \App\Models\ActivityLog::log('created', $role, 'Role created: ' . $role->display_name);

        return response()->json(['success' => true, 'message' => 'Role created successfully']);
    }

    public function edit(Role $role)
    {
        $this->authorize('view', $role);
        
        if (request()->wantsJson()) {
            $role->load('permissions');
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'display_name' => $role->display_name,
                    'description' => $role->description,
                    'permissions' => $role->permissions->pluck('id')->toArray()
                ]
            ]);
        }
        
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id . '|regex:/^[a-z_]+$/',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description']
        ]);

        if (isset($validated['permissions'])) {
            if (!PermissionService::validateRolePermissions($validated['permissions'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid permissions provided'
                ], 422);
            }
            $role->permissions()->sync($validated['permissions']);
        }

        \App\Models\ActivityLog::log('updated', $role, 'Role updated: ' . $role->display_name, $role->getChanges());

        return response()->json(['success' => true, 'message' => 'Role updated successfully']);
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        
        // Check if role can be deleted
        if (!PermissionService::canDeleteRole($role)) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete system role or role with assigned users'
            ], 422);
        }
        
        \App\Models\ActivityLog::log('deleted', $role, 'Role deleted: ' . $role->display_name);
        
        $role->delete();
        return response()->json(['success' => true, 'message' => 'Role deleted successfully']);
    }
}
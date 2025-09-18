<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'dashboard'],
            
            // Clients
            ['name' => 'clients.create', 'display_name' => 'Create Clients', 'module' => 'clients'],
            ['name' => 'clients.read', 'display_name' => 'View Clients', 'module' => 'clients'],
            ['name' => 'clients.update', 'display_name' => 'Edit Clients', 'module' => 'clients'],
            ['name' => 'clients.delete', 'display_name' => 'Delete Clients', 'module' => 'clients'],
            
            // Projects
            ['name' => 'projects.create', 'display_name' => 'Create Projects', 'module' => 'projects'],
            ['name' => 'projects.read', 'display_name' => 'View Projects', 'module' => 'projects'],
            ['name' => 'projects.update', 'display_name' => 'Edit Projects', 'module' => 'projects'],
            ['name' => 'projects.delete', 'display_name' => 'Delete Projects', 'module' => 'projects'],
            
            // Invoices
            ['name' => 'invoices.create', 'display_name' => 'Create Invoices', 'module' => 'invoices'],
            ['name' => 'invoices.read', 'display_name' => 'View Invoices', 'module' => 'invoices'],
            ['name' => 'invoices.update', 'display_name' => 'Edit Invoices', 'module' => 'invoices'],
            ['name' => 'invoices.delete', 'display_name' => 'Delete Invoices', 'module' => 'invoices'],
            ['name' => 'invoices.send', 'display_name' => 'Send Invoices', 'module' => 'invoices'],
            
            // Proposals
            ['name' => 'proposals.create', 'display_name' => 'Create Proposals', 'module' => 'proposals'],
            ['name' => 'proposals.read', 'display_name' => 'View Proposals', 'module' => 'proposals'],
            ['name' => 'proposals.update', 'display_name' => 'Edit Proposals', 'module' => 'proposals'],
            ['name' => 'proposals.delete', 'display_name' => 'Delete Proposals', 'module' => 'proposals'],
            
            // Quotations
            ['name' => 'quotations.create', 'display_name' => 'Create Quotations', 'module' => 'quotations'],
            ['name' => 'quotations.read', 'display_name' => 'View Quotations', 'module' => 'quotations'],
            ['name' => 'quotations.update', 'display_name' => 'Edit Quotations', 'module' => 'quotations'],
            ['name' => 'quotations.delete', 'display_name' => 'Delete Quotations', 'module' => 'quotations'],
            
            // Users
            ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'users'],
            ['name' => 'users.read', 'display_name' => 'View Users', 'module' => 'users'],
            ['name' => 'users.update', 'display_name' => 'Edit Users', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'users'],
            
            // Roles
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'module' => 'roles'],
            ['name' => 'roles.read', 'display_name' => 'View Roles', 'module' => 'roles'],
            ['name' => 'roles.update', 'display_name' => 'Edit Roles', 'module' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'module' => 'roles'],
            
            // Activity Logs
            ['name' => 'activity_logs.view', 'display_name' => 'View Activity Logs', 'module' => 'system'],
            ['name' => 'activity_logs.delete', 'display_name' => 'Delete Activity Logs', 'module' => 'system'],
            
            // System
            ['name' => 'system.settings', 'display_name' => 'System Settings', 'module' => 'system'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Create roles
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Full system access'
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrative access'
            ],
            [
                'name' => 'content_manager',
                'display_name' => 'Content Manager',
                'description' => 'Content management access'
            ],
            [
                'name' => 'finance',
                'display_name' => 'Finance',
                'description' => 'Financial operations access'
            ],
            [
                'name' => 'guest',
                'display_name' => 'Guest',
                'description' => 'Read-only access'
            ],
            [
                'name' => 'client',
                'display_name' => 'Client',
                'description' => 'Client access to project management and invoices'
            ]
        ];

        foreach ($roles as $roleData) {
            $role = Role::firstOrCreate(['name' => $roleData['name']], $roleData);
            
            // Assign permissions based on role
            switch ($roleData['name']) {
                case 'super_admin':
                    $role->permissions()->sync(Permission::all());
                    break;
                    
                case 'admin':
                    $adminPermissions = Permission::whereNotIn('name', ['system.settings', 'roles.delete', 'users.delete'])->get();
                    $role->permissions()->sync($adminPermissions);
                    break;
                    
                case 'content_manager':
                    $contentPermissions = Permission::whereIn('module', ['dashboard', 'clients', 'projects'])
                        ->whereNotIn('name', ['clients.delete', 'projects.delete'])
                        ->get();
                    $role->permissions()->sync($contentPermissions);
                    break;
                    
                case 'finance':
                    $financePermissions = Permission::whereIn('module', ['dashboard', 'invoices', 'quotations'])
                        ->orWhere('name', 'clients.read')
                        ->get();
                    $role->permissions()->sync($financePermissions);
                    break;
                    
                case 'guest':
                    $guestPermissions = Permission::where('name', 'like', '%.read')
                        ->orWhere('name', 'dashboard.view')
                        ->orWhere('name', 'activity_logs.view')
                        ->get();
                    $role->permissions()->sync($guestPermissions);
                    break;
                    
                case 'client':
                    // Clients have no admin permissions - they use separate client routes
                    $role->permissions()->sync([]);
                    break;
            }
        }
    }
}
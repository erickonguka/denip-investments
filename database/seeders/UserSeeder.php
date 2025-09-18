<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@denipinvestments.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567890',
                'status' => 'active',
                'role' => 'super_admin'
            ],
            [
                'name' => 'System Administrator',
                'email' => 'admin@denipinvestments.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567891',
                'status' => 'active',
                'role' => 'admin'
            ],
            [
                'name' => 'Content Manager',
                'email' => 'content@denipinvestments.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567892',
                'status' => 'active',
                'role' => 'content_manager'
            ],
            [
                'name' => 'Finance Manager',
                'email' => 'finance@denipinvestments.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567893',
                'status' => 'active',
                'role' => 'finance'
            ],
            [
                'name' => 'Guest User',
                'email' => 'guest@denipinvestments.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567894',
                'status' => 'active',
                'role' => 'guest'
            ],
            [
                'name' => 'Inactive User',
                'email' => 'inactive@denipinvestments.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567895',
                'status' => 'inactive',
                'role' => 'guest'
            ]
        ];

        foreach ($users as $userData) {
            $roleName = $userData['role'];
            $role = Role::where('name', $roleName)->first();
            
            if (!$role) {
                $this->command->error("Role '{$roleName}' not found!");
                continue;
            }
            
            $user = User::firstOrCreate(['email' => $userData['email']], $userData);
            
            // Ensure role is assigned in pivot table
            if (!$user->hasRole($roleName)) {
                $user->roles()->attach($role->id);
            }
        }
    }
}
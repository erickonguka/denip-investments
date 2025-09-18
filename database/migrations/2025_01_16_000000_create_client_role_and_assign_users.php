<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        // Create client role if it doesn't exist
        $clientRole = Role::firstOrCreate([
            'name' => 'client'
        ], [
            'display_name' => 'Client',
            'description' => 'Client access to project management and invoices'
        ]);

        // Assign all users with role = 'client' to the client role
        if (Schema::hasColumn('users', 'role')) {
            $clientUsers = User::where('role', 'client')->get();
            foreach ($clientUsers as $user) {
                if (!$user->roles()->where('name', 'client')->exists()) {
                    $user->roles()->attach($clientRole->id);
                }
            }
        }
    }

    public function down(): void
    {
        // Remove client role assignments
        $clientRole = Role::where('name', 'client')->first();
        if ($clientRole) {
            $clientRole->users()->detach();
            $clientRole->delete();
        }
    }
};
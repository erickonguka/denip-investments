<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Check if display_name column doesn't exist
            if (!Schema::hasColumn('roles', 'display_name')) {
                $table->string('display_name')->after('name')->nullable();
            }
        });
        
        // Update existing roles with display names
        \DB::table('roles')->where('name', 'super_admin')->update(['display_name' => 'Super Admin']);
        \DB::table('roles')->where('name', 'admin')->update(['display_name' => 'Admin']);
        \DB::table('roles')->where('name', 'content_manager')->update(['display_name' => 'Content Manager']);
        \DB::table('roles')->where('name', 'finance')->update(['display_name' => 'Finance']);
        \DB::table('roles')->where('name', 'guest')->update(['display_name' => 'Guest']);
        \DB::table('roles')->where('name', 'client')->update(['display_name' => 'Client']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'display_name')) {
                $table->dropColumn('display_name');
            }
        });
    }
};

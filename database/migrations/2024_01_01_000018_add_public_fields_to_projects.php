<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'is_public')) {
                $table->boolean('is_public')->default(false);
            }
            if (!Schema::hasColumn('projects', 'public_token')) {
                $table->string('public_token')->nullable()->unique();
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['is_public', 'public_token']);
        });
    }
};
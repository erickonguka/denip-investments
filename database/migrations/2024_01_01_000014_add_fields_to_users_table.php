<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->boolean('mfa_enabled')->default(false)->after('password');
            $table->string('mfa_secret')->nullable()->after('mfa_enabled');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active')->after('mfa_secret');
            $table->timestamp('last_login_at')->nullable()->after('status');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'mfa_enabled', 'mfa_secret', 'status', 'last_login_at', 'last_login_ip']);
        });
    }
};
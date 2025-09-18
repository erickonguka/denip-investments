<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('contact_preference')->nullable()->after('place_id');
            $table->string('company_size')->nullable()->after('contact_preference');
            $table->string('years_in_business')->nullable()->after('company_size');
            $table->string('registration_number')->nullable()->after('years_in_business');
            $table->text('project_description')->nullable()->after('registration_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['contact_preference', 'company_size', 'years_in_business', 'registration_number', 'project_description']);
        });
    }
};
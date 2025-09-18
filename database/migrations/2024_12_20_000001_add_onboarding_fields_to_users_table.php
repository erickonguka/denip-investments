<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('job_title')->nullable()->after('company');
            $table->string('industry')->nullable()->after('job_title');
            $table->string('project_type')->nullable()->after('industry');
            $table->string('project_scale')->nullable()->after('project_type');
            $table->string('project_location')->nullable()->after('project_scale');
            $table->string('project_timeline')->nullable()->after('project_location');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['job_title', 'industry', 'project_type', 'project_scale', 'project_location', 'project_timeline']);
        });
    }
};
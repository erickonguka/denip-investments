<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('projects', 'slug')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
            });
            
            // Generate slugs for existing projects
            $projects = \App\Models\Project::all();
            foreach ($projects as $project) {
                $project->slug = \Str::slug($project->title);
                $project->save();
            }
            
            Schema::table('projects', function (Blueprint $table) {
                $table->string('slug')->unique()->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
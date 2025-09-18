<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('project_timeline');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->text('formatted_address')->nullable()->after('longitude');
            $table->string('place_id')->nullable()->after('formatted_address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'formatted_address', 'place_id']);
        });
    }
};
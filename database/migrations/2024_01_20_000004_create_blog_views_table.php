<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->onDelete('cascade');
            $table->string('ip_address');
            $table->timestamp('viewed_at');
            
            $table->unique(['blog_id', 'ip_address']);
            $table->index(['blog_id', 'viewed_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_views');
    }
};
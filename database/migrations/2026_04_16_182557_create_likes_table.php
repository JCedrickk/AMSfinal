<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id('like_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts', 'post_id')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'post_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('likes');
    }
};
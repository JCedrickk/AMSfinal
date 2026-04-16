<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id('post_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->text('content');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('image_path')->nullable();
            $table->string('location')->nullable();
            $table->enum('type', ['announcement', 'event', 'job'])->default('announcement');
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('pinned_at')->nullable();
            $table->timestamp('event_date')->nullable();
            $table->string('company')->nullable();
            $table->string('salary')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
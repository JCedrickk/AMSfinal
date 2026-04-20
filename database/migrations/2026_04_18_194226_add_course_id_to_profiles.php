<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Add course_id column
            if (!Schema::hasColumn('profiles', 'course_id')) {
                $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('set null')->after('user_id');
            }
            
            // Keep course column for backward compatibility (can be removed later)
            if (Schema::hasColumn('profiles', 'course')) {
                $table->string('course')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'edit_pending_content')) {
                $table->text('edit_pending_content')->nullable()->after('content');
            }
            if (!Schema::hasColumn('posts', 'edit_status')) {
                $table->enum('edit_status', ['pending', 'approved', 'rejected'])->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['edit_pending_content', 'edit_status']);
        });
    }
};
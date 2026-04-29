<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumni_id_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('alumni_id_requests', 'claimed')) {
                $table->boolean('claimed')->default(false)->after('status');
            }
            if (!Schema::hasColumn('alumni_id_requests', 'claimed_at')) {
                $table->timestamp('claimed_at')->nullable()->after('claimed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('alumni_id_requests', function (Blueprint $table) {
            $table->dropColumn(['claimed', 'claimed_at']);
        });
    }
};
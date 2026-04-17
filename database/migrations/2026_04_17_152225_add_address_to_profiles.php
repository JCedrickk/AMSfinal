<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'address')) {
                $table->text('address')->nullable()->after('contact_number');
            }
            if (!Schema::hasColumn('profiles', 'birthday')) {
                $table->date('birthday')->nullable()->after('year_graduated');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['address', 'birthday']);
        });
    }
};
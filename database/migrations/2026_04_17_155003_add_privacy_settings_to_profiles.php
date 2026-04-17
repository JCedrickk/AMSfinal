<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('profiles', 'show_contact_number')) {
                $table->boolean('show_contact_number')->default(true)->after('contact_number');
            }
            if (!Schema::hasColumn('profiles', 'show_birthday')) {
                $table->boolean('show_birthday')->default(true)->after('birthday');
            }
            if (!Schema::hasColumn('profiles', 'show_address')) {
                $table->boolean('show_address')->default(true)->after('address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['show_contact_number', 'show_birthday', 'show_address']);
        });
    }
};
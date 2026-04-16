<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AlumniProfile;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'approved',
        ]);

        AlumniProfile::create([
            'user_id' => $admin->user_id,
            'full_name' => 'Admin User',
            'course' => 'Information Technology',
            'year_graduated' => 2020,
        ]);
    }
}
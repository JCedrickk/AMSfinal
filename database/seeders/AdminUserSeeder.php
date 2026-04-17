<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin already exists
        $user = User::where('email', 'admin@admin.com')->first();
        
        if (!$user) {
            $user = User::create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'approved'
            ]);

            Profile::create([
                'user_id' => $user->id,
                'course' => 'Administration',
                'year_graduated' => 2024,
                'contact_number' => '09123456789',
                'job_title' => 'System Administrator'
            ]);
            
            $this->command->info('Admin user created successfully!');
        } else {
            $this->command->info('Admin user already exists!');
        }
    }
}
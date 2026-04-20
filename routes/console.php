<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Permanently delete accounts archived for more than 30 days
Schedule::call(function () {
    $users = User::onlyTrashed()
        ->where('deleted_at', '<', Carbon::now()->subDays(30))
        ->get();

    $count = 0;

    foreach ($users as $user) {
        // Delete profile picture if exists
        if ($user->profile && $user->profile->profile_picture) {
            Storage::disk('public')->delete($user->profile->profile_picture);
        }
        
        // Delete post images
        foreach ($user->posts as $post) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
        }
        
        // Force delete user and all related data
        $user->forceDelete();
        $count++;
    }

    if ($count > 0) {
        \Log::info("Permanently deleted {$count} archived accounts.");
    }
})->daily();
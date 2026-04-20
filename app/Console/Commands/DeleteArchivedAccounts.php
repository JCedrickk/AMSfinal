<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\AlumniIdRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteArchivedAccounts extends Command
{
    protected $signature = 'accounts:delete-archived';
    protected $description = 'Permanently delete accounts that have been archived for more than 30 days';

    public function handle()
    {
        // Get users deleted more than 30 days ago
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
            
            // Force delete user and all related data (cascade will handle related tables)
            $user->forceDelete();
            $count++;
        }

        $this->info("Permanently deleted {$count} archived accounts.");
    }
}
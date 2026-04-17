<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'approved']);
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get approved posts for feed
        $posts = Post::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->with('user', 'likes', 'comments.user')
            ->paginate(10);
        
        // Get unread notifications count
        $unreadNotifications = Notification::where('user_id', $user->user_id)
            ->where('is_read', false)
            ->count();
        
        // Get user's recent posts
        $userPosts = Post::where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get recent notifications
        $recentNotifications = Notification::where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact('posts', 'unreadNotifications', 'userPosts', 'recentNotifications'));
    }
}
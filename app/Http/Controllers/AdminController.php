<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        $pendingUsers = User::where('status', 'pending')->count();
        $pendingPosts = Post::where('status', 'pending')->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalPosts = Post::count();
        
        return view('admin.dashboard', compact('pendingUsers', 'pendingPosts', 'totalUsers', 'totalPosts'));
    }

    public function pendingUsers()
    {
        $users = User::where('status', 'pending')
                     ->where('role', 'user')
                     ->with('alumniProfile')
                     ->get();
        
        return view('admin.pending-users', compact('users'));
    }

    public function approveUser(User $user)
    {
        $user->update(['status' => 'approved']);
        
        // Create notification for user
        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_approved',
            'message' => 'Your account has been approved! You can now log in and participate.',
        ]);
        
        return redirect()->back()->with('success', 'User approved successfully.');
    }

    public function rejectUser(User $user)
    {
        $user->update(['status' => 'rejected']);
        
        return redirect()->back()->with('success', 'User rejected successfully.');
    }

    public function pendingPosts()
    {
        $posts = Post::where('status', 'pending')
                     ->with('user')
                     ->orderBy('created_at', 'desc')
                     ->get();
        
        return view('admin.pending-posts', compact('posts'));
    }

    public function approvePost(Post $post)
    {
        $post->update(['status' => 'approved']);
        
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_approved',
            'message' => 'Your post has been approved and is now visible to everyone.',
        ]);
        
        return redirect()->back()->with('success', 'Post approved successfully.');
    }

    public function rejectPost(Post $post)
    {
        $post->update(['status' => 'rejected']);
        
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_rejected',
            'message' => 'Your post was rejected. Please ensure it follows community guidelines.',
        ]);
        
        return redirect()->back()->with('success', 'Post rejected successfully.');
    }
}
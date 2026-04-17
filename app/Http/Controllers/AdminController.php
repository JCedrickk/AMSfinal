<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\AlumniIdRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized access. Admin only.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $pendingUsers = User::where('status', 'pending')->where('role', 'user')->count();
        $pendingPosts = Post::where('status', 'pending')->count();
        $pendingEdits = Post::where('edit_status', 'pending')->count();
        $pendingIdRequests = AlumniIdRequest::where('status', 'pending')->count();
        $totalAlumni = User::where('role', 'user')->where('status', 'approved')->count();
        $totalAdmins = User::where('role', 'admin')->count(); // ADD THIS LINE
        
        $recentUsers = User::with('profile')->where('role', 'user')->latest()->take(5)->get();
        $recentPosts = Post::with('user')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact('pendingUsers', 'pendingPosts', 'pendingEdits', 'pendingIdRequests', 'totalAlumni', 'totalAdmins', 'recentUsers', 'recentPosts'));
    }

    public function pendingUsers()
    {
        $users = User::with('profile')->where('status', 'pending')->where('role', 'user')->get();
        return view('admin.users.pending', compact('users'));
    }

    public function approveUser(User $user)
    {
        $user->update(['status' => 'approved']);
        
        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_approved',
            'message' => '✅ Your account has been approved! You can now login to the Alumni Management System.',
            'is_read' => false
        ]);
        
        return back()->with('success', 'User approved successfully.');
    }

    public function rejectUser(User $user)
    {
        $user->update(['status' => 'rejected']);
        
        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_rejected',
            'message' => '❌ Your account has been rejected. Please contact the admin for more information.',
            'is_read' => false
        ]);
        
        return back()->with('success', 'User rejected.');
    }

    public function pendingPosts()
    {
        $posts = Post::with('user')->where('status', 'pending')->orderBy('created_at', 'desc')->get();
        return view('admin.posts.pending', compact('posts'));
    }

    public function approvePost(Post $post)
    {
        $post->update(['status' => 'approved']);
        
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_approved',
            'message' => 'Your post has been approved.',
            'is_read' => false
        ]);
        
        return back()->with('success', 'Post approved.');
    }

    public function rejectPost(Post $post)
    {
        $post->update(['status' => 'rejected']);
        
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_rejected',
            'message' => 'Your post has been rejected.',
            'is_read' => false
        ]);
        
        return back()->with('success', 'Post rejected.');
    }

    // ========== PENDING EDITS METHODS ==========
    
    public function pendingEdits()
    {
        $posts = Post::with('user')
            ->where('edit_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.posts.pending-edits', compact('posts'));
    }

    public function approveEdit(Post $post)
    {
        // Apply the pending edit to the actual content
        $post->update([
            'content' => $post->edit_pending_content,
            'edit_pending_content' => null,
            'edit_status' => 'approved'
        ]);
        
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_edit_approved',
            'message' => 'Your post edit has been approved. The post has been updated.',
            'is_read' => false
        ]);
        
        return back()->with('success', 'Post edit approved.');
    }

    public function rejectEdit(Post $post)
    {
        $post->update([
            'edit_pending_content' => null,
            'edit_status' => 'rejected'
        ]);
        
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_edit_rejected',
            'message' => 'Your post edit request has been rejected. The post remains unchanged.',
            'is_read' => false
        ]);
        
        return back()->with('success', 'Post edit rejected.');
    }

    // ========== USER MANAGEMENT ==========
    
    public function allUsers()
    {
        $users = User::with('profile')->where('role', 'user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    public function removeAdmin(Request $request, User $user)
    {
        // Verify the current user is an admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Verify password
        if (!\Hash::check($request->admin_password, auth()->user()->password)) {
            return back()->withErrors(['admin_password' => 'Incorrect password.'])->withInput();
        }
        
        // Prevent removing your own admin privileges
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot remove your own admin privileges.');
        }
        
        // Update user role to user
        $user->role = 'user';
        $user->save();
        
        // Notify the user
        Notification::create([
            'user_id' => $user->id,
            'type' => 'admin_privileges_removed',
            'message' => 'Your administrator privileges have been removed. You are now a regular user.' . 
                        ($request->reason ? ' Reason: ' . $request->reason : ''),
            'is_read' => false
        ]);
        
        // Notify other admins
        $admins = User::where('role', 'admin')->where('id', '!=', auth()->id())->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'admin_privileges_removed',
                'message' => auth()->user()->first_name . ' ' . auth()->user()->last_name . ' has removed admin privileges from ' . $user->first_name . ' ' . $user->last_name,
                'is_read' => false
            ]);
        }
        
        return back()->with('success', 'Admin privileges removed from ' . $user->first_name . ' ' . $user->last_name);
    }

    public function adminList()
    {
        $admins = User::with('profile')
            ->where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $totalAdmins = User::where('role', 'admin')->count();
        
        return view('admin.users.admins', compact('admins', 'totalAdmins'));
    }
}
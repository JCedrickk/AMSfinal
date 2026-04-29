<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Course;
use App\Models\AlumniIdRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || !Auth::user()->isAdmin()) {
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
        $totalAdmins = User::where('role', 'admin')->count();
        $archivedUsersCount = User::onlyTrashed()->where('role', 'user')->count();
        
        // ID Claim Statistics
        $claimedIdsCount = AlumniIdRequest::where('status', 'approved')->where('claimed', true)->count();
        $unclaimedIdsCount = AlumniIdRequest::where('status', 'approved')->where('claimed', false)->count();
        $replacementRequestsCount = AlumniIdRequest::where('remarks', 'like', '%Replacement request%')->count();
        $totalIdRequests = AlumniIdRequest::count();
        
        // Course statistics
        $courses = Course::where('is_active', true)->orderBy('sort_order')->get();
        $courseStats = [];
        
        foreach ($courses as $course) {
            $count = Profile::where('course_id', $course->id)
                ->whereHas('user', function($query) {
                    $query->where('role', 'user')->where('status', 'approved');
                })
                ->count();
            
            if ($count > 0) {
                $courseStats[] = [
                    'course_name' => $course->name,
                    'course_code' => $course->code,
                    'total' => $count
                ];
            }
        }
        
        usort($courseStats, function($a, $b) {
            return $b['total'] - $a['total'];
        });
        
        $recentUsers = User::with('profile')->where('role', 'user')->latest()->take(5)->get();
        $recentPosts = Post::with('user')->latest()->take(5)->get();
        
        return view('admin.dashboard', compact(
            'pendingUsers', 'pendingPosts', 'pendingEdits', 'pendingIdRequests', 
            'totalAlumni', 'totalAdmins', 'archivedUsersCount',
            'claimedIdsCount', 'unclaimedIdsCount', 'replacementRequestsCount', 'totalIdRequests',
            'courseStats', 'recentUsers', 'recentPosts'
        ));
    }

    public function pendingUsers()
    {
        $users = User::with('profile')->where('status', 'pending')->where('role', 'user')->get();
        return view('admin.users.pending', compact('users'));
    }

    public function approveUser(User $user)
    {
        // Update user status
        $user->update(['status' => 'approved']);
        
        // Send email notification to the user
        try {
            Mail::to($user->email)->send(new AccountApprovedMail($user));
            $emailSent = true;
        } catch (\Exception $e) {
            $emailSent = false;
            Log::error('Failed to send approval email to ' . $user->email . ': ' . $e->getMessage());
        }
        
        // Create database notification
        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_approved',
            'message' => 'Your account has been approved! You can now login to the Alumni Management System.' . 
                        ($emailSent ? ' An email confirmation has been sent to your email address.' : ''),
            'is_read' => false
        ]);
        
        // Notify admin who approved
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'user_approved',
                'message' => Auth::user()->first_name . ' ' . Auth::user()->last_name . ' approved ' . $user->first_name . ' ' . $user->last_name . '\'s account.',
                'is_read' => false
            ]);
        }
        
        $message = 'User approved successfully.';
        if ($emailSent) {
            $message .= ' Email notification sent to ' . $user->email;
        } else {
            $message .= ' Email could not be sent. Please check mail configuration.';
        }
        
        return back()->with('success', $message);
    }

    public function rejectUser(Request $request, User $user)
    {
        $request->validate([
            'remarks' => 'nullable|string'
        ]);
        
        $user->update(['status' => 'rejected']);
        
        // Send email notification
        try {
            Mail::to($user->email)->send(new AccountRejectedMail($user, $request->remarks));
        } catch (\Exception $e) {
            Log::error('Failed to send rejection email: ' . $e->getMessage());
        }
        
        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_rejected',
            'message' => '❌ Your account has been rejected. Reason: ' . ($request->remarks ?? 'No reason provided'),
            'is_read' => false
        ]);
        
        return back()->with('success', 'User rejected successfully.');
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

   public function rejectPost(Request $request, Post $post)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:5'
        ]);
        
        $post->update(['status' => 'rejected']);
        
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_rejected',
            'message' => 'Your post has been rejected. Reason: ' . $request->rejection_reason,
            'is_read' => false
        ]);
        
        return back()->with('success', 'Post rejected. User has been notified.');
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

    public function rejectEdit(Request $request, Post $post)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:5'
        ]);

        $post->update([
            'edit_pending_content' => null,
            'edit_status' => 'rejected'
        ]);
        
        Notification::create([
            'user_id' => $post->user_id,
            'type' => 'post_edit_rejected',
            'message' => 'Your post has been rejected. Reason: ' . $request->rejection_reason,
            'is_read' => false
        ]);
        
        return back()->with('success', 'Post edit rejected.');
    }

    // ========== USER MANAGEMENT ==========
    
    public function allUsers()
    {
        $users = User::with('profile')->where('role', 'user')->orderBy('created_at', 'desc')->paginate(20);
        
        // Add stats
        $totalUsers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();
        
        return view('admin.users.index', compact('users', 'totalUsers', 'totalAdmins', 'totalRegularUsers'));
    }

    public function deleteUser(User $user)
    {
        // Prevent admin from deleting their own account
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        
        // Soft delete the account (archive it)
        $user->delete(); // This sets deleted_at timestamp
        
        // Notify the user
        Notification::create([
            'user_id' => $user->id,
            'type' => 'account_archived',
            'message' => 'Your account has been archived by an administrator. It will be permanently deleted after 30 days. You can contact support to restore your account within this period.',
            'is_read' => false
        ]);
        
        // Notify other admins
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'account_archived_by_admin',
                'message' => Auth::user()->first_name . ' ' . Auth::user()->last_name . ' has archived the account of ' . $user->first_name . ' ' . $user->last_name . '. It will be permanently deleted after 30 days.',
                'is_read' => false
            ]);
        }
        
        return back()->with('success', 'User account has been archived. It will be permanently deleted after 30 days.');
    }

    public function restoreUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        
        // Check if within 30 days
        if ($user->deleted_at && Carbon::parse($user->deleted_at)->diffInDays(now()) <= 30) {
            $user->restore();
            
            Notification::create([
                'user_id' => $user->id,
                'type' => 'account_restored',
                'message' => 'Your account has been restored by an administrator. You can now login again.',
                'is_read' => false
            ]);
            
            return back()->with('success', 'User account has been restored successfully.');
        }
        
        return back()->with('error', 'Cannot restore account. More than 30 days have passed.');
    }

    public function archivedUsers()
    {
        $archivedUsers = User::onlyTrashed()
            ->where('role', 'user')
            ->orderBy('deleted_at', 'desc')
            ->paginate(20);
        
        return view('admin.users.archived', compact('archivedUsers'));
    }

    public function removeAdmin(Request $request, User $user)
    {
        // Verify the current user is an admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Verify password
        if (!Hash::check($request->admin_password, Auth::user()->password)) {
            return back()->withErrors(['admin_password' => 'Incorrect password.'])->withInput();
        }

        // Prevent removing your own admin privileges
        if ($user->id === Auth::id()) {
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
        $admins = User::where('role', 'admin')->where('id', '!=', Auth::id())->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'admin_privileges_removed',
                'message' => Auth::user()->first_name . ' ' . Auth::user()->last_name . ' has removed admin privileges from ' . $user->first_name . ' ' . $user->last_name,
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

    // ========== COURSE MANAGEMENT ==========
    
    public function courses()
    {
        $courses = Course::orderBy('sort_order')->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function createCourse()
    {
        return view('admin.courses.create');
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:courses',
            'code' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
        ]);

        Course::create([
            'name' => $request->name,
            'code' => $request->code,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        return redirect()->route('admin.courses')->with('success', 'Course added successfully.');
    }

    public function editCourse(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function updateCourse(Request $request, Course $course)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:courses,name,' . $course->id,
            'code' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $course->update([
            'name' => $request->name,
            'code' => $request->code,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully.');
    }

    public function deleteCourse(Course $course)
    {
        if ($course->profiles()->count() > 0) {
            return back()->with('error', 'Cannot delete course because it has alumni members.');
        }
        
        $course->delete();
        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully.');
    }

    public function markIdAsClaimed(AlumniIdRequest $idRequest)
    {
        if ($idRequest->status !== 'approved') {
            return back()->with('error', 'Only approved ID requests can be marked as claimed.');
        }
        
        $idRequest->update([
            'claimed' => true,
            'claimed_at' => now()
        ]);
        
        // Notify the user
        Notification::create([
            'user_id' => $idRequest->user_id,
            'type' => 'id_claimed',
            'message' => 'Your Alumni ID has been marked as claimed. Thank you for visiting the Alumni Office!',
            'is_read' => false
        ]);
        
        return back()->with('success', 'ID marked as claimed successfully.');
    }

    public function markIdAsUnclaimed(AlumniIdRequest $idRequest)
    {
        if ($idRequest->status !== 'approved') {
            return back()->with('error', 'Only approved ID requests can be marked as unclaimed.');
        }
        
        $idRequest->update([
            'claimed' => false,
            'claimed_at' => null
        ]);
        
        // Notify the user
        Notification::create([
            'user_id' => $idRequest->user_id,
            'type' => 'id_unclaimed',
            'message' => 'Your Alumni ID status has been updated to "Not Claimed". Please visit the Alumni Office to claim your ID.',
            'is_read' => false
        ]);
        
        return back()->with('success', 'ID marked as unclaimed successfully.');
    }
}
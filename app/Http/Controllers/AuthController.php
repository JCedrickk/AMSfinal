<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if user exists (including soft deleted)
        $user = User::withTrashed()->where('email', $request->email)->first();

        if ($user && $user->trashed()) {
            // Check if within 30-day restoration period
            $daysArchived = Carbon::parse($user->deleted_at)->diffInDays(now());
            
            if ($daysArchived <= 30) {
                // Auto-restore the account
                $user->restore();
                
                // Notify user about restoration
                session()->flash('success', 'Your account has been restored successfully! You can now login.');
            } else {
                // Account permanently deleted
                return back()->withErrors(['email' => 'Your account has been permanently deleted and cannot be restored.']);
            }
        }

        // Check if user is approved
        if ($user && $user->status !== 'approved') {
            return back()->withErrors(['email' => 'Your account is pending approval.']);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('feed');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

   public function showRegister()
    {
        $courses = Course::where('is_active', true)->orderBy('sort_order')->get();
        return view('auth.register', compact('courses'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'course_id' => 'required|exists:courses,id',
            'year_graduated' => 'required|integer',
            'birthday' => 'nullable|date',
            'contact_number' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500'
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'pending'
        ]);

        $course = Course::find($request->course_id);

        Profile::create([
            'user_id' => $user->id,
            'course_id' => $request->course_id,
            'course' => $course->name, // Store course name as backup
            'year_graduated' => $request->year_graduated,
            'birthday' => $request->birthday,
            'contact_number' => $request->contact_number,
            'job_title' => $request->job_title,
            'address' => $request->address
    ]);

        // Notify admins...
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'user_registration',
                'message' => '📝 New user registration pending approval: ' . $user->first_name . ' ' . $user->last_name,
                'is_read' => false
            ]);
        }

        return redirect()->route('login')->with('success', 'Registration successful! Please wait for admin approval.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
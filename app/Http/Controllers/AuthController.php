<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Profile;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

        $user = User::where('email', $request->email)->first();

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
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'nullable|date',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'course' => 'required|string|max:255',
            'year_graduated' => 'required|integer'
        ]);

        // Create user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'pending'
        ]);

        // Create profile
        Profile::create([
            'user_id' => $user->id,
            'course' => $request->course,
            'year_graduated' => $request->year_graduated
        ]);

        // Send notification to all admins about new user registration
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'user_registration',
                'message' => '📝 New user registration pending approval: ' . $user->first_name . ' ' . $user->last_name . ' (' . $user->email . ')',
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
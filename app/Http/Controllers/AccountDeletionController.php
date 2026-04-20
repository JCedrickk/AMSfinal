<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\AlumniIdRequest;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AccountDeletionController extends Controller
{
    public function showDeletionForm()
    {
        $user = Auth::user();
        $daysRemaining = null;
        
        if ($user->deleted_at) {
            $daysRemaining = 30 - Carbon::parse($user->deleted_at)->diffInDays(now());
            if ($daysRemaining < 0) $daysRemaining = 0;
        }
        
        return view('profile.delete-account', compact('daysRemaining'));
    }

    public function verifyAndDelete(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirmation_text' => 'required|in:DELETE MY ACCOUNT',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The password you entered is incorrect.']);
        }

        // Generate restore token
        $restoreToken = Str::random(64);
        
        $user->restore_token = $restoreToken;
        $user->restore_token_expires_at = Carbon::now()->addDays(30);
        $user->save();
        
        // Soft delete
        $user->delete();
        
        // Send restore email (optional)
        // Mail::to($user->email)->send(new AccountArchivedMail($user, $restoreToken));
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('welcome')->with('success', 
            'Your account has been archived. You have 30 days to restore it by simply logging in again. After 30 days, your account will be permanently deleted.');
    }
    
    public function showRestoreForm($token)
    {
        $user = User::onlyTrashed()
            ->where('restore_token', $token)
            ->where('restore_token_expires_at', '>', Carbon::now())
            ->first();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 
                'Invalid or expired restore link. Please contact support if you need assistance.');
        }
        
        return view('auth.restore-account', compact('token'));
    }
    
    public function restoreAccount(Request $request, $token)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $user = User::onlyTrashed()
            ->where('restore_token', $token)
            ->where('restore_token_expires_at', '>', Carbon::now())
            ->first();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired restore link.');
        }
        
        if ($user->email !== $request->email) {
            return back()->withErrors(['email' => 'Email does not match the archived account.']);
        }
        
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }
        
        // Restore the account
        $user->restore();
        $user->restore_token = null;
        $user->restore_token_expires_at = null;
        $user->save();
        
        return redirect()->route('login')->with('success', 
            'Your account has been restored successfully! You can now login.');
    }
}
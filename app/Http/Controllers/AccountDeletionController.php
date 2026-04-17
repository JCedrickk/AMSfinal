<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\AlumniIdRequest;

class AccountDeletionController extends Controller
{
    public function showDeletionForm()
    {
        return view('profile.delete-account');
    }

    public function verifyAndDelete(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirmation_text' => 'required|in:DELETE MY ACCOUNT',
        ], [
            'password.required' => 'Please enter your password to confirm account deletion.',
            'confirmation_text.required' => 'Please type "DELETE MY ACCOUNT" to confirm.',
            'confirmation_text.in' => 'Please type exactly "DELETE MY ACCOUNT" to confirm deletion.',
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The password you entered is incorrect.']);
        }

        // Start deletion process
        try {
            // Delete user's data
            $this->deleteUserData($user);
            
            // Logout the user
            Auth::logout();
            
            // Invalidate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('welcome')->with('success', 'Your account has been permanently deleted. We\'re sad to see you go!');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting your account. Please try again later.');
        }
    }

    private function deleteUserData($user)
    {
        // Delete user's posts (they will be soft deleted or permanently deleted)
        Post::where('user_id', $user->id)->delete();
        
        // Delete user's comments
        Comment::where('user_id', $user->id)->delete();
        
        // Delete user's likes
        Like::where('user_id', $user->id)->delete();
        
        // Delete user's notifications
        Notification::where('user_id', $user->id)->delete();
        
        // Delete user's alumni ID requests
        AlumniIdRequest::where('user_id', $user->id)->delete();
        
        // Delete user's profile
        if ($user->profile) {
            // Delete profile picture if exists
            if ($user->profile->profile_picture) {
                \Storage::disk('public')->delete($user->profile->profile_picture);
            }
            $user->profile->delete();
        }
        
        // Finally, delete the user
        $user->delete();
    }
}
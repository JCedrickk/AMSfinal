<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\AlumniProfile;
use App\Models\Post;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        // Load profile if exists
        if (!$user->profile) {
            AlumniProfile::create([
                'user_id' => $user->user_id,
                'full_name' => $user->first_name . ' ' . $user->last_name,
                'course' => '',
                'year_graduated' => $user->year_graduated ?? date('Y'),
            ]);
            $user->load('profile');
        }
        
        // Get user's posts
        $myPosts = Post::where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('profile.show', compact('user', 'myPosts'));
    }
    
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'year_graduated' => 'nullable|integer|min:1950|max:' . date('Y'),
            'course' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'facebook_link' => 'nullable|url|max:255',
            'twitter_link' => 'nullable|url|max:255',
            'instagram_link' => 'nullable|url|max:255',
            'linkedin_link' => 'nullable|url|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Update user
        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'year_graduated' => $validated['year_graduated'],
        ]);
        
        // Handle profile picture
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
            $user->save();
        }
        
        // Update or create alumni profile
        $profileData = [
            'full_name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'course' => $validated['course'] ?? '',
            'year_graduated' => $validated['year_graduated'] ?? date('Y'),
            'job_title' => $validated['job_title'] ?? null,
            'company' => $validated['company'] ?? null,
            'contact_number' => $validated['contact_number'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'facebook_link' => $validated['facebook_link'] ?? null,
            'twitter_link' => $validated['twitter_link'] ?? null,
            'instagram_link' => $validated['instagram_link'] ?? null,
            'linkedin_link' => $validated['linkedin_link'] ?? null,
        ];
        
        AlumniProfile::updateOrCreate(
            ['user_id' => $user->user_id],
            $profileData
        );
        
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
    
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        // Delete profile picture
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        
        // Delete user's posts, comments, likes
        Post::where('user_id', $user->user_id)->delete();
        Comment::where('user_id', $user->user_id)->delete();
        Like::where('user_id', $user->user_id)->delete();
        
        // Delete profile
        AlumniProfile::where('user_id', $user->user_id)->delete();
        
        // Delete user
        $user->delete();
        
        Auth::logout();
        
        return redirect('/')->with('success', 'Account deleted successfully.');
    }
}
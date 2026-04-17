<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    public function showChangeForm()
    {
        return view('profile.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()],
            'new_password_confirmation' => 'required'
        ], [
            'current_password.current_password' => 'The current password is incorrect.',
            'new_password.required' => 'Please enter a new password.',
            'new_password.confirmed' => 'The password confirmation does not match.',
            'new_password.min' => 'Password must be at least 8 characters.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Password changed successfully!');
    }
}
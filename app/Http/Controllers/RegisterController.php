<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AlumniProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'year_graduated' => ['required', 'integer', 'min:1950', 'max:' . date('Y')],
            'course' => ['required', 'string', 'max:255'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'alumni',
            'status' => 'pending',
            'year_graduated' => $request->year_graduated,
        ]);

        AlumniProfile::create([
            'user_id' => $user->user_id,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'course' => $request->course,
            'year_graduated' => $request->year_graduated,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please wait for admin approval.');
    }
}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center">
            <div class="flex justify-center">
                <div class="w-16 h-16 bg-[#2c3e66] rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-graduation-cap text-white text-3xl"></i>
                </div>
            </div>
            <h2 class="mt-6 font-display font-bold text-3xl text-[#1a2a4a]">
                Welcome Back!
            </h2>
            <p class="mt-2 text-[#4a5568]">
                Sign in to your alumni account
            </p>
        </div>
        
        <!-- Login Form -->
        <div class="glass-card rounded-2xl p-8 shadow-xl">
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-[#2c3e66] mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email Address
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="glass-input w-full px-4 py-3 rounded-xl @error('email') border-red-500 ring-red-500 @enderror"
                           placeholder="you@example.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-[#2c3e66] mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <input type="password" id="password" name="password" required
                           class="glass-input w-full px-4 py-3 rounded-xl @error('password') border-red-500 ring-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#2c3e66] focus:ring-[#2c3e66]">
                        <span class="ml-2 text-sm text-[#4a5568]">Remember me</span>
                    </label>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold text-white transition-all">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
                
                <!-- Register Link -->
                <div class="text-center pt-4">
                    <p class="text-sm text-[#4a5568]">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-semibold text-[#2c3e66] hover:text-[#1e2a4a] transition-colors">
                            Register here
                        </a>
                    </p>
                </div>
            </form>
        </div>
        
        <!-- School Info -->
        <div class="text-center">
            <p class="text-xs text-[#4a5568]">
                <i class="fas fa-shield-alt mr-1"></i> Secure login for verified alumni only
            </p>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center">
            <div class="flex justify-center">
                <div class="w-16 h-16 bg-[#2c3e66] rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-plus text-white text-3xl"></i>
                </div>
            </div>
            <h2 class="mt-6 font-display font-bold text-3xl text-[#1a2a4a]">
                Create Account
            </h2>
            <p class="mt-2 text-[#4a5568]">
                Join our alumni community
            </p>
        </div>
        
        <!-- Registration Form -->
        <div class="glass-card rounded-2xl p-8 shadow-xl">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                
                <!-- Name Fields Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-[#2c3e66] mb-2">
                            <i class="fas fa-user mr-2"></i>First Name
                        </label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required
                               class="glass-input w-full px-4 py-3 rounded-xl @error('first_name') border-red-500 ring-red-500 @enderror"
                               placeholder="Enter your first name">
                        @error('first_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-[#2c3e66] mb-2">
                            <i class="fas fa-user mr-2"></i>Last Name
                        </label>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required
                               class="glass-input w-full px-4 py-3 rounded-xl @error('last_name') border-red-500 ring-red-500 @enderror"
                               placeholder="Enter your last name">
                        @error('last_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Birthday -->
                <div class="mb-3">
                    <label for="birthday" class="form-label">
                        <i class="fas fa-birthday-cake me-2"></i>Birthday (Optional)
                    </label>
                    <input type="date" class="form-control glass-input" 
                        id="birthday" name="birthday" value="{{ old('birthday') }}">
                </div>
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-[#2c3e66] mb-2">
                        <i class="fas fa-envelope mr-2"></i>Email Address
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="glass-input w-full px-4 py-3 rounded-xl @error('email') border-red-500 ring-red-500 @enderror"
                           placeholder="you@example.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password Fields Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-[#2c3e66] mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <input type="password" id="password" name="password" required
                               class="glass-input w-full px-4 py-3 rounded-xl @error('password') border-red-500 ring-red-500 @enderror"
                               placeholder="Create a password">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-[#2c3e66] mb-2">
                            <i class="fas fa-check-circle mr-2"></i>Confirm Password
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="glass-input w-full px-4 py-3 rounded-xl"
                               placeholder="Confirm your password">
                    </div>
                </div>
                
                <!-- Password Requirements Hint -->
                <div class="text-xs text-[#4a5568] space-y-1 bg-gray-50 p-3 rounded-lg">
                    <p class="font-semibold mb-1">Password requirements:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        <li>At least 8 characters long</li>
                        <li>Contains uppercase and lowercase letters</li>
                        <li>Contains at least one number</li>
                        <li>Contains at least one special character</li>
                    </ul>
                </div>
                
                <!-- Course and Year Fields Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Course -->
                    <div>
                        <label for="course" class="block text-sm font-medium text-[#2c3e66] mb-2">
                            <i class="fas fa-book mr-2"></i>Course
                        </label>
                        <input type="text" id="course" name="course" value="{{ old('course') }}" required
                               class="glass-input w-full px-4 py-3 rounded-xl @error('course') border-red-500 ring-red-500 @enderror"
                               placeholder="e.g., Computer Science">
                        @error('course')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Year Graduated -->
                    <div>
                        <label for="year_graduated" class="block text-sm font-medium text-[#2c3e66] mb-2">
                            <i class="fas fa-calendar-alt mr-2"></i>Year Graduated
                        </label>
                        <select id="year_graduated" name="year_graduated" required
                                class="glass-input w-full px-4 py-3 rounded-xl @error('year_graduated') border-red-500 ring-red-500 @enderror">
                            <option value="">Select Year</option>
                            @for($year = date('Y'); $year >= date('Y')-50; $year--)
                                <option value="{{ $year }}" {{ old('year_graduated') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                        @error('year_graduated')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn-primary w-full py-3 rounded-xl font-semibold text-white transition-all mt-6">
                    <i class="fas fa-user-plus mr-2"></i>Create Account
                </button>
                
                <!-- Login Link -->
                <div class="text-center pt-4">
                    <p class="text-sm text-[#4a5568]">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-semibold text-[#2c3e66] hover:text-[#1e2a4a] transition-colors">
                            Sign in here
                        </a>
                    </p>
                </div>
            </form>
        </div>
        
        <!-- Info Note -->
        <div class="text-center">
            <p class="text-xs text-[#4a5568]">
                <i class="fas fa-shield-alt mr-1"></i> Your information is secure and will only be used for alumni verification
            </p>
        </div>
    </div>
</div>
@endsection
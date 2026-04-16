<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Alumni Management System') }} - Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        
        .input-field {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(203, 213, 225, 0.5);
        }
        
        .input-field:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background: white;
        }
        
        .btn-register {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.4);
        }
        
        .link-hover {
            transition: all 0.2s ease;
        }
        
        .link-hover:hover {
            color: #4f46e5;
        }
    </style>
</head>
<body class="antialiased">
    
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">
            
            {{-- Logo and Brand --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm mb-4">
                    <img src="{{ asset('images/unilogo.jpg') }}" alt="Logo" class="w-14 h-14 object-cover rounded-full">
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Join the Alumni Family</h2>
                <p class="text-indigo-100">Create your alumni account</p>
            </div>
            
            {{-- Registration Form --}}
            <div class="glass-card rounded-2xl p-8">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    {{-- First Name --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                        <input type="text" 
                               name="first_name" 
                               value="{{ old('first_name') }}" 
                               required 
                               class="input-field w-full px-4 py-3 rounded-xl focus:outline-none @error('first_name') border-red-500 @enderror"
                               placeholder="Enter your first name">
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Last Name --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                        <input type="text" 
                               name="last_name" 
                               value="{{ old('last_name') }}" 
                               required 
                               class="input-field w-full px-4 py-3 rounded-xl focus:outline-none @error('last_name') border-red-500 @enderror"
                               placeholder="Enter your last name">
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               class="input-field w-full px-4 py-3 rounded-xl focus:outline-none @error('email') border-red-500 @enderror"
                               placeholder="your@email.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Course --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Course</label>
                        <input type="text" 
                               name="course" 
                               value="{{ old('course') }}" 
                               required 
                               class="input-field w-full px-4 py-3 rounded-xl focus:outline-none @error('course') border-red-500 @enderror"
                               placeholder="e.g., Information Technology">
                        @error('course')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Year Graduated --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Year Graduated</label>
                        <select name="year_graduated" 
                                required 
                                class="input-field w-full px-4 py-3 rounded-xl focus:outline-none @error('year_graduated') border-red-500 @enderror">
                            <option value="">Select year</option>
                            @for($year = date('Y'); $year >= 1970; $year--)
                                <option value="{{ $year }}" {{ old('year_graduated') == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endfor
                        </select>
                        @error('year_graduated')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Password --}}
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" 
                               name="password" 
                               required 
                               class="input-field w-full px-4 py-3 rounded-xl focus:outline-none @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Confirm Password --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" 
                               name="password_confirmation" 
                               required 
                               class="input-field w-full px-4 py-3 rounded-xl focus:outline-none"
                               placeholder="••••••••">
                    </div>
                    
                    {{-- Submit Button --}}
                    <button type="submit" class="btn-register w-full py-3 rounded-xl text-white font-semibold text-lg">
                        Create Account
                    </button>
                    
                    {{-- Login Link --}}
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 link-hover">
                                Sign In
                            </a>
                        </p>
                    </div>
                </form>
            </div>
            
            {{-- Footer Note --}}
            <div class="text-center mt-8">
                <p class="text-xs text-indigo-200">
                    Join the Brokenshire College Alumni Community
                </p>
            </div>
        </div>
    </div>
    
</body>
</html>
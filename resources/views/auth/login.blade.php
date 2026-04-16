<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Alumni Management System') }} - Login</title>

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
        
        .btn-login {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
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
                <h2 class="text-3xl font-bold text-white mb-2">Welcome Back</h2>
                <p class="text-indigo-100">Sign in to your alumni account</p>
            </div>
            
            {{-- Login Form --}}
            <div class="glass-card rounded-2xl p-8">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    {{-- Email Field --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               class="input-field w-full px-4 py-3 rounded-xl focus:outline-none @error('email') border-red-500 @enderror"
                               placeholder="your@email.com">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Password Field --}}
                    <div class="mb-6">
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
                    
                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between mb-8">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-700 link-hover">
                            Forgot password?
                        </a>
                    </div>
                    
                    {{-- Submit Button --}}
                    <button type="submit" class="btn-login w-full py-3 rounded-xl text-white font-semibold text-lg">
                        Sign In
                    </button>
                    
                    {{-- Register Link --}}
                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-indigo-600 font-semibold hover:text-indigo-700 link-hover">
                                Create Account
                            </a>
                        </p>
                    </div>
                </form>
            </div>
            
            {{-- Footer Note --}}
            <div class="text-center mt-8">
                <p class="text-xs text-indigo-200">
                    Brokenshire College Alumni Association
                </p>
            </div>
        </div>
    </div>
    
</body>
</html>
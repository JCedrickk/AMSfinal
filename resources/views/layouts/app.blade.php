<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Alumni Management System')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Poppins', 'Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                    },
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        }
        
        .glass-input {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 0.75rem;
            padding: 0.625rem 1rem;
            transition: all 0.2s ease;
        }
        
        .glass-input:focus {
            background: white;
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            padding: 0.625rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.8);
            color: #1a1a2e;
            font-weight: 600;
            padding: 0.625rem 1.5rem;
            border-radius: 0.75rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            font-weight: 600;
            padding: 0.625rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }
        
        .btn-danger:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    @auth
    <nav class="glass sticky top-0 z-50 border-b border-white/30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <span class="font-bold text-slate-800 text-lg hidden sm:block">Alumni Management System</span>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('feed') }}" class="text-slate-600 hover:text-indigo-600 transition-colors {{ request()->routeIs('feed') ? 'text-indigo-600 font-semibold' : '' }}">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="{{ route('directory') }}" class="text-slate-600 hover:text-indigo-600 transition-colors {{ request()->routeIs('directory') ? 'text-indigo-600 font-semibold' : '' }}">
                        <i class="fas fa-address-book mr-2"></i>Directory
                    </a>
                    <a href="{{ route('alumni-id.request') }}" class="text-slate-600 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-id-card mr-2"></i>Alumni ID
                    </a>
                    <a href="{{ route('notifications.index') }}" class="relative text-slate-600 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-bell text-xl"></i>
                        @php $unreadCount = Auth::user()->notifications->where('is_read', false)->count(); @endphp
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-2 w-4 h-4 bg-red-500 rounded-full text-[10px] text-white flex items-center justify-center">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </div>
                
                <!-- User Menu -->
                <div class="flex items-center space-x-4" x-data="{ open: false }">
                    <div class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 glass px-3 py-2 rounded-xl hover:bg-white/40 transition-all">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center shadow-sm">
                                <span class="text-white text-sm font-semibold">{{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}</span>
                            </div>
                            <span class="text-slate-700 font-medium hidden sm:inline">{{ Auth::user()->first_name }}</span>
                            <i class="fas fa-chevron-down text-slate-500 text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 glass rounded-xl shadow-xl z-50 overflow-hidden">
                            <div class="py-2">
                                <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-slate-700 hover:bg-white/40 transition-colors">
                                    <i class="fas fa-user-circle w-5 mr-3"></i>My Profile
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-slate-700 hover:bg-white/40 transition-colors">
                                    <i class="fas fa-edit w-5 mr-3"></i>Edit Profile
                                </a>
                                <a href="{{ route('change-password') }}" class="flex items-center px-4 py-2 text-slate-700 hover:bg-white/40 transition-colors">
                                    <i class="fas fa-key w-5 mr-3"></i>Change Password
                                </a>
                                @if(Auth::user()->isAdmin())
                                <div class="border-t border-slate-200 my-1"></div>
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-indigo-600 hover:bg-white/40 transition-colors">
                                    <i class="fas fa-tachometer-alt w-5 mr-3"></i>Admin Dashboard
                                </a>
                                @endif
                                <div class="border-t border-slate-200 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-red-600 hover:bg-white/40 transition-colors">
                                        <i class="fas fa-sign-out-alt w-5 mr-3"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endauth
    
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-100/80 backdrop-blur-sm border border-emerald-200 rounded-xl text-emerald-700 animate-fade-in">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100/80 backdrop-blur-sm border border-red-200 rounded-xl text-red-700 animate-fade-in">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-4 p-4 bg-amber-100/80 backdrop-blur-sm border border-amber-200 rounded-xl text-amber-700 animate-fade-in">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="glass mt-16 py-6 border-t border-white/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="items-center text-sm">
                <p>&copy; {{ date('Y') }} Alumni Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>
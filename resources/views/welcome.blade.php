<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Alumni Management System | Welcome</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                        'display': ['Poppins', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                        'fade-in-up-delay': 'fadeInUp 0.8s ease-out 0.2s both',
                        'float': 'float 4s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                    },
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #e8eef4 0%, #d1d9e6 100%);
            min-height: 100vh;
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }
        
        .glass-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.85);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
        }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.8);
        }
        
        .btn-primary {
            background: #2c3e66;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #1e2a4a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 62, 102, 0.2);
        }
        
        .btn-outline {
            background: transparent;
            border: 1.5px solid #2c3e66;
            color: #2c3e66;
            transition: all 0.3s ease;
        }
        
        .btn-outline:hover {
            background: #2c3e66;
            color: white;
            transform: translateY(-2px);
        }
        
        .feature-icon {
            background: linear-gradient(135deg, #2c3e66 0%, #4a627a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e66;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #e8eef4;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #2c3e66;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #1e2a4a;
        }
        
        /* Welcome Badge */
        .welcome-badge {
            background: rgba(44, 62, 102, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(44, 62, 102, 0.2);
            border-radius: 100px;
            padding: 0.5rem 1.25rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #2c3e66;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Navigation -->
    <nav class="glass-nav fixed top-0 left-0 right-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2 group">
                    <div class="w-10 h-10 bg-[#2c3e66] rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-all">
                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <span class="font-display font-bold text-[#2c3e66] text-lg hidden sm:block">Alumni Management System</span>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#home" class="text-[#4a5568] hover:text-[#2c3e66] font-medium transition-colors">Home</a>
                    <a href="#features" class="text-[#4a5568] hover:text-[#2c3e66] font-medium transition-colors">Features</a>
                    <a href="#about" class="text-[#4a5568] hover:text-[#2c3e66] font-medium transition-colors">About</a>
                    @auth
                        <a href="{{ route('feed') }}" class="btn-primary text-white px-5 py-2 rounded-xl font-semibold text-sm">
                            <i class="fas fa-home mr-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-[#2c3e66] font-semibold hover:opacity-80 transition">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary text-white px-5 py-2 rounded-xl font-semibold text-sm">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-[#2c3e66] focus:outline-none" id="mobile-menu-button">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden glass border-t border-white/30">
            <div class="px-4 py-3 space-y-2">
                <a href="#home" class="block py-2 text-[#4a5568] hover:text-[#2c3e66] font-medium">Home</a>
                <a href="#features" class="block py-2 text-[#4a5568] hover:text-[#2c3e66] font-medium">Features</a>
                <a href="#about" class="block py-2 text-[#4a5568] hover:text-[#2c3e66] font-medium">About</a>
                @auth
                    <a href="{{ route('feed') }}" class="block py-2 btn-primary text-white text-center rounded-xl font-semibold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block py-2 text-[#2c3e66] font-semibold">Login</a>
                    <a href="{{ route('register') }}" class="block py-2 btn-primary text-white text-center rounded-xl font-semibold">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <!-- Welcome Badge -->
                    <div class="welcome-badge animate-fade-in-up">
                        <i class="fas fa-hand-wave text-[#2c3e66]"></i>
                        <span>Welcome, Alumni.</span>
                    </div>
                    
                    <h1 class="font-display font-bold text-4xl lg:text-5xl text-[#1a2a4a] leading-tight animate-fade-in-up">
                        Stay Connected.<br>Go Beyond.
                    </h1>
                    <p class="text-[#4a5568] text-lg leading-relaxed animate-fade-in-up-delay">
                        A dynamic digital hub connecting generations of excellence. <br>
                        Share your journey, celebrate achievements, and stay connected with your alma mater and fellow graduates.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-4">
                        @auth
                            <a href="{{ route('feed') }}" class="btn-primary text-white px-6 py-3 rounded-xl font-semibold inline-flex items-center shadow-md hover:shadow-lg transition-all">
                                <i class="fas fa-home mr-2"></i>Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-primary text-white px-6 py-3 rounded-xl font-semibold inline-flex items-center shadow-md hover:shadow-lg transition-all">
                                <i class="fas fa-user-plus mr-2"></i>Get Started
                            </a>
                            <a href="{{ route('login') }}" class="btn-outline px-6 py-3 rounded-xl font-semibold inline-flex items-center transition-all">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                        @endauth
                    </div>
                </div>
                <div class="flex justify-center lg:justify-end">
                    <div class="animate-float">
                        <div class="w-64 h-64 lg:w-80 lg:h-80 bg-gradient-to-br from-[#2c3e66]/10 to-[#4a627a]/10 rounded-full flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-7xl lg:text-8xl text-[#2c3e66]/30"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-display font-bold text-3xl lg:text-4xl text-[#1a2a4a] mb-3">Powerful Features</h2>
                <p class="text-[#4a5568] text-lg">Everything you need to stay connected with your alumni community!</p>
            </div>
            
            <!-- First Row - 3 cards -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Feature 1 -->
                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-[#2c3e66]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-newspaper text-3xl text-[#2c3e66]"></i>
                    </div>
                    <h5 class="font-display font-semibold text-xl text-[#1a2a4a] mb-2">News Feed</h5>
                    <p class="text-[#4a5568]">Share updates, achievements, and memories with fellow alumni</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-[#2c3e66]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-address-book text-3xl text-[#2c3e66]"></i>
                    </div>
                    <h5 class="font-display font-semibold text-xl text-[#1a2a4a] mb-2">Alumni Directory</h5>
                    <p class="text-[#4a5568]">Search and connect with graduates from different batches and courses</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-[#2c3e66]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-id-card text-3xl text-[#2c3e66]"></i>
                    </div>
                    <h5 class="font-display font-semibold text-xl text-[#1a2a4a] mb-2">Alumni ID</h5>
                    <p class="text-[#4a5568]">Request your official Alumni ID card for exclusive benefits</p>
                </div>
            </div>
            
            <!-- Second Row - 2 cards centered -->
            <div class="flex justify-center">
                <div class="grid md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                    <!-- Feature 4 -->
                    <div class="glass-card rounded-2xl p-6 text-center">
                        <div class="w-16 h-16 bg-[#2c3e66]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bell text-3xl text-[#2c3e66]"></i>
                        </div>
                        <h5 class="font-display font-semibold text-xl text-[#1a2a4a] mb-2">Notifications</h5>
                        <p class="text-[#4a5568]">Stay updated with important announcements and updates</p>
                    </div>
                    
                    <!-- Feature 5 -->
                    <div class="glass-card rounded-2xl p-6 text-center">
                        <div class="w-16 h-16 bg-[#2c3e66]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-3xl text-[#2c3e66]"></i>
                        </div>
                        <h5 class="font-display font-semibold text-xl text-[#1a2a4a] mb-2">Networking</h5>
                        <p class="text-[#4a5568]">Build professional connections within the alumni community</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-2xl p-8 lg:p-12">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="font-display font-bold text-3xl lg:text-4xl text-[#1a2a4a] mb-4">About the System</h2>
                        <p class="text-[#4a5568] leading-relaxed mb-4">
                            The Alumni Management System is a comprehensive platform designed to 
                            bridge the gap between graduates and their alma mater. It provides a 
                            centralized space for alumni to connect, share experiences, and stay 
                            informed about university events and opportunities.
                        </p>
                        <p class="text-[#4a5568] leading-relaxed mb-6">
                            Built with modern technology and a user-friendly interface, this system 
                            ensures seamless communication and engagement within the alumni community.
                        </p>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-[#2c3e66] mr-3"></i>
                                <span class="text-[#4a5568]">Secure and Private</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-[#2c3e66] mr-3"></i>
                                <span class="text-[#4a5568]">Real-time Updates</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-[#2c3e66] mr-3"></i>
                                <span class="text-[#4a5568]">24/7 Accessibility</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <div class="w-64 h-64 bg-gradient-to-br from-[#2c3e66]/10 to-[#4a627a]/10 rounded-full flex items-center justify-center">
                            <i class="fas fa-school text-7xl text-[#2c3e66]/30"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-2xl p-12 text-center">
                <h3 class="font-display font-bold text-2xl lg:text-3xl text-[#1a2a4a] mb-3">Ready to Join the Community?</h3>
                <p class="text-[#4a5568] mb-6">Become a part of our growing alumni network and stay connected forever.</p>
                @auth
                    <a href="{{ route('feed') }}" class="btn-primary text-white px-8 py-3 rounded-xl font-semibold inline-flex items-center shadow-md hover:shadow-lg transition-all">
                        <i class="fas fa-home mr-2"></i>Go to Dashboard
                    </a>
                @else
                    <div class="flex flex-wrap gap-4 justify-center">
                        <a href="{{ route('register') }}" class="btn-primary text-white px-8 py-3 rounded-xl font-semibold inline-flex items-center shadow-md hover:shadow-lg transition-all">
                            <i class="fas fa-user-plus mr-2"></i>Register Now
                        </a>
                        <a href="{{ route('login') }}" class="btn-outline px-8 py-3 rounded-xl font-semibold inline-flex items-center transition-all">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#1a2a4a] text-white py-8 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-graduation-cap text-xl"></i>
                        <span class="font-display font-semibold">Alumni Management System</span>
                    </div>
                    <p class="text-sm text-white/70 mt-1">Connecting graduates worldwide</p>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-sm text-white/70">
                        &copy; 2026 Alumni Management System. All rights reserved.
                    </p>
                    <p class="text-sm text-white/70 mt-1">
                        Developed by Joshua Cedrick C. Palgan – IT9aL (8420)
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Close mobile menu when clicking a link
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    </script>
</body>
</html>
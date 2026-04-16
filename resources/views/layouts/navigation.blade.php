<nav class="nav-glass sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo --}}
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-full overflow-hidden bg-white shadow-sm flex-shrink-0">
                    <img src="{{ asset('images/unilogo.jpg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div class="leading-tight hidden sm:block">
                    <span class="text-sm font-bold text-gray-800">Brokenshire College</span>
                    <span class="text-xs text-gray-500 block">Alumni Association</span>
                </div>
            </div>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Home</a>
                <a href="{{ route('directory.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Directory</a>
                <a href="{{ route('posts.create') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Create Post</a>
            </div>

            {{-- User Menu --}}
            <div class="flex items-center space-x-4" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="hidden md:inline text-sm text-gray-700">{{ Auth::user()->first_name ?? Auth::user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" x-transition class="absolute right-4 top-16 w-48 glass-card rounded-xl shadow-lg py-2 z-50">
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition">My Profile</a>
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition">Settings</a>
                    <hr class="my-1 border-gray-200">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
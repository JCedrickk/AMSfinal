<nav class="glass-nav sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            {{-- Logo --}}
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-full overflow-hidden bg-white shadow-sm">
                    <img src="{{ asset('images/unilogo.jpg') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <div>
                    <span class="text-sm font-bold text-gray-800">Brokenshire College</span>
                    <span class="text-xs text-gray-500 block">Alumni Association</span>
                </div>
            </div>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Home</a>
                <a href="{{ route('posts.index') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Posts</a>
                <a href="{{ route('search') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Search</a>
                
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-purple-600 hover:text-purple-700 transition">Admin Panel</a>
                @endif
            </div>

            {{-- User Menu --}}
            <div class="flex items-center space-x-4" x-data="{ open: false, notificationCount: 0 }">
                {{-- Notifications --}}
                <div class="relative">
                    <button @click="open = !open" class="relative focus:outline-none">
                        <svg class="w-6 h-6 text-gray-600 hover:text-indigo-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span x-show="notificationCount > 0" x-text="notificationCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"></span>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 glass-card rounded-xl shadow-lg z-50">
                        <div class="p-3 border-b border-gray-200">
                            <span class="font-semibold text-gray-700">Notifications</span>
                        </div>
                        <div id="notification-list" class="max-h-96 overflow-y-auto">
                            <div class="p-4 text-center text-gray-500">Loading...</div>
                        </div>
                    </div>
                </div>

                {{-- User Dropdown --}}
                <div class="relative" x-data="{ dropdownOpen: false }">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2 focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) }}
                        </div>
                        <span class="hidden md:inline text-sm text-gray-700">{{ Auth::user()->first_name }}</span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition class="absolute right-0 mt-2 w-48 glass-card rounded-xl shadow-lg py-2 z-50">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition">My Profile</a>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition">Settings</a>
                        <hr class="my-1 border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Sign Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Load notifications via AJAX
    function loadNotifications() {
        fetch('{{ route("notifications.latest") }}')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('notification-list');
                if (data.notifications && data.notifications.length > 0) {
                    container.innerHTML = data.notifications.map(n => `
                        <div class="p-3 border-b border-gray-100 hover:bg-indigo-50 transition">
                            <p class="text-sm text-gray-700">${n.message}</p>
                            <p class="text-xs text-gray-400 mt-1">${new Date(n.created_at).toLocaleDateString()}</p>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = '<div class="p-4 text-center text-gray-500">No new notifications</div>';
                }
            });
        
        // Update unread count
        fetch('{{ route("notifications.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                const alpineData = document.querySelector('[x-data]').__x.$data;
                alpineData.notificationCount = data.count;
            });
    }
    
    // Load notifications every 30 seconds
    setInterval(loadNotifications, 30000);
    loadNotifications();
</script>
<x-app-layout>
    <div class="py-8 px-4 max-w-7xl mx-auto">
        
        {{-- Welcome Header --}}
        <div class="glass-card rounded-2xl p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Welcome back, {{ Auth::user()->first_name ?? Auth::user()->name }}!</h1>
                    <p class="text-gray-500 mt-1">Stay connected with your alumni community</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('posts.create') }}" class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-700 text-white rounded-lg text-sm font-medium hover:shadow-lg transition">
                        + Create Post
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Left Sidebar - Profile Summary --}}
            <div class="lg:col-span-1">
                <div class="glass-card rounded-2xl p-6 sticky top-24">
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto rounded-full overflow-hidden bg-gradient-to-br from-indigo-500 to-purple-600 mb-4">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white text-3xl font-bold">
                                    {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ Auth::user()->role ?? 'Alumni' }}</p>
                        @if(Auth::user()->year_graduated)
                            <p class="text-xs text-gray-400 mt-1">Batch {{ Auth::user()->year_graduated }}</p>
                        @endif
                    </div>

                    <div class="mt-6 space-y-2">
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-indigo-50 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('directory.index') }}" class="flex items-center gap-3 px-4 py-2 text-gray-700 hover:bg-indigo-50 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>Alumni Directory</span>
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Posts</span>
                            <span class="font-semibold text-gray-800">{{ Auth::user()->posts->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm mt-2">
                            <span class="text-gray-500">Likes Received</span>
                            <span class="font-semibold text-gray-800">{{ Auth::user()->posts->sum(function($post) { return $post->likes->count(); }) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Feed --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Feed Tabs --}}
                <div class="glass-card rounded-xl p-1 flex gap-1">
                    <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600'" class="flex-1 py-2 text-sm font-medium rounded-lg transition">
                        All Posts
                    </button>
                    <button @click="activeTab = 'announcements'" :class="activeTab === 'announcements' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600'" class="flex-1 py-2 text-sm font-medium rounded-lg transition">
                        Announcements
                    </button>
                    <button @click="activeTab = 'events'" :class="activeTab === 'events' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600'" class="flex-1 py-2 text-sm font-medium rounded-lg transition">
                        Events
                    </button>
                    <button @click="activeTab = 'jobs'" :class="activeTab === 'jobs' ? 'bg-indigo-600 text-white' : 'text-gray-600 hover:text-indigo-600'" class="flex-1 py-2 text-sm font-medium rounded-lg transition">
                        Jobs
                    </button>
                </div>

                {{-- Posts Feed --}}
                <div class="space-y-4">
                    @forelse($posts as $post)
                        @php
                            $postType = $post->type ?? 'announcement';
                        @endphp
                        <div class="glass-card rounded-xl p-6 hover:shadow-lg transition">
                            {{-- Post Header --}}
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($post->user->first_name ?? $post->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $post->user->first_name }} {{ $post->user->last_name }}</p>
                                        <div class="flex items-center gap-2 text-xs text-gray-400">
                                            <span>{{ $post->created_at->diffForHumans() }}</span>
                                            <span>•</span>
                                            <span class="capitalize">{{ $post->type }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($post->is_pinned)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">
                                        📌 Pinned
                                    </span>
                                @endif
                            </div>

                            {{-- Post Content --}}
                            <div class="mb-4">
                                <p class="text-gray-700 leading-relaxed">{{ $post->content }}</p>
                                @if($post->image_path)
                                    <img src="{{ asset('storage/' . $post->image_path) }}" class="mt-3 rounded-xl max-h-96 w-full object-cover">
                                @endif
                                @if($post->location)
                                    <p class="text-sm text-gray-500 mt-2 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $post->location }}
                                    </p>
                                @endif
                                @if($post->event_date)
                                    <p class="text-sm text-emerald-600 mt-2 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($post->event_date)->format('F j, Y, g:i A') }}
                                    </p>
                                @endif
                            </div>

                            {{-- Post Actions --}}
                            <div class="flex items-center gap-6 pt-4 border-t border-gray-100">
                                <button class="flex items-center gap-2 text-gray-500 hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span>{{ $post->likes->count() }}</span>
                                </button>
                                <button class="flex items-center gap-2 text-gray-500 hover:text-indigo-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    <span>{{ $post->comments->count() }}</span>
                                </button>
                                <a href="{{ route('posts.show', $post->post_id) }}" class="flex items-center gap-2 text-gray-500 hover:text-indigo-600 transition ml-auto">
                                    <span>View Details</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="glass-card rounded-xl p-12 text-center">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p class="text-gray-500">No posts yet</p>
                            <a href="{{ route('posts.create') }}" class="inline-block mt-4 text-indigo-600 hover:text-indigo-700">
                                Create your first post →
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('dashboard', () => ({
                activeTab: 'all'
            }))
        })
    </script>
</x-app-layout>
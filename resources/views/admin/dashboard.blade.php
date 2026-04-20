@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-6">
        <!-- Stats Cards Row 1 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-4">
            <a href="{{ route('admin.users.pending') }}" class="glass-card rounded-2xl p-4 text-center hover:shadow-xl transition-all">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-clock text-xl text-[#2c3e66]"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#1a2a4a]">{{ $pendingUsers }}</h3>
                <p class="text-xs text-[#4a5568] mt-1">Pending Users</p>
            </a>
            
            <a href="{{ route('admin.posts.pending') }}" class="glass-card rounded-2xl p-4 text-center hover:shadow-xl transition-all">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-file-alt text-xl text-[#2c3e66]"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#1a2a4a]">{{ $pendingPosts }}</h3>
                <p class="text-xs text-[#4a5568] mt-1">Pending Posts</p>
            </a>
            
            <a href="{{ route('admin.posts.pending-edits') }}" class="glass-card rounded-2xl p-4 text-center hover:shadow-xl transition-all">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-edit text-xl text-[#2c3e66]"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#1a2a4a]">{{ $pendingEdits ?? 0 }}</h3>
                <p class="text-xs text-[#4a5568] mt-1">Pending Edits</p>
            </a>
            
            <a href="{{ route('admin.id-requests.pending') }}" class="glass-card rounded-2xl p-4 text-center hover:shadow-xl transition-all">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-id-card text-xl text-[#2c3e66]"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#1a2a4a]">{{ $pendingIdRequests }}</h3>
                <p class="text-xs text-[#4a5568] mt-1">ID Requests</p>
            </a>

            <a href="{{ route('admin.users.index') }}" class="glass-card rounded-2xl p-4 text-center hover:shadow-xl transition-all">
                <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-graduation-cap text-xl text-[#2c3e66]"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#1a2a4a]">{{ $totalAlumni }}</h3>
                <p class="text-xs text-[#4a5568] mt-1">Total Alumni</p>
            </a>

            <!-- Total Admins Card - CLICKABLE -->
            <a href="{{ route('admin.users.admins') }}" class="glass-card rounded-2xl p-4 text-center hover:shadow-xl transition-all">
                <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-shield text-xl text-[#2c3e66]"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#1a2a4a]">{{ $totalAdmins }}</h3>
                <p class="text-xs text-[#4a5568] mt-1">Total Admins</p>
            </a>

            <!-- Archived Users Card - CLICKABLE -->
            <a href="{{ route('admin.users.archived') }}" class="glass-card rounded-2xl p-4 text-center hover:shadow-xl transition-all">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-archive text-xl text-[#2c3e66]"></i>
                </div>
                <h3 class="text-2xl font-bold text-[#1a2a4a]">{{ $archivedUsersCount ?? 0 }}</h3>
                <p class="text-xs text-[#4a5568] mt-1">Archived Users</p>
            </a>
        </div>
        
        <!-- Alumni by Course Section -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-display font-semibold text-lg text-[#1a2a4a]">
                            Alumni by Course
                        </h5>
                        <p class="text-xs text-[#4a5568]">Distribution of alumni across different courses</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($courseStats as $courseStat)
                        <div class="bg-gray-50 rounded-xl p-4 hover:shadow-md transition-all">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-book text-[#2c3e66] text-sm"></i>
                                        <h6 class="font-semibold text-[#1a2a4a]">{{ $courseStat['course_name'] }}</h6>
                                    </div>
                                    <p class="text-xs text-[#4a5568] mt-1">Code: {{ $courseStat['course_code'] ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-[#2c3e66]">{{ $courseStat['total'] }}</p>
                                    <p class="text-xs text-[#4a5568]">Alumni</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-[#2c3e66] h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $totalAlumni > 0 ? ($courseStat['total'] / $totalAlumni) * 100 : 0 }}%"></div>
                                </div>
                                <p class="text-xs text-[#4a5568] mt-1">
                                    {{ $totalAlumni > 0 ? round(($courseStat['total'] / $totalAlumni) * 100, 1) : 0 }}% of total alumni
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-8">
                            <i class="fas fa-chart-simple text-4xl text-gray-300 mb-2"></i>
                            <p class="text-[#4a5568]">No course data available</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-layer-group text-[#2c3e66]"></i>
                            <span class="text-sm text-[#4a5568]">Total Courses: <strong class="text-[#1a2a4a]">{{ count($courseStats) }}</strong></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-chart-line text-[#2c3e66]"></i>
                            <span class="text-sm text-[#4a5568]">Most Popular: <strong class="text-[#1a2a4a]">
                                @if(!empty($courseStats))
                                    @php $mostPopular = collect($courseStats)->sortByDesc('total')->first(); @endphp
                                    {{ $mostPopular['course_name'] ?? 'N/A' }} ({{ $mostPopular['total'] ?? 0 }} alumni)
                                @else
                                    N/A
                                @endif
                            </strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Users Table with Show/Hide -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h5 class="font-display font-semibold text-lg text-[#1a2a4a]">
                    <i class="fas fa-user-plus mr-2"></i>Recent Registrations
                </h5>
                @if($recentUsers->count() > 2)
                <button onclick="toggleRecentUsers()" id="toggleUsersBtn" class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">
                    <i class="fas fa-chevron-down mr-1"></i> Show All ({{ $recentUsers->count() }})
                </button>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Year</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $visibleUsers = $recentUsers->take(2);
                            $hiddenUsers = $recentUsers->skip(2);
                        @endphp
                        
                        @foreach($visibleUsers as $user)
                        <tr class="hover:bg-gray-50 transition users-row visible-user">
                            <td class="px-6 py-4 text-sm text-[#1a2a4a] font-medium">{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->profile->course ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->profile->year_graduated ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @if($user->status == 'pending')
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">Pending</span>
                                @elseif($user->status == 'approved')
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">Approved</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-semibold">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->status == 'pending')
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition">Reject</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        
                        @foreach($hiddenUsers as $user)
                        <tr class="hover:bg-gray-50 transition hidden-user" style="display: none;">
                            <td class="px-6 py-4 text-sm text-[#1a2a4a] font-medium">{{ $user->first_name }} {{ $user->last_name }}</td>
                            <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->profile->course ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->profile->year_graduated ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @if($user->status == 'pending')
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">Pending</span>
                                @elseif($user->status == 'approved')
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">Approved</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-semibold">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->status == 'pending')
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition">Reject</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        
                        @if($recentUsers->count() == 0)
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#4a5568]">No recent users</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recent Posts Table with Show/Hide -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h5 class="font-display font-semibold text-lg text-[#1a2a4a]">
                    <i class="fas fa-newspaper mr-2"></i>Recent Posts
                </h5>
                @if($recentPosts->count() > 2)
                <button onclick="toggleRecentPosts()" id="togglePostsBtn" class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold hover:bg-gray-200 transition">
                    <i class="fas fa-chevron-down mr-1"></i> Show All ({{ $recentPosts->count() }})
                </button>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Content</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $visiblePosts = $recentPosts->take(2);
                            $hiddenPosts = $recentPosts->skip(2);
                        @endphp
                        
                        @foreach($visiblePosts as $post)
                        <tr class="hover:bg-gray-50 transition posts-row visible-post">
                            <td class="px-6 py-4 text-sm text-[#1a2a4a] font-medium">{{ $post->user->first_name }} {{ $post->user->last_name }}</td>
                            <td class="px-6 py-4 text-sm text-[#4a5568]">{{ Str::limit($post->content, 50) }}</td>
                            <td class="px-6 py-4">
                                @if($post->status == 'pending')
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">Pending</span>
                                @elseif($post->status == 'approved')
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">Approved</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-semibold">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $post->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4">
                                @if($post->status == 'pending')
                                    <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.posts.reject', $post) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition">Reject</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        
                        @foreach($hiddenPosts as $post)
                        <tr class="hover:bg-gray-50 transition hidden-post" style="display: none;">
                            <td class="px-6 py-4 text-sm text-[#1a2a4a] font-medium">{{ $post->user->first_name }} {{ $post->user->last_name }}</td>
                            <td class="px-6 py-4 text-sm text-[#4a5568]">{{ Str::limit($post->content, 50) }}</td>
                            <td class="px-6 py-4">
                                @if($post->status == 'pending')
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">Pending</span>
                                @elseif($post->status == 'approved')
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">Approved</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-semibold">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $post->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4">
                                @if($post->status == 'pending')
                                    <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.posts.reject', $post) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition">Reject</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        
                        @if($recentPosts->count() == 0)
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-[#4a5568]">No recent posts</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let usersExpanded = false;
    let postsExpanded = false;
    
    function toggleRecentUsers() {
        const hiddenUsers = document.querySelectorAll('.hidden-user');
        const toggleBtn = document.getElementById('toggleUsersBtn');
        
        if (!usersExpanded) {
            hiddenUsers.forEach(user => {
                user.style.display = 'table-row';
            });
            toggleBtn.innerHTML = '<i class="fas fa-chevron-up mr-1"></i> Show Less';
            usersExpanded = true;
        } else {
            hiddenUsers.forEach(user => {
                user.style.display = 'none';
            });
            toggleBtn.innerHTML = '<i class="fas fa-chevron-down mr-1"></i> Show All (' + hiddenUsers.length + ')';
            usersExpanded = false;
        }
    }
    
    function toggleRecentPosts() {
        const hiddenPosts = document.querySelectorAll('.hidden-post');
        const toggleBtn = document.getElementById('togglePostsBtn');
        
        if (!postsExpanded) {
            hiddenPosts.forEach(post => {
                post.style.display = 'table-row';
            });
            toggleBtn.innerHTML = '<i class="fas fa-chevron-up mr-1"></i> Show Less';
            postsExpanded = true;
        } else {
            hiddenPosts.forEach(post => {
                post.style.display = 'none';
            });
            toggleBtn.innerHTML = '<i class="fas fa-chevron-down mr-1"></i> Show All (' + hiddenPosts.length + ')';
            postsExpanded = false;
        }
    }
</script>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        transition: all 0.3s ease;
    }
    
    .glass-card:hover {
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
    }
    
    .font-display {
        font-family: 'Poppins', system-ui, sans-serif;
    }
</style>
@endsection
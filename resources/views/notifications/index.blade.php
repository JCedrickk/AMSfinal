@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="glass-card rounded-2xl overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                <i class="fas fa-bell mr-2"></i>Notifications
            </h4>
            @if($allNotifications->where('is_read', false)->count() > 0)
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-100 rounded-xl text-sm font-semibold text-[#4a5568] hover:bg-gray-200 transition">
                    <i class="fas fa-check-double mr-2"></i>Mark all as read
                </button>
            </form>
            @endif
        </div>
        
        <!-- Filter Tabs -->
        <div class="px-6 pt-4 border-b border-gray-200">
            <div class="flex flex-wrap gap-2">
                <button onclick="filterNotifications('all')" 
                        id="filter-all"
                        class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all bg-[#2c3e66] text-white">
                    <i class="fas fa-bell mr-1"></i> All
                    @if($allNotifications->where('is_read', false)->count() > 0)
                        <span class="ml-1 px-1.5 py-0.5 bg-red-500 text-white rounded-full text-xs">{{ $allNotifications->where('is_read', false)->count() }}</span>
                    @endif
                </button>
                <button onclick="filterNotifications('user')" 
                        id="filter-user"
                        class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all bg-gray-100 text-[#4a5568] hover:bg-gray-200">
                    <i class="fas fa-user-check mr-1"></i> Account
                    @if($userNotifications->where('is_read', false)->count() > 0)
                        <span class="ml-1 px-1.5 py-0.5 bg-red-500 text-white rounded-full text-xs">{{ $userNotifications->where('is_read', false)->count() }}</span>
                    @endif
                </button>
                <button onclick="filterNotifications('post')" 
                        id="filter-post"
                        class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all bg-gray-100 text-[#4a5568] hover:bg-gray-200">
                    <i class="fas fa-newspaper mr-1"></i> Posts
                    @if($postNotifications->where('is_read', false)->count() > 0)
                        <span class="ml-1 px-1.5 py-0.5 bg-red-500 text-white rounded-full text-xs">{{ $postNotifications->where('is_read', false)->count() }}</span>
                    @endif
                </button>
                <button onclick="filterNotifications('like')" 
                        id="filter-like"
                        class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all bg-gray-100 text-[#4a5568] hover:bg-gray-200">
                    <i class="fas fa-heart mr-1"></i> Likes
                    @if($likeNotifications->where('is_read', false)->count() > 0)
                        <span class="ml-1 px-1.5 py-0.5 bg-red-500 text-white rounded-full text-xs">{{ $likeNotifications->where('is_read', false)->count() }}</span>
                    @endif
                </button>
                <button onclick="filterNotifications('comment')" 
                        id="filter-comment"
                        class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all bg-gray-100 text-[#4a5568] hover:bg-gray-200">
                    <i class="fas fa-comment mr-1"></i> Comments
                    @if($commentNotifications->where('is_read', false)->count() > 0)
                        <span class="ml-1 px-1.5 py-0.5 bg-red-500 text-white rounded-full text-xs">{{ $commentNotifications->where('is_read', false)->count() }}</span>
                    @endif
                </button>
                <button onclick="filterNotifications('id_request')" 
                        id="filter-id_request"
                        class="filter-btn px-4 py-2 rounded-lg text-sm font-semibold transition-all bg-gray-100 text-[#4a5568] hover:bg-gray-200">
                    <i class="fas fa-id-card mr-1"></i> Alumni ID
                    @if($idRequestNotifications->where('is_read', false)->count() > 0)
                        <span class="ml-1 px-1.5 py-0.5 bg-red-500 text-white rounded-full text-xs">{{ $idRequestNotifications->where('is_read', false)->count() }}</span>
                    @endif
                </button>
            </div>
        </div>
        
        <!-- Notifications List -->
        <div class="p-6">
            <!-- All Notifications -->
            <div id="notifications-all" class="notification-group">
                @forelse($allNotifications as $notification)
                <div class="mb-3 p-4 rounded-xl transition-all notification-item {{ $notification->is_read ? 'bg-gray-50' : 'bg-indigo-50' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                @if($notification->type == 'like')
                                    <i class="fas fa-heart text-red-500 text-sm"></i>
                                @elseif($notification->type == 'comment')
                                    <i class="fas fa-comment text-blue-500 text-sm"></i>
                                @elseif($notification->type == 'post_approved' || $notification->type == 'post_edit_approved')
                                    <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                @elseif($notification->type == 'post_rejected' || $notification->type == 'post_edit_rejected')
                                    <i class="fas fa-times-circle text-red-500 text-sm"></i>
                                @elseif($notification->type == 'post_pending' || $notification->type == 'post_edit_pending')
                                    <i class="fas fa-clock text-amber-500 text-sm"></i>
                                @elseif($notification->type == 'account_approved')
                                    <i class="fas fa-user-check text-green-500 text-sm"></i>
                                @elseif($notification->type == 'account_rejected')
                                    <i class="fas fa-user-times text-red-500 text-sm"></i>
                                @elseif($notification->type == 'user_registration')
                                    <i class="fas fa-user-plus text-indigo-500 text-sm"></i>
                                @elseif($notification->type == 'admin_privileges_removed')
                                    <i class="fas fa-user-shield text-orange-500 text-sm"></i>
                                @elseif($notification->type == 'id_request' || $notification->type == 'id_request_approved')
                                    <i class="fas fa-id-card text-indigo-500 text-sm"></i>
                                @else
                                    <i class="fas fa-bell text-[#2c3e66] text-sm"></i>
                                @endif
                                <p class="text-[#1a2a4a] text-sm font-medium">{{ $notification->message }}</p>
                            </div>
                            <small class="text-[#4a5568] text-xs">
                                <i class="far fa-clock mr-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @if(!$notification->is_read)
                        <form action="{{ route('notifications.read', ['notification' => $notification->id]) }}" method="POST" class="ml-3">
                            @csrf
                            <button type="submit" class="w-8 h-8 bg-white rounded-lg text-[#2c3e66] hover:bg-gray-100 transition flex items-center justify-center" title="Mark as read">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-3xl text-[#2c3e66]"></i>
                    </div>
                    <p class="text-[#1a2a4a] font-medium">No notifications yet</p>
                    <p class="text-sm text-[#4a5568] mt-1">When you receive notifications, they will appear here.</p>
                </div>
                @endforelse
            </div>
            
            <!-- User Account Notifications -->
            <div id="notifications-user" class="notification-group hidden">
                @forelse($userNotifications as $notification)
                <div class="mb-3 p-4 rounded-xl transition-all notification-item {{ $notification->is_read ? 'bg-gray-50' : 'bg-indigo-50' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                @if($notification->type == 'account_approved')
                                    <i class="fas fa-user-check text-green-500 text-sm"></i>
                                @elseif($notification->type == 'account_rejected')
                                    <i class="fas fa-user-times text-red-500 text-sm"></i>
                                @elseif($notification->type == 'user_registration')
                                    <i class="fas fa-user-plus text-indigo-500 text-sm"></i>
                                @elseif($notification->type == 'admin_privileges_removed')
                                    <i class="fas fa-user-shield text-orange-500 text-sm"></i>
                                @else
                                    <i class="fas fa-bell text-[#2c3e66] text-sm"></i>
                                @endif
                                <p class="text-[#1a2a4a] text-sm font-medium">{{ $notification->message }}</p>
                            </div>
                            <small class="text-[#4a5568] text-xs">
                                <i class="far fa-clock mr-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @if(!$notification->is_read)
                        <form action="{{ route('notifications.read', ['notification' => $notification->id]) }}" method="POST" class="ml-3">
                            @csrf
                            <button type="submit" class="w-8 h-8 bg-white rounded-lg text-[#2c3e66] hover:bg-gray-100 transition flex items-center justify-center" title="Mark as read">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-slash text-3xl text-[#2c3e66]"></i>
                    </div>
                    <p class="text-[#1a2a4a] font-medium">No account notifications</p>
                    <p class="text-sm text-[#4a5568] mt-1">Account approval notifications will appear here.</p>
                </div>
                @endforelse
            </div>
            
            <!-- Post Notifications -->
            <div id="notifications-post" class="notification-group hidden">
                @forelse($postNotifications as $notification)
                <div class="mb-3 p-4 rounded-xl transition-all notification-item {{ $notification->is_read ? 'bg-gray-50' : 'bg-indigo-50' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                @if($notification->type == 'admin_announcement')
                                    <i class="fas fa-bullhorn text-purple-500 text-sm"></i>
                                @elseif(str_contains($notification->type, 'approved'))
                                    <i class="fas fa-check-circle text-green-500 text-sm"></i>
                                @elseif(str_contains($notification->type, 'rejected'))
                                    <i class="fas fa-times-circle text-red-500 text-sm"></i>
                                @else
                                    <i class="fas fa-clock text-amber-500 text-sm"></i>
                                @endif
                                <p class="text-[#1a2a4a] text-sm font-medium">{{ $notification->message }}</p>
                            </div>
                            <small class="text-[#4a5568] text-xs">
                                <i class="far fa-clock mr-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @if(!$notification->is_read)
                        <form action="{{ route('notifications.read', ['notification' => $notification->id]) }}" method="POST" class="ml-3">
                            @csrf
                            <button type="submit" class="w-8 h-8 bg-white rounded-lg text-[#2c3e66] hover:bg-gray-100 transition flex items-center justify-center" title="Mark as read">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-newspaper text-3xl text-[#2c3e66]"></i>
                    </div>
                    <p class="text-[#1a2a4a] font-medium">No post notifications</p>
                    <p class="text-sm text-[#4a5568] mt-1">Post approval notifications and Admin announcements will appear here.</p>
                </div>
                @endforelse
            </div>
            
            <!-- Like Notifications -->
            <div id="notifications-like" class="notification-group hidden">
                @forelse($likeNotifications as $notification)
                <div class="mb-3 p-4 rounded-xl transition-all notification-item {{ $notification->is_read ? 'bg-gray-50' : 'bg-indigo-50' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-heart text-red-500 text-sm"></i>
                                <p class="text-[#1a2a4a] text-sm font-medium">{{ $notification->message }}</p>
                            </div>
                            <small class="text-[#4a5568] text-xs">
                                <i class="far fa-clock mr-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @if(!$notification->is_read)
                        <form action="{{ route('notifications.read', ['notification' => $notification->id]) }}" method="POST" class="ml-3">
                            @csrf
                            <button type="submit" class="w-8 h-8 bg-white rounded-lg text-[#2c3e66] hover:bg-gray-100 transition flex items-center justify-center" title="Mark as read">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart-broken text-3xl text-[#2c3e66]"></i>
                    </div>
                    <p class="text-[#1a2a4a] font-medium">No like notifications</p>
                    <p class="text-sm text-[#4a5568] mt-1">When someone likes your posts, notifications will appear here.</p>
                </div>
                @endforelse
            </div>
            
            <!-- Comment Notifications -->
            <div id="notifications-comment" class="notification-group hidden">
                @forelse($commentNotifications as $notification)
                <div class="mb-3 p-4 rounded-xl transition-all notification-item {{ $notification->is_read ? 'bg-gray-50' : 'bg-indigo-50' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-comment text-blue-500 text-sm"></i>
                                <p class="text-[#1a2a4a] text-sm font-medium">{{ $notification->message }}</p>
                            </div>
                            <small class="text-[#4a5568] text-xs">
                                <i class="far fa-clock mr-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @if(!$notification->is_read)
                        <form action="{{ route('notifications.read', ['notification' => $notification->id]) }}" method="POST" class="ml-3">
                            @csrf
                            <button type="submit" class="w-8 h-8 bg-white rounded-lg text-[#2c3e66] hover:bg-gray-100 transition flex items-center justify-center" title="Mark as read">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comment-slash text-3xl text-[#2c3e66]"></i>
                    </div>
                    <p class="text-[#1a2a4a] font-medium">No comment notifications</p>
                    <p class="text-sm text-[#4a5568] mt-1">When someone comments on your posts, notifications will appear here.</p>
                </div>
                @endforelse
            </div>
            
            <!-- Alumni ID Notifications -->
            <div id="notifications-id_request" class="notification-group hidden">
                @forelse($idRequestNotifications as $notification)
                <div class="mb-3 p-4 rounded-xl transition-all notification-item {{ $notification->is_read ? 'bg-gray-50' : 'bg-indigo-50' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-id-card text-indigo-500 text-sm"></i>
                                <p class="text-[#1a2a4a] text-sm font-medium">{{ $notification->message }}</p>
                            </div>
                            <small class="text-[#4a5568] text-xs">
                                <i class="far fa-clock mr-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @if(!$notification->is_read)
                        <form action="{{ route('notifications.read', ['notification' => $notification->id]) }}" method="POST" class="ml-3">
                            @csrf
                            <button type="submit" class="w-8 h-8 bg-white rounded-lg text-[#2c3e66] hover:bg-gray-100 transition flex items-center justify-center" title="Mark as read">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-id-card text-3xl text-[#2c3e66]"></i>
                    </div>
                    <p class="text-[#1a2a4a] font-medium">No Alumni ID notifications</p>
                    <p class="text-sm text-[#4a5568] mt-1">ID request status updates will appear here.</p>
                </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($allNotifications->hasPages())
            <div class="mt-6">
                {{ $allNotifications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function filterNotifications(type) {
    // Hide all notification groups
    document.querySelectorAll('.notification-group').forEach(group => {
        group.classList.add('hidden');
    });
    
    // Show selected group
    document.getElementById(`notifications-${type}`).classList.remove('hidden');
    
    // Update button styles
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-[#2c3e66]', 'text-white');
        btn.classList.add('bg-gray-100', 'text-[#4a5568]');
    });
    
    // Highlight active button
    const activeBtn = document.getElementById(`filter-${type}`);
    activeBtn.classList.remove('bg-gray-100', 'text-[#4a5568]');
    activeBtn.classList.add('bg-[#2c3e66]', 'text-white');
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
    
    .notification-item {
        transition: all 0.2s ease;
    }
    
    .notification-item:hover {
        transform: translateX(4px);
    }
</style>
@endsection
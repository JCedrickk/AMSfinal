@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#2c3e66] rounded-xl flex items-center justify-center">
                        <i class="fas fa-newspaper text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                            Pending Posts
                        </h4>
                        <p class="text-sm text-[#4a5568] mt-0.5">Review and approve or reject new post submissions</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        <i class="fas fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @forelse($posts as $post)
            <div class="glass-card rounded-xl mb-4 overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="p-5">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4 pb-3 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            @if($post->user->profile && $post->user->profile->profile_picture)
                                <img src="{{ asset('storage/' . $post->user->profile->profile_picture) }}" 
                                     alt="Profile" 
                                     class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                            @endif
                            <div>
                                <strong class="text-[#1a2a4a] text-base">{{ $post->user->first_name }} {{ $post->user->last_name }}</strong>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <small class="text-xs text-[#4a5568]">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $post->created_at->format('F j, Y g:i A') }}
                                    </small>
                                    <span class="inline-flex items-center px-2 py-0.5 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">
                                        <i class="fas fa-clock mr-1 text-xs"></i> Pending Approval
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <!-- Approve Button -->
                            <form action="{{ route('admin.posts.approve', $post) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-1.5 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition-all flex items-center gap-1">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            
                            <!-- Reject Button - Opens Modal -->
                            <button type="button" class="px-4 py-1.5 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition-all flex items-center gap-1" 
                                    onclick="openRejectModal({{ $post->id }})">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    </div>
                    
                    <!-- Post Content -->
                    <div>
                        <p class="text-xs font-semibold text-[#4a5568] mb-2 flex items-center gap-1">
                            <i class="fas fa-align-left"></i> Post Content
                        </p>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <p class="text-sm text-[#1a2a4a] leading-relaxed whitespace-pre-line">{{ $post->content }}</p>
                        </div>
                    </div>
                    
                    <!-- Post Image -->
                    @if($post->image)
                    <div class="mt-3">
                        <p class="text-xs font-semibold text-[#4a5568] mb-2 flex items-center gap-1">
                            <i class="fas fa-image"></i> Attached Image
                        </p>
                        <img src="{{ asset('storage/' . $post->image) }}" 
                             alt="Post image" 
                             class="rounded-xl max-h-48 object-cover cursor-pointer"
                             onclick="window.open('{{ asset('storage/' . $post->image) }}', '_blank')">
                    </div>
                    @endif
                    
                    <!-- User Info -->
                    <div class="mt-4 pt-3 border-t border-gray-200">
                        <div class="flex flex-wrap gap-4 text-xs text-[#4a5568]">
                            <div class="flex items-center gap-1">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Course: {{ $post->user->profile->course ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-calendar"></i>
                                <span>Batch: {{ $post->user->profile->year_graduated ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-envelope"></i>
                                <span>{{ $post->user->email }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Hint -->
                    <div class="mt-3 pt-2">
                        <div class="flex items-center gap-2 text-xs text-[#4a5568]">
                            <i class="fas fa-info-circle"></i>
                            <span>Approved posts will be visible to all alumni. Rejected posts will be removed.</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reject Modal for each post -->
            <div id="rejectModal{{ $post->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeRejectModal({{ $post->id }})"></div>
                    
                    <div class="relative bg-white rounded-2xl max-w-md w-full mx-auto shadow-xl transform transition-all">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                                </div>
                                <h5 class="font-display font-bold text-lg text-[#1a2a4a]">Reject Post</h5>
                            </div>
                        </div>
                        <form action="{{ route('admin.posts.reject', $post) }}" method="POST" id="rejectForm{{ $post->id }}">
                            @csrf
                            <div class="p-6">
                                <p class="text-sm text-[#4a5568] mb-3">
                                    You are about to reject this post from:
                                </p>
                                <p class="font-semibold text-[#1a2a4a] text-base mb-4">
                                    {{ $post->user->first_name }} {{ $post->user->last_name }}
                                </p>
                                
                                <!-- REASON FIELD -->
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                                        Reason for Rejection <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="rejection_reason" class="glass-input w-full rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                                              rows="4" required placeholder="Please provide a reason why this post is being rejected..."></textarea>
                                    <p class="text-xs text-[#4a5568] mt-2">This reason will be shared with the user.</p>
                                </div>
                                
                                <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-3">
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                                        <p class="text-xs text-amber-700">
                                            The user will receive a notification with your rejection reason.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                                <button type="button" class="px-4 py-2 bg-gray-100 text-[#4a5568] rounded-lg font-semibold text-sm hover:bg-gray-200 transition" 
                                        onclick="closeRejectModal({{ $post->id }})">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition">Reject Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-3xl text-[#2c3e66]"></i>
                </div>
                <p class="text-[#1a2a4a] font-medium">No pending posts</p>
                <p class="text-sm text-[#4a5568] mt-1">All posts have been reviewed.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function openRejectModal(postId) {
        const modal = document.getElementById(`rejectModal${postId}`);
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeRejectModal(postId) {
        const modal = document.getElementById(`rejectModal${postId}`);
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('[id^="rejectModal"]').forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        }
    });
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
    
    .glass-input {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    
    .glass-input:focus {
        outline: none;
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .font-display {
        font-family: 'Poppins', system-ui, sans-serif;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #2c3e66;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #1e2a4a;
    }
</style>
@endsection
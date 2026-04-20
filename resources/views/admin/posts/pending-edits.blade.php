@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#2c3e66] rounded-xl flex items-center justify-center">
                        <i class="fas fa-edit text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                            Pending Posts Edits
                        </h4>
                        <p class="text-sm text-[#4a5568] mt-0.5">Review and approve or reject post edit requests</p>
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
                        <div class="flex items-center gap-2">
                            @if($post->user->profile && $post->user->profile->profile_picture)
                                <img src="{{ asset('storage/' . $post->user->profile->profile_picture) }}" 
                                     alt="Profile" 
                                     class="w-8 h-8 rounded-full object-cover">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center">
                                    <i class="fas fa-user text-white text-xs"></i>
                                </div>
                            @endif
                            <div>
                                <strong class="text-[#1a2a4a] text-sm">{{ $post->user->first_name }} {{ $post->user->last_name }}</strong>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <small class="text-xs text-[#4a5568]">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        {{ $post->created_at->format('F j, Y g:i A') }}
                                    </small>
                                    <span class="inline-flex items-center px-2 py-0.5 bg-purple-100 text-purple-700 rounded-lg text-xs font-semibold">
                                        <i class="fas fa-edit mr-1 text-xs"></i> Edit Request
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <!-- Approve Edit Button -->
                            <form action="{{ route('admin.posts.approve-edit', $post) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-1.5 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition-all flex items-center gap-1">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            
                            <!-- Reject Edit Button - Opens Modal -->
                            <button type="button" class="px-4 py-1.5 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition-all flex items-center gap-1" 
                                    onclick="openRejectEditModal({{ $post->id }})">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    </div>
                    
                    <!-- Content Comparison -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Current Content -->
                        <div>
                            <p class="text-xs font-semibold text-[#4a5568] mb-2 flex items-center gap-1">
                                <i class="fas fa-file-alt"></i> Current Content
                            </p>
                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                                <p class="text-sm text-[#1a2a4a] leading-relaxed">{{ $post->content }}</p>
                            </div>
                        </div>
                        
                        <!-- Pending Edit -->
                        <div>
                            <p class="text-xs font-semibold text-[#4a5568] mb-2 flex items-center gap-1">
                                <i class="fas fa-pen text-amber-600"></i> Pending Edit
                            </p>
                            <div class="bg-amber-50 rounded-xl p-3 border border-amber-200">
                                <p class="text-sm text-[#1a2a4a] leading-relaxed">{{ $post->edit_pending_content }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Hint -->
                    <div class="mt-4 pt-3 border-t border-gray-200">
                        <div class="flex items-center gap-2 text-xs text-[#4a5568]">
                            <i class="fas fa-info-circle"></i>
                            <span>Approve to replace current content with the pending edit. Reject to discard the changes.</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reject Edit Modal for each post -->
            <div id="rejectEditModal{{ $post->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeRejectEditModal({{ $post->id }})"></div>
                    
                    <div class="relative bg-white rounded-2xl max-w-md w-full mx-auto shadow-xl transform transition-all">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                                </div>
                                <h5 class="font-display font-bold text-lg text-[#1a2a4a]">Reject Edit Request</h5>
                            </div>
                        </div>
                        <form action="{{ route('admin.posts.reject-edit', $post) }}" method="POST">
                            @csrf
                            <div class="p-6">
                                <p class="text-sm text-[#4a5568] mb-3">
                                    You are about to reject the edit request from:
                                </p>
                                <p class="font-semibold text-[#1a2a4a] text-base mb-4">
                                    {{ $post->user->first_name }} {{ $post->user->last_name }}
                                </p>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                                        Reason for rejection <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="rejection_reason" class="glass-input w-full rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                                              rows="4" required placeholder="Please provide a reason why this edit is being rejected..."></textarea>
                                    <p class="text-xs text-[#4a5568] mt-2">This reason will be shared with the user.</p>
                                </div>
                                
                                <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-3">
                                    <div class="flex items-start gap-2">
                                        <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                                        <p class="text-xs text-amber-700">
                                            The user will receive a notification with your rejection reason. The original post will remain unchanged.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                                <button type="button" class="px-4 py-2 bg-gray-100 text-[#4a5568] rounded-lg font-semibold text-sm hover:bg-gray-200 transition" 
                                        onclick="closeRejectEditModal({{ $post->id }})">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition">Reject Edit</button>
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
                <p class="text-[#1a2a4a] font-medium">No pending edit requests</p>
                <p class="text-sm text-[#4a5568] mt-1">All edit requests have been processed.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function openRejectEditModal(postId) {
        const modal = document.getElementById(`rejectEditModal${postId}`);
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeRejectEditModal(postId) {
        const modal = document.getElementById(`rejectEditModal${postId}`);
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('[id^="rejectEditModal"]').forEach(modal => {
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
</style>
@endsection
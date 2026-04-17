@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Sidebar - User Information (Sticky) -->
        <div class="lg:col-span-4">
            <div class="sticky top-24 space-y-6">
                <!-- Profile Card -->
                <div class="glass-card rounded-2xl p-6">
                    <div class="text-center">
                        <!-- Profile Picture -->
                        <div class="mb-4">
                            @if(auth()->user()->profile && auth()->user()->profile->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile->profile_picture) }}" 
                                     alt="Profile Picture" 
                                     class="w-28 h-28 rounded-full object-cover border-3 border-[#2c3e66] mx-auto shadow-md">
                            @else
                                <div class="w-28 h-28 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center mx-auto shadow-md">
                                    <i class="fas fa-user-graduate text-4xl text-white"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- User Info -->
                        <h4 class="font-display font-bold text-xl text-[#1a2a4a] mb-1">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h4>
                        <p class="text-sm text-[#4a5568] mb-2">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            Alumni - Batch {{ auth()->user()->profile->year_graduated ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-[#4a5568] mb-4">
                            <i class="fas fa-book mr-1"></i>
                            {{ auth()->user()->profile->course ?? 'Course not set' }}
                        </p>
                        
                        <!-- Navigation Buttons -->
                        <div class="space-y-2">
                            <a href="{{ route('profile.show') }}" class="btn-primary block w-full text-center py-2.5 rounded-xl font-semibold">
                                <i class="fas fa-user-circle mr-2"></i>My Profile
                            </a>
                            <a href="{{ route('alumni-id.request') }}" class="btn-outline block w-full text-center py-2.5 rounded-xl font-semibold">
                                <i class="fas fa-id-card mr-2"></i>Alumni ID
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Card -->
                @if(auth()->user()->isAdmin())
                    <div class="text-center">
                        <a href="{{ route('admin.dashboard') }}" class="btn-primary block w-full text-center py-2.5 rounded-xl font-semibold">
                            <i class="fas fa-arrow-right mr-2"></i>Go to Admin Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Right Side - Feed -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Create Post Card -->
            <div class="glass-card rounded-2xl p-6">
                <h5 class="font-display font-semibold text-lg text-[#1a2a4a] mb-4">
                    <i class="fas fa-edit mr-2"></i>Create a Post
                </h5>
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <textarea class="glass-input w-full rounded-xl p-4 focus:ring-2 focus:ring-[#2c3e66] focus:border-transparent" 
                              name="content" rows="3" placeholder="Share something with the alumni community..." required></textarea>
                    
                    <!-- Image Upload -->
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-[#4a5568] mb-2">
                            <i class="fas fa-image mr-2"></i>Add Image (Optional)
                        </label>
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <input type="file" name="image" id="postImage" class="hidden" accept="image/*" onchange="previewImage(this)">
                                <button type="button" onclick="document.getElementById('postImage').click()" 
                                        class="px-4 py-2 bg-gray-100 text-[#4a5568] rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                                    <i class="fas fa-upload mr-1"></i> Choose Image
                                </button>
                            </div>
                            <div id="imagePreview" class="hidden">
                                <img id="preview" src="#" alt="Preview" class="w-20 h-20 object-cover rounded-lg">
                                <button type="button" onclick="removeImage()" class="text-red-500 text-xs mt-1">Remove</button>
                            </div>
                        </div>
                        <p class="text-xs text-[#4a5568] mt-1">Max 5MB. Supported formats: JPG, PNG, GIF</p>
                    </div>
                    
                    <div class="text-end mt-3">
                        <button type="submit" class="btn-primary px-6 py-2.5 rounded-xl font-semibold">
                            <i class="fas fa-paper-plane mr-2"></i>Post
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Alumni Feed Title -->
            <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                <i class="fas fa-newspaper mr-2"></i>Alumni Feed
            </h4>
            
            <!-- Feed Posts -->
            @forelse($posts as $post)
            @php
                $userLiked = $post->likes->contains('user_id', auth()->id());
            @endphp
            <div class="glass-card rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="p-6">
                    <!-- Post Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                @if($post->user->profile && $post->user->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $post->user->profile->profile_picture) }}" 
                                         alt="Profile" 
                                         class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center">
                                        <i class="fas fa-user text-white text-lg"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <!-- Clickable Author Name - Fixed with url() helper -->
                                <a href="{{ url('/profile/user/' . $post->user->id) }}" class="hover:underline">
                                    <h6 class="font-semibold text-[#1a2a4a] hover:text-[#2c3e66] transition-colors">
                                        {{ $post->user->first_name }} {{ $post->user->last_name }}
                                    </h6>
                                </a>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    <small class="text-xs text-[#4a5568]">
                                        <i class="fas fa-graduation-cap mr-1"></i>
                                        Batch {{ $post->user->profile->year_graduated ?? 'N/A' }}
                                    </small>
                                    <small class="text-xs text-[#4a5568]">
                                        <i class="fas fa-book mr-1"></i>
                                        {{ $post->user->profile->course ?? 'Course not set' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Timestamp -->
                        <div class="text-right">
                            <small class="text-xs text-[#4a5568]">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ $post->created_at->format('M d, Y') }}
                            </small>
                            <br>
                            <small class="text-xs text-[#4a5568]">
                                <i class="far fa-clock mr-1"></i>
                                {{ $post->created_at->format('g:i A') }}
                            </small>
                        </div>
                    </div>
                    
                    <!-- Post Content -->
                    <div class="mb-4">
                        <p class="text-[#1a2a4a] leading-relaxed">{{ $post->content }}</p>
                    </div>

                    <!-- Post Image -->
                    @if($post->image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $post->image) }}" 
                             alt="Post image" 
                             class="rounded-xl w-full max-h-96 object-cover cursor-pointer"
                             onclick="openImageModal('{{ asset('storage/' . $post->image) }}')">
                    </div>
                    @endif
                    
                    <!-- Interaction Buttons -->
                    <div class="flex flex-wrap gap-3 pt-3 border-t border-gray-200">
                        <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 px-3 py-1.5 bg-gray-100 rounded-lg text-sm text-[#4a5568] hover:bg-gray-200 transition">
                                <i class="fas fa-heart {{ $userLiked ? 'text-red-500' : 'text-gray-400' }}"></i>
                                <span>{{ $post->likes->count() }} Likes</span>
                            </button>
                        </form>
                        
                        <button class="flex items-center gap-1 px-3 py-1.5 bg-gray-100 rounded-lg text-sm text-[#4a5568] hover:bg-gray-200 transition" 
                                onclick="toggleComment({{ $post->id }})">
                            <i class="fas fa-comment"></i>
                            <span>{{ $post->comments->count() }} Comments</span>
                        </button>
                        
                        <!-- Edit and Delete buttons for post owner -->
                        @if($post->user_id == auth()->id())
                            <div class="flex-1 text-right">
                                <a href="{{ route('posts.edit', $post) }}" class="inline-flex px-3 py-1.5 bg-gray-100 rounded-lg text-sm text-[#4a5568] hover:bg-gray-200 transition mr-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="inline-flex px-3 py-1.5 bg-gray-100 rounded-lg text-sm text-red-600 hover:bg-red-50 transition" 
                                        onclick="confirmDelete({{ $post->id }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $post->id }}" action="{{ route('posts.destroy', $post) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>
                    
                    <!-- Comments Section -->
                    <div id="comments-{{ $post->id }}" style="display: none;" class="mt-4 pt-4 border-t border-gray-200">
                        <div class="space-y-3">
                            @foreach($post->comments as $comment)
                            <div class="flex gap-2">
                                <div class="flex-shrink-0">
                                    @if($comment->user->profile && $comment->user->profile->profile_picture)
                                        <img src="{{ asset('storage/' . $comment->user->profile->profile_picture) }}" 
                                             alt="Profile" 
                                             class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="bg-gray-50 rounded-lg p-2">
                                        <strong class="text-xs text-[#1a2a4a]">{{ $comment->user->first_name }} {{ $comment->user->last_name }}</strong>
                                        <p class="text-xs text-[#4a5568] mt-0.5">{{ $comment->comment }}</p>
                                    </div>
                                    <small class="text-xs text-[#4a5568] mt-1 block">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @endforeach
                            
                            <!-- Add Comment Form -->
                            <form action="{{ route('posts.comment', $post) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="text" name="comment" class="glass-input flex-1 rounded-xl px-3 py-2 text-sm" 
                                           placeholder="Write a comment..." required>
                                    <button type="submit" class="btn-primary px-4 py-2 rounded-xl text-sm font-semibold">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="glass-card rounded-2xl py-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-newspaper text-3xl text-[#2c3e66]"></i>
                </div>
                <p class="text-[#1a2a4a] font-medium">No posts yet.</p>
                <p class="text-sm text-[#4a5568] mt-1">Be the first to share something with the alumni community!</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm items-center justify-center" onclick="closeImageModal()">
    <img id="modalImage" src="" alt="Full size image" class="max-w-[90vw] max-h-[90vh] object-contain">
    <button class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300 transition" onclick="closeImageModal()">&times;</button>
</div>

@push('scripts')
<script>
    function toggleComment(postId) {
        const commentsDiv = document.getElementById(`comments-${postId}`);
        if (commentsDiv.style.display === 'none' || commentsDiv.style.display === '') {
            commentsDiv.style.display = 'block';
        } else {
            commentsDiv.style.display = 'none';
        }
    }

    function confirmDelete(postId) {
        if (confirm('Are you sure you want to delete this post? This action cannot be undone!')) {
            document.getElementById(`delete-form-${postId}`).submit();
        }
    }

    function previewImage(input) {
        const previewDiv = document.getElementById('imagePreview');
        const preview = document.getElementById('preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewDiv.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeImage() {
        const fileInput = document.getElementById('postImage');
        const previewDiv = document.getElementById('imagePreview');
        fileInput.value = '';
        previewDiv.classList.add('hidden');
    }
    
    function openImageModal(src) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        modalImg.src = src;
        document.body.style.overflow = 'hidden';
    }
    
    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
</script>
@endpush

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
        border-color: #2c3e66;
        box-shadow: 0 0 0 3px rgba(44, 62, 102, 0.1);
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
    
    .font-display {
        font-family: 'Poppins', system-ui, sans-serif;
    }
</style>
@endsection
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
                            @if($user->profile && $user->profile->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" 
                                     alt="Profile Picture" 
                                     class="w-28 h-28 rounded-full object-cover border-3 border-[#2c3e66] mx-auto shadow-md">
                            @else
                                <div class="w-28 h-28 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center mx-auto shadow-md">
                                    <i class="fas fa-user-graduate text-4xl text-white"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- User Info -->
                        <h4 class="font-display font-bold text-xl text-[#1a2a4a] mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                        <p class="text-sm text-[#4a5568] mb-2">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            Alumni - Batch {{ $user->profile->year_graduated ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-[#4a5568] mb-4">
                            <i class="fas fa-book mr-1"></i>
                            {{ $user->profile->course_name ?? 'Course not set' }}
                        </p>

                        <!-- Address - Only show if user has address AND allows it to be shown -->
                        @if($user->profile && $user->profile->address && $user->profile->show_address)
                        <div class="text-left bg-gray-50 rounded-xl p-3 mb-3">
                            <p class="text-[#4a5568] text-xs font-semibold mb-1">Address</p>
                            <p class="text-[#1a2a4a] text-sm">{{ $user->profile->address }}</p>
                        </div>
                        @endif
                        
                        <!-- Contact Number - Only show if user has contact AND allows it to be shown -->
                        @if($user->profile && $user->profile->contact_number && $user->profile->show_contact_number)
                        <div class="text-left bg-gray-50 rounded-xl p-3 mb-3">
                            <p class="text-[#4a5568] text-xs font-semibold mb-1">Contact Number</p>
                            <p class="text-[#1a2a4a] text-sm">{{ $user->profile->contact_number }}</p>
                        </div>
                        @endif

                        <!-- Birthday - Only show if user has birthday AND allows it to be shown -->
                        @if($user->profile && $user->profile->birthday && $user->profile->show_birthday)
                        <div class="text-left bg-gray-50 rounded-xl p-3 mb-3">
                            <p class="text-[#4a5568] text-xs font-semibold mb-1">Birthday</p>
                            <p class="text-[#1a2a4a] text-sm">{{ \Carbon\Carbon::parse($user->profile->birthday)->format('F j, Y') }}</p>
                        </div>
                        @endif
                        
                        <!-- Job Title - Always visible if provided -->
                        @if($user->profile && $user->profile->job_title)
                        <div class="text-left bg-gray-50 rounded-xl p-3 mb-3">
                            <p class="text-[#4a5568] text-xs font-semibold mb-1">Job Title</p>
                            <p class="text-[#1a2a4a] text-sm">{{ $user->profile->job_title }}</p>
                        </div>
                        @endif
                        
                        <!-- Email - Always visible -->
                        <div class="text-left bg-gray-50 rounded-xl p-3 mb-3">
                            <p class="text-[#4a5568] text-xs font-semibold mb-1">Email</p>
                            <p class="text-[#1a2a4a] text-sm">{{ $user->email }}</p>
                        </div>
                        
                        <!-- Member Since - Always visible -->
                        <div class="text-left bg-gray-50 rounded-xl p-3 mb-3">
                            <p class="text-[#4a5568] text-xs font-semibold mb-1">Member Since</p>
                            <p class="text-[#1a2a4a] text-sm">{{ $user->created_at->format('F j, Y') }}</p>
                        </div>
                        
                        <!-- Social Media Links -->
                        @if($user->profile && ($user->profile->facebook || $user->profile->twitter || $user->profile->instagram || $user->profile->linkedin || $user->profile->github))
                        <div class="text-left bg-gray-50 rounded-xl p-3 mb-4">
                            <p class="text-[#4a5568] text-xs font-semibold mb-2">Connect With Me</p>
                            <div class="flex flex-wrap gap-2">
                                @if($user->profile->facebook)
                                    <a href="{{ $user->profile->facebook_url }}" target="_blank" class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300 transition">
                                        <i class="fab fa-facebook-f text-[#2c3e66] text-sm"></i>
                                    </a>
                                @endif
                                @if($user->profile->twitter)
                                    <a href="{{ $user->profile->twitter_url }}" target="_blank" class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300 transition">
                                        <i class="fab fa-twitter text-[#2c3e66] text-sm"></i>
                                    </a>
                                @endif
                                @if($user->profile->instagram)
                                    <a href="{{ $user->profile->instagram_url }}" target="_blank" class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300 transition">
                                        <i class="fab fa-instagram text-[#2c3e66] text-sm"></i>
                                    </a>
                                @endif
                                @if($user->profile->linkedin)
                                    <a href="{{ $user->profile->linkedin_url }}" target="_blank" class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300 transition">
                                        <i class="fab fa-linkedin-in text-[#2c3e66] text-sm"></i>
                                    </a>
                                @endif
                                @if($user->profile->github)
                                    <a href="{{ $user->profile->github_url }}" target="_blank" class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300 transition">
                                        <i class="fab fa-github text-[#2c3e66] text-sm"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <!-- Back Button -->
                        <a href="{{ route('directory') }}" class="btn-outline block w-full text-center py-2.5 rounded-xl font-semibold">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Directory
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - User's Posts -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Title -->
            <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                <i class="fas fa-file-alt mr-2"></i>{{ $user->first_name }}'s Posts
            </h4>
            
            <!-- User's Posts Feed -->
            @forelse($user->posts as $post)
            <div class="glass-card rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="p-6">
                    <!-- Post Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0">
                                @if($user->profile && $user->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" 
                                         alt="Profile" 
                                         class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center">
                                        <i class="fas fa-user text-white text-lg"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h6 class="font-semibold text-[#1a2a4a]">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                <div class="flex flex-wrap gap-2 mt-1">
                                    <small class="text-xs text-[#4a5568]">
                                        <i class="fas fa-graduation-cap mr-1"></i>
                                        Batch {{ $user->profile->year_graduated ?? 'N/A' }}
                                    </small>
                                    <small class="text-xs text-[#4a5568]">
                                        <i class="fas fa-book mr-1"></i>
                                        {{ $user->profile->course_name ?? 'Course not set' }}
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
                    
                    <!-- Post Status Badge -->
                    <div class="mb-4">
                        @if($post->status === 'approved')
                            <span class="inline-flex items-center px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">
                                <i class="fas fa-check-circle mr-1 text-xs"></i> Approved
                            </span>
                        @elseif($post->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">
                                <i class="fas fa-clock mr-1 text-xs"></i> Pending Review
                            </span>
                        @elseif($post->status === 'rejected')
                            <span class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-semibold">
                                <i class="fas fa-times-circle mr-1 text-xs"></i> Rejected
                            </span>
                        @endif
                    </div>
                    
                    <!-- Interaction Buttons (View Only - Can Like and Comment) -->
                    <div class="flex flex-wrap gap-3 pt-3 border-t border-gray-200">
                        <form action="{{ route('posts.like', $post->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 px-3 py-1.5 bg-gray-100 rounded-lg text-sm text-[#4a5568] hover:bg-gray-200 transition">
                                <i class="fas fa-heart text-red-500"></i>
                                <span>{{ $post->likes->count() }} Likes</span>
                            </button>
                        </form>
                        
                        <button class="flex items-center gap-1 px-3 py-1.5 bg-gray-100 rounded-lg text-sm text-[#4a5568] hover:bg-gray-200 transition" 
                                onclick="toggleComments({{ $post->id }})">
                            <i class="fas fa-comment"></i>
                            <span>{{ $post->comments->count() }} Comments</span>
                        </button>
                    </div>
                    
                    <!-- Comments Section -->
                    <div class="mt-4 pt-4 border-t border-gray-200" id="comments-section-{{ $post->id }}" style="display: none;">
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
                            <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="mt-3">
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
                    <i class="fas fa-file-alt text-3xl text-[#2c3e66]"></i>
                </div>
                <p class="text-[#1a2a4a] font-medium">No posts yet.</p>
                <p class="text-sm text-[#4a5568] mt-1">When {{ $user->first_name }} creates posts, they will appear here.</p>
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
function toggleComments(postId) {
    const commentsSection = document.getElementById(`comments-section-${postId}`);
    if (commentsSection.style.display === 'none' || commentsSection.style.display === '') {
        commentsSection.style.display = 'block';
    } else {
        commentsSection.style.display = 'none';
    }
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
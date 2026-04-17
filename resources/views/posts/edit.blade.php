@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#2c3e66] rounded-xl flex items-center justify-center">
                    <i class="fas fa-edit text-white text-lg"></i>
                </div>
                <div>
                    <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                        Edit Post
                    </h4>
                    <p class="text-sm text-[#4a5568] mt-0.5">Update your post content below</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Post Info -->
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex-shrink-0">
                        @if(auth()->user()->profile && auth()->user()->profile->profile_picture)
                            <img src="{{ asset('storage/' . auth()->user()->profile->profile_picture) }}" 
                                 alt="Profile" 
                                 class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-[#1a2a4a] text-sm">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                        <p class="text-xs text-[#4a5568]">Posted on {{ $post->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($post->status === 'approved')
                        <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">
                            <i class="fas fa-check-circle mr-1 text-xs"></i> Approved
                        </span>
                    @elseif($post->status === 'pending')
                        <span class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">
                            <i class="fas fa-clock mr-1 text-xs"></i> Pending Review
                        </span>
                    @elseif($post->status === 'rejected')
                        <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-semibold">
                            <i class="fas fa-times-circle mr-1 text-xs"></i> Rejected
                        </span>
                    @endif
                    
                    @if($post->edit_status === 'pending')
                        <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-semibold">
                            <i class="fas fa-clock mr-1 text-xs"></i> Edit Pending Approval
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Edit Form -->
            <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="content" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                        <i class="fas fa-align-left mr-2"></i>Post Content
                    </label>
                    <textarea class="glass-input w-full rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#2c3e66] focus:border-transparent" 
                              name="content" id="content" rows="8" required>{{ $post->content }}</textarea>
                    <p class="text-xs text-[#4a5568] mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        @if(auth()->user()->isAdmin())
                            Your changes will be applied immediately.
                        @else
                            Your changes will be submitted for admin approval. The original post will remain visible until approved.
                        @endif
                    </p>
                </div>
                
                <!-- Character Counter -->
                <div class="flex justify-between items-center mb-4 text-xs text-[#4a5568]">
                    <span id="char-count">0</span>
                    <span>characters</span>
                </div>
                
                <!-- Current Image -->
                @if($post->image)
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-[#1a2a4a] mb-2">Current Image</label>
                    <div class="relative inline-block">
                        <img src="{{ asset('storage/' . $post->image) }}" 
                             alt="Current post image" 
                             class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                        <button type="button" onclick="removeCurrentImage()" 
                                class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs hover:bg-red-600 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <input type="hidden" name="remove_image" id="remove_image" value="0">
                    <p class="text-xs text-[#4a5568] mt-1">Click the X to remove the current image</p>
                </div>
                @endif
                
                <!-- New Image Upload -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                        <i class="fas fa-image mr-2"></i>Change Image (Optional)
                    </label>
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <input type="file" name="image" id="postImage" class="hidden" accept="image/*" onchange="previewNewImage(this)">
                            <button type="button" onclick="document.getElementById('postImage').click()" 
                                    class="px-4 py-2 bg-gray-100 text-[#4a5568] rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                                <i class="fas fa-upload mr-1"></i> Choose New Image
                            </button>
                        </div>
                        <div id="newImagePreview" class="hidden">
                            <img id="newPreview" src="#" alt="Preview" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                            <button type="button" onclick="removeNewImage()" class="text-red-500 text-xs mt-1">Remove</button>
                        </div>
                    </div>
                    <p class="text-xs text-[#4a5568] mt-1">Max 5MB. Supported formats: JPG, PNG, GIF</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="btn-primary flex-1 py-2.5 rounded-xl font-semibold text-center">
                        <i class="fas fa-save mr-2"></i>Update Post
                    </button>
                    <a href="{{ route('feed') }}" class="btn-outline flex-1 py-2.5 rounded-xl font-semibold text-center">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Info Card -->
    <div class="glass-card rounded-2xl mt-6 overflow-hidden">
        <div class="p-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                </div>
                <div>
                    <h6 class="font-semibold text-[#1a2a4a] text-sm mb-1">About Post Updates</h6>
                    <p class="text-xs text-[#4a5568] leading-relaxed">
                        @if(auth()->user()->isAdmin())
                            As an administrator, your edits will be applied immediately. Please ensure content follows community guidelines.
                        @else
                            Your edited post will be reviewed by an administrator before it becomes visible. 
                            You will receive a notification once your edit is approved or rejected.
                            The original post will remain visible during the review process.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Character counter
    const textarea = document.getElementById('content');
    const charCount = document.getElementById('char-count');
    
    function updateCharCount() {
        const count = textarea.value.length;
        charCount.textContent = count;
        
        if (count > 500) {
            charCount.classList.add('text-red-500');
            charCount.classList.remove('text-[#4a5568]');
        } else {
            charCount.classList.add('text-[#4a5568]');
            charCount.classList.remove('text-red-500');
        }
    }
    
    textarea.addEventListener('input', updateCharCount);
    updateCharCount();
    
    // Image preview functions
    function previewNewImage(input) {
        const previewDiv = document.getElementById('newImagePreview');
        const preview = document.getElementById('newPreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewDiv.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeNewImage() {
        const fileInput = document.getElementById('postImage');
        const previewDiv = document.getElementById('newImagePreview');
        fileInput.value = '';
        previewDiv.classList.add('hidden');
    }
    
    function removeCurrentImage() {
        if (confirm('Remove the current image from this post?')) {
            document.getElementById('remove_image').value = '1';
            const currentImageDiv = document.querySelector('.relative.inline-block');
            if (currentImageDiv) {
                currentImageDiv.style.display = 'none';
            }
        }
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
        resize: vertical;
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
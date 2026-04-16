@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('posts.index') }}" id="filterForm">
                        <div class="mb-3">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-select" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="most_liked" {{ request('sort') == 'most_liked' ? 'selected' : '' }}>Most Liked</option>
                                <option value="most_commented" {{ request('sort') == 'most_commented' ? 'selected' : '' }}>Most Commented</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Time Period</label>
                            <select name="period" class="form-select" onchange="this.form.submit()">
                                <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>All Time</option>
                                <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>This Month</option>
                                <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>This Year</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Search Posts</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Search posts..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-secondary w-100" onclick="resetFilters()">
                            <i class="fas fa-undo-alt"></i> Reset Filters
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Stats Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Community Stats</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Posts:</span>
                        <strong>{{ $totalPosts ?? $posts->total() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Comments:</span>
                        <strong>{{ $totalComments ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total Likes:</span>
                        <strong>{{ $totalLikes ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-md-6">
            <!-- Create Post Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <strong><i class="fas fa-pen-alt me-2"></i>Create New Post</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('posts.store') }}" id="createPostForm">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      name="content" rows="3" 
                                      placeholder="Share something with your fellow alumni..."
                                      id="postContent">{{ old('content') }}</textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted" id="charCount">0 / 5000 characters</small>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Will be reviewed by admin
                                </small>
                            </div>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="submitPost">
                                <i class="fas fa-paper-plane me-1"></i> Submit Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Posts Loop -->
            @forelse($posts as $post)
                <div class="card mb-4 shadow-sm post-card" data-post-id="{{ $post->id }}">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary text-white me-2" 
                                     style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $post->user->name }}</strong>
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $post->created_at->diffForHumans() }}
                                            @if($post->created_at != $post->updated_at)
                                                · <i class="fas fa-edit"></i> Edited
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            @if($post->user_id === auth()->id())
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-dark" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('posts.edit', $post) }}">
                                                <i class="fas fa-edit"></i> Edit Post
                                            </a>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-danger" type="button" 
                                                    onclick="confirmDelete({{ $post->id }}, '{{ addslashes($post->content) }}')">
                                                <i class="fas fa-trash"></i> Delete Post
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <p class="card-text post-content">{{ $post->content }}</p>
                        
                        <!-- Post Stats -->
                        <div class="d-flex justify-content-around mb-3 pt-2 border-top border-bottom py-2">
                            <div class="text-center">
                                <i class="fas fa-heart text-danger"></i>
                                <span class="ms-1 like-count-{{ $post->id }}">{{ $post->likes->count() }}</span>
                                <div><small class="text-muted">Likes</small></div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-comment text-primary"></i>
                                <span class="ms-1">{{ $post->comments->count() }}</span>
                                <div><small class="text-muted">Comments</small></div>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-share text-success"></i>
                                <span class="ms-1">0</span>
                                <div><small class="text-muted">Shares</small></div>
                            </div>
                        </div>
                        
                        <!-- Like Button -->
                        <div class="mb-3">
                            <form method="POST" action="{{ route('posts.like', $post) }}" class="d-inline like-form">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $post->likes->where('user_id', auth()->id())->count() > 0 ? 'btn-danger' : 'btn-outline-danger' }} like-btn">
                                    <i class="fas fa-heart"></i> 
                                    {{ $post->likes->where('user_id', auth()->id())->count() > 0 ? 'Liked' : 'Like' }}
                                </button>
                            </form>
                            
                            <button class="btn btn-sm btn-outline-primary ms-2" type="button" onclick="toggleComments({{ $post->id }})">
                                <i class="fas fa-comment"></i> Comments ({{ $post->comments->count() }})
                            </button>
                        </div>
                        
                        <!-- Comments Section (Collapsible) -->
                        <div class="comments-section" id="comments-{{ $post->id }}" style="display: none;">
                            <hr>
                            <div class="comments-list mb-3" id="comments-list-{{ $post->id }}">
                                @foreach($post->comments as $comment)
                                    <div class="comment-item mb-3" id="comment-{{ $comment->id }}">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong>{{ $comment->user->name }}</strong>
                                                <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            @if($comment->user_id === auth()->id())
                                                <button class="btn btn-sm btn-link text-danger" onclick="deleteComment({{ $comment->id }})">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @endif
                                        </div>
                                        <p class="mb-0 mt-1">{{ $comment->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($post->comments->isEmpty())
                                <p class="text-muted text-center small">No comments yet. Be the first to comment!</p>
                            @endif
                            
                            <form method="POST" action="{{ route('posts.comment', $post) }}" class="comment-form">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="comment" class="form-control form-control-sm" 
                                           placeholder="Write a comment..." required>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-paper-plane"></i> Post
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                        <h5>No posts found</h5>
                        <p class="text-muted">Be the first to share something with the community!</p>
                        <button class="btn btn-primary" onclick="document.getElementById('postContent').focus()">
                            <i class="fas fa-plus"></i> Create First Post
                        </button>
                    </div>
                </div>
            @endforelse
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $posts->appends(request()->query())->links() }}
            </div>
        </div>
        
        <!-- Trending Sidebar -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-fire me-2"></i>Trending Posts</h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @php
                        $trendingPosts = $posts->sortByDesc(function($post) {
                            return $post->likes->count() + $post->comments->count();
                        })->take(5);
                    @endphp
                    
                    @foreach($trendingPosts as $trending)
                        <div class="mb-3 pb-2 border-bottom">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">{{ $trending->user->name }}</small>
                                <small class="text-muted">
                                    <i class="fas fa-heart text-danger"></i> {{ $trending->likes->count() }}
                                </small>
                            </div>
                            <p class="small mb-0">{{ Str::limit($trending->content, 80) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Top Contributors</h6>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @php
                        $topUsers = \App\Models\User::withCount(['posts' => function($q) {
                            $q->where('status', 'approved');
                        }])->orderBy('posts_count', 'desc')->limit(5)->get();
                    @endphp
                    
                    @foreach($topUsers as $topUser)
                        <div class="d-flex align-items-center mb-2">
                            <div class="avatar-circle bg-info text-white me-2" 
                                 style="width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                {{ strtoupper(substr($topUser->name, 0, 1)) }}
                            </div>
                            <div>
                                <div><small>{{ $topUser->name }}</small></div>
                                <div><small class="text-muted">{{ $topUser->posts_count }} posts</small></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Post Modal -->
<div class="modal fade" id="deletePostModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Delete Post</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this post?</p>
                <div id="postPreview" class="alert alert-secondary"></div>
                <p class="text-danger small">⚠️ This action cannot be undone. All comments and likes will be permanently removed.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deletePostForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete Post</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .post-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .post-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .post-content {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    
    .like-btn {
        transition: all 0.3s;
    }
    
    .like-btn:hover {
        transform: scale(1.05);
    }
    
    .comment-item {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 8px;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>

<script>
    // Character counter for post content
    const textarea = document.getElementById('postContent');
    if (textarea) {
        const charCount = document.getElementById('charCount');
        
        function updateCharCount() {
            const count = textarea.value.length;
            charCount.innerHTML = `${count} / 5000 characters`;
            
            if (count > 5000) {
                charCount.style.color = 'red';
                document.getElementById('submitPost').disabled = true;
            } else if (count < 3 && count > 0) {
                charCount.style.color = 'orange';
                document.getElementById('submitPost').disabled = false;
            } else {
                charCount.style.color = '#6c757d';
                document.getElementById('submitPost').disabled = false;
            }
        }
        
        textarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }
    
    // Toggle comments visibility
    function toggleComments(postId) {
        const commentsDiv = document.getElementById(`comments-${postId}`);
        if (commentsDiv.style.display === 'none') {
            commentsDiv.style.display = 'block';
        } else {
            commentsDiv.style.display = 'none';
        }
    }
    
    // Confirm delete post
    function confirmDelete(postId, postContent) {
        const modal = new bootstrap.Modal(document.getElementById('deletePostModal'));
        const form = document.getElementById('deletePostForm');
        const preview = document.getElementById('postPreview');
        
        form.action = `/posts/${postId}`;
        preview.innerHTML = `<strong>Post content:</strong><br>"${postContent.substring(0, 200)}${postContent.length > 200 ? '...' : ''}"`;
        
        modal.show();
    }
    
    // AJAX Like functionality
    document.querySelectorAll('.like-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = this.action;
            const postId = this.closest('.post-card').dataset.postId;
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Update like count
                    const likeCountSpan = document.querySelector(`.like-count-${postId}`);
                    likeCountSpan.textContent = data.likes_count;
                    
                    // Update button appearance
                    const likeBtn = this.querySelector('.like-btn');
                    if (data.liked) {
                        likeBtn.classList.remove('btn-outline-danger');
                        likeBtn.classList.add('btn-danger');
                        likeBtn.innerHTML = '<i class="fas fa-heart"></i> Liked';
                    } else {
                        likeBtn.classList.remove('btn-danger');
                        likeBtn.classList.add('btn-outline-danger');
                        likeBtn.innerHTML = '<i class="fas fa-heart"></i> Like';
                    }
                    
                    // Show notification
                    showNotification(data.message, 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error processing your request', 'danger');
            }
        });
    });
    
    // AJAX Comment functionality
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const url = this.action;
            const postId = this.closest('.post-card').dataset.postId;
            const commentInput = this.querySelector('input[name="comment"]');
            const comment = commentInput.value;
            
            if (!comment.trim()) {
                showNotification('Please enter a comment', 'warning');
                return;
            }
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Add new comment to the list
                    const commentsList = document.getElementById(`comments-list-${postId}`);
                    const newComment = `
                        <div class="comment-item mb-3" id="comment-${data.comment.id}">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>${data.comment.user_name}</strong>
                                    <small class="text-muted ms-2">Just now</small>
                                </div>
                                <button class="btn btn-sm btn-link text-danger" onclick="deleteComment(${data.comment.id})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                            <p class="mb-0 mt-1">${data.comment.comment}</p>
                        </div>
                    `;
                    
                    if (commentsList.innerHTML.includes('No comments yet')) {
                        commentsList.innerHTML = newComment;
                    } else {
                        commentsList.insertAdjacentHTML('beforeend', newComment);
                    }
                    
                    // Clear input
                    commentInput.value = '';
                    
                    // Update comment count
                    const commentCount = document.querySelector(`#comments-${postId} .btn-outline-primary`);
                    const currentCount = parseInt(commentCount.textContent.match(/\d+/)[0]);
                    commentCount.innerHTML = `<i class="fas fa-comment"></i> Comments (${currentCount + 1})`;
                    
                    showNotification('Comment added successfully!', 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error posting comment', 'danger');
            }
        });
    });
    
    // Delete comment function
    async function deleteComment(commentId) {
        if (!confirm('Are you sure you want to delete this comment?')) return;
        
        try {
            const response = await fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                document.getElementById(`comment-${commentId}`).remove();
                showNotification('Comment deleted successfully', 'success');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error deleting comment', 'danger');
        }
    }
    
    // Show notification
    function showNotification(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
    
    // Reset filters
    function resetFilters() {
        window.location.href = "{{ route('posts.index') }}";
    }
    
    // Auto-expand textarea
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
    }
    
    // Load more comments (infinite scroll)
    let loading = false;
    window.addEventListener('scroll', function() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
            if (!loading && document.querySelector('.pagination .next')) {
                loading = true;
                const nextPageUrl = document.querySelector('.pagination .next a')?.href;
                if (nextPageUrl) {
                    window.location.href = nextPageUrl;
                }
            }
        }
    });
</script>
@endsection
{{-- resources/views/posts/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Post</h5>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Note:</strong> After editing, your post will need to be re-approved by admin.
                    </div>
                    
                    <form method="POST" action="{{ route('posts.update', $post) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Edit your post</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="6" 
                                      required>{{ old('content', $post->content) }}</textarea>
                            
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="alert alert-secondary">
                                <small>
                                    <strong>Original Post Date:</strong> {{ $post->created_at->format('F d, Y g:i A') }}<br>
                                    <strong>Current Status:</strong> 
                                    <span class="badge bg-{{ $post->status === 'approved' ? 'success' : 'warning' }}">
                                        {{ ucfirst($post->status) }}
                                    </span>
                                </small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
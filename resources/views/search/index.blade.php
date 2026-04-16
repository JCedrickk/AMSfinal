{{-- resources/views/search/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search Alumni Directory</h5>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('search') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" name="query" class="form-control form-control-lg" 
                                           placeholder="Search by name, course, job title, or post content..." 
                                           value="{{ request('query') }}">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="type" class="form-control form-control-lg">
                                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="users" {{ request('type') == 'users' ? 'selected' : '' }}>Alumni</option>
                                    <option value="posts" {{ request('type') == 'posts' ? 'selected' : '' }}>Posts</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="year" class="form-control form-control-lg">
                                    <option value="">All Years</option>
                                    @foreach($graduationYears as $year)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Search Results -->
                    @if(request()->has('query'))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Found {{ $users->total() + $posts->total() }} results for "{{ request('query') }}"
                        </div>
                        
                        <!-- Users Results -->
                        @if($users->count() > 0 && (request('type') == 'all' || request('type') == 'users'))
                            <h4 class="mb-3">
                                <i class="fas fa-users"></i> Alumni 
                                <small class="text-muted">({{ $users->total() }} found)</small>
                            </h4>
                            
                            <div class="row">
                                @foreach($users as $user)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary text-white me-3" 
                                                         style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-1">
                                                            <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none">
                                                                {{ $user->alumniProfile->full_name ?? $user->name }}
                                                            </a>
                                                        </h5>
                                                        <p class="mb-0 text-muted small">
                                                            <i class="fas fa-graduation-cap"></i> {{ $user->alumniProfile->course ?? 'N/A' }}<br>
                                                            <i class="fas fa-calendar"></i> {{ $user->alumniProfile->year_graduated ?? 'N/A' }}
                                                            @if($user->alumniProfile->job_title)
                                                                <br><i class="fas fa-briefcase"></i> {{ $user->alumniProfile->job_title }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            {{ $users->appends(request()->query())->links() }}
                        @endif
                        
                        <!-- Posts Results -->
                        @if($posts->count() > 0 && (request('type') == 'all' || request('type') == 'posts'))
                            <h4 class="mb-3 mt-4">
                                <i class="fas fa-newspaper"></i> Posts 
                                <small class="text-muted">({{ $posts->total() }} found)</small>
                            </h4>
                            
                            @foreach($posts as $post)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <strong>
                                                <a href="{{ route('profile.show', $post->user->id) }}">
                                                    {{ $post->user->name }}
                                                </a>
                                            </strong>
                                            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p>{{ Str::limit($post->content, 300) }}</p>
                                        <div class="text-muted small">
                                            <span class="me-3">
                                                <i class="fas fa-heart text-danger"></i> {{ $post->likes->count() }} likes
                                            </span>
                                            <span>
                                                <i class="fas fa-comment"></i> {{ $post->comments->count() }} comments
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            {{ $posts->appends(request()->query())->links() }}
                        @endif
                        
                        @if($users->total() == 0 && $posts->total() == 0)
                            <div class="text-center py-5">
                                <i class="fas fa-search fa-4x text-muted mb-3"></i>
                                <h4>No results found</h4>
                                <p class="text-muted">Try different keywords or browse all alumni</p>
                                <a href="{{ route('profile.directory') }}" class="btn btn-primary">
                                    Browse All Alumni
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-4x text-muted mb-3"></i>
                            <h4>Search Alumni and Posts</h4>
                            <p class="text-muted">Enter a name, course, job title, or keyword to get started</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .card:hover {
        transform: translateY(-2px);
        transition: transform 0.2s;
    }
</style>
@endsection
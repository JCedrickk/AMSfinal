@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Search Card -->
    <div class="glass-card rounded-2xl mb-8">
        <div class="p-6 border-b border-gray-200">
            <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                <i class="fas fa-address-book mr-2"></i>Alumni Directory
            </h4>
        </div>
        <div class="p-6">
            <!-- Search Form -->
            <form method="GET" action="{{ route('search') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="search" class="glass-input w-full rounded-xl px-4 py-2.5" 
                           placeholder="Search by name or course..." value="{{ request('search') }}">
                </div>
                <div class="md:col-span-1">
                    <select name="year" class="glass-input w-full rounded-xl px-4 py-2.5">
                        <option value="">All Years</option>
                        @foreach($years ?? [] as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <button type="submit" class="btn-primary w-full py-2.5 rounded-xl font-semibold">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Section -->
    @if(request()->has('search') || request()->has('course') || request()->has('year'))
        <!-- Results Title -->
        <div class="mb-6">
            <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                <i class="fas fa-users mr-2"></i>Search Results
            </h4>
            <p class="text-sm text-[#4a5568] mt-1">
                Found {{ $alumni->total() }} alumni member(s) matching your criteria
            </p>
        </div>
        
        <!-- Alumni Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($alumni as $alumnus)
            <div class="glass-card rounded-2xl overflow-hidden alumni-card cursor-pointer hover:shadow-xl transition-all duration-300" 
                 onclick="window.location='{{ route('profile.show.other', $alumnus->id) }}'">
                <div class="p-6 text-center">
                    <!-- Profile Picture -->
                    <div class="mb-4">
                        @if($alumnus->profile && $alumnus->profile->profile_picture)
                            <img src="{{ asset('storage/' . $alumnus->profile->profile_picture) }}" 
                                 alt="Profile Picture" 
                                 class="w-24 h-24 rounded-full object-cover border-3 border-[#2c3e66] mx-auto shadow-md">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center mx-auto shadow-md">
                                <i class="fas fa-user-graduate text-3xl text-white"></i>
                            </div>
                        @endif
                    </div>
                    
                    @if($alumnus->profile)
                        <h5 class="font-display font-semibold text-lg text-[#1a2a4a] mb-1">{{ $alumnus->first_name }} {{ $alumnus->last_name }}</h5>
                        <p class="text-sm text-[#4a5568] mb-2">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            {{ $alumnus->profile->course }}
                        </p>
                        <p class="text-sm text-[#4a5568] mb-2">
                            <i class="fas fa-calendar mr-1"></i>
                            Class of {{ $alumnus->profile->year_graduated }}
                        </p>
                        @if($alumnus->profile->job_title)
                        <p class="text-sm text-[#4a5568] mb-3">
                            <i class="fas fa-briefcase mr-1"></i>
                            {{ $alumnus->profile->job_title }}
                        </p>
                        @endif
                        
                        <hr class="my-3 border-gray-200">
                        
                        <div class="text-left space-y-1">
                            <small class="text-xs text-[#4a5568] block">
                                <i class="fas fa-envelope mr-1"></i> {{ $alumnus->email }}
                            </small>
                            @if($alumnus->profile->contact_number)
                            <small class="text-xs text-[#4a5568] block">
                                <i class="fas fa-phone mr-1"></i> {{ $alumnus->profile->contact_number }}
                            </small>
                            @endif
                        </div>
                        
                        <!-- View Profile Button -->
                        <div class="mt-4">
                            <button class="btn-outline w-full py-2 rounded-lg text-sm font-semibold" 
                                    onclick="event.stopPropagation(); window.location='{{ route('profile.show.other', $alumnus->id) }}'">
                                <i class="fas fa-user-circle mr-1"></i> View Profile
                            </button>
                        </div>
                    @else
                        <h5 class="text-amber-600 font-semibold">{{ $alumnus->first_name }} {{ $alumnus->last_name }}</h5>
                        <p class="text-sm text-[#4a5568] mt-2">Profile incomplete</p>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="glass-card rounded-2xl py-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-[#2c3e66]"></i>
                    </div>
                    <p class="text-[#1a2a4a] font-medium">No alumni members found</p>
                    <p class="text-sm text-[#4a5568] mt-1">Try adjusting your search terms or filters.</p>
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($alumni->hasPages())
        <div class="mt-8">
            {{ $alumni->links() }}
        </div>
        @endif
        
    @else
        <!-- No Search Performed Yet -->
        <div class="glass-card rounded-2xl py-12 text-center">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-4xl text-[#2c3e66]"></i>
            </div>
            <h4 class="font-display font-bold text-xl text-[#1a2a4a] mb-2">Search Alumni Directory</h4>
            <p class="text-[#4a5568] max-w-md mx-auto">
                Use the search form above to find alumni by name, course, or graduation year.
            </p>
            <div class="mt-6 flex justify-center gap-8">
                <div class="text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-user text-[#2c3e66] text-xl"></i>
                    </div>
                    <p class="text-xs text-[#4a5568]">Search by Name</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-book text-[#2c3e66] text-xl"></i>
                    </div>
                    <p class="text-xs text-[#4a5568]">Filter by Course</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-calendar text-[#2c3e66] text-xl"></i>
                    </div>
                    <p class="text-xs text-[#4a5568]">Filter by Year</p>
                </div>
            </div>
        </div>
    @endif
</div>

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
    
    .alumni-card {
        transition: all 0.3s ease;
    }
    
    .alumni-card:hover {
        transform: translateY(-5px);
    }
    
    .font-display {
        font-family: 'Poppins', system-ui, sans-serif;
    }
</style>
@endsection
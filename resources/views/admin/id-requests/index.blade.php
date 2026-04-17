@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#2c3e66] rounded-xl flex items-center justify-center">
                        <i class="fas fa-list text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                            All ID Requests
                        </h4>
                        <p class="text-sm text-[#4a5568] mt-0.5">View and manage all alumni ID requests</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.id-requests.pending') }}" class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg text-sm font-semibold hover:bg-amber-200 transition">
                        <i class="fas fa-clock mr-1"></i> Pending Only
                    </a>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Request Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">ID Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-[#1a2a4a] font-medium">{{ $req->id }}</td>
                        <td class="px-6 py-4 text-sm text-[#1a2a4a]">
                            @if($req->user && $req->user->profile)
                                <div class="flex items-center gap-2">
                                    @if($req->user->profile->profile_picture)
                                        <img src="{{ asset('storage/' . $req->user->profile->profile_picture) }}" 
                                             alt="Profile" 
                                             class="w-6 h-6 rounded-full object-cover">
                                    @else
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </div>
                                    @endif
                                    <span>{{ $req->user->first_name }} {{ $req->user->last_name }}</span>
                                </div>
                            @else
                                <span class="text-amber-600">{{ $req->user->name ?? 'Unknown' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $req->user->email ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $req->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @if($req->status == 'pending')
                                <span class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-clock mr-1 text-xs"></i> Pending
                                </span>
                            @elseif($req->status == 'approved')
                                <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i> Approved
                                </span>
                            @elseif($req->status == 'rejected')
                                <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-times-circle mr-1 text-xs"></i> Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-[#4a5568] font-mono">
                            {{ $req->alumni_id_number ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-[#4a5568] max-w-xs truncate" title="{{ $req->remarks ?? '' }}">
                            {{ $req->remarks ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-[#4a5568]">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-id-card text-2xl text-[#2c3e66]"></i>
                                </div>
                                <p class="font-medium">No ID requests found</p>
                                <p class="text-sm">There are no alumni ID requests in the system.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
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
    
    .font-display {
        font-family: 'Poppins', system-ui, sans-serif;
    }
    
    /* Custom pagination styling */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .pagination .page-item .page-link {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #e2e8f0;
        color: #2c3e66;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .pagination .page-item.active .page-link {
        background: #2c3e66;
        border-color: #2c3e66;
        color: white;
    }
    
    .pagination .page-item .page-link:hover {
        background: #2c3e66;
        border-color: #2c3e66;
        color: white;
        transform: translateY(-1px);
    }
</style>
@endsection
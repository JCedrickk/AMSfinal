@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-id-card text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                            All ID Requests
                        </h4>
                        <p class="text-sm text-[#4a5568] mt-0.5">View and manage all alumni ID requests</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        <i class="fas fa-arrow-left mr-1"></i> Back
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Claimed</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">ID Number</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Remarks</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-[#1a2a4a] font-medium">{{ $req->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($req->user && $req->user->profile && $req->user->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $req->user->profile->profile_picture) }}" 
                                         alt="Profile" 
                                         class="w-6 h-6 rounded-full object-cover">
                                @else
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center">
                                        <i class="fas fa-user text-white text-xs"></i>
                                    </div>
                                @endif
                                <span>{{ $req->user->first_name ?? 'Unknown' }} {{ $req->user->last_name ?? '' }}</span>
                            </div>
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
                        <td class="px-6 py-4">
                            @if($req->status == 'approved')
                                @if($req->claimed)
                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-semibold">
                                        <i class="fas fa-check-circle mr-1 text-xs"></i> Claimed
                                    </span>
                                    <br>
                                    <small class="text-xs text-gray-400">{{ $req->claimed_at ? $req->claimed_at->format('M d, Y') : '' }}</small>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-semibold">
                                        <i class="fas fa-clock mr-1 text-xs"></i> Not Claimed
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-[#4a5568] font-mono">
                            {{ $req->alumni_id_number ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-[#4a5568] max-w-xs truncate" title="{{ $req->remarks ?? '' }}">
                            {{ $req->remarks ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                               @if($req->status == 'approved' && !$req->claimed)
                                    <form action="{{ route('admin.id-requests.mark-claimed', $req) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded-lg text-xs font-semibold hover:bg-green-700 transition">
                                            <i class="fas fa-check-circle mr-1"></i> Mark Claimed
                                        </button>
                                    </form>
                                @endif

                                @if($req->status == 'approved' && $req->claimed)
                                    <form action="{{ route('admin.id-requests.mark-unclaimed', $req) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-yellow-600 text-white rounded-lg text-xs font-semibold hover:bg-yellow-700 transition">
                                            <i class="fas fa-undo mr-1"></i> Mark Unclaimed
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-id-card text-2xl text-[#2c3e66]"></i>
                                </div>
                                <p class="text-[#1a2a4a] font-medium">No ID requests found</p>
                                <p class="text-sm text-[#4a5568]">There are no alumni ID requests in the system.</p>
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
</style>
@endsection
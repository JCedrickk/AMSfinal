@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-archive text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                            Archived Users
                        </h4>
                        <p class="text-sm text-[#4a5568] mt-0.5">Accounts archived and pending permanent deletion</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                        <i class="fas fa-arrow-left mr-1"></i> Back to All Users
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Info Box -->
        <div class="px-6 py-3 bg-blue-50 border-b border-blue-200">
            <div class="flex items-start gap-2">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                <div>
                    <p class="text-sm font-semibold text-blue-700">Auto-Restore Available</p>
                    <p class="text-xs text-blue-600">
                        Archived users can restore their own accounts by simply logging in within 30 days. 
                        No admin action required. After 30 days, accounts are permanently deleted.
                    </p>
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Archived Date</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Days Left</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($archivedUsers as $user)
                    @php
                        $deletedAt = \Carbon\Carbon::parse($user->deleted_at);
                        $daysRemaining = 30 - $deletedAt->diffInDays(now());
                        $restoreDate = $deletedAt->addDays(30)->format('M d, Y');
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-[#1a2a4a] font-medium">{{ $user->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($user->profile && $user->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" 
                                         alt="Profile" 
                                         class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-500 to-gray-700 flex items-center justify-center">
                                        <i class="fas fa-user text-white text-xs"></i>
                                    </div>
                                @endif
                                <span class="text-sm font-medium text-[#1a2a4a]">{{ $user->first_name }} {{ $user->last_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $deletedAt->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            @if($daysRemaining > 0)
                                <div class="flex flex-col">
                                    <span class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">
                                        {{ $daysRemaining }} days remaining
                                    </span>
                                    <span class="text-xs text-gray-400 mt-1">Expires: {{ $restoreDate }}</span>
                                </div>
                            @else
                                <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-semibold">
                                    Pending permanent deletion
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($daysRemaining > 0)
                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Restore this account? The user will be able to login again.')">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition">
                                    <i class="fas fa-trash-restore mr-1"></i> Restore
                                </button>
                            </form>
                            @else
                                <span class="text-xs text-gray-400">Cannot restore</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-archive text-2xl text-[#2c3e66]"></i>
                                </div>
                                <p class="text-[#1a2a4a] font-medium">No archived users</p>
                                <p class="text-sm text-[#4a5568]">There are no archived accounts at this time.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($archivedUsers->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $archivedUsers->links() }}
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
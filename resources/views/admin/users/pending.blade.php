@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#2c3e66] rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-clock text-white text-lg"></i>
                </div>
                <div>
                   <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                            Pending User Approvals
                        </h4>
                        <p class="text-sm text-[#4a5568] mt-0.5">Review and approve or reject new alumni registration requests</p>
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Full Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Birthday</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Batch</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Registered</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($user->profile && $user->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" 
                                         alt="Profile" 
                                         class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center">
                                        <i class="fas fa-user text-white text-xs"></i>
                                    </div>
                                @endif
                                <span class="text-sm font-medium text-[#1a2a4a]">{{ $user->first_name }} {{ $user->last_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->profile->course ?? 'N/A' }}</td>
                        <<td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->profile->birthday ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->profile->year_graduated ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg text-xs font-semibold hover:bg-emerald-700 transition-all flex items-center gap-1">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <button type="button" class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition-all flex items-center gap-1" 
                                        onclick="openRejectModal({{ $user->id }})">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </div>
                            
                            <!-- Reject Modal -->
                            <div id="rejectModal{{ $user->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-center justify-center min-h-screen px-4">
                                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeRejectModal({{ $user->id }})"></div>
                                    
                                    <div class="relative bg-white rounded-2xl max-w-md w-full mx-auto shadow-xl transform transition-all">
                                        <div class="px-6 py-4 border-b border-gray-200">
                                            <h5 class="font-display font-bold text-lg text-[#1a2a4a]">Reject User Registration</h5>
                                        </div>
                                        <form action="{{ route('admin.users.reject', $user) }}" method="POST">
                                            @csrf
                                            <div class="p-6">
                                                <p class="text-sm text-[#4a5568] mb-3">
                                                    You are about to reject the registration request for:
                                                    <strong class="text-[#1a2a4a] block mt-1">{{ $user->first_name }} {{ $user->last_name }}</strong>
                                                </p>
                                                <label class="block text-sm font-semibold text-[#1a2a4a] mb-2">Reason for rejection (optional):</label>
                                                <textarea name="remarks" class="glass-input w-full rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-transparent" rows="3" placeholder="Enter reason for rejection..."></textarea>
                                            </div>
                                            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                                                <button type="button" class="px-4 py-2 bg-gray-100 text-[#4a5568] rounded-lg font-semibold text-sm hover:bg-gray-200 transition" 
                                                        onclick="closeRejectModal({{ $user->id }})">Cancel</button>
                                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition">Reject User</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-check text-2xl text-[#2c3e66]"></i>
                                </div>
                                <p class="text-[#1a2a4a] font-medium">No pending users</p>
                                <p class="text-sm text-[#4a5568]">All registration requests have been processed.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(userId) {
        const modal = document.getElementById(`rejectModal${userId}`);
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeRejectModal(userId) {
        const modal = document.getElementById(`rejectModal${userId}`);
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('[id^="rejectModal"]').forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        }
    });
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
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }
    
    .font-display {
        font-family: 'Poppins', system-ui, sans-serif;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #2c3e66;
        border-radius: 4px;
    }
</style>
@endsection
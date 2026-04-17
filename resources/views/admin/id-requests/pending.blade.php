@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#2c3e66] rounded-xl flex items-center justify-center">
                    <i class="fas fa-id-card text-white text-lg"></i>
                </div>
                <div>
                    <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                        Pending Alumni ID Requests
                    </h4>
                    <p class="text-sm text-[#4a5568] mt-0.5">Review and process alumni ID card requests</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @forelse($requests as $request)
            <div class="glass-card rounded-xl mb-4 overflow-hidden hover:shadow-lg transition-all duration-300">
                <div class="p-5">
                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <!-- User Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                @if($request->user && $request->user->profile && $request->user->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $request->user->profile->profile_picture) }}" 
                                         alt="Profile" 
                                         class="w-12 h-12 rounded-full object-cover border-2 border-[#2c3e66]">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center">
                                        <i class="fas fa-user text-white text-lg"></i>
                                    </div>
                                @endif
                                <div>
                                    @if($request->user && $request->user->profile)
                                        <h5 class="font-display font-bold text-lg text-[#1a2a4a]">
                                            {{ $request->user->first_name }} {{ $request->user->last_name }}
                                        </h5>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="inline-flex items-center px-2 py-0.5 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">
                                                <i class="fas fa-clock mr-1 text-xs"></i> Pending
                                            </span>
                                            <span class="text-xs text-[#4a5568]">Requested {{ $request->created_at->diffForHumans() }}</span>
                                        </div>
                                    @else
                                        <h5 class="font-display font-bold text-lg text-amber-600">
                                            {{ $request->user->name ?? 'Unknown User' }}
                                        </h5>
                                        <span class="inline-flex items-center px-2 py-0.5 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold mt-1">
                                            <i class="fas fa-exclamation-triangle mr-1 text-xs"></i> Profile Incomplete
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                <div class="flex items-center gap-2 text-sm text-[#4a5568]">
                                    <i class="fas fa-envelope w-4 text-[#2c3e66]"></i>
                                    <span>{{ $request->user->email ?? 'No email' }}</span>
                                </div>
                                @if($request->user && $request->user->profile)
                                <div class="flex items-center gap-2 text-sm text-[#4a5568]">
                                    <i class="fas fa-graduation-cap w-4 text-[#2c3e66]"></i>
                                    <span>{{ $request->user->profile->course }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-[#4a5568]">
                                    <i class="fas fa-calendar w-4 text-[#2c3e66]"></i>
                                    <span>Class of {{ $request->user->profile->year_graduated }}</span>
                                </div>
                                @if($request->user->profile->contact_number)
                                <div class="flex items-center gap-2 text-sm text-[#4a5568]">
                                    <i class="fas fa-phone w-4 text-[#2c3e66]"></i>
                                    <span>{{ $request->user->profile->contact_number }}</span>
                                </div>
                                @endif
                                @endif
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex flex-row md:flex-col gap-2 md:min-w-[140px]">
                            <form action="{{ route('admin.id-requests.approve', $request) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-emerald-600 text-white rounded-lg font-semibold text-sm hover:bg-emerald-700 transition-all flex items-center justify-center gap-2" 
                                        {{ !$request->user || !$request->user->profile ? 'disabled' : '' }}>
                                    <i class="fas fa-check"></i> Approve
                                </button>
                            </form>
                            <button type="button" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition-all flex items-center justify-center gap-2" 
                                    onclick="openRejectModal({{ $request->id }})">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div id="rejectModal{{ $request->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4">
                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeRejectModal({{ $request->id }})"></div>
                    
                    <div class="relative bg-white rounded-2xl max-w-md w-full mx-auto shadow-xl transform transition-all">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h5 class="font-display font-bold text-lg text-[#1a2a4a]">Reject ID Request</h5>
                        </div>
                        <form action="{{ route('admin.id-requests.reject', $request) }}" method="POST">
                            @csrf
                            <div class="p-6">
                                <label class="block text-sm font-semibold text-[#1a2a4a] mb-2">Reason for rejection:</label>
                                <textarea name="remarks" class="glass-input w-full rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-transparent" rows="4" required></textarea>
                                <p class="text-xs text-[#4a5568] mt-2">This reason will be shared with the applicant.</p>
                            </div>
                            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                                <button type="button" class="px-4 py-2 bg-gray-100 text-[#4a5568] rounded-lg font-semibold text-sm hover:bg-gray-200 transition" 
                                        onclick="closeRejectModal({{ $request->id }})">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition">Reject Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-id-card text-3xl text-[#2c3e66]"></i>
                </div>
                <p class="text-[#1a2a4a] font-medium">No pending ID requests</p>
                <p class="text-sm text-[#4a5568] mt-1">All ID requests have been processed.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(requestId) {
        const modal = document.getElementById(`rejectModal${requestId}`);
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeRejectModal(requestId) {
        const modal = document.getElementById(`rejectModal${requestId}`);
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
    
    /* Custom scrollbar for modal */
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
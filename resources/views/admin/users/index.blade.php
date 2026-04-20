@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#2c3e66] rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                            All Alumni Members
                        </h4>
                        <p class="text-sm text-[#4a5568] mt-0.5">View and manage all registered alumni</p>
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
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Full Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Year</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-[#4a5568] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-[#1a2a4a] font-medium">{{ $user->id }}</td>
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
                        <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->profile->year_graduated ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @if($user->role == 'admin')
                                <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-user-shield mr-1 text-xs"></i> Admin
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-user mr-1 text-xs"></i> User
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status == 'approved')
                                <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i> Approved
                                </span>
                            @elseif($user->status == 'pending')
                                <span class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-clock mr-1 text-xs"></i> Pending
                                </span>
                            @elseif($user->status == 'rejected')
                                <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-semibold">
                                    <i class="fas fa-times-circle mr-1 text-xs"></i> Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-[#4a5568]">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <!-- Remove Admin Button (for existing admins, except current user) -->
                                @if($user->role == 'admin' && $user->id != auth()->id())
                                    <button type="button" class="px-3 py-1.5 bg-orange-600 text-white rounded-lg text-xs font-semibold hover:bg-orange-700 transition-all flex items-center gap-1" 
                                            onclick="openRemoveAdminModal({{ $user->id }})">
                                        <i class="fas fa-user-minus"></i> Remove Admin
                                    </button>
                                @endif
                                
                                <button type="button" class="px-3 py-1.5 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition-all flex items-center gap-1" 
                                        onclick="openDeleteModal({{ $user->id }})">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                            
                            <!-- Remove Admin Modal -->
                            <div id="removeAdminModal{{ $user->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-center justify-center min-h-screen px-4">
                                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeRemoveAdminModal({{ $user->id }})"></div>
                                    
                                    <div class="relative bg-white rounded-2xl max-w-md w-full mx-auto shadow-xl transform transition-all">
                                        <div class="px-6 py-4 border-b border-gray-200">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                                                </div>
                                                <h5 class="font-display font-bold text-lg text-[#1a2a4a]">Remove Administrator</h5>
                                            </div>
                                        </div>
                                        <form action="{{ route('admin.users.remove-admin', $user) }}" method="POST" onsubmit="return validateRemoveAdminForm({{ $user->id }})">
                                            @csrf
                                            <div class="p-6">
                                                <p class="text-sm text-[#4a5568] mb-2">
                                                    You are about to remove admin privileges from:
                                                </p>
                                                <p class="font-semibold text-[#1a2a4a] text-base mb-4">
                                                    {{ $user->first_name }} {{ $user->last_name }}
                                                </p>
                                                
                                                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-3 mb-4">
                                                    <div class="flex items-start gap-2">
                                                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                                                        <p class="text-xs text-red-700">
                                                            <strong>Warning:</strong> This user will lose all administrative access. They will become a regular user with standard permissions.
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-4">
                                                    <label class="block text-sm font-semibold text-[#1a2a4a] mb-2">Security Confirmation:</label>
                                                    <input type="password" name="admin_password" class="glass-input w-full rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                                                           placeholder="Enter your admin password to confirm" required>
                                                    <p class="text-xs text-[#4a5568] mt-1">Please enter your password to verify your identity.</p>
                                                </div>
                                                
                                                <div class="mb-4">
                                                    <label class="block text-sm font-semibold text-[#1a2a4a] mb-2">Reason for removal (optional):</label>
                                                    <textarea name="reason" class="glass-input w-full rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-transparent" 
                                                              rows="3" placeholder="Provide a reason for removing admin privileges..."></textarea>
                                                </div>
                                                
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" id="confirmRemove{{ $user->id }}" class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500" required>
                                                    <span class="text-sm text-[#4a5568]">I understand that this action will revoke this user's administrative privileges</span>
                                                </label>
                                            </div>
                                            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                                                <button type="button" class="px-4 py-2 bg-gray-100 text-[#4a5568] rounded-lg font-semibold text-sm hover:bg-gray-200 transition" 
                                                        onclick="closeRemoveAdminModal({{ $user->id }})">Cancel</button>
                                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition opacity-50 cursor-not-allowed" 
                                                        id="removeAdminBtn{{ $user->id }}" disabled>Remove Admin Privileges</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Delete Confirmation Modal -->
                            <div id="deleteModal{{ $user->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-center justify-center min-h-screen px-4">
                                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal({{ $user->id }})"></div>
                                    
                                    <div class="relative bg-white rounded-2xl max-w-md w-full mx-auto shadow-xl transform transition-all">
                                        <div class="px-6 py-4 border-b border-gray-200">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-exclamation-triangle text-red-600 text-lg"></i>
                                                </div>
                                                <h5 class="font-display font-bold text-lg text-[#1a2a4a]">Delete User</h5>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <p class="text-sm text-[#4a5568] mb-2">
                                                You are about to permanently delete the account of:
                                            </p>
                                            <p class="font-semibold text-[#1a2a4a] text-base mb-4">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </p>
                                            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-3 mb-4">
                                                <div class="flex items-start gap-2">
                                                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                                                    <p class="text-xs text-red-700">
                                                        This action cannot be undone. All user data including posts, comments, and ID requests will be permanently deleted.
                                                    </p>
                                                </div>
                                            </div>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" id="confirmDelete{{ $user->id }}" class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                                <span class="text-sm text-[#4a5568]">I understand that this action is permanent</span>
                                            </label>
                                        </div>
                                        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                                            <button type="button" class="px-4 py-2 bg-gray-100 text-[#4a5568] rounded-lg font-semibold text-sm hover:bg-gray-200 transition" 
                                                    onclick="closeDeleteModal({{ $user->id }})">Cancel</button>
                                            <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="inline" id="deleteForm{{ $user->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition opacity-50 cursor-not-allowed" 
                                                        id="deleteBtn{{ $user->id }}" disabled>Delete User</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-users-slash text-2xl text-[#2c3e66]"></i>
                                </div>
                                <p class="text-[#1a2a4a] font-medium">No users found</p>
                                <p class="text-sm text-[#4a5568]">There are no registered alumni in the system.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function openRemoveAdminModal(userId) {
        const modal = document.getElementById(`removeAdminModal${userId}`);
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        const checkbox = document.getElementById(`confirmRemove${userId}`);
        const removeBtn = document.getElementById(`removeAdminBtn${userId}`);
        
        const handleChange = function() {
            if (this.checked) {
                removeBtn.disabled = false;
                removeBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                removeBtn.disabled = true;
                removeBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        };
        
        if (checkbox) {
            checkbox.removeEventListener('change', handleChange);
            checkbox.addEventListener('change', handleChange);
        }
    }
    
    function closeRemoveAdminModal(userId) {
        const modal = document.getElementById(`removeAdminModal${userId}`);
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        const checkbox = document.getElementById(`confirmRemove${userId}`);
        if (checkbox) {
            checkbox.checked = false;
        }
        
        const removeBtn = document.getElementById(`removeAdminBtn${userId}`);
        if (removeBtn) {
            removeBtn.disabled = true;
            removeBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    function openDeleteModal(userId) {
        const modal = document.getElementById(`deleteModal${userId}`);
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        const checkbox = document.getElementById(`confirmDelete${userId}`);
        const deleteBtn = document.getElementById(`deleteBtn${userId}`);
        
        const handleChange = function() {
            if (this.checked) {
                deleteBtn.disabled = false;
                deleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                deleteBtn.disabled = true;
                deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        };
        
        if (checkbox) {
            checkbox.removeEventListener('change', handleChange);
            checkbox.addEventListener('change', handleChange);
        }
    }
    
    function closeDeleteModal(userId) {
        const modal = document.getElementById(`deleteModal${userId}`);
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        const checkbox = document.getElementById(`confirmDelete${userId}`);
        if (checkbox) {
            checkbox.checked = false;
        }
        
        const deleteBtn = document.getElementById(`deleteBtn${userId}`);
        if (deleteBtn) {
            deleteBtn.disabled = true;
            deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    function validateRemoveAdminForm(userId) {
        const password = document.querySelector(`#removeAdminModal${userId} input[name="admin_password"]`).value;
        if (!password) {
            alert('Please enter your admin password to confirm.');
            return false;
        }
        return true;
    }
    
    // Close modals on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('[id^="removeAdminModal"]').forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
            document.querySelectorAll('[id^="deleteModal"]').forEach(modal => {
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
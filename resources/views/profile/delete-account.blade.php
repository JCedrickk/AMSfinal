@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Warning Card -->
    <div class="glass-card rounded-2xl mb-6 overflow-hidden">
        <div class="p-6">
            <div class="bg-red-50 border-l-4 border-red-500 rounded-xl p-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                    </div>
                    <h4 class="font-display font-bold text-lg text-red-700">Warning: This action cannot be undone!</h4>
                </div>
                <p class="text-red-600 text-sm ml-14">
                    Deleting your account is permanent and will remove all your data from our system.
                </p>
            </div>
        </div>
    </div>
    
    <!-- What Gets Deleted Card -->
    <div class="glass-card rounded-2xl mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="font-display font-semibold text-lg text-[#1a2a4a]">
                <i class="fas fa-trash-alt mr-2 text-red-500"></i>What will be deleted?
            </h5>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-3">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-user-circle text-red-400 mt-0.5"></i>
                        <div>
                            <strong class="text-[#1a2a4a] text-sm">Your Profile</strong>
                            <p class="text-[#4a5568] text-xs">All profile information and profile picture</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-newspaper text-red-400 mt-0.5"></i>
                        <div>
                            <strong class="text-[#1a2a4a] text-sm">Your Posts</strong>
                            <p class="text-[#4a5568] text-xs">All posts you've created</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-comments text-red-400 mt-0.5"></i>
                        <div>
                            <strong class="text-[#1a2a4a] text-sm">Your Comments</strong>
                            <p class="text-[#4a5568] text-xs">All comments you've made</p>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-heart text-red-400 mt-0.5"></i>
                        <div>
                            <strong class="text-[#1a2a4a] text-sm">Your Likes</strong>
                            <p class="text-[#4a5568] text-xs">All likes you've given</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-id-card text-red-400 mt-0.5"></i>
                        <div>
                            <strong class="text-[#1a2a4a] text-sm">ID Requests</strong>
                            <p class="text-[#4a5568] text-xs">All alumni ID requests</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fas fa-bell text-red-400 mt-0.5"></i>
                        <div>
                            <strong class="text-[#1a2a4a] text-sm">Notifications</strong>
                            <p class="text-[#4a5568] text-xs">All your notifications</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Deletion Form -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h5 class="font-display font-semibold text-lg text-[#1a2a4a]">
                <i class="fas fa-shield-alt mr-2"></i>Confirm Account Deletion
            </h5>
        </div>
        <div class="p-6">
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-xl p-4 mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    <span class="text-blue-700 text-sm">Please complete the following steps to verify account deletion.</span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('account.delete.process') }}" id="deleteAccountForm">
                @csrf
                
                <!-- Step 1: Enter Password -->
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-[#2c3e66] rounded-full flex items-center justify-center text-white font-bold text-sm">1</div>
                        <h6 class="font-semibold text-[#1a2a4a]">Enter your current password</h6>
                    </div>
                    <input type="password" class="glass-input w-full rounded-xl px-4 py-2.5 @error('password') border-red-500 ring-red-500 @enderror" 
                           id="password" name="password" placeholder="Enter your password" required>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Step 2: Confirmation Text -->
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 bg-[#2c3e66] rounded-full flex items-center justify-center text-white font-bold text-sm">2</div>
                        <h6 class="font-semibold text-[#1a2a4a]">Type <strong class="text-red-600">"DELETE MY ACCOUNT"</strong> to confirm</h6>
                    </div>
                    <input type="text" class="glass-input w-full rounded-xl px-4 py-2.5 @error('confirmation_text') border-red-500 ring-red-500 @enderror" 
                           id="confirmation_text" name="confirmation_text" 
                           placeholder="Type: DELETE MY ACCOUNT" required>
                    <p class="text-xs text-[#4a5568] mt-1">Please type exactly as shown (uppercase)</p>
                    @error('confirmation_text')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Warning Checkbox -->
                <div class="mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="confirmCheck" class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500" required>
                        <span class="text-sm text-[#4a5568]">
                            I understand that this action is <strong class="text-red-600">permanent and irreversible</strong>
                        </span>
                    </label>
                </div>
                
                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="btn-danger flex-1 py-2.5 rounded-xl font-semibold text-center" id="deleteButton" disabled>
                        <i class="fas fa-trash-alt mr-2"></i>Permanently Delete My Account
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn-outline flex-1 py-2.5 rounded-xl font-semibold text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Cancel & Keep My Account
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Need Help Card -->
    <div class="glass-card rounded-2xl mt-6 overflow-hidden">
        <div class="p-6">
            <h6 class="font-semibold text-[#1a2a4a] mb-2 flex items-center gap-2">
                <i class="fas fa-question-circle text-[#2c3e66]"></i>
                Need Help?
            </h6>
            <p class="text-sm text-[#4a5568]">
                If you're experiencing issues or have questions about account deletion, please contact our support team at 
                <strong class="text-[#2c3e66]">support@alumni.com</strong> or call us at <strong class="text-[#2c3e66]">(082) 123-4567</strong>.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Enable delete button only when checkbox is checked and confirmation text is correct
    const confirmCheck = document.getElementById('confirmCheck');
    const confirmationText = document.getElementById('confirmation_text');
    const deleteButton = document.getElementById('deleteButton');
    const passwordInput = document.getElementById('password');
    
    function validateForm() {
        const isChecked = confirmCheck.checked;
        const isCorrectText = confirmationText.value === 'DELETE MY ACCOUNT';
        const hasPassword = passwordInput.value.length > 0;
        
        deleteButton.disabled = !(isChecked && isCorrectText && hasPassword);
        
        // Update button styling
        if (deleteButton.disabled) {
            deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
            deleteButton.classList.remove('hover:transform', 'hover:shadow-lg');
        } else {
            deleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
            deleteButton.classList.add('hover:transform', 'hover:shadow-lg');
        }
    }
    
    confirmCheck.addEventListener('change', validateForm);
    confirmationText.addEventListener('keyup', validateForm);
    passwordInput.addEventListener('keyup', validateForm);
    
    // Additional confirmation on form submit
    document.getElementById('deleteAccountForm').addEventListener('submit', function(e) {
        if (!confirm('⚠️ WARNING: This action is permanent and cannot be undone!\n\nAre you absolutely sure you want to delete your account?')) {
            e.preventDefault();
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
    
    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        transition: all 0.3s ease;
        color: white;
    }
    
    .btn-danger:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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
    
    .font-display {
        font-family: 'Poppins', system-ui, sans-serif;
    }
</style>
@endsection
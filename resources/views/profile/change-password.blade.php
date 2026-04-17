@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Change Password Card -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                <i class="fas fa-key mr-2"></i>Change Password
            </h4>
            <p class="text-sm text-[#4a5568] mt-1">Update your password to keep your account secure</p>
        </div>
        
        <div class="p-6">
            <form method="POST" action="{{ route('change-password.update') }}" id="passwordForm">
                @csrf
                
                <!-- Current Password -->
                <div class="mb-6">
                    <label for="current_password" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                        <i class="fas fa-lock mr-2"></i>Current Password
                    </label>
                    <input type="password" class="glass-input w-full rounded-xl px-4 py-2.5 @error('current_password') border-red-500 ring-red-500 @enderror" 
                           id="current_password" name="current_password" required>
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- New Password -->
                <div class="mb-6">
                    <label for="new_password" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                        <i class="fas fa-key mr-2"></i>New Password
                    </label>
                    <input type="password" class="glass-input w-full rounded-xl px-4 py-2.5 @error('new_password') border-red-500 ring-red-500 @enderror" 
                           id="new_password" name="new_password" required>
                    @error('new_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                    <!-- Password Requirements -->
                    <div class="mt-3 p-3 bg-gray-50 rounded-xl">
                        <p class="text-xs font-semibold text-[#4a5568] mb-2">Password requirements:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div id="req-length" class="requirement-unmet flex items-center gap-2 text-xs">
                                <i class="fas fa-circle text-[8px]"></i>
                                <span>At least 8 characters</span>
                            </div>
                            <div id="req-upper" class="requirement-unmet flex items-center gap-2 text-xs">
                                <i class="fas fa-circle text-[8px]"></i>
                                <span>At least 1 uppercase letter</span>
                            </div>
                            <div id="req-lower" class="requirement-unmet flex items-center gap-2 text-xs">
                                <i class="fas fa-circle text-[8px]"></i>
                                <span>At least 1 lowercase letter</span>
                            </div>
                            <div id="req-number" class="requirement-unmet flex items-center gap-2 text-xs">
                                <i class="fas fa-circle text-[8px]"></i>
                                <span>At least 1 number</span>
                            </div>
                            <div id="req-symbol" class="requirement-unmet flex items-center gap-2 text-xs">
                                <i class="fas fa-circle text-[8px]"></i>
                                <span>At least 1 symbol</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Confirm New Password -->
                <div class="mb-6">
                    <label for="new_password_confirmation" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                        <i class="fas fa-check-circle mr-2"></i>Confirm New Password
                    </label>
                    <input type="password" class="glass-input w-full rounded-xl px-4 py-2.5" 
                           id="new_password_confirmation" name="new_password_confirmation" required>
                    <div id="password-match" class="mt-2 text-sm"></div>
                </div>
                
                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="btn-primary flex-1 py-2.5 rounded-xl font-semibold text-center">
                        <i class="fas fa-save mr-2"></i>Change Password
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn-outline flex-1 py-2.5 rounded-xl font-semibold text-center">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Security Tips Card -->
    <div class="glass-card rounded-2xl mt-6">
        <div class="p-6">
            <h6 class="font-semibold text-[#1a2a4a] mb-3 flex items-center">
                <i class="fas fa-shield-alt mr-2 text-[#2c3e66]"></i>
                Password Security Tips
            </h6>
            <ul class="space-y-2 text-sm text-[#4a5568]">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                    <span>Use a unique password that you don't use for other accounts</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                    <span>Avoid using personal information like your name or birthday</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                    <span>Consider using a password manager to generate and store strong passwords</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-emerald-500 mt-0.5"></i>
                    <span>Change your password regularly (every 3-6 months)</span>
                </li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Real-time password validation
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const passwordMatch = document.getElementById('password-match');
    
    // Requirement elements
    const reqLength = document.getElementById('req-length');
    const reqUpper = document.getElementById('req-upper');
    const reqLower = document.getElementById('req-lower');
    const reqNumber = document.getElementById('req-number');
    const reqSymbol = document.getElementById('req-symbol');
    
    function validatePassword() {
        const password = newPassword.value;
        
        // Check length
        if (password.length >= 8) {
            reqLength.classList.add('requirement-met');
            reqLength.classList.remove('requirement-unmet');
            reqLength.innerHTML = '<i class="fas fa-check-circle text-emerald-500 text-xs mr-2"></i><span>At least 8 characters</span>';
        } else {
            reqLength.classList.add('requirement-unmet');
            reqLength.classList.remove('requirement-met');
            reqLength.innerHTML = '<i class="fas fa-circle text-gray-300 text-xs mr-2"></i><span>At least 8 characters</span>';
        }
        
        // Check uppercase
        if (/[A-Z]/.test(password)) {
            reqUpper.classList.add('requirement-met');
            reqUpper.classList.remove('requirement-unmet');
            reqUpper.innerHTML = '<i class="fas fa-check-circle text-emerald-500 text-xs mr-2"></i><span>At least 1 uppercase letter</span>';
        } else {
            reqUpper.classList.add('requirement-unmet');
            reqUpper.classList.remove('requirement-met');
            reqUpper.innerHTML = '<i class="fas fa-circle text-gray-300 text-xs mr-2"></i><span>At least 1 uppercase letter</span>';
        }
        
        // Check lowercase
        if (/[a-z]/.test(password)) {
            reqLower.classList.add('requirement-met');
            reqLower.classList.remove('requirement-unmet');
            reqLower.innerHTML = '<i class="fas fa-check-circle text-emerald-500 text-xs mr-2"></i><span>At least 1 lowercase letter</span>';
        } else {
            reqLower.classList.add('requirement-unmet');
            reqLower.classList.remove('requirement-met');
            reqLower.innerHTML = '<i class="fas fa-circle text-gray-300 text-xs mr-2"></i><span>At least 1 lowercase letter</span>';
        }
        
        // Check number
        if (/[0-9]/.test(password)) {
            reqNumber.classList.add('requirement-met');
            reqNumber.classList.remove('requirement-unmet');
            reqNumber.innerHTML = '<i class="fas fa-check-circle text-emerald-500 text-xs mr-2"></i><span>At least 1 number</span>';
        } else {
            reqNumber.classList.add('requirement-unmet');
            reqNumber.classList.remove('requirement-met');
            reqNumber.innerHTML = '<i class="fas fa-circle text-gray-300 text-xs mr-2"></i><span>At least 1 number</span>';
        }
        
        // Check symbol
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            reqSymbol.classList.add('requirement-met');
            reqSymbol.classList.remove('requirement-unmet');
            reqSymbol.innerHTML = '<i class="fas fa-check-circle text-emerald-500 text-xs mr-2"></i><span>At least 1 symbol</span>';
        } else {
            reqSymbol.classList.add('requirement-unmet');
            reqSymbol.classList.remove('requirement-met');
            reqSymbol.innerHTML = '<i class="fas fa-circle text-gray-300 text-xs mr-2"></i><span>At least 1 symbol</span>';
        }
    }
    
    function checkPasswordMatch() {
        if (confirmPassword.value.length > 0) {
            if (newPassword.value === confirmPassword.value) {
                passwordMatch.innerHTML = '<i class="fas fa-check-circle text-emerald-500 mr-1"></i> Passwords match';
                passwordMatch.classList.add('text-emerald-600');
                passwordMatch.classList.remove('text-red-600');
            } else {
                passwordMatch.innerHTML = '<i class="fas fa-times-circle text-red-500 mr-1"></i> Passwords do not match';
                passwordMatch.classList.add('text-red-600');
                passwordMatch.classList.remove('text-emerald-600');
            }
        } else {
            passwordMatch.innerHTML = '';
        }
    }
    
    newPassword.addEventListener('keyup', validatePassword);
    newPassword.addEventListener('keyup', checkPasswordMatch);
    confirmPassword.addEventListener('keyup', checkPasswordMatch);
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
    
    .requirement-met {
        color: #10b981;
    }
    
    .requirement-unmet {
        color: #9ca3af;
    }
    
    .font-display {
        font-family: 'Poppins', system-ui, sans-serif;
    }
</style>
@endsection
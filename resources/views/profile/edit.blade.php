@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h4 class="font-display font-bold text-xl text-[#1a2a4a]">
                <i class="fas fa-edit mr-2"></i>Edit Profile
            </h4>
            <p class="text-sm text-[#4a5568] mt-1">Update your profile information and social media links</p>
        </div>
        
        <div class="p-6">
            @if(!$user->profile)
                <div class="bg-amber-50 border-l-4 border-amber-500 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle text-amber-500"></i>
                        <span class="text-amber-700 text-sm">You don't have a profile yet. Please complete your profile information.</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Profile Picture -->
                <div class="mb-6 text-center">
                    <div class="relative inline-block">
                        @if($user->profile && $user->profile->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" 
                                 alt="Profile Picture" 
                                 class="w-28 h-28 rounded-full object-cover border-3 border-[#2c3e66] shadow-md">
                        @else
                            <div class="w-28 h-28 rounded-full bg-gradient-to-br from-[#2c3e66] to-[#4a627a] flex items-center justify-center shadow-md">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                        <label for="profile_picture" class="absolute bottom-0 right-0 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-md cursor-pointer hover:bg-gray-100 transition">
                            <i class="fas fa-camera text-[#2c3e66] text-sm"></i>
                        </label>
                        <input type="file" class="hidden" id="profile_picture" name="profile_picture" accept="image/*">
                    </div>
                    <p class="text-xs text-[#4a5568] mt-2">Max 2MB (JPG, PNG, GIF)</p>
                </div>

                <!-- Name Fields (Disabled) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#1a2a4a] mb-2">First Name</label>
                        <input type="text" class="glass-input w-full rounded-xl px-4 py-2.5 bg-gray-100 cursor-not-allowed" 
                               value="{{ $user->first_name }}" disabled>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-[#1a2a4a] mb-2">Last Name</label>
                        <input type="text" class="glass-input w-full rounded-xl px-4 py-2.5 bg-gray-100 cursor-not-allowed" 
                               value="{{ $user->last_name }}" disabled>
                    </div>
                </div>

                <!-- Email (Disabled) -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-[#1a2a4a] mb-2">Email Address</label>
                    <input type="email" class="glass-input w-full rounded-xl px-4 py-2.5 bg-gray-100 cursor-not-allowed" 
                           value="{{ $user->email }}" disabled>
                </div>

                <!-- Course -->
                <div class="mb-4">
                    <label for="course" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                        <i class="fas fa-book mr-2"></i>Course
                    </label>
                    <input type="text" class="glass-input w-full rounded-xl px-4 py-2.5 @error('course') border-red-500 ring-red-500 @enderror" 
                           id="course" name="course" value="{{ old('course', $user->profile->course ?? '') }}" required>
                    @error('course')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Year Graduated -->
                <div class="mb-4">
                    <label for="year_graduated" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                        <i class="fas fa-calendar-alt mr-2"></i>Year Graduated
                    </label>
                    <select class="glass-input w-full rounded-xl px-4 py-2.5 @error('year_graduated') border-red-500 ring-red-500 @enderror" 
                            id="year_graduated" name="year_graduated" required>
                        <option value="">Select Year</option>
                        @for($year = date('Y'); $year >= date('Y')-50; $year--)
                            <option value="{{ $year }}" {{ old('year_graduated', $user->profile->year_graduated ?? '') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                    @error('year_graduated')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Birthday -->
                <div class="mb-4">
                    <label for="birthday" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                        <i class="fas fa-birthday-cake mr-2"></i>Birthday (Optional)
                    </label>
                    <input type="date" class="glass-input w-full rounded-xl px-4 py-2.5" 
                        id="birthday" name="birthday" value="{{ old('birthday', $user->profile->birthday ?? '') }}">
                    <p class="text-xs text-[#4a5568] mt-1">Enter your birth date</p>
                </div>

                <!-- Address -->
                <div class="mb-4">
                    <label for="address" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                        <i class="fas fa-home mr-2"></i>Address (Optional)
                    </label>
                    <textarea class="glass-input w-full rounded-xl px-4 py-2.5" 
                            id="address" name="address" rows="2" placeholder="Enter your complete address">{{ old('address', $user->profile->address ?? '') }}</textarea>
                    <p class="text-xs text-[#4a5568] mt-1">Street, City, Province, Postal Code</p>
                </div>

                <!-- Contact Number -->
                <div class="mb-4">
                    <label for="contact_number" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                        <i class="fas fa-phone mr-2"></i>Contact Number (Optional)
                    </label>
                    <input type="text" class="glass-input w-full rounded-xl px-4 py-2.5" 
                           id="contact_number" name="contact_number" value="{{ old('contact_number', $user->profile->contact_number ?? '') }}">
                    <p class="text-xs text-[#4a5568] mt-1">Enter your mobile or phone number</p>
                </div>

                <!-- Job Title -->
                <div class="mb-6">
                    <label for="job_title" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                        <i class="fas fa-briefcase mr-2"></i>Job Title (Optional)
                    </label>
                    <input type="text" class="glass-input w-full rounded-xl px-4 py-2.5" 
                           id="job_title" name="job_title" value="{{ old('job_title', $user->profile->job_title ?? '') }}">
                    <p class="text-xs text-[#4a5568] mt-1">e.g., Software Engineer, Manager, etc.</p>
                </div>

                <hr class="my-6 border-gray-200">

                <!-- Privacy Settings -->
                <hr class="my-6 border-gray-200">

                <h5 class="font-display font-semibold text-lg text-[#1a2a4a] mb-4">
                    <i class="fas fa-lock mr-2"></i>Privacy Settings
                </h5>
                <p class="text-sm text-[#4a5568] mb-4">Control who can see your personal information on your profile.</p>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div>
                            <label class="font-semibold text-[#1a2a4a] text-sm">Contact Number</label>
                            <p class="text-xs text-[#4a5568]">Show your contact number to other alumni</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_contact_number" class="sr-only peer" value="1" 
                                {{ old('show_contact_number', $user->profile->show_contact_number ?? true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div>
                            <label class="font-semibold text-[#1a2a4a] text-sm">Birthday</label>
                            <p class="text-xs text-[#4a5568]">Show your birthday to other alumni</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_birthday" class="sr-only peer" value="1" 
                                {{ old('show_birthday', $user->profile->show_birthday ?? true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div>
                            <label class="font-semibold text-[#1a2a4a] text-sm">Address</label>
                            <p class="text-xs text-[#4a5568]">Show your address to other alumni</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="show_address" class="sr-only peer" value="1" 
                                {{ old('show_address', $user->profile->show_address ?? true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </div>

                <div class="mt-3 p-3 bg-blue-50 rounded-xl">
                    <p class="text-xs text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        When these options are turned off, other alumni will see "Private" instead of your information.
                        You will always be able to see your own information.
                    </p>
                </div>

                <!-- Social Media Links -->
                <h5 class="font-display font-semibold text-lg text-[#1a2a4a] mb-4">
                    <i class="fas fa-share-alt mr-2"></i>Social Media Links
                </h5>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Facebook -->
                    <div class="mb-3">
                        <label for="facebook" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                            <i class="fab fa-facebook mr-2 text-blue-600"></i>Facebook
                        </label>
                        <input type="text" class="glass-input w-full rounded-xl px-4 py-2.5" 
                               id="facebook" name="facebook" placeholder="username or full URL"
                               value="{{ old('facebook', $user->profile->facebook ?? '') }}">
                        <p class="text-xs text-[#4a5568] mt-1">Enter your Facebook username or full profile URL</p>
                    </div>

                    <!-- Twitter -->
                    <div class="mb-3">
                        <label for="twitter" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                            <i class="fab fa-twitter mr-2 text-sky-500"></i>Twitter/X
                        </label>
                        <input type="text" class="glass-input w-full rounded-xl px-4 py-2.5" 
                               id="twitter" name="twitter" placeholder="username or full URL"
                               value="{{ old('twitter', $user->profile->twitter ?? '') }}">
                        <p class="text-xs text-[#4a5568] mt-1">Enter your Twitter username or full profile URL</p>
                    </div>

                    <!-- Instagram -->
                    <div class="mb-3">
                        <label for="instagram" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                            <i class="fab fa-instagram mr-2 text-pink-600"></i>Instagram
                        </label>
                        <input type="text" class="glass-input w-full rounded-xl px-4 py-2.5" 
                               id="instagram" name="instagram" placeholder="username or full URL"
                               value="{{ old('instagram', $user->profile->instagram ?? '') }}">
                        <p class="text-xs text-[#4a5568] mt-1">Enter your Instagram username or full profile URL</p>
                    </div>

                    <!-- LinkedIn -->
                    <div class="mb-3">
                        <label for="linkedin" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                            <i class="fab fa-linkedin mr-2 text-blue-700"></i>LinkedIn
                        </label>
                        <input type="text" class="glass-input w-full rounded-xl px-4 py-2.5" 
                               id="linkedin" name="linkedin" placeholder="username or full URL"
                               value="{{ old('linkedin', $user->profile->linkedin ?? '') }}">
                        <p class="text-xs text-[#4a5568] mt-1">Enter your LinkedIn username or full profile URL</p>
                    </div>

                    <!-- GitHub -->
                    <div class="mb-3">
                        <label for="github" class="block text-sm font-semibold text-[#1a2a4a] mb-2">
                            <i class="fab fa-github mr-2 text-gray-700"></i>GitHub
                        </label>
                        <input type="text" class="glass-input w-full rounded-xl px-4 py-2.5" 
                               id="github" name="github" placeholder="username or full URL"
                               value="{{ old('github', $user->profile->github ?? '') }}">
                        <p class="text-xs text-[#4a5568] mt-1">Enter your GitHub username or full profile URL</p>
                    </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="submit" class="btn-primary flex-1 py-2.5 rounded-xl font-semibold text-center">
                        <i class="fas fa-save mr-2"></i>Update Profile
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn-outline flex-1 py-2.5 rounded-xl font-semibold text-center">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview profile picture before upload
    document.getElementById('profile_picture').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = document.querySelector('.rounded-full');
                if (img && img.tagName === 'IMG') {
                    img.src = event.target.result;
                } else {
                    // If no image exists, create one
                    const container = document.querySelector('.relative.inline-block');
                    const newImg = document.createElement('img');
                    newImg.src = event.target.result;
                    newImg.className = 'w-28 h-28 rounded-full object-cover border-3 border-[#2c3e66] shadow-md';
                    container.appendChild(newImg);
                    // Remove the placeholder div
                    const placeholder = container.querySelector('.rounded-full.bg-gradient-to-br');
                    if (placeholder) placeholder.remove();
                }
            }
            reader.readAsDataURL(file);
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
        border-color: #2c3e66;
        box-shadow: 0 0 0 3px rgba(44, 62, 102, 0.1);
    }
    
    .glass-input:disabled {
        background: #f3f4f6;
        cursor: not-allowed;
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
    
    .font-display {
        font-family: 'Poppins', system-ui, sans-serif;
    }
</style>
@endsection
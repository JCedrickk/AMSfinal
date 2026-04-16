<x-app-layout>
    <div class="py-8 px-4 max-w-4xl mx-auto">
        
        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Create New Post</h1>
            <p class="text-gray-500 mt-1">Share announcements, events, or job opportunities</p>
        </div>

        {{-- Create Post Form --}}
        <div class="glass-card rounded-2xl p-8">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- Post Type Selection --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Post Type</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="type" value="announcement" checked class="text-indigo-600">
                            <span class="text-gray-700">Announcement</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="type" value="event" class="text-indigo-600">
                            <span class="text-gray-700">Event</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="type" value="job" class="text-indigo-600">
                            <span class="text-gray-700">Job Opportunity</span>
                        </label>
                    </div>
                </div>

                {{-- Title --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
                    <input type="text" 
                           name="title" 
                           required 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:outline-none"
                           placeholder="Enter post title">
                </div>

                {{-- Content --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Content</label>
                    <textarea name="content" 
                              rows="8" 
                              required 
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:outline-none"
                              placeholder="Write your post content here..."></textarea>
                </div>

                {{-- Image Upload --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Image (Optional)</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-indigo-500 transition">
                        <input type="file" name="image" id="image" class="hidden" accept="image/*">
                        <label for="image" class="cursor-pointer">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-500">Click to upload image</p>
                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF up to 2MB</p>
                        </label>
                    </div>
                </div>

                {{-- Location --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Location (Optional)</label>
                    <input type="text" 
                           name="location" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:outline-none"
                           placeholder="Enter location">
                </div>

                {{-- Event Date (shown only for events) --}}
                <div class="mb-6 event-field hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Event Date & Time</label>
                    <input type="datetime-local" 
                           name="event_date" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:outline-none">
                </div>

                {{-- Job Fields (shown only for jobs) --}}
                <div class="mb-6 job-field hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Company</label>
                    <input type="text" 
                           name="company" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:outline-none"
                           placeholder="Company name">
                </div>

                <div class="mb-6 job-field hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Salary (Optional)</label>
                    <input type="text" 
                           name="salary" 
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:outline-none"
                           placeholder="e.g., ₱30,000 - ₱50,000">
                </div>

                {{-- Submit Buttons --}}
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium hover:shadow-lg transition">
                        Publish Post
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Show/hide fields based on post type
        const radioButtons = document.querySelectorAll('input[name="type"]');
        const eventFields = document.querySelectorAll('.event-field');
        const jobFields = document.querySelectorAll('.job-field');
        
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'event') {
                    eventFields.forEach(field => field.classList.remove('hidden'));
                    jobFields.forEach(field => field.classList.add('hidden'));
                } else if (this.value === 'job') {
                    eventFields.forEach(field => field.classList.add('hidden'));
                    jobFields.forEach(field => field.classList.remove('hidden'));
                } else {
                    eventFields.forEach(field => field.classList.add('hidden'));
                    jobFields.forEach(field => field.classList.add('hidden'));
                }
            });
        });
        
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.querySelector('.border-dashed');
                    preview.innerHTML = `<img src="${event.target.result}" class="max-h-48 mx-auto rounded-lg">`;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>
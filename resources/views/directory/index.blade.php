<x-app-layout>
    <div class="py-8 px-4 max-w-7xl mx-auto">
        
        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Alumni Directory</h1>
            <p class="text-gray-500 mt-1">Connect with fellow Brokenshirians</p>
        </div>

        {{-- Search and Filter --}}
        <div class="glass-card rounded-xl p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" 
                           id="searchInput"
                           placeholder="Search by name, course, or batch..."
                           class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:outline-none">
                </div>
                <div class="w-full md:w-48">
                    <select id="batchFilter" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:outline-none">
                        <option value="">All Batches</option>
                        @for($year = date('Y'); $year >= 1970; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                <div class="w-full md:w-48">
                    <select id="courseFilter" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-indigo-500 focus:outline-none">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course }}">{{ $course }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Alumni Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="alumniGrid">
            @foreach($alumni as $member)
                <div class="glass-card rounded-xl p-6 hover:shadow-lg transition alumni-card"
                     data-name="{{ strtolower($member->first_name . ' ' . $member->last_name) }}"
                     data-batch="{{ $member->year_graduated }}"
                     data-course="{{ strtolower($member->profile->course ?? '') }}">
                    
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto rounded-full overflow-hidden bg-gradient-to-br from-indigo-500 to-purple-600 mb-4">
                            @if($member->profile_picture)
                                <img src="{{ asset('storage/' . $member->profile_picture) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-white text-3xl font-bold">
                                    {{ strtoupper(substr($member->first_name, 0, 1)) }}{{ strtoupper(substr($member->last_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $member->first_name }} {{ $member->last_name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $member->profile->course ?? 'Course not specified' }}</p>
                        <p class="text-xs text-gray-400">Batch {{ $member->year_graduated }}</p>
                        
                        @if($member->profile->job_title || $member->profile->company)
                            <p class="text-sm text-gray-600 mt-2">
                                {{ $member->profile->job_title ?? '' }} @if($member->profile->job_title && $member->profile->company) at @endif {{ $member->profile->company ?? '' }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex justify-center gap-3">
                            @if($member->profile->facebook_link)
                                <a href="{{ $member->profile->facebook_link }}" target="_blank" class="text-gray-500 hover:text-blue-600 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($member->profile->linkedin_link)
                                <a href="{{ $member->profile->linkedin_link }}" target="_blank" class="text-gray-500 hover:text-blue-700 transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/>
                                        <rect x="2" y="9" width="4" height="12"/>
                                        <circle cx="4" cy="4" r="2"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                        <a href="{{ route('profile.public', $member->user_id) }}" class="block mt-3 text-center text-sm text-indigo-600 hover:text-indigo-700">
                            View Profile →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if($alumni->isEmpty())
            <div class="glass-card rounded-xl p-12 text-center">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <p class="text-gray-500">No alumni found</p>
            </div>
        @endif
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', filterAlumni);
        document.getElementById('batchFilter').addEventListener('change', filterAlumni);
        document.getElementById('courseFilter').addEventListener('change', filterAlumni);

        function filterAlumni() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const batch = document.getElementById('batchFilter').value;
            const course = document.getElementById('courseFilter').value.toLowerCase();
            const cards = document.querySelectorAll('.alumni-card');
            
            cards.forEach(card => {
                const name = card.dataset.name;
                const cardBatch = card.dataset.batch;
                const cardCourse = card.dataset.course;
                
                const matchesSearch = name.includes(searchTerm);
                const matchesBatch = !batch || cardBatch == batch;
                const matchesCourse = !course || cardCourse.includes(course);
                
                if (matchesSearch && matchesBatch && matchesCourse) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
{{-- Social Media Links --}}
@if($user->profile)
<div class="mt-6 pt-6 border-t border-gray-200">
    <h3 class="text-sm font-semibold text-gray-700 mb-3">Connect on Social Media</h3>
    <div class="flex gap-4">
        @if($user->profile->facebook_link)
            <a href="{{ $user->profile->facebook_link }}" target="_blank" class="text-blue-600 hover:text-blue-800 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                </svg>
            </a>
        @endif
        @if($user->profile->twitter_link)
            <a href="{{ $user->profile->twitter_link }}" target="_blank" class="text-blue-400 hover:text-blue-600 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                </svg>
            </a>
        @endif
        @if($user->profile->instagram_link)
            <a href="{{ $user->profile->instagram_link }}" target="_blank" class="text-pink-600 hover:text-pink-800 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                    <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/>
                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                </svg>
            </a>
        @endif
        @if($user->profile->linkedin_link)
            <a href="{{ $user->profile->linkedin_link }}" target="_blank" class="text-blue-700 hover:text-blue-900 transition">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/>
                    <rect x="2" y="9" width="4" height="12"/>
                    <circle cx="4" cy="4" r="2"/>
                </svg>
            </a>
        @endif
    </div>
</div>
@endif
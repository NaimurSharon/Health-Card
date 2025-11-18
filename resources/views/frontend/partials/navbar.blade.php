<header class="bg-white dark:bg-gray-900 border-b dark:border-gray-700 shadow-sm sticky top-0 z-50 transition-colors duration-300">
    <div class="container mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-4">

        <!-- Logo Title & Tagline -->
        <div class="flex flex-col">
            @php
                $siteTitle = setting('site_title', 'আমার দেশ');
                $titleParts = explode(' ', $siteTitle, 2);
            @endphp
            
            <span class="text-3xl md:text-4xl font-extrabold text-dark dark:text-white leading-tight">
                {{ $titleParts[0] ?? '' }} 
                @if(isset($titleParts[1]))
                    <span class="text-red-600 dark:text-red-600">{{ $titleParts[1] }}</span>
                @endif
            </span>
            
            @if(setting('site_tagline'))
                <small class="text-sm text-gray-500 dark:text-white hidden md:inline-block">
                    {{ setting('site_tagline') }}
                </small>
            @endif
        </div>

        <!-- Social Icons -->
        <div class="flex items-center gap-4 text-2xl md:text-3xl">
            @if(setting('facebook_url'))
                <a href="{{ setting('facebook_url') }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-500 dark:hover:text-blue-400 transition"><i class="fab fa-facebook-square"></i></a>
            @endif
            @if(setting('tiktok_url'))
                <a href="{{ setting('tiktok_url') }}" target="_blank" class="text-black hover:text-gray-700 dark:text-gray-300 dark:hover:text-white transition"><i class="fab fa-tiktok"></i></a>
            @endif
            @if(setting('whatsapp_url'))
                <a href="{{ setting('whatsapp_url') }}" target="_blank" class="text-green-500 hover:text-green-600 dark:hover:text-green-400 transition"><i class="fab fa-whatsapp"></i></a>
            @endif
            @if(setting('pinterest_url'))
                <a href="{{ setting('pinterest_url') }}" target="_blank" class="text-red-500 hover:text-red-600 dark:hover:text-red-400 transition"><i class="fab fa-pinterest"></i></a>
            @endif
            @if(setting('youtube_url'))
                <a href="{{ setting('youtube_url') }}" target="_blank" class="text-red-600 hover:text-red-700 dark:hover:text-red-500 transition"><i class="fab fa-youtube"></i></a>
            @endif
            @if(setting('instagram_url'))
                <a href="{{ setting('instagram_url') }}" target="_blank" class="text-pink-500 hover:text-pink-600 dark:hover:text-pink-400 transition"><i class="fab fa-instagram"></i></a>
            @endif
            @if(setting('linkedin_url'))
                <a href="{{ setting('linkedin_url') }}" target="_blank" class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-400 transition"><i class="fab fa-linkedin"></i></a>
            @endif
        </div>

    </div>
</header>
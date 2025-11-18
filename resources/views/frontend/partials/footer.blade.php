<footer class="bg-white dark:bg-gray-900 border-t dark:border-gray-700 shadow-inner mt-10 transition-colors duration-300">
    <div class="container mx-auto px-4 py-6 md:py-8 text-center">

        <!-- Social Icons -->
        <div class="flex justify-center gap-6 text-2xl md:text-3xl mb-4">
            @if(setting('facebook_url'))
                <a href="{{ setting('facebook_url') }}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:hover:text-blue-400 transition"><i class="fab fa-facebook-square"></i></a>
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
        
        @if(setting('footer_links'))
            <div class="mt-4 flex flex-wrap justify-center gap-4 text-sm">
                @foreach(json_decode(setting('footer_links'), true) as $link)
                    <a href="{{ $link['url'] }}" class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 transition">
                        {{ $link['text'] }}
                    </a>
                @endforeach
            </div>
        @endif
        
        <!-- Copyright -->
        <p class="text-gray-600 dark:text-gray-400 text-sm">
            &copy; {{ date('Y') }} <strong class="text-gray-800 dark:text-gray-100">{{ setting('site_title', 'বাংলাদেশ ই-পেপার') }}</strong>. সর্বস্বত্ব সংরক্ষিত।
        </p>
        
    </div>
</footer>
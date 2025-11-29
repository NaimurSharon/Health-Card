{{-- ===========================
         HERO SECTION
    ============================ --}}
<section class="relative bg-white tiro rounded-3xl shadow-xl overflow-hidden">

<div class="px-4 py-6 sm:py-8 md:px-8 md:py-12 lg:px-16 lg:py-16">
    <!-- Main Hero Layout -->
    <div class="flex flex-col lg:flex-row items-stretch justify-between gap-6">
        <!-- YouTube Video Section -->
        <div class="w-full lg:w-2/3 lg:h-full mb-6 lg:mb-0">
<<<<<<< HEAD
            @if(website_setting('hero', 'youtube_playlist_id'))
            <div class="bg-black rounded-2xl overflow-hidden shadow-2xl h-full flex flex-col">
                <div class="flex-1">
                    <iframe 
                        class="w-full h-full min-h-[500px]"
                        src="https://www.youtube.com/embed?listType=playlist&list={{ website_setting('hero', 'youtube_playlist_id') }}&autoplay={{ website_setting('hero', 'youtube_auto_play') ? 1 : 0 }}&rel=0&modestbranding=1"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen>
=======
            @if(website_setting('hero', 'youtube_playlist_url'))
            <div class="bg-black rounded-2xl overflow-hidden shadow-2xl h-full flex flex-col">
                <div class="flex-1">
                    <iframe 
                    class="w-full h-full min-h-[500px]" 
                    src="https://www.youtube.com/embed/videoseries?si=Kp5eZixfMM6HSTnx&amp;list={{ website_setting('hero', 'youtube_playlist_id') }}" 
                    title="YouTube video player" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen>
>>>>>>> c356163 (video call ui setup)
                    </iframe>
                </div>
            </div>
            
            <!-- Subtitle Section Below YouTube Video -->
            @if(website_setting('hero', 'hero_subtitle'))
            <div class="mt-6">
                <div class="bg-gradient-to-br from-gray-50 to-white rounded-2xl p-6 md:p-8 text-start shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                    <p class="text-base md:text-lg text-gray-700 tiro leading-relaxed">
                        {!! nl2br(e(website_setting('hero', 'hero_subtitle'))) !!}
                    </p>
                </div>
            </div>
            @endif
            
            @else
            <!-- Fallback School Image -->
            <div class="h-full rounded-2xl overflow-hidden shadow-2xl">
                <img src="{{ $school->cover_image ? asset('public/storage/' . $school->cover_image) : asset('images/school-hero.png') }}" 
                     alt="{{ $school->name }}" 
                     class="w-full h-full object-cover">
            </div>
            @endif
        </div>

        @php
            $ministers = website_setting('ministers', 'ministers_list', []);
            $displayCount = website_setting('ministers', 'display_count', 6);
            $sectionTitle = website_setting('ministers', 'section_title', 'আমাদের সম্মানিত মন্ত্রীবর্গ');
<<<<<<< HEAD
        
            // Determine PC columns dynamically
            $pcColumns = ($displayCount == 3) ? 3 : 2;
        @endphp
        
        @if(count($ministers) > 0)
=======
            
            // Add principal as the last item
            $principalData = [
                'name' => $school->principal ? $school->principal->name : 'প্রধান শিক্ষক',
                'ministry' => 'প্রধান শিক্ষক',
                'image_link' => $school->principal ? $school->principal->profile_image : null
            ];
            
            // Add principal to ministers list
            $allDisplayItems = array_merge($ministers, [$principalData]);
            $totalDisplayCount = min(count($allDisplayItems), $displayCount);
            
            // Determine PC columns dynamically
            $pcColumns = ($totalDisplayCount == 3) ? 3 : 2;
        @endphp
        
        @if(count($allDisplayItems) > 0)
>>>>>>> c356163 (video call ui setup)
        <div class="w-full lg:w-1/3 h-auto lg:h-full">
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-3xl p-6 md:p-8 h-full flex flex-col shadow-xl">
        
                <!-- Section Header -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-3">{{ $sectionTitle }}</h2>
<<<<<<< HEAD
                    <p class="text-gray-600 text-lg">দেশের উন্নয়নে নিবেদিত প্রাণ</p>
=======
>>>>>>> c356163 (video call ui setup)
                </div>
        
                <!-- Ministers Grid -->
                <div class="flex-1">
                    <div class="
                        grid 
                        grid-cols-2           <!-- Mobile: 2 columns -->
                        sm:grid-cols-2        <!-- Small tablets: 2 columns -->
                        md:grid-cols-3        <!-- Tablets: 3 columns -->
                        lg:grid-cols-{{ $pcColumns }}  <!-- Desktop: Dynamic columns -->
                        gap-4 md:gap-6
                        justify-items-center
                    ">
<<<<<<< HEAD
                        @foreach(array_slice($ministers, 0, $displayCount) as $minister)
                        <div class="text-center group w-full max-w-[180px] sm:max-w-[200px] md:max-w-none">
                            <div class="relative inline-block mb-4">
        
                                <!-- Minister Image - Large and consistent across devices -->
=======
                        @foreach(array_slice($allDisplayItems, 0, $totalDisplayCount) as $index => $minister)
                        <div class="text-center group w-full max-w-[180px] sm:max-w-[200px] md:max-w-none">
                            <div class="relative inline-block mb-4">
        
                                <!-- Minister/Principal Image - Large and consistent across devices -->
>>>>>>> c356163 (video call ui setup)
                                <div class="
                                    w-28 h-28     <!-- Mobile: 112px -->
                                    sm:w-32 sm:h-32 <!-- Small: 128px -->
                                    md:w-36 md:h-36 <!-- Tablet: 144px -->
                                    lg:w-30 lg:h-30 <!-- Desktop: 160px -->
                                    xl:w-45 xl:h-45 <!-- Large Desktop: 192px -->
                                    mx-auto 
                                    bg-white 
                                    rounded-full 
                                    overflow-hidden 
                                    border-4 
                                    border-white 
                                    shadow-xl 
                                    group-hover:shadow-2xl
                                    transition-all 
                                    duration-300 
                                ">
                                    @if(!empty($minister['image_link']) && file_exists(public_path('storage/' . $minister['image_link'])))
                                        <img src="{{ asset('public/storage/' . $minister['image_link']) }}" 
                                             alt="{{ $minister['name'] }}"
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-700 flex items-center justify-center text-white font-extrabold text-xl sm:text-2xl md:text-3xl">
                                            {{ substr($minister['name'], 0, 1) }}
                                        </div>
                                    @endif
                                </div>
        
                            </div>
        
<<<<<<< HEAD
                            <!-- Minister Details -->
=======
                            <!-- Minister/Principal Details -->
>>>>>>> c356163 (video call ui setup)
                            <div class="text-gray-800 px-1 sm:px-2">
                                <h3 class="font-semibold text-sm sm:text-base md:text-lg leading-tight mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                                    {{ $minister['name'] }}
                                </h3>
                                <p class="text-xs sm:text-sm md:text-base text-gray-600 leading-tight line-clamp-2">
                                    {{ Str::limit($minister['ministry'], 35) }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
        
                <!-- View All Button -->
<<<<<<< HEAD
                @if(count($ministers) > $displayCount)
                <div class="text-center mt-6 pt-6 border-t border-gray-200">
                    <button onclick="showAllMinisters()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                        সব মন্ত্রী দেখুন ({{ count($ministers) }})
=======
                @if(count($allDisplayItems) > $displayCount)
                <div class="text-center mt-6 pt-6 border-t border-gray-200">
                    <button onclick="showAllMinisters()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg text-sm md:text-base">
                        সব দেখুন ({{ count($allDisplayItems) }})
>>>>>>> c356163 (video call ui setup)
                    </button>
                </div>
                @endif
            </div>
        </div>
        @else
        <!-- Fallback -->
        <div class="w-full lg:w-1/3 h-auto lg:h-full">
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-3xl p-8 h-full shadow-xl flex items-center justify-center">
                <div class="text-center">
                    <div class="text-4xl mb-3">🏛️</div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">মন্ত্রীদের তথ্য</h3>
                    <p class="text-gray-500 text-base">শীঘ্রই আপডেট করা হবে</p>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
</section>

<!-- All Ministers Modal -->
<div id="allMinistersModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white rounded-2xl md:rounded-3xl max-w-2xl md:max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-4 md:p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-xl md:text-2xl font-bold text-gray-800">{{ $sectionTitle ?? 'আমাদের সম্মানিত মন্ত্রীবর্গ' }}</h3>
            <button onclick="closeAllMinisters()" class="text-gray-500 hover:text-gray-700 text-2xl">
                &times;
            </button>
        </div>
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
<<<<<<< HEAD
                @foreach($ministers as $minister)
=======
                @foreach($allDisplayItems as $minister)
>>>>>>> c356163 (video call ui setup)
                <div class="bg-gray-50 rounded-xl md:rounded-2xl p-4 md:p-6 text-center border border-gray-200 hover:border-blue-300 transition-all duration-300">
                    <div class="w-20 h-20 md:w-24 md:h-24 mx-auto rounded-full overflow-hidden border-4 border-blue-500 mb-3 md:mb-4 shadow-lg">
                        @if(!empty($minister['image_link']) && file_exists(public_path('storage/' . $minister['image_link'])))
                            <img src="{{ asset('public/storage/' . $minister['image_link']) }}" 
                                 alt="{{ $minister['name'] }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center text-white font-bold text-lg md:text-xl">
                                {{ substr($minister['name'], 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="font-bold text-sm md:text-base text-gray-800 mb-1 md:mb-2">{{ $minister['name'] }}</h4>
                    <p class="text-gray-600 text-xs md:text-sm">{{ $minister['ministry'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <div class="p-4 md:p-6 border-t border-gray-200 text-center">
            <button onclick="closeAllMinisters()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 md:px-8 md:py-3 rounded-full font-semibold transition-colors duration-300 text-sm md:text-base">
                বন্ধ করুন
            </button>
        </div>
    </div>
</div>

<script>
function showAllMinisters() {
    document.getElementById('allMinistersModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAllMinisters() {
    document.getElementById('allMinistersModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('allMinistersModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAllMinisters();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAllMinisters();
    }
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth transitions for modal */
#allMinistersModal {
    transform: translateY(30px) scale(0.95);
    transition: opacity 0.4s ease, transform 0.4s ease;
}

#allMinistersModal.hidden {
    opacity: 0;
    pointer-events: none;
    transform: translateY(30px) scale(0.95);
}

#allMinistersModal:not(.hidden) {
    opacity: 1;
    transform: translateY(0) scale(1);
}
</style>
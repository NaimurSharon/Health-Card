@extends('layouts.frontend')

@section('title', 'Today\'s E-Paper')

@section('content')
@if($edition && $edition->pages->isNotEmpty())

<!-- Enhanced Newspaper Search System with Page Numbers -->
<div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-4 mb-6 transition-colors duration-300 border border-gray-100 dark:border-gray-700/50">
    <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 md:space-x-4">
        <!-- Date Picker -->
        <div class="relative flex-1 w-full md:w-auto">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <input type="date" 
                   name="date"
                   value="{{ request('date') ?? now()->format('Y-m-d') }}"
                   class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-green-500 focus:border-green-500 sm:text-sm transition duration-150">
        </div>

        <!-- Edition Selector -->
        <div class="relative flex-1 w-full md:w-auto">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v8a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"></path>
                </svg>
            </div>
            <select name="edition" class="block w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-green-500 focus:border-green-500 sm:text-sm transition duration-150">
                <option value="">সকল সংস্করণ</option>
                <option value="morning" {{ request('edition') == 'morning' ? 'selected' : '' }}>সকালের সংস্করণ</option>
                <option value="evening" {{ request('edition') == 'evening' ? 'selected' : '' }}>সান্ধ্য সংস্করণ</option>
                <option value="special" {{ request('edition') == 'special' ? 'selected' : '' }}>বিশেষ সংস্করণ</option>
            </select>
        </div>

        <!-- Search Button -->
        <button type="submit" 
                class="w-full md:w-auto flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:ring-green-500 transition duration-150 shadow-sm">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
            </svg>
            খুঁজুন
        </button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-12 gap-4">
    <!-- Left Sidebar: Thumbnails -->
    <div class="hidden md:block md:col-span-1 overflow-y-auto bg-white dark:bg-gray-800 transition-colors rounded-l-xl" style="max-height: 85vh;">
        @foreach ($pages as $p)
            <div class="cursor-pointer hover:opacity-90 transition mb-2 page-thumbnail"
                 data-page-id="{{ $p->id }}"
                 data-image-src="{{ asset('public/storage/' . $p->thumbnail_path) }}"
                 data-sections="{{ json_encode($p->sections->map(function($section) {
                     return [
                         'id' => $section->id,
                         'name' => $section->name,
                         'coordinates' => is_array($section->coordinates) ? $section->coordinates : json_decode($section->coordinates, true),
                         'image_path' => $section->image_path,
                         'linkedSections' => $section->linkedSections->map(function($linked) {
                             return [
                                 'id' => $linked->id,
                                 'name' => $linked->name,
                                 'image_path' => $linked->image_path
                             ];
                         })
                     ];
                 })) }}"
                 data-natural-width="{{ $p->image_width }}"
                 data-natural-height="{{ $p->image_height }}">
                <img src="{{ asset('public/storage/' . $p->thumbnail_path) }}"
                     class="w-full border-2 {{ $p->is_front_page ? 'border-red-500 dark:border-red-400' : 'border-gray-200 dark:border-gray-600' }} rounded-lg"
                     alt="Page {{ $p->page_number }}">
            </div>
        @endforeach
    </div>

    <!-- Center: Main Page -->
    <div class="col-span-12 md:col-span-5 relative text-center transition-colors">
        <div class="relative inline-block mx-auto">
            @if($page)
                <div class="relative" id="image-container">
                    <img src="{{ asset('public/storage/' . $page->thumbnail_path) }}"
                         alt="Page {{ $page->page_number }}"
                         class="max-w-full h-auto" 
                         id="page-image"
                         data-natural-width="{{ $page->image_width }}"
                         data-natural-height="{{ $page->image_height }}">
                    <div class="absolute inset-0" id="section-overlays-container"></div>
                </div>
            @else
                <div class="bg-gray-100 dark:bg-gray-700 p-8 text-center rounded-lg">
                    <p class="text-gray-500 dark:text-gray-300">No pages available for this edition</p>
                </div>
            @endif
        </div>
    </div>
    <!-- Right: Section List -->
    <div class="hidden md:block md:col-span-6 bg-white dark:bg-gray-800 p-4 transition-colors rounded">
        <div id="section-view">
            <h2 class="text-xl font-semibold mb-3 border-b border-gray-200 dark:border-gray-700 pb-2 dark:text-gray-200">Sections</h2>
            <ul class="space-y-2" id="section-list">
                @if($page)
                    @foreach ($page->sections as $section)
                        <li class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg px-3 py-2 cursor-pointer section-list-item transition border border-gray-200 dark:border-gray-600"
                            onclick="showSection({{ $section->id }}, {{ json_encode($section->linkedSections->map(function($linked) {
                                return [
                                    'id' => $linked->id,
                                    'name' => $linked->name,
                                    'image_path' => $linked->image_path
                                ];
                            })) }}, '{{ $section->name }}', '{{ $section->image_path }}')"
                            data-section-id="{{ $section->id }}">
                            <div class="font-medium text-gray-700 dark:text-gray-200">
                                {{ $section->name }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $section->linkedSections->count() }} linked sections
                            </div>
                        </li>
                    @endforeach
                @else
                    <li class="text-gray-500 dark:text-gray-400 text-sm">No sections available</li>
                @endif
            </ul>
        </div>
        
        <!-- Linked Images View (hidden by default) -->
        <div id="linked-images-view" class="hidden">
            <div class="flex items-center mb-3 border-b border-gray-200 dark:border-gray-700 pb-2">
                <button onclick="backToSections()" class="mr-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <h2 class="text-xl font-semibold dark:text-gray-200" id="linked-images-title"></h2>
            </div>
            <div class="grid grid-cols-1 gap-2 p-4 rounded bg-white border border-gray-200 dark:border-gray-600" id="linked-images-container">
                <!-- Linked images will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-4xl w-full max-h-[90vh] flex flex-col animate-scaleIn">
        <!-- Modal Header with Close Button -->
        <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-2">
                <!-- Logo -->
                <div class="flex flex-col">
                    <span class="text-xl font-extrabold text-gray-800 dark:text-white leading-tight">
                        আমার <span class="text-red-600 dark:text-red-400">দেশ</span>
                    </span>
                    <small class="text-xs text-gray-500 dark:text-gray-400">
                        সত্যের পথে অবিচল
                    </small>
                </div>
            </div>
            <button id="close-modal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-full p-1 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Modal Content - Image -->
        <div class="flex-1 overflow-auto p-4">
            <img id="modal-image" src="" alt="" class="mx-auto max-h-[70vh] object-contain">
        </div>
        
        <!-- Modal Footer with Share Options -->
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex justify-center items-center space-x-4">
                <!-- Facebook -->
                <button onclick="shareOnFacebook()" class="text-white bg-blue-600 hover:bg-blue-700 rounded-full p-2 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                    </svg>
                </button>
                
                <!-- Twitter -->
                <button onclick="shareOnTwitter()" class="text-white bg-blue-400 hover:bg-blue-500 rounded-full p-2 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                </button>
                
                <!-- WhatsApp -->
                <button onclick="shareOnWhatsApp()" class="text-white bg-green-500 hover:bg-green-600 rounded-full p-2 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-6.29 1.324c-.429 0-.794-.147-1.093-.293l-1.024-.6-.99.258c-.273.071-.52-.111-.594-.41L7.16 10.88c-.074-.297.025-.41.198-.545l.843-.844c.148-.148.198-.297.074-.644L7.57 7.285c-.05-.149-.124-.223-.297-.223h-.99c-.174 0-.347.074-.446.223l-.843 1.652c-.173.297-.495.644-.718.843-.223.199-.495.322-.817.322-.322 0-.495-.074-.718-.149l-3.35-1.243c-.273-.1-.57-.223-.57-.718 0-.496.421-.966.99-1.243L16.31.223c.57-.272 1.14-.272 1.488 0l2.383 1.243c.57.297.99.767.99 1.243 0 .496-.421.966-.99 1.243l-9.874 4.735c-.248.124-.421.149-.644.149z"/>
                    </svg>
                </button>
                
                <!-- LinkedIn -->
                <button onclick="shareOnLinkedIn()" class="text-white bg-blue-700 hover:bg-blue-800 rounded-full p-2 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </button>
                
                <!-- Telegram -->
                <button onclick="shareOnTelegram()" class="text-white bg-blue-500 hover:bg-blue-600 rounded-full p-2 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.138-.295.295-.295.295l.213-3.053 5.56-5.023c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.64-.203-.658-.64.135-.954l11.566-4.458c.538-.196 1.006.128.832.941z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Dummy News Blocks -->
<div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    {{-- সর্বশেষ সংবাদ --}}
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow border border-gray-200 dark:border-gray-700/50">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3 border-b border-gray-200 dark:border-gray-700 pb-2">সর্বশেষ সংবাদ</h2>
        <div class="space-y-4">
            <div class="flex gap-3">
                <img src="https://images.dailyamardesh.com/original_images/pabna_eLPM4ev.jpg" alt="" class="rounded-lg w-20 h-20 object-cover border border-gray-200 dark:border-gray-600">
                <a href="#" class="text-sm font-medium text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    ঢাকার যানজটে নাজেহাল জনজীবন
                </a>
            </div>
            <div class="flex gap-3">
                <img src="https://images.dailyamardesh.com/original_images/dhaka-1_0GdGqWy.jpg" alt="" class="rounded-lg w-20 h-20 object-cover border border-gray-200 dark:border-gray-600">
                <a href="#" class="text-sm font-medium text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    বিদ্যুৎ বিপর্যয়ে রাজশাহীসহ ৪ জেলা
                </a>
            </div>
        </div>
    </div>

    {{-- রাজনীতি --}}
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow border border-gray-200 dark:border-gray-700/50">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3 border-b border-gray-200 dark:border-gray-700 pb-2">রাজনীতি</h2>
        <div class="space-y-4">
            <div class="flex gap-3">
                <img src="https://images.dailyamardesh.com/original_images/M.jpg" alt="" style='object-fit:cover;' class="rounded-lg w-20 h-20 object-cover border border-gray-200 dark:border-gray-600">
                <a href="#" class="text-sm font-medium text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    দলীয় প্রতীক নিয়ে নির্বাচনে যাবে বিএনপি
                </a>
            </div>
            <div class="flex gap-3">
                <img src="https://images.dailyamardesh.com/original_images/trump_8.jpg" style='object-fit:cover;' alt="" class="rounded-lg w-20 h-20 object-cover border border-gray-200 dark:border-gray-600">
                <a href="#" class="text-sm font-medium text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    সংসদে নতুন বাজেট পেশ আজ
                </a>
            </div>
        </div>
    </div>

    {{-- খেলা --}}
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow border border-gray-200 dark:border-gray-700/50">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3 border-b border-gray-200 dark:border-gray-700 pb-2">খেলা</h2>
        <div class="space-y-4">
            <div class="flex gap-3">
                <img src="https://images.dailyamardesh.com/original_images/BD-SL-ODI.jpg" alt="" style='object-fit:cover;' class="rounded-lg w-20 h-20 object-cover border border-gray-200 dark:border-gray-600">
                <a href="#" class="text-sm font-medium text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    তাসকিনের দুর্দান্ত বোলিংয়ে জিম্বাবুয়ে পরাজিত
                </a>
            </div>
            <div class="flex gap-3">
                <img src="https://images.dailyamardesh.com/original_images/jahanara_alam_xaIjVso.jpg" alt="" style='object-fit:cover;' class="rounded-lg w-20 h-20 object-cover border border-gray-200 dark:border-gray-600">
                <a href="#" class="text-sm font-medium text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    ফুটবলে বাংলাদেশের সম্ভাবনা কতটুকু?
                </a>
            </div>
        </div>
    </div>

    {{-- বিনোদন --}}
    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow border border-gray-200 dark:border-gray-700/50">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3 border-b border-gray-200 dark:border-gray-700 pb-2">বিনোদন</h2>
        <div class="space-y-4">
            <div class="flex gap-3">
                <img src="https://images.dailyamardesh.com/original_images/Shakib-Khan.jpg"  style='object-fit:cover;' alt="" class="rounded-lg w-20 h-20 object-cover border border-gray-200 dark:border-gray-600">
                <a href="#" class="text-sm font-medium text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    চঞ্চল চৌধুরীর নতুন সিনেমা 'নকশীকাঁথা'
                </a>
            </div>
            <div class="flex gap-3">
                <img src="https://images.dailyamardesh.com/original_images/Manadan-Karimi.jpg" alt=""  style='object-fit:cover;' class="rounded-lg w-20 h-20 object-cover border border-gray-200 dark:border-gray-600">
                <a href="#" class="text-sm font-medium text-gray-800 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                    মাহির বিয়ের গুঞ্জন, কী বলছে পরিবার?
                </a>
            </div>
        </div>
    </div>
</div>

@else
<!-- No Edition content (already exists in your template) -->
<div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700/50">
    <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-200 mb-4">No News published for selected Date</h2>
    <p class="text-gray-600 dark:text-gray-300">Please try a different date or edition type.</p>
    <a href="{{ route('home') }}" class="mt-4 inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition border-2 border-blue-700 dark:border-blue-500">
        View Latest News
    </a>
</div>
@endif
<style>
    .section-highlight {
        position: absolute;
        cursor:pointer;
        z-index: 10;
    }
    .section-highlight:hover {
        background-color: rgba(0, 0, 0, 0.3);
        border-color: rgba(0, 0, 0, 0.3);
    }
    .section-highlight.active {
        background-color: rgba(59, 130, 246, 0.4);
        border-color: rgba(59, 130, 246, 1);
    }
    .dark .section-highlight:hover {
        background-color: rgba(0, 0, 0, 0.3);
        border-color: rgba(0, 0, 0, 0.3);
    }
    .dark .section-highlight.active {
        background-color: rgba(99, 102, 241, 0.4);
        border-color: rgba(99, 102, 241, 1);
    }
    .page-thumbnail.selected img {
        border-color: #2563eb !important;
        border-width: 4px !important;
    }
    .page-number-btn.active {
        background-color: #10B981;
        color: white;
        border-color: #10B981;
    }
    
    
    @keyframes scaleIn {
        from {
            transform: scale(0.95);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes scaleOut {
        from {
            transform: scale(1);
            opacity: 1;
        }
        to {
            transform: scale(0.95);
            opacity: 0;
        }
    }

    .animate-scaleIn {
        animation: scaleIn 0.2s ease-out forwards;
    }

    .animate-scaleOut {
        animation: scaleOut 0.2s ease-out forwards;
    }
    
    /* Modal Content - Image Slider */
#image-modal .flex-1.overflow-auto.p-4 {
    display: flex;
    flex-direction: column;
    height: calc(100% - 120px); /* Account for header/footer */
}

#slider-container {
    height: 100%;
    display: flex;
}

.slide {
    width: 100%;
    height: 100%;
    flex-shrink: 0;
    display: flex;
    justify-content: center;
    align-items: flex-start; /* Align to top */
    overflow-y: auto;
}

.slide img {
    width: 100%;
    height: auto;
    max-width: 100%;
    object-fit: contain;
}

/*delete the code under to turn off the scrolling effect in the modal image */
#image-modal > div {
    width: 90vw;
    max-width: 1200px;
    height: 90vh;
}

#image-modal img {
    width: 100%;
    height: auto;
    max-height: none;
    object-fit: contain;
}
</style>

<script>
    // Global variables for slider functionality
    let currentSlideIndex = 0;
    let linkedImages = [];
    let touchStartX = 0;
    let touchEndX = 0;
    let sliderContainer = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize with front page selected
        const frontPageThumbnail = document.querySelector('.page-thumbnail img[alt^="Page {{ $page->page_number ?? '' }}"]')?.parentElement;
        if (frontPageThumbnail) {
            frontPageThumbnail.classList.add('selected');
            const pageNumberBtn = document.querySelector(`.page-number-btn[data-page-id="{{ $page->id ?? '' }}"]`);
            if (pageNumberBtn) {
                pageNumberBtn.classList.add('active');
            }
            
            // Initialize sections for the first page
            const sectionsData = JSON.parse(frontPageThumbnail.getAttribute('data-sections'));
            updateSectionOverlays(sectionsData);
        }
    
        // Handle page thumbnail clicks
        document.querySelectorAll('.page-thumbnail').forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const pageId = this.getAttribute('data-page-id');
                selectPageNumber(pageId);
            });
        });
        
        // Handle window resize with debounce
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                const currentPage = document.querySelector('.page-thumbnail.selected');
                if (currentPage) {
                    const sectionsData = JSON.parse(currentPage.getAttribute('data-sections'));
                    updateSectionOverlays(sectionsData);
                }
            }, 100);
        });
    
        // Close modal when clicking outside image
        document.getElementById('image-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    
        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    });
    
    function selectPageNumber(pageId) {
        // Update selected state for thumbnails
        document.querySelectorAll('.page-thumbnail').forEach(t => t.classList.remove('selected'));
        const selectedThumbnail = document.querySelector(`.page-thumbnail[data-page-id="${pageId}"]`);
        if (selectedThumbnail) {
            selectedThumbnail.classList.add('selected');
        }
    
        // Update active state for page number buttons
        document.querySelectorAll('.page-number-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        const pageNumberBtn = document.querySelector(`.page-number-btn[data-page-id="${pageId}"]`);
        if (pageNumberBtn) {
            pageNumberBtn.classList.add('active');
        }
    
        // Get page data from attributes
        const imageSrc = selectedThumbnail.getAttribute('data-image-src');
        const sectionsData = JSON.parse(selectedThumbnail.getAttribute('data-sections'));
        const naturalWidth = selectedThumbnail.getAttribute('data-natural-width');
        const naturalHeight = selectedThumbnail.getAttribute('data-natural-height');
    
        // Update main image
        const pageImage = document.getElementById('page-image');
        pageImage.src = imageSrc;
        pageImage.alt = selectedThumbnail.querySelector('img').alt;
        pageImage.setAttribute('data-natural-width', naturalWidth);
        pageImage.setAttribute('data-natural-height', naturalHeight);
        
        // Wait for image to load before updating coordinates
        pageImage.onload = function() {
            updateSectionOverlays(sectionsData);
            updateSectionList(sectionsData);
        };
    }

    function updateSectionOverlays(sectionsData) {
    const container = document.getElementById('section-overlays-container');
    if (!container) return;
    
    const pageImage = document.getElementById('page-image');
    if (!pageImage) return;
    
    // Clear existing overlays
    container.innerHTML = '';
    
    // Get the image's actual displayed dimensions and position
    const imageRect = pageImage.getBoundingClientRect();
    const containerRect = container.getBoundingClientRect();
    
    // Calculate the relative position of the image within its container
    const imageOffsetX = imageRect.left - containerRect.left;
    const imageOffsetY = imageRect.top - containerRect.top;
    
    // Get natural dimensions
    const naturalWidth = parseInt(pageImage.getAttribute('data-natural-width'));
    const naturalHeight = parseInt(pageImage.getAttribute('data-natural-height'));
    
    // Calculate scaling factors
    const scaleX = imageRect.width / naturalWidth;
    const scaleY = imageRect.height / naturalHeight;
    
    // Create new overlays
    sectionsData.forEach(section => {
        const coords = section.coordinates;
        if (!coords || !Array.isArray(coords) || coords.length !== 4) {
            console.warn('Invalid coordinates for section', section.id, coords);
            return;
        }
        
        // Scale coordinates to fit displayed image and adjust for image position
        const left = (coords[0] * scaleX) + imageOffsetX;
        const top = (coords[1] * scaleY) + imageOffsetY;
        const width = (coords[2] - coords[0]) * scaleX;
        const height = (coords[3] - coords[1]) * scaleY;
        
        const overlay = document.createElement('div');
        overlay.className = 'section-highlight';
        overlay.style.left = `${left}px`;
        overlay.style.top = `${top}px`;
        overlay.style.width = `${width}px`;
        overlay.style.height = `${height}px`;
        overlay.setAttribute('data-section-id', section.id);
        overlay.setAttribute('title', section.name);
        overlay.addEventListener('click', function() {
            const allImages = [];

            if (section.image_path) {
                allImages.push({
                    image_path: section.image_path,
                    name: section.name,
                    id: section.id,
                    isMain: true
                });
            }

            if (Array.isArray(section.linkedSections)) {
                allImages.push(...section.linkedSections.map(linked => ({
                    ...linked,
                    isMain: false
                })));
            }

            if (window.innerWidth < 768) {
                showModalSlider(allImages, section.name, section.id);
            } else {
                showSection(
                    section.id, 
                    section.linkedSections || [], 
                    section.name,
                    section.image_path
                ); 
            }
        });
        
        container.appendChild(overlay);
    });
}


    function updateSectionList(sectionsData) {
    const sectionList = document.getElementById('section-list');
    if (!sectionList) return;
    
    sectionList.innerHTML = '';
    
    sectionsData.forEach(section => {
        const li = document.createElement('li');
        li.className = 'bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded px-3 py-2 cursor-pointer section-list-item transition-colors duration-200';
        li.setAttribute('data-section-id', section.id);
        li.onclick = function() { 
            showSection(
                section.id, 
                section.linkedSections || [], 
                section.name,
                section.image_path
            ); 
        };
        li.innerHTML = `
            <div class="font-medium text-gray-700 dark:text-gray-200">
                ${section.name}
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                ${section.linkedSections ? section.linkedSections.length : 0} linked sections
            </div>
        `;
        sectionList.appendChild(li);
    });
}

    function showSection(sectionId, linkedSections, sectionName, sectionImagePath) {
    // Highlight the section in the list
    document.querySelectorAll('.section-list-item').forEach(item => {
        item.classList.remove('bg-gray-200', 'dark:bg-gray-600');
    });
    const listItem = document.querySelector(`.section-list-item[data-section-id="${sectionId}"]`);
    if (listItem) {
        listItem.classList.add('bg-gray-200', 'dark:bg-gray-600');
    }
    
    // Highlight the section overlay
    document.querySelectorAll('.section-highlight').forEach(overlay => {
        overlay.classList.remove('active');
    });
    const overlay = document.querySelector(`.section-highlight[data-section-id="${sectionId}"]`);
    if (overlay) {
        overlay.classList.add('active');
    }
    
    // Show linked sections' images in right column
    showLinkedSections(sectionName, linkedSections, sectionImagePath);
}

    function showLinkedSections(sectionName, linkedSections, sectionImagePath) {
    const sectionView = document.getElementById('section-view');
    const linkedImagesView = document.getElementById('linked-images-view');
    const titleElement = document.getElementById('linked-images-title');
    const container = document.getElementById('linked-images-container');
    
    // Update title
    titleElement.textContent = sectionName;
    
    // Clear existing content
    container.innerHTML = '';
    
    // Create an array that always includes the main section image first, then linked images
    const allImages = [];
    if (sectionImagePath) {
        allImages.push({image_path: sectionImagePath, name: sectionName, isMain: true});
    }
    if (linkedSections && linkedSections.length > 0) {
        allImages.push(...linkedSections.map(section => ({...section, isMain: false})));
    }

    // Display all images (main first, then linked)
    if (allImages.length > 0) {
        allImages.forEach((image, index) => {
            const sectionDiv = document.createElement('div');
            sectionDiv.className = 'cursor-pointer relative mb-4 overflow-visible';
            sectionDiv.style.border = '2px solid #009933';
            sectionDiv.style.borderRadius = '10px';
            sectionDiv.style.padding = '2.7rem 0.2rem 0 0.2rem';
            
            const brandingDiv = document.createElement('div');
            brandingDiv.className = 'absolute top-0 left-4 z-10 bg-white py-1 px-2';
            brandingDiv.style.marginTop = '-17px';
            brandingDiv.innerHTML = `
        <img src="{{ asset('public/storage/' . setting('site_logo', '/images/favicon.ico')) }}" alt="Site Logo" class="h-10">
    `;
            
            const img = document.createElement('img');
            img.src = image.image_path ? '{{ asset('public/storage/') }}/' + image.image_path : '{{ asset('images/default-image.png') }}';
            img.alt = image.name;
            img.className = 'linked-image w-full h-auto mb-2';
            img.onclick = function() {
                showModalSlider(allImages, sectionName, image.id);
            };
            img.onerror = function() {
                this.src = '{{ asset('public/storage/default_image.webp') }}';
            };
            
            sectionDiv.appendChild(brandingDiv);
            sectionDiv.appendChild(img);
            container.appendChild(sectionDiv);
        });
    } else {
        container.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-sm col-span-2">No images found</p>';
    }
    
    // Switch views
    sectionView.classList.add('hidden');
    linkedImagesView.classList.remove('hidden');
}

    function showModalSlider(images, sectionName, initialImageId = null) {
        const modal = document.getElementById('image-modal');
        const modalContent = document.querySelector('#image-modal > div > div.flex-1.overflow-auto.p-4');
        
        // Store the images globally for navigation
        linkedImages = images;
        currentSlideIndex = initialImageId ? 
            Math.max(0, images.findIndex(img => img.id === initialImageId)) : 0;
        
        // Clear existing content and create slider
        modalContent.innerHTML = `
            <div class="relative h-full w-full overflow-hidden touch-none">
                <div id="slider-container" class="flex transition-transform duration-300 ease-in-out h-full" style="width: ${images.length * 100}%">
                    ${images.map(image => `
                        <div class="slide w-full h-full flex items-center justify-center" style="width: ${100 / images.length}%">
                            <img src="${image.image_path ? '{{ asset('public/storage/') }}/' + image.image_path : '{{ asset('images/default-image.png') }}'}" 
                                 alt="${image.name}" 
                                 class="mx-auto max-w-[100%] object-contain"
                                 onerror="this.src='{{ asset('images/default-image.png') }}'"
                                 draggable="false">
                        </div>
                    `).join('')}
                </div>
            </div>
            <!-- Navigation arrows - larger for mobile -->
            <button id="prev-slide" class="absolute left-2 md:left-4 top-1/2 transform -translate-y-1/2 z-10 bg-white/80 dark:bg-gray-700/80 rounded-full p-2 shadow-md hover:bg-white dark:hover:bg-gray-600 transition-colors duration-200 touch-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button id="next-slide" class="absolute right-2 md:right-4 top-1/2 transform -translate-y-1/2 z-10 bg-white/80 dark:bg-gray-700/80 rounded-full p-2 shadow-md hover:bg-white dark:hover:bg-gray-600 transition-colors duration-200 touch-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        `;
        
        // Get slider container reference
        sliderContainer = document.getElementById('slider-container');
        
        // Add event listeners for navigation
        document.getElementById('prev-slide').addEventListener('click', goToPrevSlide);
        document.getElementById('next-slide').addEventListener('click', goToNextSlide);
        

        
        // Show modal with animation
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.classList.add('animate-scaleIn');
        }, 10);
        
        // Close modal handler
        document.getElementById('close-modal').onclick = closeModal;
    }
    
    function goToPrevSlide() {
        if (currentSlideIndex > 0) {
            currentSlideIndex--;
        } else {
            currentSlideIndex = linkedImages.length - 1; // Loop to last slide
        }
        updateSliderPosition();
    }
    
    function goToNextSlide() {
        if (currentSlideIndex < linkedImages.length - 1) {
            currentSlideIndex++;
        } else {
            currentSlideIndex = 0; // Loop to first slide
        }
        updateSliderPosition();
    }
    
    function updateSliderPosition() {
        if (!sliderContainer) return;
        
        const slideWidth = 100 / linkedImages.length;
        sliderContainer.style.transition = 'transform 0.3s ease-in-out';
        sliderContainer.style.transform = `translateX(-${currentSlideIndex * slideWidth}%)`;
        
        // Update slide indicators
        const indicators = document.querySelectorAll('#image-modal .absolute.bottom-4 div');
        if (indicators) {
            indicators.forEach((indicator, index) => {
                if (index === currentSlideIndex) {
                    indicator.classList.add('bg-green-500');
                    indicator.classList.remove('bg-gray-300');
                } else {
                    indicator.classList.remove('bg-green-500');
                    indicator.classList.add('bg-gray-300');
                }
            });
        }
    }
    
    function shareOnFacebook() {
        const currentImage = linkedImages[currentSlideIndex];
        const imageUrl = currentImage.image_path ? '{{ url('public/storage/') }}/' + currentImage.image_path : '{{ url('images/default-image.png') }}';
        const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(imageUrl)}`;
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }
    
    function shareOnTwitter() {
        const currentImage = linkedImages[currentSlideIndex];
        const imageUrl = currentImage.image_path ? '{{ url('public/storage/') }}/' + currentImage.image_path : '{{ url('images/default-image.png') }}';
        const shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(imageUrl)}&text=Check%20this%20out`;
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }
    
    function shareOnWhatsApp() {
        const currentImage = linkedImages[currentSlideIndex];
        const imageUrl = currentImage.image_path ? '{{ url('public/storage/') }}/' + currentImage.image_path : '{{ url('images/default-image.png') }}';
        const shareUrl = `https://wa.me/?text=${encodeURIComponent('Check this out: ' + imageUrl)}`;
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }
    
    function shareOnLinkedIn() {
        const currentImage = linkedImages[currentSlideIndex];
        const imageUrl = currentImage.image_path ? '{{ url('public/storage/') }}/' + currentImage.image_path : '{{ url('images/default-image.png') }}';
        const shareUrl = `https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(imageUrl)}`;
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }
    
    function shareOnTelegram() {
        const currentImage = linkedImages[currentSlideIndex];
        const imageUrl = currentImage.image_path ? '{{ url('public/storage/') }}/' + currentImage.image_path : '{{ url('images/default-image.png') }}';
        const shareUrl = `https://t.me/share/url?url=${encodeURIComponent(imageUrl)}&text=Check%20this%20out`;
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }
    
    function closeModal() {
        const modal = document.getElementById('image-modal');
        modal.classList.remove('animate-scaleIn');
        modal.classList.add('animate-scaleOut');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'animate-scaleOut');
        }, 200);
    }
    
    function backToSections() {
        document.getElementById('section-view').classList.remove('hidden');
        document.getElementById('linked-images-view').classList.add('hidden');
    }
</script>
@endsection
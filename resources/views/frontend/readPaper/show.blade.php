@extends('layouts.frontend')

@section('title', 'E-Paper View')

@section('content')

<!-- Modal Structure -->
<div id="sectionModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-auto">
        <div class="flex justify-between items-center border-b p-4">
            <h3 id="modalTitle" class="text-xl font-bold"></h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="modalContent" class="p-4">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<div class="grid grid-cols-12 gap-4">
    <!-- Left Sidebar: Page Thumbnails -->
    <div class="col-span-1 overflow-y-auto border-r border-gray-300" style="max-height: 85vh;">
        @foreach ($pages as $p)
            <a href="{{ route('epaper.show', ['edition' => $edition->id, 'page' => $p->id]) }}">
                <img src="{{ asset('public/storage/' . $p->thumbnail_path) }}"
                     class="w-full mb-2 border {{ $p->id == $page->id ? 'border-red-600 border-4' : 'border-gray-200' }}"
                     alt="Page {{ $p->page_number }}">
            </a>
        @endforeach
    </div>

    <!-- Center: Main Page Image with Overlays -->
    <div class="col-span-8 relative text-center border-x border-gray-300">
        <div class="relative inline-block">
            <img src="{{ asset('public/storage/' . $page->image_path) }}"
                 alt="Page {{ $page->page_number }}"
                 class="max-w-full h-auto" id="page-image">
    
            <!-- Interactive overlay areas -->
            <div class="absolute inset-0 top-0 left-0 z-10">
                @foreach ($sections as $section)
                    @php
                        $coords = $section->coordinates;
                        $left = $coords[0];
                        $top = $coords[1];
                        $width = $coords[2] - $coords[0];
                        $height = $coords[3] - $coords[1];
                    @endphp
                    <div onclick="showSection('{{ $section->name }}', '{{ asset('public/storage/' . $section->image_path) }}')"
                       class="absolute cursor-pointer hover:bg-white/30 transition-all duration-150 section-highlight"
                       style="left: {{ $left }}px; top: {{ $top }}px; width: {{ $width }}px; height: {{ $height }}px;"
                       title="{{ $section->name }}">
                        <span class="sr-only">{{ $section->name }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right: Section List -->
    <div class="col-span-3 bg-white p-4 border-l border-gray-300">
        <h2 class="text-xl font-semibold mb-3 border-b pb-2">সংক্ষিপ্ত বিভাগসমূহ</h2>
        <ul class="space-y-2">
            @foreach ($sections as $section)
                <li class="bg-gray-100 hover:bg-gray-200 rounded px-3 py-2 cursor-pointer"
                    onclick="showSection('{{ $section->name }}', '{{ asset('public/storage/' . $section->image_path) }}')">
                    <div class="font-medium text-gray-700">
                        {{ $section->name }}
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<style>
    .hover-bg-light:hover {
        background-color: #f8f9fa;
    }
    #main-page-img {
        max-height: 85vh;
        width: auto;
    }
    .section-highlight {
        background-color: rgba(255, 0, 0, 0.2);
        border: 1px dashed red;
    }
    .modal-content img {
        max-width: 100%;
        height: auto;
    }
</style>

<script>
    function showSection(title, imageUrl) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalContent').innerHTML = `
            <div class="text-center">
                <img src="${imageUrl}" alt="${title}" class="mx-auto">
            </div>
            <div class="mt-4 text-center">
                <button onclick="closeModal()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Close
                </button>
            </div>
        `;
        document.getElementById('sectionModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('sectionModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside content
    document.getElementById('sectionModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
</script>
@endsection
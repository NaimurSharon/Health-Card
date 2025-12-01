@extends('layouts.global')

@section('title', $hospital->name ?? 'হাসপাতালের বিস্তারিত')
@section('subtitle', 'মানসম্মত স্বাস্থ্য সেবা আপনার দোরগোড়ায়')

@section('content')
<div class="space-y-16 tiro">

    {{-- ===========================
         HERO SECTION
    ============================ --}}
    <section class="bg-gradient-to-br from-blue-600 to-teal-600 rounded-3xl text-white p-12 shadow-2xl">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ $hospital->name }}</h1>
                <p class="text-xl mb-8 text-blue-100 leading-relaxed">
                    {{ $hospital->name }} হাসপাতাল যেখানে আমরা মানসম্মত স্বাস্থ্য সেবা নিশ্চিত করি
                </p>
                <div class="flex flex-wrap gap-4">
                    <div class="flex flex-col items-center text-center bg-opacity-20 backdrop-blur-sm rounded-2xl px-6 py-3 border border-white border-opacity-30">
                        <div class="text-2xl font-bold">{{ $hospital->doctors_count }}</div>
                        <div class="text-sm opacity-90">ডাক্তার</div>
                    </div>
                    <div class="flex flex-col items-center text-center  bg-opacity-20 backdrop-blur-sm rounded-2xl px-6 py-3 border border-white border-opacity-30">
                        <div class="text-2xl font-bold">২৪/৭</div>
                        <div class="text-sm opacity-90">সেবা</div>
                    </div>
                    <div class="flex flex-col items-center text-center  bg-opacity-20 backdrop-blur-sm rounded-2xl px-6 py-3 border border-white border-opacity-30">
                        <div class="text-2xl font-bold">
                            @if($hospital->status == 'active') সক্রিয়
                            @else অকার্যকর @endif
                        </div>
                        <div class="text-sm opacity-90">স্ট্যাটাস</div>
                    </div>
                </div>
            </div>
                {{-- ===========================
                         YOUTUBE VIDEO SECTION
                    ============================ --}}
                    
                    
                @if($hospital->youtube_video_url)
               
                <div class="text-center">
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-3xl">
                        <div class="aspect-w-16 aspect-h-9 bg-black rounded-2xl overflow-hidden shadow-2xl">
                            <iframe
                                class="w-full h-96"
                                src="{{ $hospital->youtube_video_url }}"
                                title="YouTube video player" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen>
                                
                            </iframe>
                        </div>
                    </div>
                </div>
              @endif
        </div>
    </section>

    {{-- ===========================
             ABOUT SECTION WITH CAROUSEL
        ============================ --}}
    <section id="about" class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="grid md:grid-cols-2 gap-0">
            <div class="p-12 bg-gradient-to-br from-gray-50 to-white">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">হাসপাতাল সম্পর্কে</h2>
                <div class="space-y-6 text-gray-700 leading-relaxed">
                    <p class="text-lg">
                        {{ $hospital->name }} {{ $hospital->address ? $hospital->address . ' এ অবস্থিত' : '' }} একটি {{ $hospital->type_label }} হাসপাতাল।
                    </p>
                    
                    @if($hospital->description)
                    <div class="bg-gray-50 p-6 rounded-2xl border-l-4 border-gray-500">
                        <p class="text-gray-700">{!! nl2br(e($hospital->description)) !!}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Carousel Image Display --}}
            <div class="bg-gray-100 p-8 flex items-center justify-center">
                @if($hospital->images && count($hospital->images) > 0)
                    <div class="w-full max-w-lg">
                        {{-- Image Carousel --}}
                        <div class="relative">
                            <div id="hospitalCarousel" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner rounded-2xl overflow-hidden">
                                    @foreach($hospital->images as $index => $image)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ asset('public/storage/' . $image) }}" 
                                                 class="d-block w-100 h-80 object-cover"
                                                 alt="{{ $hospital->name }} - Image {{ $index + 1 }}">
                                        </div>
                                    @endforeach
                                </div>
                                
                                {{-- Carousel Controls --}}
                                @if(count($hospital->images) > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#hospitalCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon bg-black bg-opacity-50 rounded-full p-3" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#hospitalCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon bg-black bg-opacity-50 rounded-full p-3" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                    
                                    {{-- Carousel Indicators --}}
                                    <div class="carousel-indicators position-relative mt-4">
                                        @foreach($hospital->images as $index => $image)
                                            <button type="button" 
                                                    data-bs-target="#hospitalCarousel" 
                                                    data-bs-slide-to="{{ $index }}" 
                                                    class="{{ $index === 0 ? 'active' : '' }} bg-gray-400 rounded-full w-3 h-3 mx-1"
                                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                                                    aria-label="Slide {{ $index + 1 }}">
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Fallback when no images --}}
                    <div class="text-center">
                        <div class="text-9xl mb-4 text-gray-300">🏥</div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $hospital->name }}</h3>
                        <p class="text-gray-600">{{ $hospital->type_label }} হাসপাতাল</p>
                        <p class="text-gray-400 text-sm mt-2">ছবি যোগ করা হয়নি</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ===========================
         SERVICES & FACILITIES
    ============================ --}}
    <section class="grid md:grid-cols-2 gap-8">
        {{-- Medical Services --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800">মেডিকেল সেবাসমূহ</h2>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $hospital->services ? count($hospital->services) : 0 }} সেবা
                </span>
            </div>
            <div class="space-y-6">
                @if($hospital->services && count($hospital->services) > 0)
                    @foreach($hospital->services as $service)
                    <div class="group border-l-4 border-blue-500 pl-6 py-4 hover:bg-blue-50 rounded-r-2xl transition-all duration-300">
                        <h3 class="font-semibold text-gray-800 text-lg mb-2 group-hover:text-blue-700 transition-colors">
                            {{ trim($service) }}
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            আধুনিক পদ্ধতিতে ও অভিজ্ঞ ডাক্তারদের মাধ্যমে সেবা প্রদান
                        </p>
                    </div>
                    @endforeach
                @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">🩺</div>
                    <p class="text-gray-500">সেবার তালিকা শীঘ্রই আপডেট করা হবে</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Facilities --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800">সুবিধাসমূহ</h2>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                    আধুনিক
                </span>
            </div>
            <div class="space-y-6">
                @if($hospital->facilities)
                
                @php
                    $facilities = array_filter(array_map('trim', explode(',', $hospital->facilities)));
                @endphp

                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                    <ul class="list-disc pl-6 space-y-3">
                        @foreach($facilities as $item)
                            <li class="text-lg text-gray-800 leading-relaxed">
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">🏗️</div>
                    <p class="text-gray-500">সুবিধার তালিকা শীঘ্রই আপডেট করা হবে</p>
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- ===========================
         DOCTORS SECTION
    ============================ --}}
    <section class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-3xl p-12 shadow-xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">আমাদের ডাক্তারবৃন্দ</h2>
            <p class="text-gray-600 text-lg">অভিজ্ঞ ও প্রশিক্ষিত মেডিকেল Professionals</p>
        </div>
        
        @if($hospital->doctors_count > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($hospital->doctors as $doctor)
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-center mb-4">
                    @if($doctor->profile_image)
                        <div class="w-20 h-20 lg:w-24 lg:h-24 mx-auto mb-3 rounded-full overflow-hidden border-2 border-purple-200 shadow-sm">
                            <img 
                                src="{{ $doctor->profile_image ? asset('public/storage/' . $doctor->profile_image) : asset('default/doctor.png') }}"
                                alt="{{ $doctor->name }}"
                                class="w-full h-full object-cover"
                            >
                        </div>
                    @else
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-3">
                            <i class="fas fa-user-md text-purple-600 text-xl"></i>
                        </div>
                    @endif
                    <h3 class="font-bold text-gray-800 text-lg inter">{{ $doctor->name }}</h3>
                    <p class="text-purple-600 font-medium text-sm inter">{{ $doctor->specialization }}</p>
                </div>
                
                <div class="space-y-2 text-sm text-gray-600 inter">
                    @if($doctor->qualifications)
                    <div class="flex items-center space-x-2">
                        <span>🎓</span>
                        <span>{{ $doctor->qualifications }}</span>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between tiro">
                        <span class="px-2 py-1 rounded-full text-xs font-medium 
                            {{ $doctor->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $doctor->status == 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                        </span>
                        <!--<span class="text-xs text-gray-500">-->
                        <!--    অভিজ্ঞতা: ৫+ বছর-->
                        <!--</span>-->
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">👨‍⚕️</div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">কোন ডাক্তার পাওয়া যায়নি</h3>
            <p class="text-gray-500">ডাক্তারের তথ্য শীঘ্রই আপডেট করা হবে</p>
        </div>
        @endif
    </section>

    {{-- ===========================
         CONTACT & EMERGENCY
    ============================ --}}
    <section class="grid md:grid-cols-2 gap-8">
        {{-- Contact Information --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">যোগাযোগ করুন</h2>
            <div class="space-y-6">
                @if($hospital->phone)
                <div class="flex items-start space-x-4">
                    <span class="text-2xl text-blue-600">📞</span>
                    <div>
                        <h3 class="font-semibold text-gray-800">ফোন নম্বর</h3>
                        <a href="tel:{{ $hospital->phone }}" class="text-blue-600 inter hover:text-blue-800 text-lg">
                            {{ $hospital->phone }}
                        </a>
                    </div>
                </div>
                @endif
                
                @if($hospital->email)
                <div class="flex items-start space-x-4">
                    <span class="text-2xl text-green-600">📧</span>
                    <div>
                        <h3 class="font-semibold text-gray-800">ইমেইল</h3>
                        <a href="mailto:{{ $hospital->email }}" class="text-green-600 inter hover:text-green-800">
                            {{ $hospital->email }}
                        </a>
                    </div>
                </div>
                @endif
                
                @if($hospital->address)
                <div class="flex items-start space-x-4">
                    <span class="text-2xl text-purple-600">📍</span>
                    <div>
                        <h3 class="font-semibold text-gray-800">ঠিকানা</h3>
                        <p class="text-gray-600">{{ $hospital->address }}</p>
                    </div>
                </div>
                @endif
                
                @if($hospital->website)
                <div class="flex items-start space-x-4">
                    <span class="text-2xl text-orange-600">🌐</span>
                    <div>
                        <h3 class="font-semibold text-gray-800">ওয়েবসাইট</h3>
                        <a href="{{ $hospital->website }}" class="text-orange-600 hover:text-orange-800" target="_blank">
                            {{ $hospital->website }}
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Emergency Contact --}}
        <div class="bg-gradient-to-br from-red-500 to-orange-500 rounded-3xl p-8 text-white shadow-xl">
            <h2 class="text-2xl font-bold mb-8">জরুরি যোগাযোগ</h2>
            <div class="space-y-6">
                @if($hospital->emergency_contact)
                <div class="bg-opacity-20 backdrop-blur-sm rounded-2xl p-6">
                    <div class="text-center">
                        <div class="text-4xl mb-3">🚑</div>
                        <h3 class="text-xl font-bold mb-2">জরুরি নম্বর</h3>
                        <a href="tel:{{ $hospital->emergency_contact }}" class="text-2xl inter font-bold block hover:text-red-200 transition-colors">
                            {{ $hospital->emergency_contact }}
                        </a>
                        <p class="text-red-100 mt-2">২৪/৭ জরুরি সেবা</p>
                    </div>
                </div>
                @endif
                
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 border border-white border-opacity-20">
                    <h3 class="font-semibold text-lg mb-3">জরুরি নির্দেশিকা</h3>
                    <ul class="space-y-2 text-red-100 text-sm">
                        <li>• জরুরি অবস্থায় দ্রুত হাসপাতালে আসুন</li>
                        <li>• অ্যাম্বুলেন্স সেবা পাওয়া যায়</li>
                        <li>• ইমার্জেন্সি ওয়ার্ড ২৪ ঘন্টা খোলা</li>
                        <li>• অভিজ্ঞ ডাক্তাররা উপস্থিত আছেন</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ===========================
         QUICK ACTIONS
    ============================ --}}
    <section class="bg-white rounded-3xl p-8 shadow-xl">
        <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">দ্রুত একশন</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @if($hospital->phone)
            <a href="tel:{{ $hospital->phone }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-2xl text-center transition-all duration-300 transform hover:-translate-y-1 shadow-lg">
                <div class="text-2xl mb-2">📞</div>
                <div class="font-semibold">কল করুন</div>
            </a>
            @endif
            
            @if($hospital->emergency_contact)
            <a href="tel:{{ $hospital->emergency_contact }}" 
               class="bg-red-500 hover:bg-red-600 text-white p-4 rounded-2xl text-center transition-all duration-300 transform hover:-translate-y-1 shadow-lg">
                <div class="text-2xl mb-2">🚨</div>
                <div class="font-semibold">জরুরি কল</div>
            </a>
            @endif
            
            @if($hospital->email)
            <a href="mailto:{{ $hospital->email }}" 
               class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-2xl text-center transition-all duration-300 transform hover:-translate-y-1 shadow-lg">
                <div class="text-2xl mb-2">📧</div>
                <div class="font-semibold">ইমেইল</div>
            </a>
            @endif
            
            <button onclick="navigator.clipboard.writeText('{{ $hospital->address }}').then(() => alert('ঠিকানা কপি করা হয়েছে!'))"
                    class="bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-2xl text-center transition-all duration-300 transform hover:-translate-y-1 shadow-lg">
                <div class="text-2xl mb-2">📍</div>
                <div class="font-semibold">ঠিকানা কপি</div>
            </button>
        </div>
    </section>

</div>
@endsection

@push('styles')
<style>
    .backdrop-blur-sm {
        backdrop-filter: blur(8px);
    }
    .aspect-w-16 {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    }
    .aspect-w-16 iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
@endpush

@push('scripts')
<script>
    function copyAddress() {
        const address = '{{ $hospital->address }}';
        navigator.clipboard.writeText(address).then(() => {
            alert('ঠিকানা কপি করা হয়েছে!');
        });
    }

    function openMaps() {
        const address = '{{ $hospital->address }}';
        const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
        window.open(mapsUrl, '_blank');
    }
</script>
@endpush
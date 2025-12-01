@extends('layouts.global')

@section('title', $doctor->name ?? 'ডাক্তারের বিস্তারিত')
@section('subtitle', 'অভিজ্ঞ ও প্রশিক্ষিত মেডিকেল প্রফেশনাল')

@section('content')
<div class="space-y-16 tiro">

    {{-- ===========================
         HERO SECTION
    ============================ --}}
    <section class="relative bg-gradient-to-br from-blue-50 via-white to-teal-50 rounded-3xl shadow-2xl overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23007acc\" fill-opacity=\"0.2\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"2\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <div class="relative px-6 py-8 md:px-12 md:py-12 lg:px-16 lg:py-16">
            {{-- Personal Info Section - 100% Width --}}
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 md:p-8 shadow-xl border border-white/50 mb-8">
                <div class="flex flex-col md:flex-row items-center gap-6 md:gap-8">
                    {{-- Doctor Image with Status --}}
                    <div class="relative flex-shrink-0">
                        @if($doctor->profile_image)
                            <img src="{{ asset('public/storage/' . $doctor->profile_image) }}" 
                                 alt="{{ $doctor->name }}"
                                 class="w-36 h-36 md:w-36 md:h-36 lg:w-36 lg:h-36 rounded-2xl object-cover border-4 border-white shadow-2xl">
                        @else
                            <div class="w-24 h-24 md:w-32 md:h-32 lg:w-36 lg:h-36 bg-gradient-to-br from-blue-500 to-teal-500 rounded-2xl flex items-center justify-center border-4 border-white shadow-2xl">
                                <i class="fas fa-user-md text-white text-2xl md:text-3xl lg:text-4xl"></i>
                            </div>
                        @endif
                        
                        {{-- Online Status Badge --}}
                        <!--@if($doctor->doctorDetail && $doctor->doctorDetail->is_available)-->
                        <!--<div class="absolute -bottom-2 -right-2 bg-green-500 text-white px-2 py-1 md:px-3 md:py-1 rounded-full text-xs font-bold shadow-lg border-2 border-white">-->
                        <!--    <i class="fas fa-circle text-[6px] md:text-[8px] mr-1"></i>অনলাইন-->
                        <!--</div>-->
                        <!--@else-->
                        <!--<div class="absolute -bottom-2 -right-2 bg-gray-500 text-white px-2 py-1 md:px-3 md:py-1 rounded-full text-xs font-bold shadow-lg border-2 border-white">-->
                        <!--    <i class="fas fa-circle text-[6px] md:text-[8px] mr-1"></i>অফলাইন-->
                        <!--</div>-->
                        <!--@endif-->
                    </div>
    
                    {{-- Doctor Info --}}
                    <div class="flex-1 text-center md:text-left">
                        <div class="mb-4 md:mb-6">
                            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800 mb-2 {{ detectLanguageClass($doctor->name) }}">
                                {{ $doctor->name }}
                            </h1>
                            <p class="text-lg md:text-xl lg:text-2xl text-blue-600 font-semibold {{ detectLanguageClass($doctor->specialization) }}">
                                {{ $doctor->specialization }}
                            </p>
                        </div>
    
                        {{-- Qualifications & Experience --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
                            <div class="flex items-center justify-center md:justify-start text-gray-700">
                                <i class="fas fa-graduation-cap text-blue-500 mr-3 text-lg"></i>
                                <span class="{{ detectLanguageClass($doctor->qualifications) }} text-sm md:text-base">{{ $doctor->qualifications }}</span>
                            </div>
                            
                            @if($doctor->doctorDetail && $doctor->doctorDetail->experience)
                            <div class="flex items-center justify-center md:justify-start text-gray-700">
                                <i class="fas fa-briefcase-medical text-green-500 mr-3 text-lg"></i>
                                <span class="{{ detectLanguageClass($doctor->doctorDetail->experience) }} text-sm md:text-base">{{ $doctor->doctorDetail->experience }}</span>
                            </div>
                            @endif
                            
                            @if($doctor->hospital)
                            <div class="flex items-center justify-center md:justify-start text-gray-700">
                                <i class="fas fa-hospital text-purple-500 mr-3 text-lg"></i>
                                <span class="{{ detectLanguageClass($doctor->hospital->name) }} text-sm md:text-base">{{ $doctor->hospital->name }}</span>
                            </div>
                            @endif
                        </div>
    
                        {{-- Stats --}}
                        <div class="flex flex-wrap justify-center md:justify-start gap-4 md:gap-8 pt-4 border-t border-gray-200">
                            <div class="text-center">
                                <div class="text-xl md:text-2xl font-bold text-blue-600">{{ $doctor->appointmentsAsDoctor->count() }}</div>
                                <div class="text-xs md:text-sm text-gray-600 mt-1">সফল কনসাল্টেশন</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl md:text-2xl font-bold text-green-600">
                                    @if($doctor->doctorDetail && $doctor->doctorDetail->consultation_fee)
                                        ৳{{ number_format($doctor->doctorDetail->consultation_fee) }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                                <div class="text-xs md:text-sm text-gray-600 mt-1">কন্সাল্টেশন ফি</div>
                            </div>
                            @if($doctor->doctorDetail && $doctor->doctorDetail->rating)
                            <div class="text-center">
                                <div class="text-xl md:text-2xl font-bold text-orange-600">
                                    {{ $doctor->doctorDetail->rating }}/5
                                </div>
                                <div class="text-xs md:text-sm text-gray-600 mt-1">রেটিং</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
    
            {{-- Action Buttons Row - Full Width --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Appointment Booking --}}
                <a href="#" class="group bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-blue-500 transition-colors">
                            <i class="fas fa-calendar-check text-blue-600 text-2xl group-hover:text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-2">অ্যাপয়েন্টমেন্ট</h3>
                        <p class="text-gray-600 text-sm mb-4">আপনার পছন্দের সময়সূচীতে বুক করুন</p>
                        <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-semibold group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            বুক করুন
                        </span>
                    </div>
                </a>
    
                {{-- Video Consultation --}}
                <a href="#" class="group bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-green-500 transition-colors">
                            <i class="fas fa-video text-green-600 text-2xl group-hover:text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-2">ভিডিও কনসাল্ট</h3>
                        <p class="text-gray-600 text-sm mb-4">অনলাইনে ভিডিও কলে পরামর্শ নিন</p>
                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm font-semibold group-hover:bg-green-600 group-hover:text-white transition-colors">
                            শুরু করুন
                        </span>
                    </div>
                </a>
    
                <!--{{-- Treatment Advice --}}-->
                <!--<a href="#" class="group bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">-->
                <!--    <div class="flex flex-col items-center text-center">-->
                <!--        <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-purple-500 transition-colors">-->
                <!--            <i class="fas fa-stethoscope text-purple-600 text-2xl group-hover:text-white"></i>-->
                <!--        </div>-->
                <!--        <h3 class="font-bold text-gray-800 text-lg mb-2">চিকিৎসা পরামর্শ</h3>-->
                <!--        <p class="text-gray-600 text-sm mb-4">প্রাথমিক চিকিৎসা নির্দেশনা পান</p>-->
                <!--        <span class="bg-purple-100 text-purple-700 px-4 py-2 rounded-lg text-sm font-semibold group-hover:bg-purple-600 group-hover:text-white transition-colors">-->
                <!--            পরামর্শ নিন-->
                <!--        </span>-->
                <!--    </div>-->
                <!--</a>-->
    
                {{-- Direct Call --}}
                @if($doctor->phone)
                
                {{-- Video Consultation --}}
                <a href="tel:{{ $doctor->phone }}" class="group bg-white rounded-2xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-red-500 transition-colors">
                            <i class="fas fa-phone text-red-600 text-2xl group-hover:text-white"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-2">সরাসরি কল</h3>
                        <p class="text-gray-600 text-sm mb-4">দ্রুত যোগাযোগের জন্য কল করুন</p>
                        <span class="bg-red-100 text-red-700 px-4 py-2 rounded-lg {{ detectLanguageClass($doctor->phone) }} text-sm font-semibold group-hover:bg-red-600 group-hover:text-white transition-colors">
                            {{ $doctor->phone }}
                        </span>
                    </div>
                </a>
                @else
                <div class="group bg-gray-100 rounded-2xl p-6 shadow-lg border border-gray-200">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 bg-gray-200 rounded-2xl flex items-center justify-center mb-4">
                            <i class="fas fa-phone text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg mb-2">সরাসরি কল</h3>
                        <p class="text-gray-400 text-sm mb-4">ফোন নম্বর অনুপলব্ধ</p>
                        <span class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold">
                            অনুপলব্ধ
                        </span>
                    </div>
                </div>
                @endif
            </div>
    
        </div>
    </section>

    {{-- ===========================
         ABOUT & AVAILABILITY SECTION
    ============================ --}}
    <section class="grid md:grid-cols-2 gap-8">
        {{-- Professional Details --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">পেশাগত তথ্য</h2>
            <div class="space-y-6">
                @if($doctor->doctorDetail && $doctor->doctorDetail->bio)
                <div class="bg-gray-50 p-6 rounded-2xl border-l-4 border-blue-500">
                    <p class="text-gray-700 leading-relaxed {{ detectLanguageClass($doctor->doctorDetail->bio) }}">{!! nl2br(e($doctor->doctorDetail->bio)) !!}</p>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($doctor->doctorDetail && $doctor->doctorDetail->department)
                    <div class="flex items-center space-x-3 p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <i class="fas fa-building text-blue-600 text-xl"></i>
                        <div>
                            <p class="text-sm text-gray-600">ডিপার্টমেন্ট</p>
                            <p class="font-semibold text-gray-800 {{ detectLanguageClass($doctor->doctorDetail->department) }}">{{ $doctor->doctorDetail->department }}</p>
                        </div>
                    </div>
                    @endif

                    @if($doctor->doctorDetail && $doctor->doctorDetail->designation)
                    <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-xl border border-green-100">
                        <i class="fas fa-user-tie text-green-600 text-xl"></i>
                        <div>
                            <p class="text-sm text-gray-600">পদবী</p>
                            <p class="font-semibold text-gray-800 {{ detectLanguageClass($doctor->doctorDetail->designation) }}">{{ $doctor->doctorDetail->designation }}</p>
                        </div>
                    </div>
                    @endif

                    @if($doctor->doctorDetail && $doctor->doctorDetail->license_number)
                    <div class="flex items-center space-x-3 p-4 bg-purple-50 rounded-xl border border-purple-100">
                        <i class="fas fa-id-card text-purple-600 text-xl"></i>
                        <div>
                            <p class="text-sm text-gray-600">লাইসেন্স নম্বর</p>
                            <p class="font-semibold text-gray-800 {{ detectLanguageClass($doctor->doctorDetail->license_number) }}">{{ $doctor->doctorDetail->license_number }}</p>
                        </div>
                    </div>
                    @endif

                    @if($doctor->hospital)
                    <div class="flex items-center space-x-3 p-4 bg-orange-50 rounded-xl border border-orange-100">
                        <i class="fas fa-hospital text-orange-600 text-xl"></i>
                        <div>
                            <p class="text-sm text-gray-600">হাসপাতাল</p>
                            <p class="font-semibold text-gray-800 {{ detectLanguageClass($doctor->hospital->name) }}">{{ $doctor->hospital->name }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Languages --}}
                @if($doctor->doctorDetail && $doctor->doctorDetail->languages)
                <div class="border-t pt-6">
                    <h3 class="font-semibold text-gray-800 mb-4">ভাষাগত দক্ষতা</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($doctor->doctorDetail->languages as $language)
                        <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm border border-gray-200 {{ detectLanguageClass(trim($language)) }}">
                            {{ trim($language) }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Availability Schedule --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">সাপ্তাহিক সময়সূচী</h2>
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                            <span class="text-sm text-gray-600">উপলব্ধ</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 bg-gray-300 rounded-full"></span>
                            <span class="text-sm text-gray-600">অনুপলব্ধ</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    @php
                        $daysOfWeek = [
                            'sunday' => ['name' => 'রবিবার', 'short' => 'রবি'],
                            'monday' => ['name' => 'সোমবার', 'short' => 'সোম'],
                            'tuesday' => ['name' => 'মঙ্গলবার', 'short' => 'মঙ্গল'],
                            'wednesday' => ['name' => 'বুধবার', 'short' => 'বুধ'],
                            'thursday' => ['name' => 'বৃহস্পতিবার', 'short' => 'বৃহঃ'],
                            'friday' => ['name' => 'শুক্রবার', 'short' => 'শুক্র'],
                            'saturday' => ['name' => 'শনিবার', 'short' => 'শনি']
                        ];
                        
                        $availabilities = $doctor->doctorAvailabilities->keyBy('day_of_week');
                    @endphp

                    @foreach($daysOfWeek as $dayKey => $dayInfo)
                        @php
                            $availability = $availabilities[$dayKey] ?? null;
                            $isAvailable = $availability && $availability->is_available;
                        @endphp
                        <div class="flex items-center justify-between p-3 rounded-xl border-2 {{ $isAvailable ? 'border-green-200 bg-green-50' : 'border-gray-200 bg-gray-50' }} hover:shadow-sm transition-shadow duration-300">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isAvailable ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                    <span class="text-sm font-semibold">{{ $dayInfo['short'] }}</span>
                                </div>
                                <span class="font-medium text-gray-700">{{ $dayInfo['name'] }}</span>
                            </div>
                            
                            @if($isAvailable)
                                <div class="text-right">
                                    <div class="text-sm font-medium {{ detectLanguageClass($availability->start_time) }}  text-green-600">
                                        {{ \Carbon\Carbon::parse($availability->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($availability->end_time)->format('g:i A') }}
                                    </div>
                                    <div class="text-xs {{ detectLanguageClass($availability->slot_duration) }} text-gray-500">{{ $availability->slot_duration }} মিনিট স্লট</div>
                                </div>
                            @else
                                <span class="text-sm text-gray-400 font-medium">অনুপলব্ধ</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===========================
         FEE STRUCTURE & CONTACT
    ============================ --}}
    <section class="grid md:grid-cols-2 gap-8">
        {{-- Fee Structure --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">ফি কাঠামো</h2>
            <div class="space-y-4">
                @if($doctor->doctorDetail)
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-2xl border-l-4 border-blue-500 shadow-sm">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">কন্সাল্টেশন ফি</p>
                                <p class="text-2xl font-bold {{ detectLanguageClass($doctor->doctorDetail->consultation_fee) }} text-gray-800">
                                    ৳ {{ number_format($doctor->doctorDetail->consultation_fee, 2) }}
                                </p>
                            </div>
                            <i class="fas fa-user-md text-blue-600 text-2xl"></i>
                        </div>
                    </div>

                    @if($doctor->doctorDetail->follow_up_fee)
                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-2xl border-l-4 border-green-500 shadow-sm">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">ফলো-আপ ফি</p>
                                <p class="text-2xl font-bold  {{ detectLanguageClass($doctor->doctorDetail->follow_up_fee) }} text-gray-800">
                                    ৳ {{ number_format($doctor->doctorDetail->follow_up_fee, 2) }}
                                </p>
                            </div>
                            <i class="fas fa-redo text-green-600 text-2xl"></i>
                        </div>
                    </div>
                    @endif

                    @if($doctor->doctorDetail->emergency_fee)
                    <div class="bg-gradient-to-r from-red-50 to-red-100 p-6 rounded-2xl border-l-4 border-red-500 shadow-sm">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-gray-600">জরুরি ফি</p>
                                <p class="text-2xl font-bold {{ detectLanguageClass($doctor->doctorDetail->emergency_fee) }} text-gray-800">
                                    ৳ {{ number_format($doctor->doctorDetail->emergency_fee, 2) }}
                                </p>
                            </div>
                            <i class="fas fa-ambulance text-red-600 text-2xl"></i>
                        </div>
                    </div>
                    @endif
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-money-bill-wave text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">ফি কাঠামো শীঘ্রই আপডেট করা হবে</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Contact Information --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">যোগাযোগ তথ্য</h2>
            <div class="space-y-6">
                @if($doctor->phone)
                <div class="flex items-start space-x-4 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <span class="text-2xl text-blue-600">📞</span>
                    <div>
                        <h3 class="font-semibold text-gray-800">ফোন নম্বর</h3>
                        <a href="tel:{{ $doctor->phone }}" class="text-blue-600 {{ detectLanguageClass($doctor->phone) }} hover:text-blue-800 text-lg font-medium">
                            {{ $doctor->phone }}
                        </a>
                    </div>
                </div>
                @endif
                
                @if($doctor->email)
                <div class="flex items-start space-x-4 p-4 bg-green-50 rounded-xl border border-green-100">
                    <span class="text-2xl text-green-600">📧</span>
                    <div>
                        <h3 class="font-semibold text-gray-800">ইমেইল</h3>
                        <a href="mailto:{{ $doctor->email }}" class="text-green-600 {{ detectLanguageClass($doctor->email) }} hover:text-green-800 font-medium">
                            {{ $doctor->email }}
                        </a>
                    </div>
                </div>
                @endif
                
                @if($doctor->address)
                <div class="flex items-start space-x-4 p-4 bg-purple-50 rounded-xl border border-purple-100">
                    <span class="text-2xl text-purple-600">📍</span>
                    <div>
                        <h3 class="font-semibold text-gray-800">ঠিকানা</h3>
                        <p class="text-gray-600 {{ detectLanguageClass($doctor->address) }}">{{ $doctor->address }}</p>
                    </div>
                </div>
                @endif

                @if($doctor->hospital)
                <div class="flex items-start space-x-4 p-4 bg-orange-50 rounded-xl border border-orange-100">
                    <span class="text-2xl text-orange-600">🏥</span>
                    <div>
                        <h3 class="font-semibold text-gray-800">প্রধান হাসপাতাল</h3>
                        <p class="text-gray-600 {{ detectLanguageClass($doctor->hospital->name) }}">{{ $doctor->hospital->name }}</p>
                        <p class="text-sm text-gray-500 mt-1 {{ detectLanguageClass($doctor->hospital->address) }}">{{ $doctor->hospital->address }}</p>
                        @if($doctor->hospital->phone)
                        <a href="tel:{{ $doctor->hospital->phone }}" class="text-orange-600 text-sm {{ detectLanguageClass($doctor->hospital->phone) }} font-medium">
                            {{ $doctor->hospital->phone }}
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

</div>
@endsection

@push('styles')
<style>
    .backdrop-blur-sm {
        backdrop-filter: blur(8px);
    }
    
    /* Smooth hover transitions */
    .transition-all {
        transition: all 0.3s ease;
    }
    
    /* Card hover effects */
    .shadow-xl {
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .shadow-2xl {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
</style>
@endpush
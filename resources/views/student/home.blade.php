@extends('layouts.student')

@section('title', $school->name ?? 'স্কুল হোমপেজ')
@section('subtitle', 'জ্ঞানের আলো ছড়িয়ে দিচ্ছি প্রজন্ম থেকে প্রজন্ম')

@section('content')
<div class="space-y-16 tiro">

<<<<<<< HEAD
   @include('student.partial.hero')
=======
    {{-- ===========================
         HERO SECTION
    ============================ --}}
    <section class="relative bg-gradient-to-r from-green-700 via-blue-600 to-purple-700 text-white rounded-3xl overflow-hidden shadow-2xl">
        
        @if($todaystip)
            <div class="bg-yellow-400 text-black px-6 py-3 text-center font-semibold text-lg flex items-center justify-center gap-2">
                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm">আজকের স্বাস্থ্য পরামর্শ</span>
                <marquee behavior="scroll" direction="left" scrollamount="8" class="font-medium">
                    🩺 {{ $todaystip->title }} — {{ Str::limit(strip_tags($todaystip->content), 120) }}
                </marquee>
            </div>
        @endif
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative px-8 py-16 md:px-16 md:py-24 flex flex-col md:flex-row items-center justify-between gap-12">
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    {{ $school->name ?? 'আমাদের স্কুল' }}
                </h1>
                <p class="text-xl md:text-2xl mb-8 opacity-95 leading-relaxed">
                    {{ $school->motto ?? 'শিক্ষা, শৃঙ্খলা, সাফল্য' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    @auth
                        <a href="{{ route('student.dashboard') }}" class="bg-white text-green-700 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            আমার ড্যাশবোর্ড
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-white text-green-700 px-8 py-4 rounded-full font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            লগইন করুন
                        </a>
                        <a href="#about" class="border-2 border-white text-white px-8 py-4 rounded-full font-semibold hover:bg-white hover:text-green-700 transition-all duration-300">
                            আমাদের সম্পর্কে
                        </a>
                    @endauth
                </div>
            </div>
            <div class="w-full md:w-1/2">
                <img src="{{ $school->cover_image ? asset('public/storage/' . $school->cover_image) : asset('images/school-hero.png') }}" 
                     alt="{{ $school->name }}" 
                     class="w-full rounded-2xl shadow-2xl transform transition-transform duration-500">
            </div>
        </div>
    </section>

    {{-- ===========================
         QUICK STATS
    ============================ --}}
    <section class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="text-3xl font-bold text-green-600 mb-2">{{ $school->total_students ?? '০' }}+</div>
            <div class="text-gray-600">শিক্ষার্থী</div>
        </div>
        <div class="bg-white rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="text-3xl font-bold text-blue-600 mb-2">{{ $school->total_teachers ?? '০' }}+</div>
            <div class="text-gray-600">শিক্ষক</div>
        </div>
        <div class="bg-white rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="text-3xl font-bold text-purple-600 mb-2">{{ $school->established_year ?? '১৯' }}</div>
            <div class="text-gray-600">স্থাপিত বছর</div>
        </div>
        <div class="bg-white rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="text-3xl font-bold text-orange-600 mb-2">{{ $school->campus_area ? round($school->campus_area) : '০' }}</div>
            <div class="text-gray-600">বর্গফুট ক্যাম্পাস</div>
        </div>
    </section>
>>>>>>> c356163 (video call ui setup)

    {{-- ===========================
         ABOUT SECTION
    ============================ --}}
    <section id="about" class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="grid md:grid-cols-2 gap-0">
            <div class="p-12 bg-gradient-to-br from-gray-50 to-white">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">আমাদের স্কুল</h2>
                <div class="space-y-6 text-gray-700 leading-relaxed">
                    <p class="text-lg">
                        {{ $school->name ?? 'আমাদের স্কুল' }} {{ $school->city ? $school->city . ', ' : '' }}{{ $school->district ?? '' }} এ অবস্থিত একটি প্রিমিয়াম শিক্ষাপ্রতিষ্ঠান। 
                        আমরা {{ $school->established_year ?? '১৯৯০' }} সাল থেকে মানসম্মত শিক্ষা প্রদান করে আসছি।
                    </p>
                    
                    @if($school->vision)
                    <div class="bg-blue-50 p-6 rounded-2xl border-l-4 border-blue-500">
                        <h3 class="font-semibold text-blue-800 mb-2">আমাদের ভিশন</h3>
                        <p class="text-blue-700">{{ $school->vision }}</p>
                    </div>
                    @endif

                    @if($school->mission)
                    <div class="bg-green-50 p-6 rounded-2xl border-l-4 border-green-500">
                        <h3 class="font-semibold text-green-800 mb-2">আমাদের মিশন</h3>
                        <p class="text-green-700">{{ $school->mission }}</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="bg-gray-100 p-8 flex items-center justify-center">
                <img src="{{ $school->school_image ? asset('public/storage/' . $school->school_image) : asset('images/school-building.jpg') }}" 
                     alt="School Building" 
                     class="rounded-2xl shadow-lg w-full">
            </div>
        </div>
    </section>

    {{-- ===========================
         FACILITIES SECTION
    ============================ --}}
    <section class="bg-gradient-to-br from-green-50 to-blue-50 rounded-3xl p-12 shadow-xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">আমাদের সুবিধাসমূহ</h2>
            <p class="text-gray-600 text-lg">আধুনিক সুযোগ-সুবিধা নিয়ে গড়ে উঠেছে আমাদের ক্যাম্পাস</p>
        </div>
        
        @if($school->facilities)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(json_decode($school->facilities) as $index => $facility)
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="text-2xl mb-4">
                    @switch($facility)
                        @case('library') 📚 @break
                        @case('computer_lab') 💻 @break
                        @case('science_lab') 🔬 @break
                        @case('sports_ground') ⚽ @break
                        @case('auditorium') 🎭 @break
                        @case('cafeteria') 🍽️ @break
                        @case('medical_room') 🏥 @break
                        @case('transport') 🚌 @break
                        @case('wifi') 📡 @break
                        @case('swimming_pool') 🏊 @break
                        @case('art_room') 🎨 @break
                        @case('music_room') 🎵 @break
                        @default ✅
                    @endswitch
                </div>
                <h3 class="font-semibold text-gray-800 text-lg mb-2">{{ $facility }}</h3>
                <p class="text-gray-600 text-sm">আধুনিক ও যুগোপযোগী সুবিধা</p>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-500 text-lg">সুবিধার তালিকা শীঘ্রই আপডেট করা হবে</p>
        </div>
        @endif
    </section>

    {{-- ===========================
         NOTICES & ANNOUNCEMENTS
    ============================ --}}
    <section class="grid md:grid-cols-2 gap-8">
        {{-- School Notices --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800">📢 স্কুল নোটিশ</h2>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $schoolNotices->count() }} নতুন
                </span>
            </div>
            <div class="space-y-6">
                @forelse($schoolNotices as $notice)
                <div class="group border-l-4 border-green-500 pl-6 py-4 hover:bg-green-50 rounded-r-2xl transition-all duration-300">
                    <h3 class="font-semibold text-gray-800 text-lg mb-2 group-hover:text-green-700 transition-colors">
                        {{ $notice->title }}
                    </h3>
                    <p class="text-gray-600 mb-3 leading-relaxed">
                        {{ Str::limit($notice->content, 100) }}
                    </p>
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>মেয়াদ: {{ $notice->expiry_date ? $notice->expiry_date->format('d/m/Y') : 'নির্দিষ্ট নয়' }}</span>
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($notice->priority == 'high') bg-red-100 text-red-800
                            @elseif($notice->priority == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ $notice->priority }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">📭</div>
                    <p class="text-gray-500">কোনো নতুন নোটিশ নেই</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- City Corporation Notices --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800">🏛️ সিটি কর্পোরেশন</h2>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $cityCorporationNotices->count() }} আপডেট
                </span>
            </div>
            <div class="space-y-6">
                @forelse($cityCorporationNotices as $notice)
                <div class="group border-l-4 border-blue-500 pl-6 py-4 hover:bg-blue-50 rounded-r-2xl transition-all duration-300">
                    <h3 class="font-semibold text-gray-800 text-lg mb-2 group-hover:text-blue-700 transition-colors">
                        {{ $notice->title }}
                    </h3>
                    <p class="text-gray-600 mb-3 leading-relaxed">
                        {{ Str::limit($notice->content, 100) }}
                    </p>
                    <div class="text-sm text-gray-500">
                        প্রকাশ: {{ $notice->created_at->format('d/m/Y') }}
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">🏢</div>
                    <p class="text-gray-500">কোনো সিটি নোটিশ নেই</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ===========================
         STUDENT SPOTLIGHT (Only for logged in students)
    ============================ --}}
    @auth
<<<<<<< HEAD
    <!--<section class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-3xl p-12 text-white shadow-2xl">-->
    <!--    <div class="text-center mb-12">-->
    <!--        <h2 class="text-3xl font-bold mb-4">আপনার আজকের সারসংক্ষেপ</h2>-->
    <!--        <p class="text-purple-100 text-lg">আজকের ক্লাস, পরীক্ষা এবং গুরুত্বপূর্ণ আপডেট</p>-->
    <!--    </div>-->

    <!--    <div class="grid md:grid-cols-3 gap-8">-->
    <!--        {{-- Today's Classes --}}-->
    <!--        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 border border-white border-opacity-20">-->
    <!--            <h3 class="font-semibold text-xl mb-4 flex items-center">-->
    <!--                <span class="mr-3">📚</span> আজকের ক্লাস-->
    <!--            </h3>-->
    <!--            @if($todaysSchedule && count($todaysSchedule) > 0)-->
    <!--            <div class="space-y-3">-->
    <!--                @foreach($todaysSchedule->take(3) as $schedule)-->
    <!--                <div class="flex justify-between items-center py-2 border-b border-white border-opacity-20">-->
    <!--                    <span class="font-medium">{{ $schedule->subject->name ?? 'ক্লাস' }}</span>-->
    <!--                    <span class="text-sm opacity-90">{{ $schedule->start_time }}</span>-->
    <!--                </div>-->
    <!--                @endforeach-->
    <!--            </div>-->
    <!--            @else-->
    <!--            <p class="text-purple-100 opacity-90">আজ কোনো ক্লাস নেই</p>-->
    <!--            @endif-->
    <!--        </div>-->

    <!--        {{-- Upcoming Exams --}}-->
    <!--        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 border border-white border-opacity-20">-->
    <!--            <h3 class="font-semibold text-xl mb-4 flex items-center">-->
    <!--                <span class="mr-3">🧾</span> আসন্ন পরীক্ষা-->
    <!--            </h3>-->
    <!--            @if($upcomingExams && count($upcomingExams) > 0)-->
    <!--            <div class="space-y-3">-->
    <!--                @foreach($upcomingExams as $exam)-->
    <!--                <div class="py-2 border-b border-white border-opacity-20">-->
    <!--                    <div class="font-medium">{{ $exam->title ?? 'পরীক্ষা' }}</div>-->
    <!--                    <div class="text-sm opacity-90">{{ $exam->exam_date ?? 'N/A' }}</div>-->
    <!--                </div>-->
    <!--                @endforeach-->
    <!--            </div>-->
    <!--            @else-->
    <!--            <p class="text-purple-100 opacity-90">কোনো পরীক্ষা নেই</p>-->
    <!--            @endif-->
    <!--        </div>-->

    <!--        {{-- Quick Actions --}}-->
    <!--        <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 border border-white border-opacity-20">-->
    <!--            <h3 class="font-semibold text-xl mb-4 flex items-center">-->
    <!--                <span class="mr-3">⚡</span> দ্রুত একশন-->
    <!--            </h3>-->
    <!--            <div class="space-y-3">-->
    <!--                <a href="{{ route('student.school-diary') }}" class="block bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-3 px-4 rounded-xl text-center transition-all duration-300">-->
    <!--                    হোমওয়ার্ক দেখুন-->
    <!--                </a>-->
    <!--                <a href="#" class="block bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-3 px-4 rounded-xl text-center transition-all duration-300">-->
    <!--                    ক্লাস রুটিন-->
    <!--                </a>-->
    <!--                <a href="#" class="block bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-3 px-4 rounded-xl text-center transition-all duration-300">-->
    <!--                    স্বাস্থ্য রেকর্ড-->
    <!--                </a>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</section>-->
=======
    <section class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-3xl p-12 text-white shadow-2xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">আপনার আজকের সারসংক্ষেপ</h2>
            <p class="text-purple-100 text-lg">আজকের ক্লাস, পরীক্ষা এবং গুরুত্বপূর্ণ আপডেট</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            {{-- Today's Classes --}}
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 border border-white border-opacity-20">
                <h3 class="font-semibold text-xl mb-4 flex items-center">
                    <span class="mr-3">📚</span> আজকের ক্লাস
                </h3>
                @if($todaysSchedule && count($todaysSchedule) > 0)
                <div class="space-y-3">
                    @foreach($todaysSchedule->take(3) as $schedule)
                    <div class="flex justify-between items-center py-2 border-b border-white border-opacity-20">
                        <span class="font-medium">{{ $schedule->subject->name ?? 'ক্লাস' }}</span>
                        <span class="text-sm opacity-90">{{ $schedule->start_time }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-purple-100 opacity-90">আজ কোনো ক্লাস নেই</p>
                @endif
            </div>

            {{-- Upcoming Exams --}}
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 border border-white border-opacity-20">
                <h3 class="font-semibold text-xl mb-4 flex items-center">
                    <span class="mr-3">🧾</span> আসন্ন পরীক্ষা
                </h3>
                @if($upcomingExams && count($upcomingExams) > 0)
                <div class="space-y-3">
                    @foreach($upcomingExams as $exam)
                    <div class="py-2 border-b border-white border-opacity-20">
                        <div class="font-medium">{{ $exam->title ?? 'পরীক্ষা' }}</div>
                        <div class="text-sm opacity-90">{{ $exam->exam_date ?? 'N/A' }}</div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-purple-100 opacity-90">কোনো পরীক্ষা নেই</p>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-6 border border-white border-opacity-20">
                <h3 class="font-semibold text-xl mb-4 flex items-center">
                    <span class="mr-3">⚡</span> দ্রুত একশন
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('student.school-diary') }}" class="block bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-3 px-4 rounded-xl text-center transition-all duration-300">
                        হোমওয়ার্ক দেখুন
                    </a>
                    <a href="#" class="block bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-3 px-4 rounded-xl text-center transition-all duration-300">
                        ক্লাস রুটিন
                    </a>
                    <a href="#" class="block bg-white bg-opacity-20 hover:bg-opacity-30 text-white py-3 px-4 rounded-xl text-center transition-all duration-300">
                        স্বাস্থ্য রেকর্ড
                    </a>
                </div>
            </div>
        </div>
    </section>
>>>>>>> c356163 (video call ui setup)
    @endauth
    
    {{-- ===========================
         HOSPITALS SECTION
        ============================ --}}
    <section class="bg-gradient-to-br from-red-50 to-orange-50 rounded-3xl p-12 shadow-xl">
        <div class="text-center mb-12">
<<<<<<< HEAD
            <h2 class="text-3xl font-bold text-gray-800 mb-4">জরুরি স্বাস্থ্য সেবা</h2>
=======
            <h2 class="text-3xl font-bold text-gray-800 mb-4">🏥 জরুরি স্বাস্থ্য সেবা</h2>
>>>>>>> c356163 (video call ui setup)
            <p class="text-gray-600 text-lg">নিকটস্থ হাসপাতাল ও স্বাস্থ্য কেন্দ্রসমূহ</p>
        </div>
    
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($hospitals as $hospital)
            <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border-l-4 
                @if($hospital->type == 'government') border-green-500
                @elseif($hospital->type == 'private') border-blue-500
                @elseif($hospital->type == 'specialized') border-purple-500
                @else border-gray-500 @endif">
                
                {{-- Hospital Type Badge --}}
                <div class="flex justify-between items-start mb-4">
<<<<<<< HEAD
                    <h3 class="font-bold text-xl text-gray-800">
                        <a href="{{ route('hospitals.view', $hospital->id) }}" class="hover:underline hover:text-blue-600">
                            {{ $hospital->name }}
                        </a>
                    </h3>
=======
                    <h3 class="font-bold text-xl text-gray-800">{{ $hospital->name }}</h3>
>>>>>>> c356163 (video call ui setup)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($hospital->type == 'government') bg-green-100 text-green-800
                        @elseif($hospital->type == 'private') bg-blue-100 text-blue-800
                        @elseif($hospital->type == 'specialized') bg-purple-100 text-purple-800
                        @else bg-gray-100 text-gray-800 @endif">
                        @if($hospital->type == 'government') সরকারি
                        @elseif($hospital->type == 'private') বেসরকারি
                        @elseif($hospital->type == 'specialized') বিশেষায়িত
                        @else ক্লিনিক @endif
                    </span>
                </div>
    
                {{-- Address --}}
                @if($hospital->address)
                <div class="flex items-start space-x-3 mb-3">
                    <span class="text-gray-500 mt-1">📍</span>
                    <p class="text-gray-600 text-sm flex-1">{{ $hospital->address }}</p>
                </div>
                @endif
    
                {{-- Contact Information --}}
                <div class="space-y-2 mb-4">
                    @if($hospital->phone)
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-500">📞</span>
<<<<<<< HEAD
                        <a href="tel:{{ $hospital->phone }}" class="text-blue-600 hover:text-blue-800 inter text-sm">
=======
                        <a href="tel:{{ $hospital->phone }}" class="text-blue-600 hover:text-blue-800 text-sm">
>>>>>>> c356163 (video call ui setup)
                            {{ $hospital->phone }}
                        </a>
                    </div>
                    @endif
    
                    @if($hospital->emergency_contact)
                    <div class="flex items-center space-x-3">
                        <span class="text-red-500">🚨</span>
                        <a href="tel:{{ $hospital->emergency_contact }}" class="text-red-600 hover:text-red-800 text-sm font-semibold">
<<<<<<< HEAD
                            জরুরি: <span class='inter'>{{ $hospital->emergency_contact }}</span>
=======
                            জরুরি: {{ $hospital->emergency_contact }}
>>>>>>> c356163 (video call ui setup)
                        </a>
                    </div>
                    @endif
    
                    @if($hospital->email)
                    <div class="flex items-center space-x-3">
                        <span class="text-gray-500">📧</span>
<<<<<<< HEAD
                        <a href="mailto:{{ $hospital->email }}" class="text-blue-600 hover:text-blue-800 inter text-sm">
=======
                        <a href="mailto:{{ $hospital->email }}" class="text-blue-600 hover:text-blue-800 text-sm">
>>>>>>> c356163 (video call ui setup)
                            {{ $hospital->email }}
                        </a>
                    </div>
                    @endif
                </div>
    
                {{-- Services --}}
                @if($hospital->services && count($hospital->services) > 0)
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-700 text-sm mb-2">সেবাসমূহ:</h4>
                    <div class="flex flex-wrap gap-1">
                        @foreach($hospital->services as $service)
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs">
                            {{ $service }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
    
                {{-- Action Buttons --}}
                <div class="flex space-x-2 pt-4 border-t border-gray-100">
                    @if($hospital->phone)
                    <a href="tel:{{ $hospital->phone }}" 
                       class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 px-3 rounded-lg text-sm font-semibold transition-colors duration-300">
                       কল করুন
                    </a>
                    @endif
                    
                    @if($hospital->emergency_contact)
                    <a href="tel:{{ $hospital->emergency_contact }}" 
                       class="flex-1 bg-red-500 hover:bg-red-600 text-white text-center py-2 px-3 rounded-lg text-sm font-semibold transition-colors duration-300">
                       জরুরি
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="text-6xl mb-4">🏥</div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">কোন হাসপাতাল পাওয়া যায়নি</h3>
                <p class="text-gray-500">হাসপাতালের তথ্য শীঘ্রই আপডেট করা হবে</p>
            </div>
            @endforelse
        </div>
    
        {{-- Emergency Notice --}}
        <!--<div class="mt-12 bg-red-50 border border-red-200 rounded-2xl p-6 text-center">-->
        <!--    <div class="flex items-center justify-center space-x-3 mb-3">-->
        <!--        <span class="text-2xl">🚨</span>-->
        <!--        <h3 class="text-xl font-bold text-red-800">জরুরি স্বাস্থ্য সেবা</h3>-->
        <!--    </div>-->
        <!--    <p class="text-red-700 mb-4">-->
        <!--        যেকোনো জরুরি স্বাস্থ্য সমস্যায় দ্রুত নিকটস্থ হাসপাতালে যোগাযোগ করুন। -->
        <!--        জাতীয় জরুরি সেবার জন্য <strong>৯৯৯</strong> এ কল করুন।-->
        <!--    </p>-->
        <!--    <div class="flex flex-col sm:flex-row gap-4 justify-center">-->
        <!--        <a href="tel:999" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full font-semibold transition-colors duration-300">-->
        <!--            🚑 জাতীয় জরুরি সেবা - ৯৯৯-->
        <!--        </a>-->
        <!--        <a href="#contact" class="border border-red-600 text-red-600 hover:bg-red-600 hover:text-white px-6 py-3 rounded-full font-semibold transition-colors duration-300">-->
        <!--            যোগাযোগ করুন-->
        <!--        </a>-->
        <!--    </div>-->
        <!--</div>-->
    </section>

    {{-- ===========================
         CONTACT & LOCATION
    ============================ --}}
    <section class="bg-white rounded-3xl shadow-xl overflow-hidden">
        <div class="grid md:grid-cols-2 gap-0">
            <div class="p-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">যোগাযোগ করুন</h2>
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <span class="text-2xl">📍</span>
                        <div>
                            <h3 class="font-semibold text-gray-800">ঠিকানা</h3>
                            <p class="text-gray-600">{{ $school->address ?? 'ঠিকানা আপডেট করা হবে' }}</p>
                            <p class="text-gray-500">{{ $school->city ?? '' }}, {{ $school->district ?? '' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <span class="text-2xl">📞</span>
                        <div>
                            <h3 class="font-semibold text-gray-800">ফোন</h3>
<<<<<<< HEAD
                            <p class="text-gray-600 inter">{{ $school->phone ?? 'ফোন নম্বর আপডেট করা হবে' }}</p>
=======
                            <p class="text-gray-600">{{ $school->phone ?? 'ফোন নম্বর আপডেট করা হবে' }}</p>
>>>>>>> c356163 (video call ui setup)
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <span class="text-2xl">📧</span>
                        <div>
                            <h3 class="font-semibold text-gray-800">ইমেইল</h3>
<<<<<<< HEAD
                            <p class="text-gray-600 inter">{{ $school->email ?? 'ইমেইল আপডেট করা হবে' }}</p>
=======
                            <p class="text-gray-600">{{ $school->email ?? 'ইমেইল আপডেট করা হবে' }}</p>
>>>>>>> c356163 (video call ui setup)
                        </div>
                    </div>

                    @if($school->website)
                    <div class="flex items-start space-x-4">
                        <span class="text-2xl">🌐</span>
                        <div>
                            <h3 class="font-semibold text-gray-800">ওয়েবসাইট</h3>
<<<<<<< HEAD
                            <a href="{{ $school->website }}" class="text-blue-600 hover:text-blue-800 inter" target="_blank">
=======
                            <a href="{{ $school->website }}" class="text-blue-600 hover:text-blue-800" target="_blank">
>>>>>>> c356163 (video call ui setup)
                                {{ $school->website }}
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="bg-gray-100 p-8 flex items-center justify-center">
                <div class="text-center">
                    <div class="text-6xl mb-4">🏫</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $school->name }}</h3>
                    <p class="text-gray-600">জ্ঞানের আলো ছড়িয়ে দিচ্ছি</p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('styles')
<style>
<<<<<<< HEAD
=======

    
>>>>>>> c356163 (video call ui setup)
    .backdrop-blur-sm {
        backdrop-filter: blur(8px);
    }
</style>
@endpush
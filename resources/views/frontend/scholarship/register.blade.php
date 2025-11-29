@extends('layouts.global')

@section('title', 'স্কলারশিপ নিবন্ধন')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-4">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
        <!-- Mobile Header -->
        <div class="lg:hidden text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2 {{ detectLanguageClass('স্কলারশিপ নিবন্ধন') }}">স্কলারশিপ নিবন্ধন</h1>
            <p class="text-gray-600 text-sm {{ detectLanguageClass('আমাদের মর্যাদাপূর্ণ স্কলারশিপ প্রোগ্রামের জন্য নিবন্ধন করুন') }}">
                আমাদের মর্যাদাপূর্ণ স্কলারশিপ প্রোগ্রামের জন্য নিবন্ধন করুন
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
            <!-- Sidebar Navigation - Positioned for mobile first -->
            <div class="lg:w-80 xl:w-96">
                <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 sticky top-4">
                    <!-- Progress Indicator -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 {{ detectLanguageClass('নিবন্ধন অগ্রগতি') }}">নিবন্ধন অগ্রগতি</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold mr-3">
                                    ১
                                </div>
                                <span class="text-gray-700 font-medium {{ detectLanguageClass('প্রোগ্রাম তথ্য') }}">প্রোগ্রাম তথ্য</span>
                            </div>
                            <div class="flex items-center opacity-60">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-semibold mr-3">
                                    ২
                                </div>
                                <span class="text-gray-500 {{ detectLanguageClass('আবেদন ফর্ম') }}">আবেদন ফর্ম</span>
                            </div>
                            <div class="flex items-center opacity-60">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-semibold mr-3">
                                    ৩
                                </div>
                                <span class="text-gray-500 {{ detectLanguageClass('পর্যালোচনা ও জমা') }}">পর্যালোচনা ও জমা</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Navigation -->
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 {{ detectLanguageClass('দ্রুত লিংক') }}">দ্রুত লিংক</h4>
                        <nav class="space-y-2">
                            <a href="#program-info" class="flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium {{ detectLanguageClass('প্রোগ্রাম তথ্য') }}">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                প্রোগ্রাম তথ্য
                            </a>
                            <a href="#benefits" class="flex items-center text-gray-600 hover:text-gray-700 text-sm {{ detectLanguageClass('স্কলারশিপ সুবিধা') }}">
                                <i class="fas fa-gift mr-2 text-green-500"></i>
                                স্কলারশিপ সুবিধা
                            </a>
                            <a href="#eligibility" class="flex items-center text-gray-600 hover:text-gray-700 text-sm {{ detectLanguageClass('যোগ্যতার মানদণ্ড') }}">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                যোগ্যতার মানদণ্ড
                            </a>
                            <a href="#exam-pattern" class="flex items-center text-gray-600 hover:text-gray-700 text-sm {{ detectLanguageClass('পরীক্ষার প্যাটার্ন') }}">
                                <i class="fas fa-clipboard-list mr-2 text-purple-500"></i>
                                পরীক্ষার প্যাটার্ন
                            </a>
                            <a href="#important-dates" class="flex items-center text-gray-600 hover:text-gray-700 text-sm {{ detectLanguageClass('গুরুত্বপূর্ণ তারিখসমূহ') }}">
                                <i class="fas fa-calendar-alt mr-2 text-red-500"></i>
                                গুরুত্বপূর্ণ তারিখসমূহ
                            </a>
                        </nav>
                    </div>

                    <!-- Call to Action -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs text-blue-700 font-medium mb-2 {{ detectLanguageClass('আবেদন করতে প্রস্তুত?') }}">আবেদন করতে প্রস্তুত?</p>
                            <button onclick="startApplication()" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors {{ detectLanguageClass('আবেদন শুরু করুন') }}">
                                আবেদন শুরু করুন
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1">
                <!-- Part 1: Program Information -->
                <section id="program-info" class="bg-white rounded-xl shadow-lg p-4 lg:p-6 mb-4 lg:mb-6">
                    <div class="flex items-center mb-4">
                        <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white text-xs font-bold">১</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 {{ detectLanguageClass('স্কলারশিপ প্রোগ্রাম সম্পর্কে') }}">স্কলারশিপ প্রোগ্রাম সম্পর্কে</h2>
                    </div>

                    <!-- Available Exams -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 {{ detectLanguageClass('উপলব্ধ পরীক্ষাসমূহ') }}">উপলব্ধ পরীক্ষাসমূহ</h3>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach($availableExams as $exam)
                            <div class="border border-blue-200 rounded-lg p-3 bg-blue-50">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-900 text-sm {{ detectLanguageClass($exam->title) }}">{{ $exam->title }}</h4>
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium {{ detectLanguageClass($exam->status) }}">
                                        {{ $exam->status }}
                                    </span>
                                </div>
                                <p class="text-gray-600 text-xs mb-2 {{ detectLanguageClass($exam->description) }}">{{ Str::limit($exam->description, 80) }}</p>
                                <div class="space-y-1 text-xs text-gray-500 {{ detectLanguageClass('তারিখ সময়') }}">
                                    <div class="flex justify-between">
                                        <span>তারিখ:</span>
                                        <span class="font-medium">{{ $exam->exam_date->format('M j, Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>সময়:</span>
                                        <span class="font-medium">{{ $exam->duration_minutes }} মিনিট</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Program Objectives -->
                    <div class="mb-6" id="objectives">
                        <h3 class="text-lg font-semibold text-blue-700 mb-3 flex items-center">
                            <i class="fas fa-bullseye mr-2 text-blue-600"></i>
                            <span class="{{ detectLanguageClass('প্রোগ্রামের উদ্দেশ্য') }}">প্রোগ্রামের উদ্দেশ্য</span>
                        </h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-600 text-sm">
                            @php
                                $objectives = [
                                    'একাডেমিকভাবে মেধাবী শিক্ষার্থীদের চিহ্নিত করা ও পুরস্কৃত করা',
                                    'মানসম্মত শিক্ষার জন্য আর্থিক সহায়তা প্রদান',
                                    'একাডেমিক পারফরম্যান্সে উৎকর্ষকে উৎসাহিত করা',
                                    'সব ধরনের পটভূমির যোগ্য শিক্ষার্থীদের সহায়তা করা'
                                ];
                            @endphp
                            @foreach($objectives as $objective)
                                <li class="{{ detectLanguageClass($objective) }}">{{ $objective }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Scholarship Benefits -->
                    <div class="mb-6" id="benefits">
                        <h3 class="text-lg font-semibold text-green-700 mb-3 flex items-center">
                            <i class="fas fa-gift mr-2 text-green-600"></i>
                            <span class="{{ detectLanguageClass('স্কলারশিপ সুবিধা') }}">স্কলারশিপ সুবিধা</span>
                        </h3>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @php
                                $benefits = [
                                    [
                                        'title' => '🏆 সম্পূর্ণ স্কলারশিপ',
                                        'description' => 'শীর্ষ ৩ শিক্ষার্থীর জন্য ১০০% টিউশন ফি মওকুফ',
                                        'color' => 'green'
                                    ],
                                    [
                                        'title' => '🎯 আংশিক স্কলারশিপ',
                                        'description' => 'পরবর্তী ৭ শিক্ষার্থীর জন্য ৫০% টিউশন ফি মওকুফ',
                                        'color' => 'blue'
                                    ],
                                    [
                                        'title' => '⭐ মেধা পুরস্কার',
                                        'description' => 'বিশেষ স্বীকৃতি এবং সার্টিফিকেট',
                                        'color' => 'purple'
                                    ],
                                    [
                                        'title' => '📚 অতিরিক্ত সুবিধা',
                                        'description' => 'বই অনুদান ও শিক্ষা উপকরণ',
                                        'color' => 'orange'
                                    ]
                                ];
                            @endphp
                            @foreach($benefits as $benefit)
                                <div class="bg-{{ $benefit['color'] }}-50 border border-{{ $benefit['color'] }}-200 rounded-lg p-3">
                                    <h4 class="font-semibold text-{{ $benefit['color'] }}-800 text-sm mb-1 {{ detectLanguageClass($benefit['title']) }}">{{ $benefit['title'] }}</h4>
                                    <p class="text-{{ $benefit['color'] }}-700 text-xs {{ detectLanguageClass($benefit['description']) }}">{{ $benefit['description'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Eligibility Criteria -->
                    <div class="mb-6" id="eligibility">
                        <h3 class="text-lg font-semibold text-purple-700 mb-3 flex items-center">
                            <i class="fas fa-check-circle mr-2 text-purple-600"></i>
                            <span class="{{ detectLanguageClass('যোগ্যতার মানদণ্ড') }}">যোগ্যতার মানদণ্ড</span>
                        </h3>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <ul class="space-y-2 text-sm text-purple-700">
                                @php
                                    $eligibility = [
                                        'আমাদের প্রতিষ্ঠানের বর্তমান শিক্ষার্থী হতে হবে',
                                        'ন্যূনতম ৭৫% উপস্থিতি রেকর্ড',
                                        'শৃঙ্খলামূলক সমস্যা ছাড়াই ভাল একাডেমিক অবস্থান',
                                        'সমস্ত প্রয়োজনীয় কোর্সওয়ার্ক সম্পন্ন'
                                    ];
                                @endphp
                                @foreach($eligibility as $item)
                                    <li class="flex items-start {{ detectLanguageClass($item) }}">
                                        <i class="fas fa-user-graduate mt-1 mr-2 text-purple-600"></i>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Next Section Button -->
                    <div class="flex justify-end">
                        <button onclick="startApplication()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium text-sm transition-colors flex items-center {{ detectLanguageClass('আবেদনে এগিয়ে যান') }}">
                            আবেদনে এগিয়ে যান
                            <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </section>

                <!-- Part 2: Application Form -->
                <section id="part2" class="bg-white rounded-xl shadow-lg p-4 lg:p-6 mb-4 lg:mb-6 hidden">
                    <div class="flex items-center mb-4">
                        <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white text-xs font-bold">২</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 {{ detectLanguageClass('আবেদন ফর্ম') }}">আবেদন ফর্ম</h2>
                    </div>

                    <form action="{{ route('student.scholarship.register.submit') }}" method="POST" id="registrationForm">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Academic Background -->
                            <div>
                                <label for="academic_background" class="block text-sm font-medium text-gray-700 mb-2 {{ detectLanguageClass('একাডেমিক ব্যাকগ্রাউন্ড ও পারফরম্যান্স') }}">
                                    একাডেমিক ব্যাকগ্রাউন্ড ও পারফরম্যান্স *
                                </label>
                                <textarea 
                                    id="academic_background" 
                                    name="academic_background" 
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    placeholder="আপনার একাডেমিক পারফরম্যান্স, যে বিষয়গুলোতে আপনি দক্ষ, কোনো একাডেমিক পুরস্কার বা স্বীকৃতি বর্ণনা করুন..."
                                    required
                                >{{ old('academic_background') }}</textarea>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500 {{ detectLanguageClass('ন্যূনতম ১০০ অক্ষর') }}">ন্যূনতম ১০০ অক্ষর</p>
                                    <span id="academic_count" class="text-xs text-gray-500">০/১০০</span>
                                </div>
                                @error('academic_background')
                                    <p class="mt-1 text-sm text-red-600 {{ detectLanguageClass($message) }}">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Extracurricular Activities -->
                            <div>
                                <label for="extracurricular_activities" class="block text-sm font-medium text-gray-700 mb-2 {{ detectLanguageClass('এক্সট্রা কারিকুলাম কার্যক্রম') }}">
                                    এক্সট্রা কারিকুলাম কার্যক্রম *
                                </label>
                                <textarea 
                                    id="extracurricular_activities" 
                                    name="extracurricular_activities" 
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    placeholder="খেলাধুলা, ক্লাব, কমিউনিটি সার্ভিস বা অন্যান্য কার্যক্রমে আপনার অংশগ্রহণ বর্ণনা করুন..."
                                    required
                                >{{ old('extracurricular_activities') }}</textarea>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500 {{ detectLanguageClass('ন্যূনতম ৫০ অক্ষর') }}">ন্যূনতম ৫০ অক্ষর</p>
                                    <span id="extracurricular_count" class="text-xs text-gray-500">০/৫০</span>
                                </div>
                                @error('extracurricular_activities')
                                    <p class="mt-1 text-sm text-red-600 {{ detectLanguageClass($message) }}">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Achievements -->
                            <div>
                                <label for="achievements" class="block text-sm font-medium text-gray-700 mb-2 {{ detectLanguageClass('অর্জন ও পুরস্কার') }}">
                                    অর্জন ও পুরস্কার *
                                </label>
                                <textarea 
                                    id="achievements" 
                                    name="achievements" 
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    placeholder="আপনার যে কোনো উল্লেখযোগ্য অর্জন, পুরস্কার বা স্বীকৃতির তালিকা করুন..."
                                    required
                                >{{ old('achievements') }}</textarea>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500 {{ detectLanguageClass('ন্যূনতম ৫০ অক্ষর') }}">ন্যূনতম ৫০ অক্ষর</p>
                                    <span id="achievements_count" class="text-xs text-gray-500">০/৫০</span>
                                </div>
                                @error('achievements')
                                    <p class="mt-1 text-sm text-red-600 {{ detectLanguageClass($message) }}">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Reason for Applying -->
                            <div>
                                <label for="reason_for_applying" class="block text-sm font-medium text-gray-700 mb-2 {{ detectLanguageClass('আপনি কেন এই স্কলারশিপের জন্য যোগ্য?') }}">
                                    আপনি কেন এই স্কলারশিপের জন্য যোগ্য? *
                                </label>
                                <textarea 
                                    id="reason_for_applying" 
                                    name="reason_for_applying" 
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    placeholder="ব্যাখ্যা করুন কেন আপনি এই স্কলারশিপের জন্য আবেদন করছেন এবং এটি কীভাবে আপনার শিক্ষাগত লক্ষ্য অর্জনে সাহায্য করবে..."
                                    required
                                >{{ old('reason_for_applying') }}</textarea>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500 {{ detectLanguageClass('ন্যূনতম ১০০ অক্ষর') }}">ন্যূনতম ১০০ অক্ষর</p>
                                    <span id="reason_count" class="text-xs text-gray-500">০/১০০</span>
                                </div>
                                @error('reason_for_applying')
                                    <p class="mt-1 text-sm text-red-600 {{ detectLanguageClass($message) }}">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex flex-col sm:flex-row justify-between gap-3 pt-4">
                                <button type="button" onclick="showPart(1)" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium text-sm transition-colors flex items-center justify-center order-2 sm:order-1 {{ detectLanguageClass('তথ্যে ফিরে যান') }}">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    তথ্যে ফিরে যান
                                </button>
                                <button type="button" onclick="showPart(3)" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium text-sm transition-colors flex items-center justify-center order-1 sm:order-2 {{ detectLanguageClass('পর্যালোচনায় এগিয়ে যান') }}">
                                    পর্যালোচনায় এগিয়ে যান
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </section>

                <!-- Part 3: Review & Submit -->
                <section id="part3" class="bg-white rounded-xl shadow-lg p-4 lg:p-6 mb-4 lg:mb-6 hidden">
                    <div class="flex items-center mb-4">
                        <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                            <span class="text-white text-xs font-bold">৩</span>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900 {{ detectLanguageClass('পর্যালোচনা ও জমা') }}">পর্যালোচনা ও জমা</h2>
                    </div>

                    <!-- Exam Pattern -->
                    <div class="mb-6" id="exam-pattern">
                        <h3 class="text-lg font-semibold text-red-700 mb-3 flex items-center">
                            <i class="fas fa-clipboard-list mr-2 text-red-600"></i>
                            <span class="{{ detectLanguageClass('পরীক্ষার প্যাটার্ন') }}">পরীক্ষার প্যাটার্ন</span>
                        </h3>
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            @php
                                $examPatterns = [
                                    ['value' => '২-৩ ঘন্টা', 'label' => 'সময়'],
                                    ['value' => 'MCQ', 'label' => 'প্রশ্নের ধরন'],
                                    ['value' => '৪ বিষয়', 'label' => 'কভারেজ'],
                                    ['value' => '+৪ / ০', 'label' => 'মার্কিং']
                                ];
                            @endphp
                            @foreach($examPatterns as $pattern)
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                                    <div class="text-red-600 text-lg font-bold mb-1 {{ detectLanguageClass($pattern['value']) }}">{{ $pattern['value'] }}</div>
                                    <div class="text-red-700 text-xs font-medium {{ detectLanguageClass($pattern['label']) }}">{{ $pattern['label'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Important Dates -->
                    <div class="mb-6" id="important-dates">
                        <h3 class="text-lg font-semibold text-orange-700 mb-3 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-orange-600"></i>
                            <span class="{{ detectLanguageClass('গুরুত্বপূর্ণ তারিখসমূহ') }}">গুরুত্বপূর্ণ তারিখসমূহ</span>
                        </h3>
                        <div class="space-y-2 text-sm">
                            @php
                                $importantDates = [
                                    ['label' => 'নিবন্ধনের শেষ তারিখ', 'value' => 'পরীক্ষার ৭ দিন আগে'],
                                    ['label' => 'অ্যাডমিট কার্ড উপলব্ধ', 'value' => 'পরীক্ষার ৩ দিন আগে'],
                                    ['label' => 'ফলাফল ঘোষণা', 'value' => '১৫ দিনের মধ্যে'],
                                    ['label' => 'পুরস্কার বিতরণী', 'value' => 'ফলাফলের ১ মাস পরে']
                                ];
                            @endphp
                            @foreach($importantDates as $date)
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-gray-600 {{ detectLanguageClass($date['label']) }}">{{ $date['label'] }}</span>
                                    <span class="font-semibold text-gray-900 {{ detectLanguageClass($date['value']) }}">{{ $date['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-yellow-800 mb-3 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i>
                            <span class="{{ detectLanguageClass('গুরুত্বপূর্ণ নোট') }}">গুরুত্বপূর্ণ নোট</span>
                        </h4>
                        <ul class="space-y-1 text-yellow-700 text-sm">
                            @php
                                $importantNotes = [
                                    'নির্দিষ্ট সময়সীমার আগে নিবন্ধন সম্পন্ন করতে হবে',
                                    'সমস্ত তথ্য সঠিক এবং যাচাইযোগ্য হতে হবে',
                                    'ভুল তথ্য দেওয়া হলে অযোগ্য ঘোষণা করা হবে',
                                    'পরীক্ষার এক্সেসের আগে অ্যাডমিন অনুমোদন প্রয়োজন'
                                ];
                            @endphp
                            @foreach($importantNotes as $note)
                                <li class="flex items-start {{ detectLanguageClass($note) }}">
                                    <i class="fas fa-clock mt-1 mr-2 text-yellow-600 text-xs"></i>
                                    <span>{{ $note }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Declaration -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="flex items-start">
                            <input 
                                type="checkbox" 
                                id="declaration" 
                                name="declaration"
                                class="mt-1 mr-3 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                required
                            >
                            <label for="declaration" class="text-sm text-gray-700 {{ detectLanguageClass('আমি এতদ্বারা ঘোষণা করছি যে এই আবেদনে প্রদত্ত সমস্ত তথ্য আমার জ্ঞানে সত্য ও সঠিক। আমি বুঝতে পারছি যে কোনো ভুল তথ্য প্রদান স্কলারশিপ প্রোগ্রাম থেকে অযোগ্য ঘোষণার কারণ হতে পারে।') }}">
                                আমি এতদ্বারা ঘোষণা করছি যে এই আবেদনে প্রদত্ত সমস্ত তথ্য আমার জ্ঞানে সত্য ও সঠিক। আমি বুঝতে পারছি যে কোনো ভুল তথ্য প্রদান স্কলারশিপ প্রোগ্রাম থেকে অযোগ্য ঘোষণার কারণ হতে পারে।
                            </label>
                        </div>
                        @error('declaration')
                            <p class="mt-1 text-sm text-red-600 {{ detectLanguageClass($message) }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between gap-3">
                        <button type="button" onclick="showPart(2)" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium text-sm transition-colors flex items-center justify-center {{ detectLanguageClass('ফর্মে ফিরে যান') }}">
                            <i class="fas fa-arrow-left mr-2"></i>
                            ফর্মে ফিরে যান
                        </button>
                        <button type="submit" form="registrationForm" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold text-sm transition-all duration-200 transform hover:scale-105 flex items-center justify-center shadow-lg {{ detectLanguageClass('নিবন্ধন জমা দিন') }}">
                            <i class="fas fa-paper-plane mr-2"></i>
                            নিবন্ধন জমা দিন
                        </button>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<script>
// Character counters
function setupCharacterCounters() {
    const fields = {
        'academic_background': 100,
        'extracurricular_activities': 50,
        'achievements': 50,
        'reason_for_applying': 100
    };

    Object.keys(fields).forEach(fieldId => {
        const field = document.getElementById(fieldId);
        const counter = document.getElementById(fieldId + '_count');
        
        if (field && counter) {
            field.addEventListener('input', function() {
                const count = this.value.length;
                const minLength = fields[fieldId];
                counter.textContent = `${count}/${minLength}`;
                
                if (count < minLength) {
                    counter.classList.remove('text-green-600');
                    counter.classList.add('text-red-600');
                } else {
                    counter.classList.remove('text-red-600');
                    counter.classList.add('text-green-600');
                }
            });
            
            // Trigger initial count
            field.dispatchEvent(new Event('input'));
        }
    });
}

function showPart(partNumber) {
    @if(!Auth::check())
        // User is not logged in, show auth modal
        const scholarshipData = {
            action: 'scholarship_registration',
            target_part: partNumber
        };
        showAuthModal(scholarshipData);
        return;
    @endif

    // User is logged in, proceed normally
    proceedToPart(partNumber);
}

function proceedToPart(partNumber) {
    // Hide all parts
    document.getElementById('program-info').classList.add('hidden');
    document.getElementById('part2').classList.add('hidden');
    document.getElementById('part3').classList.add('hidden');
    
    // Show selected part
    if (partNumber === 1) {
        document.getElementById('program-info').classList.remove('hidden');
    } else if (partNumber === 2) {
        document.getElementById('part2').classList.remove('hidden');
    } else if (partNumber === 3) {
        document.getElementById('part3').classList.remove('hidden');
    }
    
    // Update progress indicator
    updateProgressIndicator(partNumber);
    
    // Scroll to top of the section
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateProgressIndicator(currentPart) {
    const progressItems = document.querySelectorAll('.space-y-3 > .flex.items-center');
    
    progressItems.forEach((item, index) => {
        const partNumber = index + 1;
        const circle = item.querySelector('div');
        const text = item.querySelector('span');
        
        if (partNumber < currentPart) {
            // Completed part
            circle.className = 'w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center text-sm font-semibold mr-3';
            circle.innerHTML = '<i class="fas fa-check text-xs"></i>';
            text.className = 'text-gray-700 font-medium';
        } else if (partNumber === currentPart) {
            // Current part
            circle.className = 'w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold mr-3';
            circle.innerHTML = partNumber;
            text.className = 'text-gray-700 font-medium';
        } else {
            // Future part
            circle.className = 'w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-semibold mr-3';
            circle.innerHTML = partNumber;
            text.className = 'text-gray-500';
        }
    });
}

// Function to handle scholarship registration after login
window.handlePendingScholarship = function() {
    if (window.pendingAppointmentData && window.pendingAppointmentData.target_part) {
        proceedToPart(window.pendingAppointmentData.target_part);
    }
};

// Override the global handlePendingAppointment for scholarship
window.handlePendingAppointment = function() {
    if (window.pendingAppointmentData && window.pendingAppointmentData.action === 'scholarship_registration') {
        window.handlePendingScholarship();
    } else {
        // Handle regular appointment flow
        if (window.pendingAppointmentData && window.pendingAppointmentData.doctor_id) {
            openAppointmentModal(window.pendingAppointmentData.doctor_id);
        }
    }
};

function startApplication() {
    showPart(2);
}

function scrollToForm() {
    startApplication();
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    setupCharacterCounters();
    updateProgressIndicator(1);
    
    // Smooth scrolling for sidebar links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Form validation
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    const fields = {
        'academic_background': 100,
        'extracurricular_activities': 50,
        'achievements': 50,
        'reason_for_applying': 100
    };

    let isValid = true;
    let firstInvalidField = null;

    Object.keys(fields).forEach(fieldId => {
        const field = document.getElementById(fieldId);
        const minLength = fields[fieldId];
        
        if (field && field.value.length < minLength) {
            isValid = false;
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
            
            // Highlight the field
            field.classList.add('border-red-500', 'bg-red-50');
        } else {
            field.classList.remove('border-red-500', 'bg-red-50');
        }
    });

    // Check declaration
    const declaration = document.getElementById('declaration');
    if (!declaration.checked) {
        isValid = false;
        declaration.classList.add('border-red-500');
    } else {
        declaration.classList.remove('border-red-500');
    }

    if (!isValid) {
        e.preventDefault();
        showPart(2);
        if (firstInvalidField) {
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidField.focus();
        }
        alert('অনুগ্রহ করে সমস্ত প্রয়োজনীয় ফিল্ড ন্যূনতম অক্ষর সংখ্যা সহ পূরণ করুন এবং ঘোষণাপত্রে সম্মতি দিন।');
    }
});

</script>

<style>
/* Custom scrollbar for better mobile experience */
@media (max-width: 768px) {
    html {
        scroll-behavior: smooth;
    }
    
    /* Improve touch targets */
    button, input, textarea, select {
        font-size: 16px; /* Prevents zoom on iOS */
    }
}

/* Ensure sticky sidebar works well on mobile */
@media (max-width: 1024px) {
    .sticky {
        position: relative;
        top: 0;
    }
}

/* Better focus states for accessibility */
button:focus, input:focus, textarea:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

/* Font classes */
.tiro {
    font-family: 'Tiro Bangla', serif;
}

.inter {
    font-family: 'Inter', sans-serif;
}
</style>
@endsection
@extends('layouts.student')

@section('title', 'স্কলারশিপ নিবন্ধন')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 py-4">
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-6">
        <!-- Mobile Header -->
        <div class="lg:hidden text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2 tiro">স্কলারশিপ নিবন্ধন</h1>
            <p class="text-gray-600 text-sm tiro">
                আমাদের মর্যাদাপূর্ণ স্কলারশিপ প্রোগ্রামের জন্য নিবন্ধন করুন
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
            <!-- Sidebar Navigation - Positioned for mobile first -->
            <div class="lg:w-80 xl:w-96">
                <div class="bg-white rounded-xl shadow-lg p-4 lg:p-6 sticky top-4">
                    <!-- Progress Indicator -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 tiro">নিবন্ধন অগ্রগতি</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold mr-3">
                                    ১
                                </div>
                                <span class="text-gray-700 font-medium tiro">প্রোগ্রাম তথ্য</span>
                            </div>
                            <div class="flex items-center opacity-60">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-semibold mr-3">
                                    ২
                                </div>
                                <span class="text-gray-500 tiro">আবেদন ফর্ম</span>
                            </div>
                            <div class="flex items-center opacity-60">
                                <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-semibold mr-3">
                                    ৩
                                </div>
                                <span class="text-gray-500 tiro">পর্যালোচনা ও জমা</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Navigation -->
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3 tiro">দ্রুত লিংক</h4>
                        <nav class="space-y-2">
                            <a href="#program-info" class="flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium tiro">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                প্রোগ্রাম তথ্য
                            </a>
                            <a href="#benefits" class="flex items-center text-gray-600 hover:text-gray-700 text-sm tiro">
                                <i class="fas fa-gift mr-2 text-green-500"></i>
                                স্কলারশিপ সুবিধা
                            </a>
                            <a href="#eligibility" class="flex items-center text-gray-600 hover:text-gray-700 text-sm tiro">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                যোগ্যতার মানদণ্ড
                            </a>
                            <a href="#exam-pattern" class="flex items-center text-gray-600 hover:text-gray-700 text-sm tiro">
                                <i class="fas fa-clipboard-list mr-2 text-purple-500"></i>
                                পরীক্ষার প্যাটার্ন
                            </a>
                            <a href="#important-dates" class="flex items-center text-gray-600 hover:text-gray-700 text-sm tiro">
                                <i class="fas fa-calendar-alt mr-2 text-red-500"></i>
                                গুরুত্বপূর্ণ তারিখসমূহ
                            </a>
                        </nav>
                    </div>

                    <!-- Call to Action -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="bg-blue-50 rounded-lg p-3">
                            <p class="text-xs text-blue-700 font-medium mb-2 tiro">আবেদন করতে প্রস্তুত?</p>
                            <button onclick="scrollToForm()" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors tiro">
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
                        <h2 class="text-xl font-bold text-gray-900 tiro">স্কলারশিপ প্রোগ্রাম সম্পর্কে</h2>
                    </div>

                    <!-- Available Exams -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3 tiro">উপলব্ধ পরীক্ষাসমূহ</h3>
                        <div class="grid gap-3 sm:grid-cols-2">
                            @foreach($availableExams as $exam)
                            <div class="border border-blue-200 rounded-lg p-3 bg-blue-50">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-900 text-sm tiro">{{ $exam->title }}</h4>
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-medium tiro">
                                        {{ $exam->status }}
                                    </span>
                                </div>
                                <p class="text-gray-600 text-xs mb-2 tiro">{{ Str::limit($exam->description, 80) }}</p>
                                <div class="space-y-1 text-xs text-gray-500 tiro">
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
                            <span class="tiro">প্রোগ্রামের উদ্দেশ্য</span>
                        </h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-600 text-sm tiro">
                            <li>একাডেমিকভাবে মেধাবী শিক্ষার্থীদের চিহ্নিত করা ও পুরস্কৃত করা</li>
                            <li>মানসম্মত শিক্ষার জন্য আর্থিক সহায়তা প্রদান</li>
                            <li>একাডেমিক পারফরম্যান্সে উৎকর্ষকে উৎসাহিত করা</li>
                            <li>সব ধরনের পটভূমির যোগ্য শিক্ষার্থীদের সহায়তা করা</li>
                        </ul>
                    </div>

                    <!-- Scholarship Benefits -->
                    <div class="mb-6" id="benefits">
                        <h3 class="text-lg font-semibold text-green-700 mb-3 flex items-center">
                            <i class="fas fa-gift mr-2 text-green-600"></i>
                            <span class="tiro">স্কলারশিপ সুবিধা</span>
                        </h3>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <h4 class="font-semibold text-green-800 text-sm mb-1 tiro">🏆 সম্পূর্ণ স্কলারশিপ</h4>
                                <p class="text-green-700 text-xs tiro">শীর্ষ ৩ শিক্ষার্থীর জন্য ১০০% টিউশন ফি মওকুফ</p>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <h4 class="font-semibold text-blue-800 text-sm mb-1 tiro">🎯 আংশিক স্কলারশিপ</h4>
                                <p class="text-blue-700 text-xs tiro">পরবর্তী ৭ শিক্ষার্থীর জন্য ৫০% টিউশন ফি মওকুফ</p>
                            </div>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                                <h4 class="font-semibold text-purple-800 text-sm mb-1 tiro">⭐ মেধা পুরস্কার</h4>
                                <p class="text-purple-700 text-xs tiro">বিশেষ স্বীকৃতি এবং সার্টিফিকেট</p>
                            </div>
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                                <h4 class="font-semibold text-orange-800 text-sm mb-1 tiro">📚 অতিরিক্ত সুবিধা</h4>
                                <p class="text-orange-700 text-xs tiro">বই অনুদান ও শিক্ষা উপকরণ</p>
                            </div>
                        </div>
                    </div>

                    <!-- Eligibility Criteria -->
                    <div class="mb-6" id="eligibility">
                        <h3 class="text-lg font-semibold text-purple-700 mb-3 flex items-center">
                            <i class="fas fa-check-circle mr-2 text-purple-600"></i>
                            <span class="tiro">যোগ্যতার মানদণ্ড</span>
                        </h3>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <ul class="space-y-2 text-sm text-purple-700 tiro">
                                <li class="flex items-start">
                                    <i class="fas fa-user-graduate mt-1 mr-2 text-purple-600"></i>
                                    <span>আমাদের প্রতিষ্ঠানের বর্তমান শিক্ষার্থী হতে হবে</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-calendar-check mt-1 mr-2 text-purple-600"></i>
                                    <span>ন্যূনতম ৭৫% উপস্থিতি রেকর্ড</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-award mt-1 mr-2 text-purple-600"></i>
                                    <span>শৃঙ্খলামূলক সমস্যা ছাড়াই ভাল একাডেমিক অবস্থান</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-tasks mt-1 mr-2 text-purple-600"></i>
                                    <span>সমস্ত প্রয়োজনীয় কোর্সওয়ার্ক সম্পন্ন</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Next Section Button -->
                    <div class="flex justify-end">
                        <button onclick="showPart(2)" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium text-sm transition-colors flex items-center tiro">
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
                        <h2 class="text-xl font-bold text-gray-900 tiro">আবেদন ফর্ম</h2>
                    </div>

                    <form action="{{ route('student.scholarship.register.submit') }}" method="POST" id="registrationForm">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Academic Background -->
                            <div>
                                <label for="academic_background" class="block text-sm font-medium text-gray-700 mb-2 tiro">
                                    একাডেমিক  ব্যাকগ্রাউন্ড ও পারফরম্যান্স *
                                </label>
                                <textarea 
                                    id="academic_background" 
                                    name="academic_background" 
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm tiro"
                                    placeholder="আপনার একাডেমিক পারফরম্যান্স, যে বিষয়গুলোতে আপনি দক্ষ, কোনো একাডেমিক পুরস্কার বা স্বীকৃতি বর্ণনা করুন..."
                                    required
                                >{{ old('academic_background') }}</textarea>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500 tiro">ন্যূনতম ১০০ অক্ষর</p>
                                    <span id="academic_count" class="text-xs text-gray-500 tiro">০/১০০</span>
                                </div>
                                @error('academic_background')
                                    <p class="mt-1 text-sm text-red-600 tiro">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Extracurricular Activities -->
                            <div>
                                <label for="extracurricular_activities" class="block text-sm font-medium text-gray-700 mb-2 tiro">
                                    এক্সট্রা কারিকুলাম কার্যক্রম *
                                </label>
                                <textarea 
                                    id="extracurricular_activities" 
                                    name="extracurricular_activities" 
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm tiro"
                                    placeholder="খেলাধুলা, ক্লাব, কমিউনিটি সার্ভিস বা অন্যান্য কার্যক্রমে আপনার অংশগ্রহণ বর্ণনা করুন..."
                                    required
                                >{{ old('extracurricular_activities') }}</textarea>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500 tiro">ন্যূনতম ৫০ অক্ষর</p>
                                    <span id="extracurricular_count" class="text-xs text-gray-500 tiro">০/৫০</span>
                                </div>
                                @error('extracurricular_activities')
                                    <p class="mt-1 text-sm text-red-600 tiro">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Achievements -->
                            <div>
                                <label for="achievements" class="block text-sm font-medium text-gray-700 mb-2 tiro">
                                    অর্জন ও পুরস্কার *
                                </label>
                                <textarea 
                                    id="achievements" 
                                    name="achievements" 
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm tiro"
                                    placeholder="আপনার যে কোনো উল্লেখযোগ্য অর্জন, পুরস্কার বা স্বীকৃতির তালিকা করুন..."
                                    required
                                >{{ old('achievements') }}</textarea>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500 tiro">ন্যূনতম ৫০ অক্ষর</p>
                                    <span id="achievements_count" class="text-xs text-gray-500 tiro">০/৫০</span>
                                </div>
                                @error('achievements')
                                    <p class="mt-1 text-sm text-red-600 tiro">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Reason for Applying -->
                            <div>
                                <label for="reason_for_applying" class="block text-sm font-medium text-gray-700 mb-2 tiro">
                                    আপনি কেন এই স্কলারশিপের জন্য যোগ্য? *
                                </label>
                                <textarea 
                                    id="reason_for_applying" 
                                    name="reason_for_applying" 
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm tiro"
                                    placeholder="ব্যাখ্যা করুন কেন আপনি এই স্কলারশিপের জন্য আবেদন করছেন এবং এটি কীভাবে আপনার শিক্ষাগত লক্ষ্য অর্জনে সাহায্য করবে..."
                                    required
                                >{{ old('reason_for_applying') }}</textarea>
                                <div class="flex justify-between items-center mt-1">
                                    <p class="text-xs text-gray-500 tiro">ন্যূনতম ১০০ অক্ষর</p>
                                    <span id="reason_count" class="text-xs text-gray-500 tiro">০/১০০</span>
                                </div>
                                @error('reason_for_applying')
                                    <p class="mt-1 text-sm text-red-600 tiro">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex flex-col sm:flex-row justify-between gap-3 pt-4">
                                <button type="button" onclick="showPart(1)" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium text-sm transition-colors flex items-center justify-center order-2 sm:order-1 tiro">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    তথ্যে ফিরে যান
                                </button>
                                <button type="button" onclick="showPart(3)" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium text-sm transition-colors flex items-center justify-center order-1 sm:order-2 tiro">
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
                        <h2 class="text-xl font-bold text-gray-900 tiro">পর্যালোচনা ও জমা</h2>
                    </div>

                    <!-- Exam Pattern -->
                    <div class="mb-6" id="exam-pattern">
                        <h3 class="text-lg font-semibold text-red-700 mb-3 flex items-center">
                            <i class="fas fa-clipboard-list mr-2 text-red-600"></i>
                            <span class="tiro">পরীক্ষার প্যাটার্ন</span>
                        </h3>
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                                <div class="text-red-600 text-lg font-bold mb-1">২-৩ ঘন্টা</div>
                                <div class="text-red-700 text-xs font-medium tiro">সময়</div>
                            </div>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                                <div class="text-blue-600 text-lg font-bold mb-1 tiro">MCQ</div>
                                <div class="text-blue-700 text-xs font-medium tiro">প্রশ্নের ধরন</div>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                                <div class="text-green-600 text-lg font-bold mb-1">৪ বিষয়</div>
                                <div class="text-green-700 text-xs font-medium tiro">কভারেজ</div>
                            </div>
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 text-center">
                                <div class="text-purple-600 text-lg font-bold mb-1 tiro">+৪ / ০</div>
                                <div class="text-purple-700 text-xs font-medium tiro">মার্কিং</div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Dates -->
                    <div class="mb-6" id="important-dates">
                        <h3 class="text-lg font-semibold text-orange-700 mb-3 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-orange-600"></i>
                            <span class="tiro">গুরুত্বপূর্ণ তারিখসমূহ</span>
                        </h3>
                        <div class="space-y-2 text-sm tiro">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-600">নিবন্ধনের শেষ তারিখ</span>
                                <span class="font-semibold text-gray-900">পরীক্ষার ৭ দিন আগে</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-600">অ্যাডমিট কার্ড উপলব্ধ</span>
                                <span class="font-semibold text-gray-900">পরীক্ষার ৩ দিন আগে</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-600">ফলাফল ঘোষণা</span>
                                <span class="font-semibold text-gray-900">১৫ দিনের মধ্যে</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">পুরস্কার বিতরণী</span>
                                <span class="font-semibold text-gray-900">ফলাফলের ১ মাস পরে</span>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notes -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-yellow-800 mb-3 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2 text-yellow-600"></i>
                            <span class="tiro">গুরুত্বপূর্ণ নোট</span>
                        </h4>
                        <ul class="space-y-1 text-yellow-700 text-sm tiro">
                            <li class="flex items-start">
                                <i class="fas fa-clock mt-1 mr-2 text-yellow-600 text-xs"></i>
                                <span>নির্দিষ্ট সময়সীমার আগে নিবন্ধন সম্পন্ন করতে হবে</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle mt-1 mr-2 text-yellow-600 text-xs"></i>
                                <span>সমস্ত তথ্য সঠিক এবং যাচাইযোগ্য হতে হবে</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-ban mt-1 mr-2 text-yellow-600 text-xs"></i>
                                <span>ভুল তথ্য দেওয়া হলে অযোগ্য ঘোষণা করা হবে</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-user-shield mt-1 mr-2 text-yellow-600 text-xs"></i>
                                <span>পরীক্ষার এক্সেসের আগে অ্যাডমিন অনুমোদন প্রয়োজন</span>
                            </li>
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
                            <label for="declaration" class="text-sm text-gray-700 tiro">
                                আমি এতদ্বারা ঘোষণা করছি যে এই আবেদনে প্রদত্ত সমস্ত তথ্য আমার জ্ঞানে সত্য ও সঠিক। আমি বুঝতে পারছি যে কোনো ভুল তথ্য প্রদান স্কলারশিপ প্রোগ্রাম থেকে অযোগ্য ঘোষণার কারণ হতে পারে।
                            </label>
                        </div>
                        @error('declaration')
                            <p class="mt-1 text-sm text-red-600 tiro">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row justify-between gap-3">
                        <button type="button" onclick="showPart(2)" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium text-sm transition-colors flex items-center justify-center tiro">
                            <i class="fas fa-arrow-left mr-2"></i>
                            ফর্মে ফিরে যান
                        </button>
                        <button type="submit" form="registrationForm" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold text-sm transition-all duration-200 transform hover:scale-105 flex items-center justify-center shadow-lg tiro">
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
            text.className = 'text-gray-700 font-medium tiro';
        } else if (partNumber === currentPart) {
            // Current part
            circle.className = 'w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-semibold mr-3';
            circle.innerHTML = partNumber;
            text.className = 'text-gray-700 font-medium tiro';
        } else {
            // Future part
            circle.className = 'w-8 h-8 rounded-full bg-gray-300 text-gray-600 flex items-center justify-center text-sm font-semibold mr-3';
            circle.innerHTML = partNumber;
            text.className = 'text-gray-500 tiro';
        }
    });
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

function scrollToForm() {
    showPart(2);
}

// Form validation
document.getElementById('registrationForm').addEventListener('submit', function(e) {
    const fields = {
        'academic_background': 50,
        'extracurricular_activities': 50,
        'achievements': 50,
        'reason_for_applying': 50
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

    if (!isValid) {
        e.preventDefault();
        showPart(2);
        if (firstInvalidField) {
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidField.focus();
        }
        alert('অনুগ্রহ করে সমস্ত প্রয়োজনীয় ফিল্ড ন্যূনতম অক্ষর সংখ্যা সহ পূরণ করুন।');
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

/* Tiro Bangla font class */
.tiro {
    font-family: 'Tiro Bangla', serif;
}
</style>
@endsection
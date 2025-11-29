@extends('layouts.student')

<<<<<<< HEAD
@section('title', 'Health Report Details')
@section('subtitle', 'Comprehensive view of your health checkup report')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <!-- Header Section -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-blue-600 px-4 py-6 sm:px-8 sm:py-8 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">Health Report Details</h1>
                    <p class="text-blue-100 text-sm sm:text-base">Checkup conducted on {{ $healthReport->checkup_date->format('F j, Y') }}</p>
                </div>
                <a href="{{ route('student.health-report') }}" 
                   class="bg-white text-green-600 px-4 py-3 sm:px-6 sm:py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors w-full sm:w-auto text-center">
                    <i class="fas fa-arrow-left me-2"></i>Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Report Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
        <div class="content-card rounded-lg p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <i class="fas fa-calendar-check text-blue-600"></i>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-700">Checkup Date</p>
            <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $healthReport->checkup_date->format('M j, Y') }}</p>
        </div>

        <div class="content-card rounded-lg p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <i class="fas fa-user-md text-green-600"></i>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-700">Checked By</p>
            <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $healthReport->checked_by }}</p>
        </div>

        <div class="content-card rounded-lg p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <i class="fas fa-school text-purple-600"></i>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-700">School</p>
            <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $healthReport->school->name ?? 'Health Center' }}</p>
        </div>

        <div class="content-card rounded-lg p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <i class="fas fa-chart-line text-orange-600"></i>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-700">Metrics Recorded</p>
            <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $healthReport->data->count() }}</p>
        </div>
    </div>

    <!-- Health Data by Category -->
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-2 sm:space-y-0">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-chart-bar me-2"></i>Health Checkup Data
            </h3>
            <span class="text-xs sm:text-sm text-gray-500">{{ $healthReport->data->count() }} metrics</span>
        </div>
        
        @if($healthReport->data->count() > 0)
            <div class="space-y-6 sm:space-y-8">
                @php
                    $currentCategory = null;
                    $categoryCount = 0;
                @endphp
                
                @foreach($healthReport->data as $index => $data)
                    @if($data->field->category->name != $currentCategory)
                        @if($currentCategory !== null)
                            </div>
                        @endif
                        @php 
                            $currentCategory = $data->field->category->name;
                            $categoryCount = 0;
                        @endphp
                        <div class="border-l-4 border-blue-500 pl-3 sm:pl-4">
                            <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                                <i class="fas fa-folder me-2 text-blue-500"></i>{{ $currentCategory }}
                            </h4>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 sm:gap-4 mb-3 sm:mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">{{ $data->field->label }}</label>
                        </div>
                        <div class="md:col-span-3">
                            <p class="text-gray-900 font-medium text-sm sm:text-base">{{ $data->field_value ?? 'Not Recorded' }}</p>
                            @if($data->field->description)
                                <p class="text-xs text-gray-500 mt-1">{{ $data->field->description }}</p>
                            @endif
                        </div>
                    </div>

                    @php $categoryCount++; @endphp
                    
                    @if($loop->last)
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-8 sm:py-12">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <i class="fas fa-chart-bar text-xl sm:text-2xl text-gray-300"></i>
                </div>
                <h4 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No Health Data Recorded</h4>
                <p class="text-gray-500 text-sm sm:text-base">No health metrics were recorded during this checkup.</p>
            </div>
        @endif
    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        <!-- Download Report -->
        <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-download text-blue-600 text-lg sm:text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Download Report</h4>
                    <p class="text-xs sm:text-sm text-gray-600">Get a PDF copy for your records</p>
                </div>
            </div>
            <button class="w-full mt-3 sm:mt-4 bg-blue-600 text-white py-2 sm:py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm sm:text-base">
                <i class="fas fa-file-pdf me-2"></i>Download PDF Report
            </button>
        </div>

        <!-- Print Report -->
        <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-print text-green-600 text-lg sm:text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Print Report</h4>
                    <p class="text-xs sm:text-sm text-gray-600">Print a physical copy if needed</p>
                </div>
            </div>
            <button onclick="window.print()" class="w-full mt-3 sm:mt-4 bg-green-600 text-white py-2 sm:py-3 rounded-lg hover:bg-green-700 transition-colors font-medium text-sm sm:text-base">
                <i class="fas fa-print me-2"></i>Print Report
            </button>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <h4 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
            <i class="fas fa-info-circle me-2"></i>Additional Information
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div class="space-y-3 sm:space-y-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Report Created By</label>
                    <p class="text-gray-900 text-sm sm:text-base">{{ $healthReport->createdBy->name ?? 'Medical Staff' }}</p>
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Report Status</label>
                    <span class="px-2 py-1 sm:px-3 sm:py-1 bg-green-100 text-green-800 rounded-full text-xs sm:text-sm font-medium">
                        Completed
                    </span>
                </div>
            </div>
            <div class="space-y-3 sm:space-y-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                    <p class="text-gray-900 text-sm sm:text-base">{{ $healthReport->updated_at->format('F j, Y \\a\\t g:i A') }}</p>
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Report ID</label>
                    <p class="text-gray-900 font-mono text-sm sm:text-base">#{{ str_pad($healthReport->id, 6, '0', STR_PAD_LEFT) }}</p>
=======
@section('title', 'স্বাস্থ্য রিপোর্ট বিবরণ')
@section('subtitle', 'আপনার স্বাস্থ্য পরীক্ষার রিপোর্টের সম্পূর্ণ বিবরণ')

@section('content')
<div class="space-y-6">
    
    
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
    {{-- ID Card - Takes 2/3 width --}}
    <div class="lg:col-span-2">
        @php
            // --- STATIC PLACEHOLDERS based on your image and previous context ---
            $schoolName = $school->name ?? 'গুল এজার বেগম সিটি কর্পোরেশন মুসলিম বালিকা উচ্চ বিদ্যালয়';
            $schoolLogoPath = $school->logo ? asset('public/storage/' . $school->logo) : 'https://via.placeholder.com/40/FFFFFF/000000?text=LOGO';
            $mayorName = 'ডা. শাহাদাত হোসেন';
            $mayorTitle = 'মেয়র, চট্টগ্রাম সিটি কর্পোরেশন';
            $staticID = '0233366831';
        
            // Student/Parent Data (using actual data where possible, falling back to static/'N/A')
            $studentName = $student->name ?? 'শিক্ষার্থীর নাম';
            $dateOfBirth = $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') : '১২/০১/২০০৯';
            $birthRegistration = $studentDetails->birth_certificate ?? 'N/A';
            $bloodGroup = $studentDetails->blood_group ?? 'N/A';
            $fatherName = $studentDetails->father_name ??  'N/A';
            $motherName = $studentDetails->mother_name ??  'N/A';
            $address = Str::limit($student->address, 20) ??  'N/A';
            $mobileNumber = $studentDetails->emergency_contact ?? $student->phone ??  'N/A';
            $email = $student->email ??  'N/A';
            $className = $class->name ??  'N/A';
            $sectionName = $section->name ??  'N/A';
            $divisionName = $division->name ??  'N/A'; // Assuming $division is available or needs to be added
        @endphp
        
        <div class="px-2 md:px-0 bg-gray-50 flex justify-center items-center print:p-0 print:m-0">
            
            {{-- ID Card Outer Container - Perfectly Scaled for Mobile --}}
            <div class="relative rounded-xl lg:rounded-2xl overflow-hidden shadow-lg lg:shadow-2xl transform transition-all print:shadow-none print:border-none print:transform-none bg-white mx-auto"
                 style="width: 90vw; max-width: 310mm; height: calc(90vw * 450/310); max-height: 450mm;">
                
                {{-- Background Image --}}
                <img src="{{ asset('public/storage/logo/Background.png') }}" 
                     alt="Card Background" 
                     class="absolute inset-0 w-full h-full object-cover z-0">
            
                <div class="relative z-20 h-full flex flex-col">
                    
                    {{-- 1. Top Graphic Section - Perfectly Scaled --}}
                    <div class="relative h-[16%] bg-cover bg-center flex items-center justify-center">
                        {{-- Optional: Add logo or top banner --}}
                    </div>
            
                    {{-- Wrapper that pushes sections lower - Perfectly Scaled --}}
                    <div class="flex-1 flex flex-col justify-center" style="margin-top: 22%;"> 
                        
                        {{-- 2. Header Content (School and Mayor Info) - Perfectly Scaled --}}
                        <div class="text-center px-[4%] pb-[0.5%]">
                            <h1 class="text-[3.2vw] lg:text-3xl font-extrabold text-green-700 mb-[0.5%] tiro">
                                স্কুল শিক্ষার্থীর স্বাস্থ্য কার্ড
                            </h1>
                            <p class="text-[1.8vw] lg:text-base font-semibold text-gray-700 inter mb-[0.5%]">
                                School's Student Health Card
                            </p>
                    
                            <h2 class="text-[2.8vw] lg:text-xl font-extrabold text-red-600 mb-[0.5%] tiro">
                                {{ $mayorName }}
                            </h2>
                            <p class="text-[1.8vw] lg:text-sm text-gray-700 tiro mb-[0.5%]">
                                {{ $mayorTitle }}
                            </p>
                    
                            <h3 class="text-[2.4vw] lg:text-lg font-bold text-red-600 pb-[0.5%] mb-[0.5%] tiro">
                                {{ $schoolName }}
                            </h3>
                    
                            <div class="flex justify-center items-center text-[1.8vw] lg:text-sm font-medium text-gray-700 inter">
                                <span class="tiro">ID NO :</span>
                                <span class="font-bold ml-1">{{ $staticID }}</span>
                            </div>
                        </div>
                    
                        {{-- 3. Student Info - Directly below header (NO SPACE) - Perfectly Scaled --}}
                        <div class="w-full flex justify-center mt-0 px-[20%] lg:px-0">
                            <div class="w-full max-w-md bg-white/80 backdrop-blur-sm rounded-lg lg:rounded-xl border border-gray-300 lg:border-2 p-[2%] lg:p-4 shadow-inner">
                                <h4 class="text-[2.2vw] lg:text-base font-extrabold text-gray-800 mb-[1%] text-center tiro">
                                    শিক্ষার্থীর পরিচয়
                                </h4>
                    
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-[0.5%] md:gap-2 text-[1.6vw] lg:text-xs">
                                
                                {{-- Student Name (শিক্ষার্থীর নাম) --}}
                                <div class="flex items-center">
                                    <span class="w-2/5 text-gray-600 tiro">শিক্ষার্থীর নাম</span>
                                    <span class="w-3/5 font-semibold text-gray-900 {{ detectLanguageClass($studentName) }} ml-1">: {{ $studentName }}</span>
                                </div>
            
                                {{-- Date of Birth (জন্ম তারিখ) --}}
                                <div class="flex items-center">
                                    <span class="w-2/5 text-gray-600 tiro">জন্ম তারিখ</span>
                                    <span class="w-3/5 font-semibold text-gray-900 inter ml-1">: {{ $dateOfBirth }}</span>
                                </div>
            
                                {{-- Birth Registration (জন্ম নিবন্ধন) --}}
                                <div class="flex items-center">
                                    <span class="w-2/5 text-gray-600 tiro">জন্ম নিবন্ধন</span>
                                    <span class="w-3/5 font-semibold text-gray-900 inter ml-1">: {{ $birthRegistration }}</span>
                                </div>
            
                                {{-- Blood Group (রক্তের গ্রুপ) --}}
                                <div class="flex items-center">
                                    <span class="w-2/5 text-gray-600 tiro">রক্তের গ্রুপ</span>
                                    <span class="w-3/5 font-bold text-red-600 inter ml-1">: {{ $bloodGroup }}</span>
                                </div>
            
                                {{-- Father's Name (পিতার নাম) --}}
                                <div class="flex items-center">
                                    <span class="w-2/5 text-gray-600 tiro">পিতার নাম</span>
                                    <span class="w-3/5 font-semibold text-gray-900 {{ detectLanguageClass($fatherName) }} ml-1">: {{ $fatherName }}</span>
                                </div>
            
                                {{-- Mother's Name (মাতার নাম) --}}
                                <div class="flex items-center">
                                    <span class="w-2/5 text-gray-600 tiro">মাতার নাম</span>
                                    <span class="w-3/5 font-semibold text-gray-900 {{ detectLanguageClass($motherName) }} ml-1">: {{ $motherName }}</span>
                                </div>
                                
                                {{-- Guardian Address (অভিভাবকের ঠিকানা) --}}
                                <div class="flex items-center md:col-span-2">
                                    <span class="w-1/3 md:w-1/4 text-gray-600 tiro">অভিভাবকের ঠিকানা</span>
                                    <span class="w-2/3 md:w-3/4 font-semibold text-gray-900 {{ detectLanguageClass($address) }} ml-1">: {{ $address }}</span>
                                </div>
            
                                {{-- Mobile Number (মোবাইল নং) --}}
                                <div class="flex items-center">
                                    <span class="w-2/5 text-gray-600 tiro">মোবাইল নং</span>
                                    <span class="w-3/5 font-semibold text-gray-900 inter ml-1">: {{ $mobileNumber }}</span>
                                </div>
            
                                {{-- Email (ইমেইল) --}}
                                <div class="flex items-center">
                                    <span class="w-2/5 text-gray-600 tiro">ইমেইল</span>
                                    <span class="w-3/5 font-semibold text-gray-900 inter break-all ml-1">: {{ $email }}</span>
                                </div>
                                
                                {{-- School Name (বিদ্যালয়ের নাম) --}}
                                <div class="flex items-center md:col-span-2">
                                    <span class="w-1/3 md:w-1/4 text-gray-600 tiro">বিদ্যালয়ের নাম</span>
                                    <span class="w-2/3 md:w-3/4 font-semibold text-gray-900 {{ detectLanguageClass($schoolName) }} ml-1">: {{ $schoolName }}</span>
                                </div>
            
                                {{-- Class (শ্রেণি) --}}
                                <div class="flex items-center">
                                    <span class="w-2/5 text-gray-600 tiro">শ্রেণি</span>
                                    <span class="w-3/5 font-semibold text-gray-900 {{ detectLanguageClass($className) }} ml-1">: {{ $className }}</span>
                                </div>
                                
                                {{-- Section and Division (শাখা / বিভাগ) --}}
                                <div class="flex items-center flex-wrap gap-[0.5%] md:gap-2 md:col-span-2">
                                    <div class="flex-shrink-0">
                                        <span class="text-gray-600 tiro">শাখা</span>
                                        <span class="font-semibold text-gray-900 {{ detectLanguageClass($sectionName) }} ml-1">: {{ $sectionName }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 tiro">বিভাগ</span>
                                        <span class="font-semibold text-gray-900 {{ detectLanguageClass($divisionName) }} ml-1">: {{ $divisionName }}</span>
                                    </div>
                                </div>
                                
                            </div>
                            </div>
                        </div>
                    
                    </div>
            
                </div>
            </div>
        </div>
    </div>

    {{-- Prescription Upload Section - Responsive Version --}}
    <div class="lg:col-span-1">
        <div class="content-card rounded-lg shadow-md overflow-hidden print:hidden h-full">
    
            <div class="flex justify-between items-center bg-blue-600 px-3 md:px-4 py-2 md:py-3">
                <h3 class="text-base md:text-lg font-bold text-white inter">Prescriptions</h3>
            </div>
    
            <div>
                <div class="p-3 md:p-4 h-full">
    
                    {{-- Recent Uploads --}}
                    @php
                        $recentPrescriptions = \App\Models\MedicalRecord::where('student_id', $studentDetails->id)
                            ->whereNotNull('prescription')
                            ->orderBy('record_date', 'desc')
                            ->take(2)
                            ->get();
                    @endphp
    
                    @if($recentPrescriptions->count() > 0)
                    <div class="mt-2 md:mt-3">
    
                        <div class="space-y-2">
    
                            @foreach($recentPrescriptions as $prescription)
                            <div class="flex items-center justify-between p-2 md:p-3 border border-gray-200 rounded-lg hover:shadow-sm">
    
                                <div class="flex items-center space-x-2 md:space-x-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs md:text-sm font-semibold {{ detectLanguageClass($prescription->doctor_notes) }} text-gray-900 truncate">
                                            {{ $prescription->doctor_notes ?? 'প্রেসক্রিপশন' }}
                                        </p>
                                        <p class="text-xs text-gray-500 inter">
                                            {{ \Carbon\Carbon::parse($prescription->record_date)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
    
                                <div class="flex space-x-1 md:space-x-2">
                                    <a href="{{ asset('public/storage/' . $prescription->prescription) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 text-xs md:text-sm tiro px-2 py-1 rounded bg-blue-50">
                                        <i class="fas fa-eye"></i>
                                    </a>
    
                                    <a href="{{ asset('public/storage/' . $prescription->prescription) }}"
                                       download
                                       class="text-green-600 hover:text-green-800 text-xs md:text-sm tiro px-2 py-1 rounded bg-green-50">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
    
                            </div>
                            @endforeach
    
                        </div>
    
                    </div>
                    @endif
    
                    {{-- Upload Form --}}
                    <form action="{{ route('student.health-report.upload-prescription') }}" 
                          method="POST" enctype="multipart/form-data" class="space-y-2 md:space-y-3 mt-3 md:mt-4">
                        @csrf
    
                        {{-- Prescription Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 tiro">
                                প্রেসক্রিপশনের নাম <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="prescription_name"
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm tiro"
                                   placeholder="যেমন: জ্বরের প্রেসক্রিপশন">
                        </div>
    
                        {{-- Date --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 tiro">
                                তারিখ <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="prescription_date"
                                   required
                                   max="{{ date('Y-m-d') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm inter"
                                   value="{{ old('prescription_date', date('Y-m-d')) }}">
                        </div>
    
                        {{-- File Upload --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 tiro">
                                ফাইল <span class="text-red-500">*</span>
                            </label>
    
                            <div class="flex justify-center px-3 md:px-4 pt-3 md:pt-4 pb-3 md:pb-4 border-2 border-gray-300 border-dashed rounded-md hover:border-green-400">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-xl md:text-2xl"></i>
    
                                    <label class="relative cursor-pointer text-green-600 hover:text-green-700 text-sm tiro block mt-1 md:mt-2">
                                        ফাইল সিলেক্ট করুন
                                        <input type="file" 
                                               name="prescription_file"
                                               accept=".pdf,.jpg,.png"
                                               required
                                               class="sr-only">
                                    </label>
    
                                    <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG (৫MB)</p>
                                </div>
                            </div>
                        </div>
    
                        {{-- Upload Button --}}
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 md:px-4 py-2 rounded-md text-sm font-semibold flex items-center tiro">
                                <i class="fas fa-upload mr-1 md:mr-2 text-base"></i>
                                আপলোড
                            </button>
                        </div>
    
                    </form>
    
>>>>>>> c356163 (video call ui setup)
                </div>
            </div>
        </div>
    </div>
</div>

<<<<<<< HEAD
<style>
.content-card {
    backdrop-filter: blur(12px);
    border: 1px solid rgba(229, 231, 235, 0.8);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

@media print {
    .content-card {
        box-shadow: none;
        border: 1px solid #e5e7eb;
        break-inside: avoid;
    }
    
    .bg-gradient-to-r {
        background: #059669 !important;
    }
}
</style>
=======
    <!-- Annual Health Records Table -->
    <div class="content-card rounded-lg p-4 md:p-6 shadow-sm tiro">
        <h3 class="text-xl md:text-xl font-extrabold text-gray-800 mb-6 border-b border-gray-300 pb-2">
            স্বাস্থ্যকর স্কুল পরিবেশ
        </h3>
    
        <div class="flex flex-wrap gap-2 items-start text-sm md:text-base text-gray-700">
    
            <!-- Item 1 -->
            <div class="flex items-center gap-2 w-full sm:w-[45%] md:w-[30%] lg:w-[10%]">
                <span class="text-2xl">💧</span>
                <span class="mt-1">বিশুদ্ধ পানীয় জল</span>
            </div>
    
            <!-- Item 2 -->
            <div class="flex items-center gap-2 w-full sm:w-[45%] md:w-[30%] lg:w-[12%]">
                <span class="text-2xl">🧼</span>
                <span class="mt-1">স্যানিটেশন</span>
            </div>
    
            <!-- Item 3 -->
            <div class="flex items-center gap-2 w-full sm:w-[45%] md:w-[30%] lg:w-[12%]">
                <span class="text-2xl">💖</span>
                <span class="mt-1">স্বাস্থ্যবিধি</span>
            </div>
    
            <!-- Item 4 -->
            <div class="flex items-center gap-2 w-full sm:w-[45%] md:w-[30%] lg:w-[12%]">
                <span class="text-2xl">🤸</span>
                <span class="mt-1">নিরাপদ খেলার মাঠ</span>
            </div>
    
            <!-- Item 5 -->
            <div class="flex items-center gap-2 w-full sm:w-[45%] md:w-[30%] lg:w-[12%]">
                <span class="text-2xl">🍱</span>
                <span class="mt-1">পরিচ্ছন্ন টিফিন / মিল</span>
            </div>
    
        </div>
    </div>

    
    <!-- Annual Health Records Table -->
    <div class="content-card rounded-lg p-4 md:p-6 shadow-sm">
        <h4 class="text-lg md:text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center tiro">
            <i class="fas fa-history mr-2"></i>স্বাস্থ্য পরীক্ষা
        </h4>
        
        @php
            // Define the age range
            $ages = range(4, 18);
            // Create a map of existing records by age for quick lookup
            $recordsByAge = $annualRecords->keyBy('age');
        @endphp
        
        @if($annualRecords->count() > 0 || count($ages) > 0)
        <div class="overflow-x-auto">
            <table class="w-full table-auto min-w-[800px] md:min-w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider tiro">বয়স</th>
                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider tiro">ওজন (কেজি)</th>
                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider tiro">উচ্চতা (সেমি)</th>
                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider tiro">মাথার আকার (সেমি)</th>
                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider tiro">শিশুর বিকাশ</th>
                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider tiro">অসুবিধা সমূহ</th>
                        <th class="px-3 py-2 md:px-4 md:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider tiro">বিশেষ নির্দেশনাসমূহ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($ages as $age)
                    @php
                        $record = $recordsByAge[$age] ?? null;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- Age -->
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 inter">{{ $age }}</div>
                        </td>
                        
                        <!-- Weight -->
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-900 inter">
                                @if($record && $record->weight)
                                    {{ number_format($record->weight, 1) }} <span class="tiro">কেজি</span>
                                @else
                                    <span class="text-gray-400 italic tiro">-</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Height -->
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-900 inter">
                                @if($record && $record->height)
                                    {{ number_format($record->height, 1) }} <span class="tiro">সেমি</span>
                                @else
                                    <span class="text-gray-400 italic tiro">-</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Head Circumference -->
                        <td class="px-3 py-2 md:px-4 md:py-3 whitespace-nowrap">
                            <div class="text-sm text-gray-900 inter">
                                @if($record && $record->head_circumference)
                                    {{ number_format($record->head_circumference, 1) }} <span class="tiro">সেমি</span>
                                @else
                                    <span class="text-gray-400 italic tiro">-</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Development Notes -->
                        <td class="px-3 py-2 md:px-4 md:py-3">
                            <div class="text-sm text-gray-900 max-w-xs">
                                @if($record && $record->development_notes)
                                    <div class="truncate {{ detectLanguageClass($record->development_notes) }}" title="{{ $record->development_notes }}">
                                        {{ Str::limit($record->development_notes, 50) }}
                                    </div>
                                @else
                                    <span class="text-gray-400 italic tiro">-</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Difficulties -->
                        <td class="px-3 py-2 md:px-4 md:py-3">
                            <div class="text-sm text-gray-900 max-w-xs">
                                @if($record && $record->difficulties)
                                    <div class="truncate {{ detectLanguageClass($record->difficulties) }}" title="{{ $record->difficulties }}">
                                        {{ Str::limit($record->difficulties, 50) }}
                                    </div>
                                @else
                                    <span class="text-gray-400 italic tiro">-</span>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Special Instructions -->
                        <td class="px-3 py-2 md:px-4 md:py-3">
                            <div class="text-sm text-gray-900 max-w-xs">
                                @if($record && $record->special_instructions)
                                    <div class="truncate {{ detectLanguageClass($record->special_instructions) }}" title="{{ $record->special_instructions }}">
                                        {{ Str::limit($record->special_instructions, 50) }}
                                    </div>
                                @else
                                    <span class="text-gray-400 italic tiro">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-clipboard-list text-gray-400 text-xl"></i>
            </div>
            <h4 class="text-lg font-medium text-gray-900 mb-2 tiro">-</h4>
            <p class="text-gray-500 tiro">বার্ষিক স্বাস্থ্য রেকর্ড রেকর্ড করা হলে এখানে দেখা যাবে</p>
        </div>
        @endif
    </div>

    <!-- Health Report Status Alert -->
    @if(!$healthReport)
    <div class="content-card rounded-lg p-4 md:p-6 shadow-sm border border-yellow-200 bg-yellow-50">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-lg md:text-xl"></i>
            </div>
            <div class="ml-3 md:ml-4">
                <h4 class="text-base md:text-lg font-medium text-yellow-800 tiro">কোন স্বাস্থ্য রিপোর্ট পাওয়া যায়নি</h4>
                <p class="text-yellow-700 mt-1 text-sm md:text-base tiro">-</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Health Report Display -->
    @if($healthReport)
    <div class="space-y-4 md:space-y-6">
        
        <!-- Dynamic Categories and Fields -->
        @foreach($categories as $category)
        <div class="content-card rounded-lg p-4 md:p-6 shadow-sm">
            <h4 class="text-lg md:text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 {{ detectLanguageClass($category->name) }}">{{ $category->name }}</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                @foreach($category->fields as $field)
                    @php
                        $fieldData = $fieldValues[$field->id] ?? ['value' => null, 'formatted' => null];
                        $formattedValue = $fieldData['formatted'];
                    @endphp
                    
                    <div class="@if($field->field_type === 'textarea') md:col-span-2 lg:col-span-3 @endif">
                        <div class="bg-white border border-gray-200 rounded-lg p-3 md:p-4 hover:shadow-sm transition-shadow">
                            <div class="text-sm font-medium text-gray-700 mb-2 flex items-center {{ detectLanguageClass($field->label) }}">
                                {{ $field->label }}
                                @if($field->is_required) 
                                    <span class="text-red-500 ml-1">*</span> 
                                @endif
                            </div>
                            
                            <div class="text-gray-900 min-h-6 text-sm md:text-base {{ detectLanguageClass($formattedValue) }}">
                                @if($formattedValue)
                                    @if($field->field_type === 'checkbox')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium tiro
                                            {{ $formattedValue === 'Yes' || $formattedValue === 'হ্যাঁ' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            @if($formattedValue === 'Yes') হ্যাঁ
                                            @elseif($formattedValue === 'No') না
                                            @else {{ $formattedValue }}
                                            @endif
                                        </span>
                                    @elseif($field->field_type === 'select')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 tiro">
                                            {{ $formattedValue }}
                                        </span>
                                    @elseif($field->field_type === 'textarea')
                                        <div class="whitespace-pre-wrap bg-gray-50 p-3 rounded border text-sm tiro">
                                            {{ $formattedValue }}
                                        </div>
                                    @else
                                        <span class="{{ detectLanguageClass($formattedValue) }}">{{ $formattedValue }}</span>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic tiro">রেকর্ড করা হয়নি</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @endif
    
    <!-- 4. Footer/QR Code/Signatures (Improved) -->
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-lg p-6 md:p-8">
        <div class="flex justify-between gap-6 md:gap-10 text-gray-700 text-sm tiro">
    
            <!-- Doctor Signature -->
            <div class="flex flex-col items-center">
                <p class="font-medium mb-3">ডাক্তারের স্বাক্ষর</p>
                @if($school->assignedDoctor && $school->assignedDoctor->doctorDetail && $school->assignedDoctor->doctorDetail->signature)
                    <img src="{{ asset('public/storage/' . $school->assignedDoctor->doctorDetail->signature) }}" 
                         alt="Doctor Signature" 
                         class="h-10 md:h-12 w-32 md:w-40 object-contain border-b-2 border-gray-700">
                @else
                    <div class="h-10 md:h-12 w-32 md:w-40 border-b-2 border-gray-700 flex items-center justify-center text-gray-500 text-xs">
                        স্বাক্ষর নেই
                    </div>
                @endif
                <p class="mt-2 text-xs text-gray-600">
                    {{ $school->assignedDoctor ? $school->assignedDoctor->name : 'ডাক্তার' }}
                </p>
            </div>
            
            <!-- Principal Signature -->
            <div class="flex flex-col items-center">
                <p class="font-medium mb-3">প্রধান শিক্ষকের স্বাক্ষর</p>
                @if($school->principal->principal && $school->principal->principal->signature)
                    <img src="{{ asset('public/storage/' . $school->principal->principal->signature) }}" 
                         alt="Principal Signature" 
                         class="h-10 md:h-12 w-32 md:w-40 object-contain border-b-2 border-gray-700">
                @else
                    <div class="h-10 md:h-12 w-32 md:w-40 border-b-2 border-gray-700 flex items-center justify-center text-gray-500 text-xs">
                        স্বাক্ষর নেই
                    </div>
                @endif
                <p class="mt-2 text-xs text-gray-600">
                    {{ $school->principal ? $school->principal->name : 'প্রধান শিক্ষক' }}
                </p>
            </div>
    
        </div>
    </div>

</div>

<style>
    .content-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    @media print {
        .content-card {
            box-shadow: none;
            border: 1px solid #ddd;
        }
        
        button, a {
            display: none !important;
        }
    }

    /* Mobile-first responsive table */
    @media (max-width: 768px) {
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            overflow-x: auto;
        }
        
        table {
            font-size: 0.875rem;
        }
    }

    /* Ensure proper text wrapping for Bengali */
    .tiro {
        line-height: 1.6;
    }
</style>

<script>
    // File name display
    document.getElementById('prescription_file').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const fileDisplay = document.getElementById('file-name');
        
        if (fileName) {
            fileDisplay.textContent = 'সিলেক্টেড: ' + fileName;
            fileDisplay.classList.remove('hidden');
        } else {
            fileDisplay.classList.add('hidden');
        }
    });
    
    // Drag and drop functionality
    const dropArea = document.querySelector('.border-dashed');
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropArea.classList.add('border-green-400', 'bg-green-50');
    }
    
    function unhighlight() {
        dropArea.classList.remove('border-green-400', 'bg-green-50');
    }
    
    dropArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        const fileInput = document.getElementById('prescription_file');
        
        if (files.length > 0) {
            fileInput.files = files;
            
            // Trigger change event
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    }
</script>
<script>
// Function to show full text on click for truncated cells
document.addEventListener('DOMContentLoaded', function() {
    const truncatedCells = document.querySelectorAll('.truncate');
    
    truncatedCells.forEach(cell => {
        cell.addEventListener('click', function() {
            const fullText = this.getAttribute('title');
            if (fullText) {
                alert(fullText);
            }
        });
        
        // Add cursor pointer to indicate clickability
        cell.style.cursor = 'pointer';
    });
});

function printReport() {
    window.print();
}
</script>
>>>>>>> c356163 (video call ui setup)
@endsection
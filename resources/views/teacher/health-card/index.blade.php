@extends('layouts.teacher')

@section('title', 'My Health Card')

@section('content')
    <div class="space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div class="table-header px-3 sm:px-4 md:px-6 py-3 sm:py-4">
                <div class="flex flex-col gap-3 sm:gap-4">
                    <div class="text-center sm:text-left">
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold tiro">আমার স্বাস্থ্য কার্ড</h3>
                        <p class="text-gray-200 text-xs sm:text-sm md:text-base inter mt-1">
                            Access your digital health card
                        </p>
                    </div>
                    @if($healthCard)
                        <div class="flex flex-col xs:flex-row gap-2 justify-center sm:justify-end">
                            <a href="{{ route('teacher.health-card.download-pdf') }}"
                                class="bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition-colors no-print text-xs sm:text-sm flex items-center justify-center gap-2">
                                <i class="fas fa-file-pdf"></i>
                                <span class="inter">Download PDF</span>
                            </a>
                            <a href="{{ route('teacher.health-card.print') }}"
                                class="bg-gray-600 text-white px-3 py-2 rounded-lg hover:bg-gray-700 transition-colors no-print text-xs sm:text-sm flex items-center justify-center gap-2">
                                <i class="fas fa-print"></i>
                                <span class="inter">Print</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Health Card Section -->
        <div class="content-card rounded-lg p-3 sm:p-4 md:p-6 shadow-sm">
            <h4 class="text-base sm:text-lg md:text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2 sm:pb-3 mb-3 sm:mb-4 md:mb-6 flex items-center tiro">
                <i class="fas fa-heartbeat me-2 text-red-500 text-sm sm:text-base"></i>
                স্বাস্থ্য কার্ড
            </h4>

            @if($healthCard)
                <div class="border border-gray-200 rounded-lg p-3 sm:p-4 md:p-6 hover:shadow-md transition-shadow">
                    <!-- Health Card Preview -->
                    <div class="flex justify-center mb-3 sm:mb-4">
                        <div class="bg-white rounded-xl sm:rounded-2xl overflow-hidden shadow-lg sm:shadow-xl w-full max-w-xs sm:max-w-sm md:max-w-md">

                            {{-- Header Content (School and Mayor Info) --}}
                            <div class="text-center p-2 sm:p-3 md:p-4">
                                <h1 class="text-sm sm:text-base md:text-lg font-extrabold text-green-700 mb-1 tiro leading-tight">
                                    স্কুল শিক্ষকের স্বাস্থ্য কার্ড
                                </h1>
                                <p class="text-xs font-semibold text-gray-700 inter mb-1 sm:mb-2 md:mb-3">
                                    School Teacher's Health Card
                                </p>

                                <h2 class="text-sm sm:text-base md:text-lg font-extrabold text-red-600 mb-0 tiro leading-tight">
                                    ডা. শাহাদাত হোসেন
                                </h2>
                                <p class="text-xs text-gray-700 tiro mb-1 sm:mb-2 md:mb-3 leading-tight">
                                    মেয়র, চট্টগ্রাম সিটি কর্পোরেশন
                                </p>

                                <h3 class="text-xs sm:text-sm md:text-base font-bold text-red-600 border-b border-gray-200 pb-1 sm:pb-2 mb-1 sm:mb-2 md:mb-3 tiro leading-tight">
                                    {{ Auth::user()->school->name ?? 'School Name' }}
                                </h3>
                            </div>
                            
                            <div class="flex justify-center items-center space-x-1 sm:space-x-2 text-xs text-gray-700 font-medium inter mb-2 sm:mb-3 md:mb-4 px-2">
                                <span class="tiro">কার্ড নং:</span>
                                <span class="font-bold">{{ $healthCard->card_number }}</span>
                            </div>

                            {{-- Teacher Data Grid --}}
                            <div class="p-2 sm:p-3 md:p-4 pt-0">
                                <div class="border border-gray-300 rounded-lg sm:rounded-xl p-2 sm:p-3 md:p-4 lg:p-6 bg-white shadow-inner">
                                    <h4 class="text-sm sm:text-base md:text-lg font-extrabold text-gray-800 mb-2 sm:mb-3 md:mb-4 text-center tiro">
                                        শিক্ষকের পরিচয়
                                    </h4>

                                    <div class="grid grid-cols-1 gap-1 sm:gap-1.5 md:gap-2 text-xs sm:text-sm">

                                        {{-- Teacher Name (শিক্ষকের নাম) --}}
                                        <div class="flex items-start">
                                            <span class="w-2/5 text-gray-600 tiro text-left">শিক্ষকের নাম:</span>
                                            <span class="w-3/5 font-semibold text-gray-900 text-right tiro pl-1 sm:pl-2 break-words">
                                                {{ Auth::user()->name }}
                                            </span>
                                        </div>

                                        {{-- Date of Birth (জন্ম তারিখ) --}}
                                        <div class="flex items-start">
                                            <span class="w-2/5 text-gray-600 tiro text-left">জন্ম তারিখ:</span>
                                            <span class="w-3/5 font-semibold text-gray-900 text-right inter pl-1 sm:pl-2">
                                                {{ Auth::user()->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->date_of_birth)->format('d/m/Y') : 'N/A' }}
                                            </span>
                                        </div>

                                        {{-- Blood Group (রক্তের গ্রুপ) --}}
                                        <div class="flex items-start">
                                            <span class="w-2/5 text-gray-600 tiro text-left">রক্তের গ্রুপ:</span>
                                            <span class="w-3/5 font-bold text-red-600 text-right inter pl-1 sm:pl-2">
                                                {{ Auth::user()->blood_group ?? 'N/A' }}
                                            </span>
                                        </div>

                                        {{-- Department (বিভাগ) --}}
                                        <div class="flex items-start">
                                            <span class="w-2/5 text-gray-600 tiro text-left">বিভাগ:</span>
                                            <span class="w-3/5 font-semibold text-gray-900 text-right tiro pl-1 sm:pl-2 break-words">
                                                {{ Auth::user()->department ?? 'N/A' }}
                                            </span>
                                        </div>

                                        {{-- Specialization (বিশেষত্ব) --}}
                                        <div class="flex items-start">
                                            <span class="w-2/5 text-gray-600 tiro text-left">বিশেষত্ব:</span>
                                            <span class="w-3/5 font-semibold text-gray-900 text-right tiro pl-1 sm:pl-2 break-words">
                                                {{ Auth::user()->specialization ?? 'N/A' }}
                                            </span>
                                        </div>

                                        {{-- Mobile Number (মোবাইল নং) --}}
                                        <div class="flex items-start">
                                            <span class="w-2/5 text-gray-600 tiro text-left">মোবাইল নং:</span>
                                            <span class="w-3/5 font-semibold text-gray-900 text-right inter pl-1 sm:pl-2 break-all">
                                                {{ Auth::user()->phone ?? 'N/A' }}
                                            </span>
                                        </div>

                                        {{-- Email (ইমেইল) --}}
                                        <div class="flex items-start">
                                            <span class="w-2/5 text-gray-600 tiro text-left">ইমেইল:</span>
                                            <span class="w-3/5 font-semibold text-gray-900 text-right inter pl-1 sm:pl-2 text-xs break-all">
                                                {{ Auth::user()->email }}
                                            </span>
                                        </div>

                                        {{-- Status (অবস্থা) --}}
                                        <div class="flex items-start">
                                            <span class="w-2/5 text-gray-600 tiro text-left">অবস্থা:</span>
                                            <span class="w-3/5 font-semibold text-right pl-1 sm:pl-2 {{ $healthCard->is_expired ? 'text-red-600' : 'text-green-600' }} tiro">
                                                {{ $healthCard->is_expired ? 'মেয়াদোত্তীর্ণ' : 'সক্রিয়' }}
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Health Card Actions -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mt-4">
                        <div class="text-center sm:text-left">
                            <p class="text-xs sm:text-sm text-gray-600 inter">
                                <strong>মেয়াদ শেষ:</strong> {{ $healthCard->expiry_date->format('d/m/Y') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1 inter">
                                <strong>জারি তারিখ:</strong> {{ $healthCard->issue_date->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="flex justify-center sm:justify-end gap-2">
                            <a href="{{ route('teacher.health-card.download-pdf') }}"
                                class="bg-red-600 text-white px-3 py-1.5 sm:py-2 rounded text-xs sm:text-sm hover:bg-red-700 transition-colors no-print inline-flex items-center gap-1">
                                <i class="fas fa-file-pdf text-xs"></i>
                                <span class="inter">PDF ডাউনলোড</span>
                            </a>
                            <a href="{{ route('teacher.health-card.print') }}"
                                class="bg-gray-600 text-white px-3 py-1.5 sm:py-2 rounded text-xs sm:text-sm hover:bg-gray-700 transition-colors no-print inline-flex items-center gap-1">
                                <i class="fas fa-print text-xs"></i>
                                <span class="inter">প্রিন্ট করুন</span>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-6 sm:py-8">
                    <i class="fas fa-heartbeat text-3xl sm:text-4xl md:text-6xl text-gray-300 mb-3 sm:mb-4"></i>
                    <h3 class="text-base sm:text-lg md:text-xl font-semibold text-gray-900 mb-2 tiro">
                        কোন স্বাস্থ্য কার্ড জারি হয়নি
                    </h3>
                    <p class="text-gray-600 mb-3 sm:mb-4 text-sm sm:text-base inter">
                        Your health card has not been issued yet.
                    </p>
                    <p class="text-xs sm:text-sm text-gray-500 inter">
                        Please contact the administration office to request a health card.
                    </p>
                </div>
            @endif
        </div>

        <!-- Recent Medical Records -->
        @if($medicalRecords->count() > 0)
            <div class="content-card rounded-lg p-3 sm:p-4 md:p-6 shadow-sm">
                <h4 class="text-base sm:text-lg md:text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2 sm:pb-3 mb-3 sm:mb-4 flex items-center">
                    <i class="fas fa-notes-medical me-2 text-blue-500 text-sm sm:text-base"></i>
                    সাম্প্রতিক চিকিৎসা রেকর্ড
                </h4>

                <div class="space-y-2 sm:space-y-3">
                    @foreach($medicalRecords->take(5) as $record)
                        <div class="border border-gray-200 rounded-lg p-3 sm:p-4 hover:shadow-sm transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 text-sm sm:text-base tiro">
                                        {{ $record->record_type ? ucfirst($record->record_type) : 'Medical Record' }}
                                    </p>
                                    <p class="text-xs text-gray-600 mt-1 inter">
                                        {{ \Carbon\Carbon::parse($record->record_date)->format('d M, Y') }}
                                    </p>
                                    @if($record->diagnosis)
                                        <p class="text-xs text-gray-700 mt-2 tiro line-clamp-2">
                                            {{ Str::limit($record->diagnosis, 80) }}
                                        </p>
                                    @endif
                                </div>
                                @if($record->recorded_by)
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded self-start sm:self-center inter">
                                        By: {{ $record->recordedBy->name ?? 'Doctor' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <style>
        .content-card {
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        .table-header {
            border-bottom: 1px solid rgba(229, 231, 235, 0.6);
        }

        @media (min-width: 640px) {
            .content-card {
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .content-card {
                box-shadow: none;
                border: 1px solid #e5e7eb;
            }
        }

        .tiro {
            font-family: 'Tiro Bangla', serif;
        }

        .inter {
            font-family: 'Inter', sans-serif;
        }

        /* Responsive utilities */
        @media (max-width: 639px) {
            .text-xs {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 767px) {
            .break-words {
                word-break: break-word;
            }
            
            .break-all {
                word-break: break-all;
            }
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Extra small devices (phones, less than 400px) */
        @media (max-width: 399px) {
            .text-xxs {
                font-size: 0.7rem;
            }
        }
    </style>
@endsection
@extends('layouts.student')

@section('title', 'My ID Cards')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 sm:px-6 py-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="text-center sm:text-left">
                    <h3 class="text-xl sm:text-2xl font-bold {{ detectLanguageClass('My ID Cards') }}">My ID Cards</h3>
                    <p class="text-gray-600 text-sm sm:text-base {{ detectLanguageClass('Access your digital identification cards') }}">Access your digital identification cards</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:space-x-3 justify-center sm:justify-end">
                    @if($idCards->count() > 0)
                    <a href="{{ route('student.id-cards.download', $idCards->first()->id) }}" 
                       class="bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors no-print text-sm sm:text-base text-center {{ detectLanguageClass('Download ID Card') }}">
                        <i class="fas fa-download me-2"></i>Download ID Card
                    </a>
                    @endif
                    @if($healthCard)
                    <a href="{{ route('student.health-card.download') }}" 
                       class="bg-green-600 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-green-700 transition-colors no-print text-sm sm:text-base text-center {{ detectLanguageClass('Download Health Card') }}">
                        <i class="fas fa-download me-2"></i>Download Health Card
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cards Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- ID Cards Section -->
        <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
            <h4 class="text-lg sm:text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 sm:mb-6 flex items-center {{ detectLanguageClass('Student ID Cards') }}">
                <i class="fas fa-id-card-alt me-2 sm:me-3 text-blue-500"></i>
                Student ID Cards
            </h4>

            @if($idCards->count() > 0)
                <div class="space-y-6">
                    @foreach($idCards as $idCard)
                    <div class="border border-gray-200 rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow">
                        <!-- ID Card Preview -->
                        <div class="flex justify-center mb-4">
                            <div class="card-container mx-auto">
                                <!-- Expiry Badge -->
                                <div class="expiry-badge {{ detectLanguageClass($idCard->is_expired ? 'EXPIRED' : 'VALID') }}">
                                    {{ $idCard->is_expired ? 'EXPIRED' : 'VALID' }}
                                </div>
                                
                                <!-- Background Image -->
                                @if($idCard->template && $idCard->template->background_image)
                                <img src="{{ asset('public/storage/' . $idCard->template->background_image) }}" 
                                     alt="Card Background" 
                                     class="card-background">
                                @endif
                                
                                <!-- Overlay for better readability -->
                                <div class="card-overlay"></div>
                                
                                <!-- Card Content -->
                                <div class="card-content">
                                    
                                    <!-- Header -->
                                    <div class="card-header">
                                        @php
                                            $organizationName = config('app.name', 'Organization Name');
                                            
                                            if ($idCard->student && $idCard->student->user && $idCard->student->user->school) {
                                                $organizationName = $idCard->student->user->school->name;
                                            }
                                            elseif ($idCard->user && $idCard->user->school) {
                                                $organizationName = $idCard->user->school->name;
                                            }
                                        @endphp
                                        <div class="organization-name {{ detectLanguageClass($organizationName) }}">{{ $organizationName }}</div>
                                        <div class="card-type {{ detectLanguageClass(ucfirst($idCard->type) . ' ID CARD') }}">
                                            {{ ucfirst($idCard->type) }} ID CARD
                                        </div>
                                    </div>
                                    
                                    <!-- Body -->
                                    <div class="card-body">
                                        <div class="left-section">
                                            @if($idCard->student && $idCard->student->user && $idCard->student->user->profile_image)
                                                <div class="user-photo">
                                                    <img src="{{ asset('public/storage/' . $idCard->student->user->profile_image) }}" 
                                                         alt="{{ $idCard->student->user->name }}">
                                                </div>
                                            @elseif($idCard->user && $idCard->user->profile_image)
                                                <div class="user-photo">
                                                    <img src="{{ asset('public/storage/' . $idCard->user->profile_image) }}" 
                                                         alt="{{ $idCard->user->name }}">
                                                </div>
                                            @else
                                                <div class="user-photo">
                                                    <div class="photo-placeholder {{ detectLanguageClass('PHOTO') }}">
                                                        <div>📷</div>
                                                        <div>PHOTO</div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- QR Code -->
                                            @if($idCard->qr_code && file_exists(public_path('storage/' . $idCard->qr_code)))
                                                <div class="qr-code-container">
                                                    <img src="{{ asset('public/storage/' . $idCard->qr_code) }}" alt="QR Code">
                                                </div>
                                            @else
                                                <div class="qr-code-container" style="background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                                    <div style="text-align: center; color: #6c757d; font-size: max(6px, {{ ($idCard->template->width ?? 85) * 0.08 }}px);" class="{{ detectLanguageClass('QR CODE') }}">
                                                        <div style="margin-bottom: 1px;">📱</div>
                                                        QR CODE
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Right Section - Information -->
                                        <div class="info-section">
                                            <div class="info-grid">
                                                <div class="info-row">
                                                    <span class="info-label {{ detectLanguageClass('NAME:') }}">NAME:</span>
                                                    <span class="info-value holder-name {{ detectLanguageClass($idCard->card_holder_name) }}">{{ $idCard->card_holder_name }}</span>
                                                </div>
                                                
                                                @if($idCard->student)
                                                <div class="info-row">
                                                    <span class="info-label {{ detectLanguageClass('STUDENT ID:') }}">STUDENT ID:</span>
                                                    <span class="info-value {{ detectLanguageClass($idCard->student->student_id ?? 'N/A') }}">{{ $idCard->student->student_id ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label {{ detectLanguageClass('CLASS:') }}">CLASS:</span>
                                                    <span class="info-value {{ detectLanguageClass($idCard->student->class->name ?? 'N/A') }}">{{ $idCard->student->class->name ?? 'N/A' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label {{ detectLanguageClass('ROLL NO:') }}">ROLL NO:</span>
                                                    <span class="info-value {{ detectLanguageClass($idCard->student->roll_number ?? 'N/A') }}">{{ $idCard->student->roll_number ?? 'N/A' }}</span>
                                                </div>
                                                @endif
                                                
                                                @if($idCard->user)
                                                <div class="info-row">
                                                    <span class="info-label {{ detectLanguageClass('POSITION:') }}">POSITION:</span>
                                                    <span class="info-value {{ detectLanguageClass(ucfirst($idCard->user->role)) }}">{{ ucfirst($idCard->user->role) }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label {{ detectLanguageClass('DEPT:') }}">DEPT:</span>
                                                    <span class="info-value {{ detectLanguageClass($idCard->user->department ?? ($idCard->user->specialization ?? 'N/A')) }}">{{ $idCard->user->department ?? ($idCard->user->specialization ?? 'N/A') }}</span>
                                                </div>
                                                @endif

                                                <!-- Medical Information -->
                                                @if($idCard->student)
                                                <div class="info-row">
                                                    <span class="info-label {{ detectLanguageClass('BLOOD GROUP:') }}">BLOOD GROUP:</span>
                                                    <span class="info-value {{ detectLanguageClass($idCard->student->blood_group ?? 'N/A') }}" style="color: #e74c3c; font-weight: 700;">
                                                        {{ $idCard->student->blood_group ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                
                                                @if($idCard->student->allergies && $idCard->student->allergies !== 'None')
                                                <div class="info-row">
                                                    <span class="info-label {{ detectLanguageClass('ALLERGIES:') }}">ALLERGIES:</span>
                                                    <span class="info-value {{ detectLanguageClass($idCard->student->allergies) }}" style="color: #e74c3c; font-weight: 600;">
                                                        {{ $idCard->student->allergies }}
                                                    </span>
                                                </div>
                                                @endif
                                                
                                                <div class="info-row">
                                                    <span class="info-label {{ detectLanguageClass('EMERGENCY:') }}">EMERGENCY:</span>
                                                    <span class="info-value {{ detectLanguageClass($idCard->student->emergency_contact ?? 'N/A') }}" style="color: #e74c3c; font-weight: 700;">
                                                        {{ $idCard->student->emergency_contact ?? 'N/A' }}
                                                    </span>
                                                </div>
                                                @endif
                                                
                                                <div class="info-row">
                                                    <span class="info-label {{ detectLanguageClass('CARD NO:') }}">CARD NO:</span>
                                                    <span class="info-value">
                                                        <span class="card-number {{ detectLanguageClass($idCard->card_number) }}">{{ $idCard->card_number }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Footer -->
                                    <div class="card-footer">
                                        <!-- Signature -->
                                        <div class="signature-section">
                                            <div class="signature-line"></div>
                                            <div class="signature-text {{ detectLanguageClass('AUTHORIZED SIGNATURE') }}">AUTHORIZED SIGNATURE</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Actions -->
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                            <div class="text-center sm:text-left">
                                <p class="text-sm text-gray-600 {{ detectLanguageClass('Template: ' . ($idCard->template->name ?? 'Standard')) }}">
                                    <strong>Template:</strong> {{ $idCard->template->name ?? 'Standard' }}
                                </p>
                                <p class="text-xs text-gray-500 {{ detectLanguageClass('Valid until: ' . $idCard->expiry_date->format('M j, Y')) }}">
                                    Valid until: {{ $idCard->expiry_date->format('M j, Y') }}
                                </p>
                            </div>
                            <div class="flex justify-center sm:justify-end space-x-2">
                                <a href="{{ route('student.id-cards.download', $idCard->id) }}" 
                                   class="bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700 transition-colors no-print inline-flex items-center {{ detectLanguageClass('Download') }}">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                                <button onclick="printCard('{{ $idCard->id }}')" 
                                        class="bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700 transition-colors no-print inline-flex items-center {{ detectLanguageClass('Print') }}">
                                    <i class="fas fa-print me-1"></i>Print
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-id-card-alt text-4xl sm:text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 {{ detectLanguageClass('No ID Card Issued') }}">No ID Card Issued</h3>
                <p class="text-gray-600 mb-4 text-sm sm:text-base {{ detectLanguageClass('Your student ID card has not been issued yet.') }}">Your student ID card has not been issued yet.</p>
                <a href="{{ route('student.support') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors inline-block text-sm sm:text-base {{ detectLanguageClass('Contact Support') }}">
                    <i class="fas fa-question-circle me-2"></i>Contact Support
                </a>
            </div>
            @endif
        </div>

        <!-- Health Card Section -->
        <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
            <h4 class="text-lg sm:text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 sm:mb-6 flex items-center {{ detectLanguageClass('Health Card') }}">
                <i class="fas fa-heartbeat me-2 sm:me-3 text-red-500"></i>
                Health Card
            </h4>

            @if($healthCard)
                <div class="border border-gray-200 rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow">
                    <!-- Health Card Preview -->
                    <div class="flex justify-center mb-4">
                        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl w-full max-w-sm sm:max-w-md">
                            
                            {{-- Header Content (School and Mayor Info) --}}
                            <div class="text-center p-3 sm:p-4">
                                <h1 class="text-lg sm:text-xl font-extrabold text-green-700 mb-1 tiro">
                                    স্কুল শিক্ষার্থীর স্বাস্থ্য কার্ড
                                </h1>
                                <p class="text-xs sm:text-sm font-semibold text-gray-700 inter mb-2 sm:mb-3">
                                    School's Student Health Card
                                </p>
                                
                                <h2 class="text-base sm:text-lg font-extrabold text-red-600 mb-0 tiro">
                                    ডা. শাহাদাত হোসেন
                                </h2>
                                <p class="text-xs text-gray-700 tiro mb-2 sm:mb-3">
                                    মেয়র, চট্টগ্রাম সিটি কর্পোরেশন
                                </p>
                                
                                <h3 class="text-sm sm:text-base font-bold text-red-600 border-b border-gray-200 pb-2 mb-2 sm:mb-3 tiro leading-tight">
                                    {{ Auth::user()->school->name ?? 'গুল এজার বেগম সিটি কর্পোরেশন মুসলিম বালিকা উচ্চ বিদ্যালয়' }}
                                </h3>
                            </div>
                            <div class="flex justify-center items-center space-x-2 text-xs sm:text-sm text-gray-700 font-medium inter mb-3 sm:mb-4">
                                <span class="tiro">ID NO :</span>
                                <span class="font-bold">0233366831</span>
                            </div>
                    
                            {{-- Student Data Grid --}}
                            <div class="p-3 sm:p-4 pt-0">
                                <div class="border border-gray-300 rounded-xl p-3 sm:p-4 md:p-6 bg-white shadow-inner">
                                    <h4 class="text-base sm:text-lg font-extrabold text-gray-800 mb-3 sm:mb-4 text-center tiro">
                                        শিক্ষার্থীর পরিচয়
                                    </h4>
                                    
                                    <div class="grid grid-cols-1 gap-x-4 gap-y-1 sm:gap-y-2 text-xs sm:text-sm">
                                        
                                        {{-- Student Name (শিক্ষার্থীর নাম) --}}
                                        <div class="flex">
                                            <span class="w-2/5 text-gray-600 tiro">শিক্ষার্থীর নাম</span>
                                            <span class="w-3/5 font-semibold text-gray-900 ml-1 sm:ml-2 {{ detectLanguageClass(Auth::user()->name) }}">: {{ Auth::user()->name }}</span>
                                        </div>
                    
                                        {{-- Date of Birth (জন্ম তারিখ) --}}
                                        <div class="flex">
                                            <span class="w-2/5 text-gray-600 tiro">জন্ম তারিখ</span>
                                            <span class="w-3/5 font-semibold text-gray-900 inter ml-1 sm:ml-2">: {{ Auth::user()->student->date_of_birth ? \Carbon\Carbon::parse(Auth::user()->student->date_of_birth)->format('d/m/Y') : 'N/A' }}</span>
                                        </div>
                    
                                        {{-- Blood Group (রক্তের গ্রুপ) --}}
                                        <div class="flex">
                                            <span class="w-2/5 text-gray-600 tiro">রক্তের গ্রুপ</span>
                                            <span class="w-3/5 font-bold text-red-600 inter ml-1 sm:ml-2">: {{ Auth::user()->student->blood_group ?? 'N/A' }}</span>
                                        </div>
                    
                                        {{-- Father's Name (পিতার নাম) --}}
                                        <div class="flex">
                                            <span class="w-2/5 text-gray-600 tiro">পিতার নাম</span>
                                            <span class="w-3/5 font-semibold text-gray-900 ml-1 sm:ml-2 {{ detectLanguageClass(Auth::user()->student->father_name ?? 'N/A') }}">: {{ Auth::user()->student->father_name ?? 'N/A' }}</span>
                                        </div>
                    
                                        {{-- Mother's Name (মাতার নাম) --}}
                                        <div class="flex">
                                            <span class="w-2/5 text-gray-600 tiro">মাতার নাম</span>
                                            <span class="w-3/5 font-semibold text-gray-900 ml-1 sm:ml-2 {{ detectLanguageClass(Auth::user()->student->mother_name ?? 'N/A') }}">: {{ Auth::user()->student->mother_name ?? 'N/A' }}</span>
                                        </div>
                                        
                                        {{-- Mobile Number (মোবাইল নং) --}}
                                        <div class="flex">
                                            <span class="w-2/5 text-gray-600 tiro">মোবাইল নং</span>
                                            <span class="w-3/5 font-semibold text-gray-900 inter ml-1 sm:ml-2">: {{ Auth::user()->student->emergency_contact ?? Auth::user()->phone ?? 'N/A' }}</span>
                                        </div>
                    
                                        {{-- Class (শ্রেণি) --}}
                                        <div class="flex">
                                            <span class="w-2/5 text-gray-600 tiro">শ্রেণি</span>
                                            <span class="w-3/5 font-semibold text-gray-900 ml-1 sm:ml-2 {{ detectLanguageClass(Auth::user()->student->class->name ?? 'N/A') }}">: {{ Auth::user()->student->class->name ?? 'N/A' }}</span>
                                        </div>
                                        
                                        {{-- Section (শাখা) --}}
                                        <div class="flex">
                                            <span class="w-2/5 text-gray-600 tiro">শাখা</span>
                                            <span class="w-3/5 font-semibold text-gray-900 ml-1 sm:ml-2 {{ detectLanguageClass(Auth::user()->student->section->name ?? 'N/A') }}">: {{ Auth::user()->student->section->name ?? 'N/A' }}</span>
                                        </div>
                    
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <!-- Health Card Actions -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                        <div class="text-center sm:text-left">
                            <p class="text-sm text-gray-600 {{ detectLanguageClass('Expires: ' . $healthCard->expiry_date->format('M j, Y')) }}">
                                <strong>Expires:</strong> {{ $healthCard->expiry_date->format('M j, Y') }}
                            </p>
                        </div>
                        <div class="flex justify-center sm:justify-end space-x-2">
                            <a href="{{ route('student.health-card.download') }}" 
                               class="bg-red-600 text-white px-3 py-2 rounded text-sm hover:bg-red-700 transition-colors no-print inline-flex items-center {{ detectLanguageClass('Download') }}">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                            <button onclick="printHealthCard()" 
                                    class="bg-gray-600 text-white px-3 py-2 rounded text-sm hover:bg-gray-700 transition-colors no-print inline-flex items-center {{ detectLanguageClass('Print') }}">
                                <i class="fas fa-print me-1"></i>Print
                            </button>
                        </div>
                    </div>
                </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-heartbeat text-4xl sm:text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2 {{ detectLanguageClass('No Health Card Issued') }}">No Health Card Issued</h3>
                <p class="text-gray-600 mb-4 text-sm sm:text-base {{ detectLanguageClass('Your health card has not been issued yet.') }}">Your health card has not been issued yet.</p>
                <a href="{{ route('student.support') }}" 
                   class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors inline-block text-sm sm:text-base {{ detectLanguageClass('Contact Support') }}">
                    <i class="fas fa-question-circle me-2"></i>Contact Support
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Important Information -->
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <h4 class="text-lg sm:text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center {{ detectLanguageClass('Important Information') }}">
            <i class="fas fa-info-circle me-2 text-green-500"></i>
            Important Information
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div class="space-y-3 sm:space-y-4">
                <div class="flex items-start space-x-2 sm:space-x-3">
                    <i class="fas fa-id-card text-blue-500 mt-1 text-sm sm:text-base"></i>
                    <div>
                        <p class="font-medium text-gray-900 text-sm sm:text-base {{ detectLanguageClass('ID Card Usage') }}">ID Card Usage</p>
                        <p class="text-xs sm:text-sm text-gray-600 {{ detectLanguageClass('Required for campus access, library, and exams') }}">Required for campus access, library, and exams</p>
                    </div>
                </div>
                <div class="flex items-start space-x-2 sm:space-x-3">
                    <i class="fas fa-heart text-red-500 mt-1 text-sm sm:text-base"></i>
                    <div>
                        <p class="font-medium text-gray-900 text-sm sm:text-base {{ detectLanguageClass('Health Card Importance') }}">Health Card Importance</p>
                        <p class="text-xs sm:text-sm text-gray-600 {{ detectLanguageClass('Essential for medical emergencies and clinic visits') }}">Essential for medical emergencies and clinic visits</p>
                    </div>
                </div>
                <div class="flex items-start space-x-2 sm:space-x-3">
                    <i class="fas fa-qrcode text-purple-500 mt-1 text-sm sm:text-base"></i>
                    <div>
                        <p class="font-medium text-gray-900 text-sm sm:text-base {{ detectLanguageClass('QR Code Access') }}">QR Code Access</p>
                        <p class="text-xs sm:text-sm text-gray-600 {{ detectLanguageClass('Scan QR codes for quick verification') }}">Scan QR codes for quick verification</p>
                    </div>
                </div>
            </div>
            <div class="space-y-3 sm:space-y-4">
                <div class="flex items-start space-x-2 sm:space-x-3">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-1 text-sm sm:text-base"></i>
                    <div>
                        <p class="font-medium text-gray-900 text-sm sm:text-base {{ detectLanguageClass('Report Issues') }}">Report Issues</p>
                        <p class="text-xs sm:text-sm text-gray-600 {{ detectLanguageClass('Immediately report lost or damaged cards') }}">Immediately report lost or damaged cards</p>
                    </div>
                </div>
                <div class="flex items-start space-x-2 sm:space-x-3">
                    <i class="fas fa-sync-alt text-green-500 mt-1 text-sm sm:text-base"></i>
                    <div>
                        <p class="font-medium text-gray-900 text-sm sm:text-base {{ detectLanguageClass('Card Renewal') }}">Card Renewal</p>
                        <p class="text-xs sm:text-sm text-gray-600 {{ detectLanguageClass('Renew cards before expiry date') }}">Renew cards before expiry date</p>
                    </div>
                </div>
                <div class="flex items-start space-x-2 sm:space-x-3">
                    <i class="fas fa-shield-alt text-indigo-500 mt-1 text-sm sm:text-base"></i>
                    <div>
                        <p class="font-medium text-gray-900 text-sm sm:text-base {{ detectLanguageClass('Data Privacy') }}">Data Privacy</p>
                        <p class="text-xs sm:text-sm text-gray-600 {{ detectLanguageClass('Your information is securely protected') }}">Your information is securely protected</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printCard(cardId) {
    const url = "{{ route('student.id-cards.download', ':id') }}".replace(':id', cardId);
    const printWindow = window.open(url, '_blank');
    printWindow.onload = function() {
        printWindow.print();
    };
}

function printHealthCard() {
    const url = "{{ route('student.health-card.download') }}";
    const printWindow = window.open(url, '_blank');
    printWindow.onload = function() {
        printWindow.print();
    };
}

// Auto-refresh card status
function checkCardStatus() {
    // You can implement AJAX calls here to check for card status updates
    console.log('Checking card status...');
}

// Check status every 5 minutes
setInterval(checkCardStatus, 300000);

// Responsive card scaling
function adjustCardSize() {
    const cards = document.querySelectorAll('.card-container');
    const screenWidth = window.innerWidth;
    
    cards.forEach(card => {
        if (screenWidth < 640) {
            card.style.transform = 'scale(0.8)';
        } else if (screenWidth < 768) {
            card.style.transform = 'scale(0.9)';
        } else {
            card.style.transform = 'scale(1)';
        }
    });
}

// Initial adjustment
window.addEventListener('load', adjustCardSize);
window.addEventListener('resize', adjustCardSize);
</script>

<style>
.content-card {
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    background:#fff;
}

.table-header {
    border-bottom: 1px solid rgba(229, 231, 235, 0.6);
}

@media print {
    .no-print { display: none !important; }
    .content-card { box-shadow: none; border: 1px solid #e5e7eb; }
}

/* Custom scrollbar for medical records */
.max-h-40::-webkit-scrollbar {
    width: 4px;
}

.max-h-40::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 2px;
}

.max-h-40::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 2px;
}

.max-h-40::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Dynamic ID Card Size from Database */
.card-container {
    width: min({{ $idCard->template->width ?? 85 }}mm, 90vw);
    height: min({{ $idCard->template->height ?? 54 }}mm, 57vw);
    position: relative;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s ease;
}

.card-background {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
    object-fit: cover;
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, 
        rgba(255, 255, 255, 0.5) 0%, 
        rgba(255, 255, 255, 0.4) 100%);
    z-index: 2;
}

.card-content {
    position: relative;
    z-index: 3;
    height: 100%;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;
    padding: max(3mm, {{ ($idCard->template->height ?? 54) * 0.03 }}mm) max(4mm, {{ ($idCard->template->width ?? 85) * 0.04 }}mm);
}

.card-header {
    text-align: center;
    margin-bottom: max(2mm, {{ ($idCard->template->height ?? 54) * 0.02 }}mm);
    padding-bottom: max(1mm, {{ ($idCard->template->height ?? 54) * 0.01 }}mm);
    border-bottom: 1px solid rgba(102, 126, 234, 0.3);
}

.organization-name {
    font-weight: 800;
    color: #2c3e50;
    margin: 0;
    letter-spacing: 0.5px;
    line-height: 1.1;
    font-size: min(max(8px, {{ ($idCard->template->width ?? 85) * 0.18 }}px), 16px);
}

.card-type {
    color: #667eea;
    font-weight: 600;
    margin: max(0.5mm, {{ ($idCard->template->height ?? 54) * 0.005 }}mm) 0 0 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: min(max(6px, {{ ($idCard->template->width ?? 85) * 0.12 }}px), 12px);
}

.card-body {
    flex: 1;
    display: flex;
    gap: max(3mm, {{ ($idCard->template->width ?? 85) * 0.03 }}mm);
    margin-bottom: max(2mm, {{ ($idCard->template->height ?? 54) * 0.02 }}mm);
}

.left-section {
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: max(2mm, {{ ($idCard->template->height ?? 54) * 0.02 }}mm);
    width: min({{ ($idCard->template->width ?? 85) * 0.25 }}mm, 22vw);
}

.user-photo {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 1px solid #667eea;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    object-fit: contain;
    overflow: hidden;
    width: min({{ ($idCard->template->width ?? 85) * 0.22 }}mm, 20vw);
    height: min({{ ($idCard->template->height ?? 54) * 0.25 }}mm, 14vw);
}

.user-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-placeholder {
    text-align: center;
    color: #6c757d;
    padding: 1mm;
    font-size: min(max(4px, {{ ($idCard->template->width ?? 85) * 0.08 }}px), 8px);
}

.qr-code-container {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 3px;
    padding: 1px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: min({{ ($idCard->template->width ?? 85) * 0.22 }}mm, 20vw);
    height: min({{ ($idCard->template->width ?? 85) * 0.22 }}mm, 20vw);
}

.qr-code-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.info-section {
    flex: 1;
    min-width: 0;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: max(0.5mm, {{ ($idCard->template->height ?? 54) * 0.008 }}mm);
}

.info-row {
    display: flex;
    align-items: center;
    padding: max(0.3mm, {{ ($idCard->template->height ?? 54) * 0.005 }}mm) 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.info-label {
    font-weight: 700;
    color: #2c3e50;
    text-transform: uppercase;
    letter-spacing: 0.2px;
    min-width: min({{ ($idCard->template->width ?? 85) * 0.18 }}mm, 16vw);
    font-size: min(max(4px, {{ ($idCard->template->width ?? 85) * 0.09 }}px), 9px);
}

.info-value {
    font-weight: 600;
    color: #34495e;
    flex: 1;
    line-height: 1.1;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    font-size: min(max(5px, {{ ($idCard->template->width ?? 85) * 0.1 }}px), 10px);
}

.card-number {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 2px;
    font-weight: 700;
    padding: max(0.5px, {{ ($idCard->template->width ?? 85) * 0.005 }}mm) max(1px, {{ ($idCard->template->width ?? 85) * 0.01 }}mm);
    font-size: min(max(4px, {{ ($idCard->template->width ?? 85) * 0.08 }}px), 8px);
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    padding-top: max(1mm, {{ ($idCard->template->height ?? 54) * 0.015 }}mm);
}

.signature-section {
    text-align: center;
    flex-shrink: 0;
}

.signature-line {
    border-top: 1px solid #2c3e50;
    margin: 0.5px auto;
    width: min({{ ($idCard->template->width ?? 85) * 0.3 }}mm, 25vw);
}

.signature-text {
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    font-size: min(max(3px, {{ ($idCard->template->width ?? 85) * 0.07 }}px), 7px);
}

.expiry-badge {
    position: absolute;
    top: max(2mm, {{ ($idCard->template->height ?? 54) * 0.02 }}mm);
    right: max(2mm, {{ ($idCard->template->width ?? 85) * 0.02 }}mm);
    background: {{ $idCard->is_expired ? '#e74c3c' : '#27ae60' }};
    color: white;
    border-radius: 2px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    z-index: 4;
    padding: max(0.5px, {{ ($idCard->template->width ?? 85) * 0.005 }}mm) max(1px, {{ ($idCard->template->width ?? 85) * 0.01 }}mm);
    font-size: min(max(3px, {{ ($idCard->template->width ?? 85) * 0.07 }}px), 7px);
}

.holder-name {
    font-weight: 700 !important;
    color: #2c3e50 !important;
    font-size: min(max(6px, {{ ($idCard->template->width ?? 85) * 0.12 }}px), 12px) !important;
}

/* Responsive typography for health card */
.tiro {
    font-family: 'Tiro Bangla', serif;
}

.inter {
    font-family: 'Inter', sans-serif;
}

/* Mobile-specific adjustments */
@media (max-width: 640px) {
    .card-container {
        margin: 0 auto;
    }
    
    .content-card {
        margin: 0 -8px;
        border-radius: 0;
        border-left: none;
        border-right: none;
    }
}

/* Tablet adjustments */
@media (min-width: 641px) and (max-width: 1024px) {
    .card-container {
        width: min({{ $idCard->template->width ?? 85 }}mm, 70vw);
        height: min({{ $idCard->template->height ?? 54 }}mm, 44vw);
    }
}

/* Ensure proper printing */
@media print {
    .card-container {
        width: {{ $idCard->template->width ?? 85 }}mm !important;
        height: {{ $idCard->template->height ?? 54 }}mm !important;
        transform: none !important;
        margin: 0 auto;
    }
}
</style>
@endsection
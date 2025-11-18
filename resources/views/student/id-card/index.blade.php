{{-- resources/views/student/id-cards/index.blade.php --}}
@extends('layouts.student')

@section('title', 'My ID Cards')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">My ID Cards</h3>
                    <p class="text-gray-600">Access your digital identification cards</p>
                </div>
                <div class="flex space-x-3">
                    @if($idCards->count() > 0)
                    <a href="{{ route('student.id-cards.download', $idCards->first()->id) }}" 
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors no-print">
                        <i class="fas fa-download me-2"></i>Download ID Card
                    </a>
                    @endif
                    @if($healthCard)
                    <a href="{{ route('student.health-card.download') }}" 
                       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors no-print">
                        <i class="fas fa-download me-2"></i>Download Health Card
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ID Cards Section -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-6 flex items-center">
            <i class="fas fa-id-card-alt me-3 text-blue-500"></i>
            Student ID Cards
        </h4>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @if($idCards->count() > 0)
            @foreach($idCards as $idCard)
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <!-- ID Card Preview -->
                <div class="bg-gradient-to-br from-blue-600 to-purple-700 rounded-xl p-6 text-white shadow-lg mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-bold">{{ Auth::user()->school->name ?? 'School Name' }}</h2>
                            <p class="text-blue-100 text-sm">Student Identification Card</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-blue-200">Valid Until</p>
                            <p class="font-semibold text-sm">{{ $idCard->expiry_date->format('M j, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('public/storage/' . (Auth::user()->profile_image ?? 'default-avatar.png')) }}" 
                                 alt="Profile" class="w-16 h-16 rounded-full border-2 border-white object-cover">
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold mb-1">{{ Auth::user()->name }}</h3>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <p class="text-blue-200">Student ID</p>
                                    <p class="font-semibold">{{ $idCard->card_number }}</p>
                                </div>
                                <div>
                                    <p class="text-blue-200">Class</p>
                                    <p class="font-semibold">{{ Auth::user()->student->class->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-blue-500 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-blue-200">Issued: {{ $idCard->issue_date->format('M j, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 bg-green-500 rounded-full text-xs font-semibold">
                                {{ strtoupper($idCard->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card Actions -->
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">
                            <strong>Template:</strong> {{ $idCard->template->name ?? 'Standard' }}
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('student.id-cards.download', $idCard->id) }}" 
                           class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors no-print">
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                        <button onclick="printCard('{{ $idCard->id }}')" 
                                class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700 transition-colors no-print">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <div class="text-center py-8">
            <i class="fas fa-id-card-alt text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No ID Card Issued</h3>
            <p class="text-gray-600 mb-4">Your student ID card has not been issued yet.</p>
            <a href="{{ route('student.support') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors inline-block">
                <i class="fas fa-question-circle me-2"></i>Contact Support
            </a>
        </div>
        @endif
        
        @if($healthCard)
            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="bg-gradient-to-br from-red-600 to-pink-700 rounded-xl p-6 text-white shadow-lg mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-xl font-bold">{{ Auth::user()->school->name ?? 'School Name' }}</h2>
                            <p class="text-red-100 text-sm">Student Health Card</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-red-200">Expires</p>
                            <p class="font-semibold text-sm">{{ $healthCard->expiry_date->format('M j, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 mb-4">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('public/storage/' . (Auth::user()->profile_image ?? 'default-avatar.png')) }}" 
                                 alt="Profile" class="w-16 h-16 rounded-full border-2 border-white object-cover">
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold mb-1">{{ Auth::user()->name }}</h3>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <p class="text-red-200">Health Card No</p>
                                    <p class="font-semibold">{{ $healthCard->card_number }}</p>
                                </div>
                                <div>
                                    <p class="text-red-200">Blood Group</p>
                                    <p class="font-semibold">{{ Auth::user()->student->blood_group ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Info -->
                    <div class="bg-red-700 rounded-lg p-3 text-sm">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-phone-alt me-2 text-red-200"></i>
                            <span class="font-semibold">Emergency Contact</span>
                        </div>
                        <p class="text-red-100">{{ $healthCard->emergency_instructions ?? Auth::user()->student->emergency_contact ?? 'Contact School Administration' }}</p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-red-500 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-red-200">Issued: {{ $healthCard->issue_date->format('M j, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 bg-green-500 rounded-full text-xs font-semibold">
                                {{ strtoupper($healthCard->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Health Card Actions -->
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">
                            <strong>Medical Summary:</strong> 
                            {{ Str::limit($healthCard->medical_summary ?? 'No medical summary available', 50) }}
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('student.health-card.download') }}" 
                           class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700 transition-colors no-print">
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                        <button onclick="printHealthCard()" 
                                class="bg-gray-600 text-white px-3 py-1 rounded text-sm hover:bg-gray-700 transition-colors no-print">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-8">
            <i class="fas fa-heartbeat text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Health Card Issued</h3>
            <p class="text-gray-600 mb-4">Your health card has not been issued yet.</p>
            <a href="{{ route('student.support') }}" 
               class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors inline-block">
                <i class="fas fa-question-circle me-2"></i>Contact Support
            </a>
        </div>
        @endif
        
    </div>

    <!-- Important Information -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
            <i class="fas fa-info-circle me-2 text-green-500"></i>
            Important Information
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-id-card text-blue-500 mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-900">ID Card Usage</p>
                        <p class="text-sm text-gray-600">Required for campus access, library, and exams</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-heart text-red-500 mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-900">Health Card Importance</p>
                        <p class="text-sm text-gray-600">Essential for medical emergencies and clinic visits</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-qrcode text-purple-500 mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-900">QR Code Access</p>
                        <p class="text-sm text-gray-600">Scan QR codes for quick verification</p>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-900">Report Issues</p>
                        <p class="text-sm text-gray-600">Immediately report lost or damaged cards</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-sync-alt text-green-500 mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-900">Card Renewal</p>
                        <p class="text-sm text-gray-600">Renew cards before expiry date</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <i class="fas fa-shield-alt text-indigo-500 mt-1"></i>
                    <div>
                        <p class="font-medium text-gray-900">Data Privacy</p>
                        <p class="text-sm text-gray-600">Your information is securely protected</p>
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
</style>
@endsection
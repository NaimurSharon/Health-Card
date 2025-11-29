@extends('layouts.doctor')

@section('title', $healthCard->student->user->name . ' - Health Card')

@section('content')
<div class="space-y-6">
    <!-- Health Card Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-id-card text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Health Card Details</h3>
                    <p class="text-gray-600">{{ $healthCard->student->user->name }} - {{ $healthCard->card_number }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('doctor.health-cards.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Column - Health Card Details -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Health Card Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Health Card Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Card Number</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $healthCard->card_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Issue Date</p>
                            <p class="text-lg text-gray-900">{{ $healthCard->issue_date->format('F j, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Status</p>
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                {{ $healthCard->status == 'active' ? 'bg-green-100 text-green-800' : 
                                   ($healthCard->status == 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($healthCard->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Expiry Date</p>
                            <p class="text-lg font-semibold text-gray-900 {{ $healthCard->expiry_date->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                {{ $healthCard->expiry_date->format('F j, Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Days Remaining</p>
                            <p class="text-lg text-gray-900">
                                @if($healthCard->expiry_date->isFuture())
                                    {{ $healthCard->expiry_date->diffInDays(now()) }} days
                                @else
                                    <span class="text-red-600">Expired {{ $healthCard->expiry_date->diffForHumans() }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Medical Summary -->
                @if($healthCard->medical_summary)
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-600 mb-2">Medical Summary</p>
                    <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-400">
                        <p class="text-gray-700">{{ $healthCard->medical_summary }}</p>
                    </div>
                </div>
                @endif

                <!-- Emergency Instructions -->
                @if($healthCard->emergency_instructions)
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-2">Emergency Instructions</p>
                    <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-400">
                        <p class="text-gray-700">{{ $healthCard->emergency_instructions }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Student Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Student Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Full Name</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $healthCard->student->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Student ID</p>
                            <p class="text-lg text-gray-900">{{ $healthCard->student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Class & Section</p>
                            <p class="text-lg text-gray-900">
                                {{ $healthCard->student->class->name ?? 'N/A' }} - {{ $healthCard->student->section->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Blood Group</p>
                            <p class="text-lg text-gray-900 {{ $healthCard->student->blood_group ? 'text-red-600 font-semibold' : '' }}">
                                {{ $healthCard->student->blood_group ?? 'Not set' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Emergency Contact</p>
                            <p class="text-lg text-gray-900">{{ $healthCard->student->emergency_contact ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Medical Alerts -->
                @if($healthCard->student->allergies || $healthCard->student->medical_conditions)
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($healthCard->student->allergies)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Allergies</p>
                        <div class="bg-yellow-50 rounded-lg p-3 border-l-4 border-yellow-400">
                            <p class="text-sm text-gray-700">{{ $healthCard->student->allergies }}</p>
                        </div>
                    </div>
                    @endif

                    @if($healthCard->student->medical_conditions)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Medical Conditions</p>
                        <div class="bg-red-50 rounded-lg p-3 border-l-4 border-red-400">
                            <p class="text-sm text-gray-700">{{ $healthCard->student->medical_conditions }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Medical History -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-xl font-semibold text-gray-900">Recent Medical Records</h4>
                    <span class="text-sm text-gray-600">{{ $healthCard->student->medicalRecords->count() }} records</span>
                </div>

                @if($healthCard->student->medicalRecords->count() > 0)
                    <div class="space-y-4">
                        @foreach($healthCard->student->medicalRecords->take(5) as $record)
                        <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-medium text-gray-900">{{ $record->record_date->format('F j, Y') }}</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $record->record_type == 'emergency' ? 'bg-red-100 text-red-800' : 
                                       ($record->record_type == 'vaccination' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($record->record_type) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $record->diagnosis }}</p>
                            <p class="text-xs text-gray-500 mt-1">By: {{ $record->recordedBy->name }}</p>
                        </div>
                        @endforeach
                    </div>

                    @if($healthCard->student->medicalRecords->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('doctor.patients.show', $healthCard->student) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View all medical records →
                        </a>
                    </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-file-medical text-4xl mb-4 text-gray-300"></i>
                        <p class="text-lg text-gray-500">No medical records found</p>
                        <p class="text-sm mt-2">This student has no medical records yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Status & Actions -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Card Status</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Current Status</span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium 
                            {{ $healthCard->status == 'active' ? 'bg-green-100 text-green-800' : 
                               ($healthCard->status == 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($healthCard->status) }}
                        </span>
                    </div>
                    
                    @if($healthCard->status == 'active')
                    <div class="p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-sm text-green-700">This health card is active and valid</span>
                        </div>
                    </div>
                    @elseif($healthCard->status == 'expired')
                    <div class="p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-sm text-red-700">This health card has expired</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Validity Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Validity Information</h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Issued</span>
                        <span class="text-sm font-medium">{{ $healthCard->issue_date->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Expires</span>
                        <span class="text-sm font-medium {{ $healthCard->expiry_date->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $healthCard->expiry_date->format('M j, Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Duration</span>
                        <span class="text-sm font-medium">{{ $healthCard->issue_date->diffInDays($healthCard->expiry_date) }} days</span>
                    </div>
                </div>

                @if($healthCard->expiry_date->isFuture())
                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-blue-500 mr-2"></i>
                        <span class="text-sm text-blue-700">
                            Expires in {{ $healthCard->expiry_date->diffForHumans() }}
                        </span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                
                <div class="space-y-3">
                    <a href="{{ route('doctor.patients.show', $healthCard->student) }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-injured text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">View Patient</span>
                    </a>
                    
                    <a href="{{ route('doctor.medical-records.create') }}?student_id={{ $healthCard->student_id }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-medical text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Create Medical Record</span>
                    </a>
                    
                    <a href="{{ route('doctor.health-cards.index') }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-list text-gray-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">All Health Cards</span>
                    </a>
                </div>
            </div>

            <!-- QR Code -->
            @if($healthCard->qr_code)
            <div class="content-card rounded-lg p-6 shadow-sm text-center">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">QR Code</h4>
                
                <div class="flex justify-center mb-4">
                    <div class="bg-white p-4 rounded-lg border border-gray-200">
                        <!-- QR Code would be displayed here -->
                        <div class="w-32 h-32 bg-gray-100 rounded flex items-center justify-center">
                            <i class="fas fa-qrcode text-gray-400 text-2xl"></i>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-600">Scan to view health card information</p>
            </div>
            @endif
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

    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
@extends('layouts.student')

@section('title', 'Prescription Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 inter">Prescription Details</h1>
                <p class="text-gray-600 mt-1">Medical prescription from doctor consultation</p>
            </div>
            <a href="{{ route('student.health-report') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Health Report
            </a>
        </div>
    </div>

    <!-- Prescription Card -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between text-white">
                <div>
                    <h2 class="text-xl font-bold inter">Medical Prescription</h2>
                    <p class="text-blue-100 text-sm mt-1">
                        Date: {{ \Carbon\Carbon::parse($prescription->record_date)->format('F d, Y') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-blue-100">Record #{{ $prescription->id }}</p>
                    <span class="inline-block px-3 py-1 bg-blue-500 rounded-full text-xs font-semibold mt-1">
                        {{ ucfirst($prescription->record_type ?? 'General') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Patient Information -->
        <div class="px-6 py-4 bg-gray-50 border-b">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 inter">Patient Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Patient Name</p>
                    <p class="font-semibold text-gray-900">{{ $studentDetails->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Student ID</p>
                    <p class="font-semibold text-gray-900">{{ $studentDetails->student_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Class</p>
                    <p class="font-semibold text-gray-900">{{ $studentDetails->class->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Section</p>
                    <p class="font-semibold text-gray-900">{{ $studentDetails->section->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Doctor Information -->
        @if($prescription->recordedBy)
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 inter">Prescribed By</h3>
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-md text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">{{ $prescription->recordedBy->name }}</p>
                    <p class="text-sm text-gray-600">{{ $prescription->recordedBy->email }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Medical Details -->
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 inter">Medical Details</h3>
            
            <div class="space-y-4">
                <!-- Symptoms -->
                @if($prescription->symptoms)
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Symptoms</p>
                    <p class="{{ detectLanguageClass($prescription->symptoms) }} text-gray-900 bg-gray-50 p-3 rounded">
                        {{ $prescription->symptoms }}
                    </p>
                </div>
                @endif

                <!-- Diagnosis -->
                @if($prescription->diagnosis)
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Diagnosis</p>
                    <p class="{{ detectLanguageClass($prescription->diagnosis) }} text-gray-900 bg-gray-50 p-3 rounded">
                        {{ $prescription->diagnosis }}
                    </p>
                </div>
                @endif

                <!-- Medication -->
                @if($prescription->medication)
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Medication</p>
                    <p class="{{ detectLanguageClass($prescription->medication) }} text-gray-900 bg-blue-50 p-3 rounded border-l-4 border-blue-500">
                        {{ $prescription->medication }}
                    </p>
                </div>
                @endif

                <!-- Doctor Notes -->
                @if($prescription->doctor_notes)
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Doctor's Notes</p>
                    <p class="{{ detectLanguageClass($prescription->doctor_notes) }} text-gray-900 bg-yellow-50 p-3 rounded">
                        {{ $prescription->doctor_notes }}
                    </p>
                </div>
                @endif

                <!-- Vital Signs -->
                @if($prescription->height || $prescription->weight || $prescription->temperature || $prescription->blood_pressure)
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-2">Vital Signs</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @if($prescription->height)
                        <div class="bg-gray-50 p-3 rounded text-center">
                            <p class="text-xs text-gray-600">Height</p>
                            <p class="font-semibold text-gray-900">{{ $prescription->height }} cm</p>
                        </div>
                        @endif
                        @if($prescription->weight)
                        <div class="bg-gray-50 p-3 rounded text-center">
                            <p class="text-xs text-gray-600">Weight</p>
                            <p class="font-semibold text-gray-900">{{ $prescription->weight }} kg</p>
                        </div>
                        @endif
                        @if($prescription->temperature)
                        <div class="bg-gray-50 p-3 rounded text-center">
                            <p class="text-xs text-gray-600">Temperature</p>
                            <p class="font-semibold text-gray-900">{{ $prescription->temperature }}°F</p>
                        </div>
                        @endif
                        @if($prescription->blood_pressure)
                        <div class="bg-gray-50 p-3 rounded text-center">
                            <p class="text-xs text-gray-600">Blood Pressure</p>
                            <p class="font-semibold text-gray-900">{{ $prescription->blood_pressure }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Follow-up Date -->
                @if($prescription->follow_up_date)
                <div>
                    <p class="text-sm font-semibold text-gray-700 mb-1">Follow-up Date</p>
                    <p class="text-gray-900 bg-green-50 p-3 rounded inline-block">
                        <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                        {{ \Carbon\Carbon::parse($prescription->follow_up_date)->format('F d, Y') }}
                    </p>
                </div>
                @endif
            </div>
        </div>

       <!-- Prescription File -->
@if($prescription->prescription)
    @php
        $prescriptionPath = 'public/storage/' . $prescription->prescription;
        $fileExists = file_exists(storage_path('app/' . $prescriptionPath)) || 
                     file_exists(public_path('storage/' . $prescription->prescription));
    @endphp
    
    @if($fileExists)
    <div class="px-6 py-4 bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-800 mb-3 inter">Prescription Document</h3>
        <div class="flex items-center justify-between bg-white p-4 rounded-lg border">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-blue-100 rounded flex items-center justify-center">
                    <i class="fas fa-file-prescription text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Prescription File</p>
                    <p class="text-sm text-gray-600">{{ basename($prescription->prescription) }}</p>
                    @php
                        // Get file size if possible
                        $fileSize = '';
                        $fullPath = storage_path('app/' . $prescriptionPath);
                        if (file_exists($fullPath)) {
                            $size = filesize($fullPath);
                            if ($size < 1024) {
                                $fileSize = $size . ' bytes';
                            } elseif ($size < 1048576) {
                                $fileSize = round($size / 1024, 2) . ' KB';
                            } else {
                                $fileSize = round($size / 1048576, 2) . ' MB';
                            }
                        }
                    @endphp
                    @if($fileSize)
                        <p class="text-xs text-gray-500 mt-1">{{ $fileSize }}</p>
                    @endif
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ asset('public/storage/' . $prescription->prescription) }}" 
                   target="_blank"
                   class="btn-primary">
                    <i class="fas fa-eye mr-2"></i>View
                </a>
                <a href="{{ asset('public/storage/' . $prescription->prescription) }}" 
                   download
                   class="btn-secondary">
                    <i class="fas fa-download mr-2"></i>Download
                </a>
            </div>
        </div>
    </div>
    @endif
@endif

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('student.health-report') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Health Report
                </a>
                @if($prescription->prescription)
                <a href="{{ asset('public/storage/' . $prescription->prescription) }}" 
                   download
                   class="btn-primary">
                    <i class="fas fa-download mr-2"></i>Download Prescription
                </a>
                @endif
                <button onclick="window.print()" class="btn-secondary">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .btn-primary, .btn-secondary, nav, footer {
        display: none !important;
    }
    .container {
        max-width: 100% !important;
    }
}
</style>
@endsection

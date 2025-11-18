@extends('layouts.app')

@section('title', $student->user->name . ' - Student Details')

@section('content')
<div class="space-y-6">
    <!-- Student Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">{{ $student->user->name }}</h3>
                    <p class="text-gray-600">Student ID: {{ $student->student_id }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.students.edit', $student) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit Student
                </a>
                <a href="#" 
                   class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-id-card mr-2"></i>Generate ID Card
                </a>
                <a href="{{ route('admin.students.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Student Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Basic Information -->
        <div class="space-y-6">
            <!-- Profile Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Profile Information</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Student ID</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $student->student_id }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Roll Number</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $student->roll_number }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Class</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $student->class->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Section</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $student->section->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">School</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $student->user->school->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Admission Date</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Contact Information</h4>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-envelope text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Email</p>
                            <p class="text-sm text-gray-900">{{ $student->user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-phone text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Phone</p>
                            <p class="text-sm text-gray-900">{{ $student->user->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Address</p>
                            <p class="text-sm text-gray-900">{{ $student->user->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-phone-emergency text-gray-400 mt-1"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Emergency Contact</p>
                            <p class="text-sm text-gray-900">{{ $student->emergency_contact ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Column - Personal & Medical Information -->
        <div class="space-y-6">
            <!-- Personal Details -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Personal Details</h4>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Date of Birth</p>
                            <p class="text-sm text-gray-900">{{ $student->user->date_of_birth ? \Carbon\Carbon::parse($student->user->date_of_birth)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Age</p>
                            <p class="text-sm text-gray-900">
                                @if($student->user->date_of_birth)
                                    {{ \Carbon\Carbon::parse($student->user->date_of_birth)->age }} years
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Gender</p>
                            <p class="text-sm text-gray-900 capitalize">{{ $student->user->gender ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Blood Group</p>
                            <p class="text-sm text-gray-900">{{ $student->blood_group ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Medical Information</h4>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Allergies</p>
                        @if($student->allergies)
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $student->allergies) as $allergy)
                                    @if(trim($allergy))
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                                            {{ trim($allergy) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No allergies recorded</p>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Medical Conditions</p>
                        @if($student->medical_conditions)
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $student->medical_conditions) as $condition)
                                    @if(trim($condition))
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                            {{ trim($condition) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No medical conditions recorded</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Health Card Information -->
            @if($student->healthCard)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Health Card</h4>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Card Number</span>
                        <span class="text-sm text-gray-900 font-semibold">{{ $student->healthCard->card_number }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Status</span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium 
                            {{ $student->healthCard->status == 'active' ? 'bg-green-100 text-green-800' : 
                               ($student->healthCard->status == 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($student->healthCard->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600">Expiry Date</span>
                        <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($student->healthCard->expiry_date)->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Parent/Guardian Information -->
            @if($student->parent)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Parent/Guardian</h4>
                
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $student->parent->name }}</p>
                            <p class="text-xs text-gray-500">{{ $student->parent->email }}</p>
                        </div>
                    </div>
                    @if($student->parent->phone)
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i class="fas fa-phone"></i>
                        <span>{{ $student->parent->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Statistics & Quick Actions -->
        <div class="space-y-6">
            <!-- Quick Statistics -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Statistics</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Medical Records</p>
                            <p class="text-2xl font-bold text-blue-700">{{ $student->medicalRecords->count() }}</p>
                        </div>
                        <i class="fas fa-file-medical text-blue-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-green-600">Vaccination Records</p>
                            <p class="text-2xl font-bold text-green-700">{{ $student->vaccinationRecords->count() }}</p>
                        </div>
                        <i class="fas fa-syringe text-green-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Appointments</p>
                            <p class="text-2xl font-bold text-purple-700">{{ $student->appointments->count() }}</p>
                        </div>
                        <i class="fas fa-calendar-check text-purple-400 text-xl"></i>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-orange-600">Diary Entries</p>
                            <p class="text-2xl font-bold text-orange-700">{{ $student->diaryUpdates->count() }}</p>
                        </div>
                        <i class="fas fa-book-medical text-orange-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                
                <div class="space-y-3">
                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-plus text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Add Medical Record</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-syringe text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Add Vaccination</span>
                    </a>
                    
                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-print text-purple-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Print Health Card</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-plus text-red-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Book Appointment</span>
                    </a>
                </div>
            </div>

            <!-- Status -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Status</h4>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Account Status</span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                        {{ $student->user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($student->user->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Medical Records -->
    <div class="content-card rounded-lg shadow-sm">
        <div class="table-header px-6 py-4">
            <h4 class="text-xl font-semibold">Recent Medical Records</h4>
        </div>
        <div class="p-6">
            @if($student->medicalRecords->count() > 0)
            <div class="space-y-4">
                @foreach($student->medicalRecords->take(5) as $record)
                <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition-colors duration-200 border border-gray-100">
                    <div class="flex-shrink-0">
                        @php
                            $isEmergency = $record->record_type == 'emergency';
                            $bgColor = $isEmergency ? 'red' : 'blue';
                            $icon = $isEmergency ? 'exclamation-triangle' : 'stethoscope';
                        @endphp
                        <div class="w-10 h-10 rounded-full bg-{{ $bgColor }}-100 flex items-center justify-center border border-{{ $bgColor }}-200">
                            <i class="fas fa-{{ $icon }} text-{{ $bgColor }}-600"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $record->diagnosis ?? 'No diagnosis recorded' }}
                            </p>
                            <span class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($record->record_date)->format('M d, Y') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ Str::limit($record->symptoms, 100) }}
                        </p>
                        <div class="flex items-center mt-2 space-x-2">
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium capitalize">
                                {{ $record->record_type }}
                            </span>
                            @if($isEmergency)
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                    Emergency
                                </span>
                            @endif
                            @if($record->follow_up_date)
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                    Follow-up: {{ \Carbon\Carbon::parse($record->follow_up_date)->format('M d') }}
                                </span>
                            @endif
                        </div>
                        @if($record->prescription)
                        <div class="mt-2">
                            <p class="text-xs text-gray-500">Prescription: {{ Str::limit($record->prescription, 80) }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 border border-gray-200 rounded-lg bg-gray-50">
                <i class="fas fa-file-medical text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-500">No medical records found</p>
                <p class="text-sm text-gray-400 mt-1">Medical records will appear here once added</p>
            </div>
            @endif
            
            @if($student->medicalRecords->count() > 5)
            <div class="mt-4 text-center">
                <a href="{{ route('doctor.patients.show', $student) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    View All Medical Records ({{ $student->medicalRecords->count() }})
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Vaccination Records -->
    <div class="content-card rounded-lg shadow-sm">
        <div class="table-header px-6 py-4">
            <h4 class="text-xl font-semibold">Recent Vaccination Records</h4>
        </div>
        <div class="p-6">
            @if($student->vaccinationRecords->count() > 0)
            <div class="space-y-4">
                @foreach($student->vaccinationRecords->take(5) as $vaccination)
                <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition-colors duration-200 border border-gray-100">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center border border-green-200">
                            <i class="fas fa-syringe text-green-600"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $vaccination->vaccine_name }}
                            </p>
                            <span class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($vaccination->vaccine_date)->format('M d, Y') }}
                            </span>
                        </div>
                        <div class="flex items-center mt-2 space-x-4 text-sm text-gray-600">
                            <span>Dose: {{ $vaccination->dose_number }}</span>
                            @if($vaccination->administered_by)
                            <span>By: {{ $vaccination->administered_by }}</span>
                            @endif
                        </div>
                        @if($vaccination->next_due_date)
                        <div class="mt-2">
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">
                                Next due: {{ \Carbon\Carbon::parse($vaccination->next_due_date)->format('M d, Y') }}
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 border border-gray-200 rounded-lg bg-gray-50">
                <i class="fas fa-syringe text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-500">No vaccination records found</p>
                <p class="text-sm text-gray-400 mt-1">Vaccination records will appear here once added</p>
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
</style>
@endsection
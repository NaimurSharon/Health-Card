@extends('layouts.app')

@section('title', 'Medical Record Details')

@section('content')
<div class="space-y-6">
    <!-- Medical Record Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-medical text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Medical Record Details</h3>
                    <p class="text-gray-600">{{ $medicalRecord->record_date->format('F j, Y') }} - {{ ucfirst($medicalRecord->record_type) }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('doctor.medical-records.edit', $medicalRecord) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit Record
                </a>
                <a href="{{ route('doctor.medical-records.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Column - Record Details -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Patient Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Patient Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Student Name</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $medicalRecord->student->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Student ID</p>
                            <p class="text-lg text-gray-900">{{ $medicalRecord->student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Class & Section</p>
                            <p class="text-lg text-gray-900">
                                {{ $medicalRecord->student->class->name ?? 'N/A' }} - {{ $medicalRecord->student->section->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Blood Group</p>
                            <p class="text-lg text-gray-900 {{ $medicalRecord->student->blood_group ? 'text-red-600 font-semibold' : '' }}">
                                {{ $medicalRecord->student->blood_group ?? 'Not set' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Age</p>
                            <p class="text-lg text-gray-900">
                                @if($medicalRecord->student->user->date_of_birth)
                                    {{ $medicalRecord->student->user->date_of_birth->age }} years
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Medical Alerts -->
                @if($medicalRecord->student->allergies || $medicalRecord->student->medical_conditions)
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($medicalRecord->student->allergies)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Allergies</p>
                        <div class="bg-yellow-50 rounded-lg p-3 border-l-4 border-yellow-400">
                            <p class="text-sm text-gray-700">{{ $medicalRecord->student->allergies }}</p>
                        </div>
                    </div>
                    @endif

                    @if($medicalRecord->student->medical_conditions)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Medical Conditions</p>
                        <div class="bg-red-50 rounded-lg p-3 border-l-4 border-red-400">
                            <p class="text-sm text-gray-700">{{ $medicalRecord->student->medical_conditions }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Medical Details -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Medical Details</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Record Date</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $medicalRecord->record_date->format('F j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">Record Type</p>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            {{ $medicalRecord->record_type == 'emergency' ? 'bg-red-100 text-red-800' : 
                               ($medicalRecord->record_type == 'vaccination' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ ucfirst($medicalRecord->record_type) }}
                        </span>
                    </div>
                </div>

                <!-- Symptoms -->
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-600 mb-2">Symptoms</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $medicalRecord->symptoms }}</p>
                    </div>
                </div>

                <!-- Diagnosis -->
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-600 mb-2">Diagnosis</p>
                    <div class="bg-blue-50 rounded-lg p-4 border-l-4 border-blue-400">
                        <p class="text-gray-700">{{ $medicalRecord->diagnosis }}</p>
                    </div>
                </div>

                <!-- Treatment Information -->
                @if($medicalRecord->prescription || $medicalRecord->medication)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    @if($medicalRecord->prescription)
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-2">Prescription</p>
                            <div class="bg-green-50 rounded-lg p-4">
                                <a href="{{ asset('public/storage/' . $medicalRecord->prescription) }}" 
                                   target="_blank" 
                                   class="text-green-700 font-medium underline hover:text-green-900">
                                    View Prescription File
                                </a>
                            </div>
                        </div>
                    @endif


                    @if($medicalRecord->medication)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Medication Administered</p>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-gray-700">{{ $medicalRecord->medication }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Doctor Notes -->
                @if($medicalRecord->doctor_notes)
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-2">Doctor Notes</p>
                    <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-400">
                        <p class="text-gray-700">{{ $medicalRecord->doctor_notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Vital Signs -->
            @if($medicalRecord->height || $medicalRecord->weight || $medicalRecord->temperature || $medicalRecord->blood_pressure)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Vital Signs</h4>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @if($medicalRecord->height)
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-600">Height</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $medicalRecord->height }} cm</p>
                    </div>
                    @endif

                    @if($medicalRecord->weight)
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-600">Weight</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $medicalRecord->weight }} kg</p>
                    </div>
                    @endif

                    @if($medicalRecord->temperature)
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-600">Temperature</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $medicalRecord->temperature }} °C</p>
                    </div>
                    @endif

                    @if($medicalRecord->blood_pressure)
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-600">Blood Pressure</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $medicalRecord->blood_pressure }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Additional Information -->
        <div class="space-y-6">
            <!-- Record Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Record Information</h4>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Recorded By</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $medicalRecord->recordedBy->name }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-600">Recorded On</p>
                        <p class="text-lg text-gray-900">{{ $medicalRecord->created_at->format('F j, Y g:i A') }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-600">Last Updated</p>
                        <p class="text-lg text-gray-900">{{ $medicalRecord->updated_at->format('F j, Y g:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Follow-up Information -->
            @if($medicalRecord->follow_up_date)
            <div class="content-card rounded-lg p-6 shadow-sm border-l-4 border-green-500">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Follow-up Information</h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">Follow-up Date</span>
                        <span class="text-lg font-semibold {{ $medicalRecord->follow_up_date->isPast() ? 'text-red-600' : 'text-green-600' }}">
                            {{ $medicalRecord->follow_up_date->format('M j, Y') }}
                        </span>
                    </div>
                    
                    @if($medicalRecord->follow_up_date->isFuture())
                    <div class="p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-check text-green-500 mr-2"></i>
                            <span class="text-sm text-green-700">
                                Follow-up in {{ $medicalRecord->follow_up_date->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    @else
                    <div class="p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-sm text-red-700">
                                Follow-up was {{ $medicalRecord->follow_up_date->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Quick Actions</h4>
                
                <div class="space-y-3">
                    <a href="{{ route('doctor.medical-records.edit', $medicalRecord) }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-edit text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">Edit Record</span>
                    </a>
                    
                    <a href="{{ route('doctor.patients.show', $medicalRecord->student) }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-injured text-green-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">View Patient</span>
                    </a>
                    
                    <a href="{{ route('doctor.medical-records.index') }}" 
                       class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-list text-gray-600 text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700">All Records</span>
                    </a>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="content-card rounded-lg p-6 shadow-sm border border-red-200">
                <h4 class="text-xl font-semibold text-red-900 border-b border-red-200 pb-3 mb-4">Danger Zone</h4>
                
                <div class="space-y-3">
                    <p class="text-sm text-gray-600">
                        Once you delete a medical record, there is no going back. Please be certain.
                    </p>
                    
                    <form action="{{ route('doctor.medical-records.destroy', $medicalRecord) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="flex items-center space-x-3 p-3 hover:bg-red-50 rounded-lg transition-colors duration-200 w-full text-left text-red-600"
                                onclick="return confirm('Are you sure you want to delete this medical record? This action cannot be undone.')">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-trash text-red-600 text-sm"></i>
                            </div>
                            <span class="text-sm font-medium">Delete Record</span>
                        </button>
                    </form>
                </div>
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

    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }
</style>
@endsection
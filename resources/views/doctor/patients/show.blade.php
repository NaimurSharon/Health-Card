<<<<<<< HEAD
@extends('layouts.app')
=======
@extends('layouts.doctor')
>>>>>>> c356163 (video call ui setup)

@section('title', $student->user->name . ' - Patient Details')

@section('content')
<div class="space-y-6">
    <!-- Patient Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-injured text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">{{ $student->user->name }}</h3>
                    <p class="text-gray-600">Student ID: {{ $student->student_id }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('doctor.patients.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Patients
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left Column - Patient Information & Medical Records -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Patient Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Patient Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Full Name</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $student->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Student ID</p>
                            <p class="text-lg text-gray-900">{{ $student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Class & Section</p>
                            <p class="text-lg text-gray-900">
                                {{ $student->class->name ?? 'N/A' }} - {{ $student->section->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Roll Number</p>
                            <p class="text-lg text-gray-900">{{ $student->roll_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Blood Group</p>
                            <p class="text-lg text-gray-900 {{ $student->blood_group ? 'text-red-600 font-semibold' : '' }}">
                                {{ $student->blood_group ?? 'Not set' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Admission Date</p>
                            <p class="text-lg text-gray-900">{{ $student->admission_date ? $student->admission_date->format('M j, Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($student->allergies)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Allergies</p>
                        <div class="bg-yellow-50 rounded-lg p-3 border-l-4 border-yellow-400">
                            <p class="text-sm text-gray-700">{{ $student->allergies }}</p>
                        </div>
                    </div>
                    @endif

                    @if($student->medical_conditions)
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-2">Medical Conditions</p>
                        <div class="bg-red-50 rounded-lg p-3 border-l-4 border-red-400">
                            <p class="text-sm text-gray-700">{{ $student->medical_conditions }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                @if($student->emergency_contact)
                <div class="mt-6">
                    <p class="text-sm font-medium text-gray-600 mb-2">Emergency Contact</p>
                    <div class="bg-blue-50 rounded-lg p-3">
                        <p class="text-sm text-gray-700">{{ $student->emergency_contact }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Medical Records -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-xl font-semibold text-gray-900">Medical Records</h4>
                    <span class="text-sm text-gray-600">{{ $medicalRecords->total() }} records</span>
                </div>

                @if($medicalRecords->count() > 0)
                    <div class="space-y-4">
                        @foreach($medicalRecords as $record)
                        <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-900">{{ $record->record_date->format('F j, Y') }}</span>
                                    <span class="ml-2 px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $record->record_type == 'emergency' ? 'bg-red-100 text-red-800' : 
                                           ($record->record_type == 'vaccination' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst($record->record_type) }}
                                    </span>
                                </div>
                                <span class="text-xs text-gray-500">By: {{ $record->recordedBy->name }}</span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                <div>
                                    <p class="text-xs font-medium text-gray-600">Symptoms</p>
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ $record->symptoms }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-600">Diagnosis</p>
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ $record->diagnosis }}</p>
                                </div>
                            </div>

                            @if($record->prescription || $record->medication)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                @if($record->prescription)
                                <div>
                                    <p class="text-xs font-medium text-gray-600">Prescription</p>
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ $record->prescription }}</p>
                                </div>
                                @endif
                                @if($record->medication)
                                <div>
                                    <p class="text-xs font-medium text-gray-600">Medication</p>
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ $record->medication }}</p>
                                </div>
                                @endif
                            </div>
                            @endif

                            @if($record->doctor_notes)
                            <div>
                                <p class="text-xs font-medium text-gray-600">Doctor Notes</p>
                                <p class="text-sm text-gray-700 line-clamp-2">{{ $record->doctor_notes }}</p>
                            </div>
                            @endif

                            @if($record->follow_up_date)
                            <div class="mt-2">
                                <span class="text-xs font-medium text-gray-600">Follow-up: </span>
                                <span class="text-xs text-blue-600">{{ $record->follow_up_date->format('M j, Y') }}</span>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($medicalRecords->hasPages())
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            {{ $medicalRecords->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-file-medical text-4xl mb-4 text-gray-300"></i>
                        <p class="text-lg text-gray-500">No medical records found</p>
                        <p class="text-sm mt-2">This patient has no medical records yet.</p>
                    </div>
                @endif
            </div>

            <!-- Create New Medical Record -->
            <div class="content-card rounded-lg p-6 shadow-sm border-l-4 border-green-500">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Create New Medical Record</h4>
                
                <form action="{{ route('doctor.patients.create-medical-record', $student) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="record_type" class="block text-sm font-medium text-gray-700 mb-2">Record Type *</label>
                            <select name="record_type" id="record_type" required
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Type</option>
                                <option value="checkup">Regular Checkup</option>
                                <option value="vaccination">Vaccination</option>
                                <option value="emergency">Emergency</option>
                                <option value="routine">Routine Visit</option>
                                <option value="sickness">Sickness</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="symptoms" class="block text-sm font-medium text-gray-700 mb-2">Symptoms *</label>
                        <textarea name="symptoms" id="symptoms" rows="3" required
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Describe the symptoms...">{{ old('symptoms') }}</textarea>
                    </div>

                    <div>
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-2">Diagnosis *</label>
                        <textarea name="diagnosis" id="diagnosis" rows="3" required
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter diagnosis...">{{ old('diagnosis') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="prescription" class="block text-sm font-medium text-gray-700 mb-2">Prescription</label>
                            <textarea name="prescription" id="prescription" rows="2"
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                    placeholder="Enter prescription...">{{ old('prescription') }}</textarea>
                        </div>
                        <div>
                            <label for="medication" class="block text-sm font-medium text-gray-700 mb-2">Medication</label>
                            <textarea name="medication" id="medication" rows="2"
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                    placeholder="Enter medication...">{{ old('medication') }}</textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-2">Height (cm)</label>
                            <input type="number" step="0.1" name="height" id="height" 
                                   value="{{ old('height') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Height">
                        </div>
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                            <input type="number" step="0.1" name="weight" id="weight" 
                                   value="{{ old('weight') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Weight">
                        </div>
                        <div>
                            <label for="temperature" class="block text-sm font-medium text-gray-700 mb-2">Temperature (¡ÆC)</label>
                            <input type="number" step="0.1" name="temperature" id="temperature" 
                                   value="{{ old('temperature') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Temperature">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="blood_pressure" class="block text-sm font-medium text-gray-700 mb-2">Blood Pressure</label>
                            <input type="text" name="blood_pressure" id="blood_pressure" 
                                   value="{{ old('blood_pressure') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., 120/80">
                        </div>
                        <div>
                            <label for="follow_up_date" class="block text-sm font-medium text-gray-700 mb-2">Follow-up Date</label>
                            <input type="date" name="follow_up_date" id="follow_up_date" 
                                   value="{{ old('follow_up_date') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        </div>
                    </div>

                    <div>
                        <label for="doctor_notes" class="block text-sm font-medium text-gray-700 mb-2">Doctor Notes</label>
                        <textarea name="doctor_notes" id="doctor_notes" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter additional notes...">{{ old('doctor_notes') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                            <i class="fas fa-file-medical mr-2"></i>Create Medical Record
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column - Statistics & Information -->
        <div class="space-y-6">
            <!-- Statistics Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Patient Statistics</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-blue-600">Total Visits</p>
                            <p class="text-2xl font-bold text-blue-700">{{ $stats['total_visits'] }}</p>
                        </div>
                        <i class="fas fa-stethoscope text-blue-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-green-600">Last Visit</p>
                            <p class="text-lg font-bold text-green-700">
                                {{ $stats['last_visit'] ? $stats['last_visit']->format('M j') : 'Never' }}
                            </p>
                        </div>
                        <i class="fas fa-calendar-check text-green-400 text-xl"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Upcoming Appointments</p>
                            <p class="text-2xl font-bold text-purple-700">{{ $stats['upcoming_appointments'] }}</p>
                        </div>
                        <i class="fas fa-calendar-alt text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Vaccination Records -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Vaccination Records</h4>
                
                @if($vaccinationRecords->count() > 0)
                    <div class="space-y-3">
                        @foreach($vaccinationRecords as $vaccine)
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-sm font-medium text-gray-900">{{ $vaccine->vaccine_name }}</span>
                                <span class="text-xs text-gray-500">Dose {{ $vaccine->dose_number }}</span>
                            </div>
                            <p class="text-xs text-gray-600">{{ $vaccine->vaccine_date->format('M j, Y') }}</p>
                            @if($vaccine->next_due_date)
                            <p class="text-xs text-blue-600 mt-1">Next: {{ $vaccine->next_due_date->format('M j, Y') }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-syringe text-2xl mb-2 text-gray-300"></i>
                        <p class="text-sm text-gray-500">No vaccination records</p>
                    </div>
                @endif
            </div>

            <!-- Upcoming Appointments -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Upcoming Appointments</h4>
                
                @if($upcomingAppointments->count() > 0)
                    <div class="space-y-3">
                        @foreach($upcomingAppointments as $appointment)
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-sm font-medium text-gray-900">{{ $appointment->appointment_date->format('M j') }}</span>
                                <span class="text-xs text-gray-500">{{ $appointment->appointment_time->format('g:i A') }}</span>
                            </div>
                            <p class="text-xs text-gray-600 line-clamp-2">{{ $appointment->reason }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-times text-2xl mb-2 text-gray-300"></i>
                        <p class="text-sm text-gray-500">No upcoming appointments</p>
                    </div>
                @endif
            </div>

            <!-- Health Card Status -->
            @if($student->healthCard)
            <div class="content-card rounded-lg p-6 shadow-sm border-l-4 border-green-500">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Health Card</h4>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Card Number:</span>
                        <span class="text-sm font-medium">{{ $student->healthCard->card_number }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Status:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-medium 
                            {{ $student->healthCard->status == 'active' ? 'bg-green-100 text-green-800' : 
                               ($student->healthCard->status == 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($student->healthCard->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Expires:</span>
                        <span class="text-sm font-medium {{ $student->healthCard->expiry_date->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $student->healthCard->expiry_date->format('M j, Y') }}
                        </span>
                    </div>
                </div>
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
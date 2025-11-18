@extends('layouts.app')

@section('title', 'Appointment Details')

@section('content')
<div class="space-y-6">
    <!-- Appointment Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-bold">Appointment Details</h3>
                    <p class="text-gray-600">Scheduled for {{ $appointment->appointment_date->format('F j, Y') }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('doctor.appointments.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Appointment Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Appointment Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Appointment Information</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Student Name</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $appointment->student->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Student ID</p>
                            <p class="text-lg text-gray-900">{{ $appointment->student->student_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Class</p>
                            <p class="text-lg text-gray-900">{{ $appointment->student->class->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Appointment Date</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $appointment->appointment_date->format('F j, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Appointment Time</p>
                            <p class="text-lg text-gray-900">{{ $appointment->appointment_time->format('g:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Reason</p>
                            <p class="text-lg text-gray-900">{{ $appointment->reason }}</p>
                        </div>
                    </div>
                </div>

                <!-- Symptoms -->
                <div class="mt-6">
                    <p class="text-sm font-medium text-gray-600 mb-2">Symptoms</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $appointment->symptoms }}</p>
                    </div>
                </div>

                <!-- Notes -->
                @if($appointment->notes)
                <div class="mt-6">
                    <p class="text-sm font-medium text-gray-600 mb-2">Notes</p>
                    <div class="bg-yellow-50 rounded-lg p-4 border-l-4 border-yellow-400">
                        <p class="text-gray-700">{{ $appointment->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Update Status Form -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Update Status</h4>
                
                <form action="{{ route('doctor.appointments.update-status', $appointment) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="no_show" {{ $appointment->status == 'no_show' ? 'selected' : '' }}>No Show</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Add any notes about this appointment...">{{ old('notes', $appointment->notes) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                            <i class="fas fa-save mr-2"></i>Update Status
                        </button>
                    </div>
                </form>
            </div>

            <!-- Create Medical Record Form -->
            @if($appointment->status == 'scheduled')
            <div class="content-card rounded-lg p-6 shadow-sm border-l-4 border-green-500">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Complete Appointment</h4>
                
                <form action="{{ route('doctor.appointments.create-medical-record', $appointment) }}" method="POST" class="space-y-4">
                    @csrf
                    
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

                    <div>
                        <label for="doctor_notes" class="block text-sm font-medium text-gray-700 mb-2">Doctor Notes</label>
                        <textarea name="doctor_notes" id="doctor_notes" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter additional notes...">{{ old('doctor_notes') }}</textarea>
                    </div>

                    <div>
                        <label for="follow_up_date" class="block text-sm font-medium text-gray-700 mb-2">Follow-up Date</label>
                        <input type="date" name="follow_up_date" id="follow_up_date" 
                               value="{{ old('follow_up_date') }}"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                            <i class="fas fa-file-medical mr-2"></i>Create Medical Record & Complete
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <!-- Right Column - Student Information & History -->
        <div class="space-y-6">
            <!-- Student Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Student Information</h4>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $appointment->student->user->name }}</p>
                            <p class="text-sm text-gray-600">ID: {{ $appointment->student->student_id }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Class:</span>
                            <span class="text-sm font-medium">{{ $appointment->student->class->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Section:</span>
                            <span class="text-sm font-medium">{{ $appointment->student->section->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Blood Group:</span>
                            <span class="text-sm font-medium">{{ $appointment->student->blood_group ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical History -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Recent Medical History</h4>
                
                @if($medicalHistory->count() > 0)
                    <div class="space-y-4">
                        @foreach($medicalHistory as $record)
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-medium text-gray-900">{{ $record->record_date->format('M j, Y') }}</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($record->record_type) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ $record->diagnosis }}</p>
                            <p class="text-xs text-gray-500 mt-1">By: {{ $record->recordedBy->name }}</p>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-file-medical text-2xl mb-2 text-gray-300"></i>
                        <p class="text-sm text-gray-500">No medical history found</p>
                    </div>
                @endif
            </div>

            <!-- Status Card -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Appointment Status</h4>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Current Status</span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium 
                        {{ $appointment->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                           ($appointment->status == 'completed' ? 'bg-green-100 text-green-800' : 
                           ($appointment->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>
                
                @if($appointment->status == 'scheduled')
                <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-blue-500 mr-2"></i>
                        <span class="text-sm text-blue-700">This appointment is scheduled</span>
                    </div>
                </div>
                @elseif($appointment->status == 'completed')
                <div class="mt-3 p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-sm text-green-700">This appointment has been completed</span>
                    </div>
                </div>
                @endif
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

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
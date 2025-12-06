@extends('layouts.doctor')

@section('title', 'Consultation Details')

@section('content')
    <div class="max-w-md mx-auto lg:max-w-4xl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <a href="{{ route('doctor.consultations.index') }}"
                    class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 mr-4 shadow-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-xl font-bold text-gray-900">Consultation Details</h1>
            </div>

            <div class="flex items-center space-x-3">
                <!-- Print Button (only show for completed consultations with prescriptions) -->
                @if($consultation->status === 'completed' && $consultation->prescription)
                    <button onclick="printPrescription()"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                        <i class="fas fa-print"></i>
                        Print Prescription
                    </button>
                @endif

                <!-- Status Update Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                        <i class="fas fa-edit"></i>
                        Update Status
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                        <form method="POST" action="{{ route('doctor.consultations.update-status', $consultation->id) }}">
                            @csrf
                            <div class="p-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Update Status:</label>
                                <select name="status"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                    <option value="scheduled" {{ $consultation->status == 'scheduled' ? 'selected' : '' }}>
                                        Scheduled</option>
                                    <option value="ongoing" {{ $consultation->status == 'ongoing' ? 'selected' : '' }}>Ongoing
                                    </option>
                                    <option value="completed" {{ $consultation->status == 'completed' ? 'selected' : '' }}>
                                        Completed</option>
                                    <option value="cancelled" {{ $consultation->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                    <option value="no_show" {{ $consultation->status == 'no_show' ? 'selected' : '' }}>No Show
                                    </option>
                                </select>
                                <div class="mt-3 flex justify-end space-x-2">
                                    <button type="button" @click="open = false"
                                        class="px-3 py-1 text-sm text-gray-600 hover:text-gray-800">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-[#E8DEF8] p-6 rounded-[2rem] mb-6 relative overflow-hidden">
            <!-- Decorative circle -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-purple-200/50 rounded-full blur-2xl"></div>

            <div class="relative z-10">
                <div class="flex justify-between items-start mb-6">
                    <span
                        class="px-4 py-1.5 bg-white/60 backdrop-blur-sm rounded-full text-sm font-semibold text-purple-900">
                        {{ $consultation->status_display }}
                    </span>
                    <span class="text-purple-900 font-medium">
                        {{ $consultation->scheduled_for->format('M d, Y') }}
                    </span>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-2">Video Consultation</h2>
                <p class="text-purple-800 mb-8 opacity-80">
                    {{ $consultation->type == 'instant' ? 'Instant Consultation' : 'Scheduled Appointment' }}
                </p>

                <div class="bg-white/40 backdrop-blur-md rounded-2xl p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-bold overflow-hidden border-2 border-white">
                                @if($consultation->user && $consultation->user->profile_photo_url)
                                    <img src="{{ $consultation->user->profile_photo_url }}"
                                        alt="{{ $consultation->user->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($consultation->user->name ?? 'P', 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-purple-900 uppercase tracking-wider font-semibold opacity-70">Patient
                                </p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $consultation->user->name ?? 'Unknown Patient' }}
                                </p>
                                <p class="text-sm text-gray-600">{{ ucfirst($consultation->patient_type) }}</p>
                            </div>
                        </div>

                        @if($consultation->isAvailable())
                            <a href="{{ route('video-consultation.join', $consultation->id) }}"
                                class="bg-black text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition-colors shadow-lg transform hover:scale-105 transition-transform flex items-center gap-2">
                                <i class="fas fa-video"></i>
                                {{ $consultation->status === 'ongoing' ? 'Join Call' : 'Start Call' }}
                            </a>
                        @elseif($consultation->status == 'completed')
                            <button disabled
                                class="bg-green-100 text-green-700 px-6 py-3 rounded-xl font-bold cursor-not-allowed flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                Completed
                            </button>
                        @elseif($consultation->status == 'cancelled')
                            <button disabled
                                class="bg-red-100 text-red-700 px-6 py-3 rounded-xl font-bold cursor-not-allowed flex items-center gap-2">
                                <i class="fas fa-times-circle"></i>
                                Cancelled
                            </button>
                        @else
                            <button disabled
                                class="bg-white/50 text-gray-500 px-6 py-3 rounded-xl font-bold cursor-not-allowed flex items-center gap-2">
                                <i class="fas fa-clock"></i>
                                <span id="countdown-text">{{ $consultation->status_display }}</span>
                            </button>
                        @endif
                    </div>

                    <!-- Patient Contact Info (for doctors only) -->
                    @if($consultation->user)
                        <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-white/30">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-envelope text-purple-600"></i>
                                <span class="text-sm text-gray-700">{{ $consultation->user->email }}</span>
                            </div>
                            @if($consultation->user->phone)
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-phone text-purple-600"></i>
                                    <span class="text-sm text-gray-700">{{ $consultation->user->phone }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Hidden print content -->
        <div id="print-prescription" class="hidden">
            <div style="padding: 20px; max-width: 800px; margin: 0 auto; font-family: Arial, sans-serif;">
                <!-- Header -->
                <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px;">
                    <h1 style="font-size: 28px; font-weight: bold; color: #333;">MEDICAL PRESCRIPTION</h1>
                    <p style="color: #666; margin-top: 5px;">Video Consultation Report</p>
                </div>

                <!-- Patient & Doctor Info -->
                <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
                    <div>
                        <h3 style="font-size: 16px; font-weight: bold; color: #333; margin-bottom: 10px;">PATIENT
                            INFORMATION</h3>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 5px 0; color: #666;">Name:</td>
                                <td style="padding: 5px 0; font-weight: bold;">
                                    {{ $consultation->user->name ?? 'Unknown Patient' }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0; color: #666;">Type:</td>
                                <td style="padding: 5px 0; font-weight: bold;">{{ ucfirst($consultation->patient_type) }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0; color: #666;">Email:</td>
                                <td style="padding: 5px 0;">{{ $consultation->user->email ?? '' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0; color: #666;">Phone:</td>
                                <td style="padding: 5px 0;">{{ $consultation->user->phone ?? '' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <h3 style="font-size: 16px; font-weight: bold; color: #333; margin-bottom: 10px;">DOCTOR INFORMATION
                        </h3>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 5px 0; color: #666;">Name:</td>
                                <td style="padding: 5px 0; font-weight: bold;">Dr. {{ auth()->user()->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0; color: #666;">Date:</td>
                                <td style="padding: 5px 0;">{{ now()->format('M d, Y') }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 5px 0; color: #666;">Consultation ID:</td>
                                <td style="padding: 5px 0; font-weight: bold;">#{{ $consultation->id }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Consultation Details -->
                <div style="margin-bottom: 30px; padding: 15px; background: #f5f5f5; border-radius: 5px;">
                    <h3 style="font-size: 16px; font-weight: bold; color: #333; margin-bottom: 10px;">CONSULTATION DETAILS
                    </h3>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 5px 0; color: #666;">Date:</td>
                            <td style="padding: 5px 0;">{{ $consultation->scheduled_for->format('M d, Y') }}</td>
                            <td style="padding: 5px 0; color: #666;">Time:</td>
                            <td style="padding: 5px 0;">{{ $consultation->scheduled_for->format('h:i A') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; color: #666;">Duration:</td>
                            <td style="padding: 5px 0;">{{ gmdate('H:i', $consultation->duration ?? 0) }}</td>
                            <td style="padding: 5px 0; color: #666;">Status:</td>
                            <td style="padding: 5px 0; font-weight: bold; text-transform: capitalize;">
                                {{ $consultation->status }}
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Symptoms -->
                @if($consultation->symptoms)
                    <div style="margin-bottom: 30px;">
                        <h3
                            style="font-size: 16px; font-weight: bold; color: #333; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                            REPORTED SYMPTOMS</h3>
                        <p style="color: #333; line-height: 1.6; padding: 10px; background: #f9f9f9; border-radius: 5px;">
                            {{ $consultation->symptoms }}
                        </p>
                    </div>
                @endif

                <!-- Prescription Content -->
                <div style="margin-bottom: 30px;">
                    <h3
                        style="font-size: 16px; font-weight: bold; color: #333; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">
                        MEDICAL PRESCRIPTION</h3>

                    @if($consultation->call_metadata && isset($consultation->call_metadata['diagnosis']))
                        <div style="margin-bottom: 20px;">
                            <h4 style="font-size: 14px; font-weight: bold; color: #555; margin-bottom: 5px;">Diagnosis:</h4>
                            <p style="color: #333; line-height: 1.6; padding-left: 10px;">
                                {{ $consultation->call_metadata['diagnosis'] }}
                            </p>
                        </div>
                    @endif

                    @if($consultation->prescription)
                        <div style="margin-bottom: 20px;">
                            <h4 style="font-size: 14px; font-weight: bold; color: #555; margin-bottom: 5px;">Prescription:</h4>
                            <div style="color: #333; line-height: 1.6; padding-left: 10px; white-space: pre-line;">
                                {{ $consultation->prescription }}
                            </div>
                        </div>
                    @endif

                    @if($consultation->call_metadata && isset($consultation->call_metadata['medication']))
                        <div style="margin-bottom: 10px;">
                            <h4 style="font-size: 14px; font-weight: bold; color: #555; margin-bottom: 5px;">Medication:</h4>
                            <p style="color: #333; line-height: 1.6; padding-left: 10px;">
                                {{ $consultation->call_metadata['medication'] }}
                            </p>
                        </div>
                    @endif

                    @if($consultation->doctor_notes)
                        <div style="margin-bottom: 10px;">
                            <h4 style="font-size: 14px; font-weight: bold; color: #555; margin-bottom: 5px;">Doctor's Notes:
                            </h4>
                            <p style="color: #333; line-height: 1.6; padding-left: 10px; white-space: pre-line;">
                                {{ $consultation->doctor_notes }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div style=" padding-top: 10px; margin-top: 40px;">
                    <div style="display: flex; justify-content: space-between;">
                        <div style="text-align: center;">
                            <div style="border-bottom: 1px solid #333; width: 200px; padding-top: 10px;">

                                @if(auth()->user()->doctorDetail && auth()->user()->doctorDetail->signature)
                                    <img src="{{ asset('public/storage/' . auth()->user()->doctorDetail->signature) }}"
                                        alt="Signature" style="width: 100px;">
                                @else
                                    <p style="font-weight: bold;">Dr. {{ auth()->user()->name }}</p>
                                @endif

                            </div>
                        </div>

                        <div style="text-align: center;">
                            <div style="width: 200px; padding-top: 10px;">
                                <p style="color: #666;">{{ now()->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center; margin-top: 30px;">
                        <p style="color: #999; font-size: 12px;">
                            This is an electronically generated prescription. No physical signature required.<br>
                            Consultation ID: #{{ $consultation->id }} | Generated on: {{ now()->format('Y-m-d H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doctor Actions -->
        @if($consultation->status === 'completed' || $consultation->status === 'ongoing')
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Doctor's Actions</h3>

                <!-- Add Prescription Form -->
                <form method="POST" action="{{ route('doctor.consultations.create-medical-record', $consultation->id) }}"
                    class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Diagnosis *</label>
                        <textarea name="diagnosis" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter diagnosis...">{{ old('diagnosis', $consultation->call_metadata['diagnosis'] ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prescription</label>
                        <textarea name="prescription" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter prescription...">{{ old('prescription', $consultation->prescription) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Medication</label>
                        <input type="text" name="medication"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter prescribed medication..."
                            value="{{ old('medication', $consultation->call_metadata['medication'] ?? '') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Doctor's Notes</label>
                        <textarea name="doctor_notes" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Additional notes...">{{ old('doctor_notes', $consultation->doctor_notes) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Follow-up Date (Optional)</label>
                        <input type="date" name="follow_up_date"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('follow_up_date') }}">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="reset" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Clear
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Save & Complete Consultation
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Patient Details & Time Info Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Patient Information -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mb-4">
                    <i class="fas fa-user-injured"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Patient Information</h3>
                <p class="text-gray-500 text-sm mb-4">Details about the patient</p>

                <div class="space-y-3">
                    @php
                        $patientDetails = $consultation->getPatientDetails();
                    @endphp
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-gray-600 text-sm">Patient Type</span>
                        <span class="font-semibold text-gray-900 capitalize">{{ $patientDetails['type'] }}</span>
                    </div>

                    @if($patientDetails['type'] === 'student' && isset($patientDetails['student_id']))
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600 text-sm">Student ID</span>
                            <span class="font-semibold text-gray-900">{{ $patientDetails['student_id'] }}</span>
                        </div>
                    @endif

                    @if($patientDetails['type'] === 'student' && isset($patientDetails['parent_name']))
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600 text-sm">Parent/Guardian</span>
                            <span class="font-semibold text-gray-900">{{ $patientDetails['parent_name'] }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Time & Duration -->
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 mb-4">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Time & Duration</h3>
                <p class="text-gray-500 text-sm mb-4">Consultation timing details</p>

                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-gray-600 text-sm">Scheduled Time</span>
                        <span class="font-semibold text-gray-900">{{ $consultation->scheduled_for->format('h:i A') }}</span>
                    </div>
                    @if($consultation->started_at)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600 text-sm">Started At</span>
                            <span class="font-semibold text-gray-900">{{ $consultation->started_at->format('h:i A') }}</span>
                        </div>
                    @endif
                    @if($consultation->ended_at)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                            <span class="text-gray-600 text-sm">Ended At</span>
                            <span class="font-semibold text-gray-900">{{ $consultation->ended_at->format('h:i A') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-gray-600 text-sm">Duration</span>
                        <span class="font-semibold text-gray-900">
                            @if($consultation->duration)
                                {{ gmdate('H:i', $consultation->duration) }}
                            @else
                                15 min (scheduled)
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Symptoms -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Reported Symptoms</h3>
            <p class="text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-2xl">
                {{ $consultation->symptoms ?: 'No symptoms reported' }}
            </p>
        </div>

        <!-- Prescription Display -->
        @if($consultation->status === 'completed' && ($consultation->prescription || $consultation->doctor_notes))
            <div class="bg-[#C4E7FF] p-6 rounded-3xl mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-white/50 flex items-center justify-center text-blue-700 mr-3">
                            <i class="fas fa-prescription"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Prescription & Notes</h3>
                    </div>
                    @if($consultation->prescription)
                        <button onclick="printPrescription()"
                            class="text-blue-800 font-bold text-sm hover:underline flex items-center gap-1">
                            <i class="fas fa-print"></i> Print Prescription
                        </button>
                    @endif
                </div>

                <div class="bg-white/60 backdrop-blur-sm p-5 rounded-2xl space-y-4">
                    @if($consultation->call_metadata && isset($consultation->call_metadata['diagnosis']))
                        <div>
                            <p class="text-xs font-bold text-blue-800 uppercase mb-1">Diagnosis</p>
                            <p class="text-gray-800">{{ $consultation->call_metadata['diagnosis'] }}</p>
                        </div>
                    @endif

                    @if($consultation->prescription)
                        <div>
                            <p class="text-xs font-bold text-blue-800 uppercase mb-1">Prescription</p>
                            <p class="text-gray-800 whitespace-pre-line">{{ $consultation->prescription }}</p>
                        </div>
                    @endif

                    @if($consultation->doctor_notes)
                        <div>
                            <p class="text-xs font-bold text-blue-800 uppercase mb-1">Doctor's Notes</p>
                            <p class="text-gray-800 whitespace-pre-line">{{ $consultation->doctor_notes }}</p>
                        </div>
                    @endif

                    @if(isset($consultation->call_metadata['medication']) && $consultation->call_metadata['medication'])
                        <div>
                            <p class="text-xs font-bold text-blue-800 uppercase mb-1">Medication</p>
                            <p class="text-gray-800">{{ $consultation->call_metadata['medication'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Medical History (For Students) -->
        @if($consultation->patient_type === 'student' && $medicalHistory->count() > 0)
            <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Medical History</h3>
                <p class="text-gray-500 text-sm mb-4">Past medical records for this student</p>

                <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                    @foreach($medicalHistory as $record)
                        <div class="bg-gray-50 p-4 rounded-2xl border border-gray-200 hover:bg-gray-100 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ ucfirst($record->record_type) }}</p>
                                    <p class="text-xs text-gray-500">{{ $record->record_date->format('M d, Y') }}</p>
                                </div>
                                @if($record->recordedBy)
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        Dr. {{ $record->recordedBy->name }}
                                    </span>
                                @endif
                            </div>

                            @if($record->diagnosis)
                                <p class="text-sm text-gray-700 mb-1">
                                    <span class="font-medium">Diagnosis:</span> {{ Str::limit($record->diagnosis, 80) }}
                                </p>
                            @endif

                            @if($record->prescription)
                                <p class="text-sm text-gray-700">
                                    <span class="font-medium">Prescription:</span> {{ Str::limit($record->prescription, 80) }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Payment Info -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 mb-4">
                <i class="fas fa-wallet"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-1">Payment Details</h3>
            <p class="text-gray-500 text-sm mb-4">Fee and payment status</p>

            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-gray-600 text-sm">Consultation Fee</span>
                    <span class="font-semibold text-gray-900">৳
                        {{ number_format($consultation->consultation_fee, 2) }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                    <span class="text-gray-600 text-sm">Status</span>
                    <span
                        class="font-semibold {{ $consultation->payment_status == 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                        {{ ucfirst($consultation->payment_status) }}
                    </span>
                </div>
                @if($consultation->payment)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                        <span class="text-gray-600 text-sm">Transaction ID</span>
                        <span class="font-semibold text-gray-900 text-sm">{{ $consultation->payment->transaction_id }}</span>
                    </div>
                @endif
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script>
            // Print prescription function
            function printPrescription() {
                // Get the print content
                const printContent = document.getElementById('print-prescription').innerHTML;

                // Create a new window for printing
                const printWindow = window.open('', '_blank', 'width=800,height=600');

                // Write the content to the new window
                printWindow.document.write(`
                                                                                                                                            <!DOCTYPE html>
                                                                                                                                            <html>
                                                                                                                                            <head>
                                                                                                                                                <title>Medical Prescription - Consultation #{{ $consultation->id }}</title>
                                                                                                                                                <style>
                                                                                                                                                    @media print {
                                                                                                                                                        body { margin: 0; padding: 20px; }
                                                                                                                                                        .no-print { display: none !important; }
                                                                                                                                                        @page { margin: 0.5in; }
                                                                                                                                                    }
                                                                                                                                                    body { font-family: Arial, sans-serif; color: #333; }
                                                                                                                                                    h1, h2, h3, h4 { margin: 0 0 10px 0; }
                                                                                                                                                    table { width: 100%; border-collapse: collapse; }
                                                                                                                                                    td { padding: 5px 0; }
                                                                                                                                                    .border-bottom { border-bottom: 1px solid #ddd; padding-bottom: 10px; margin-bottom: 10px; }
                                                                                                                                                </style>
                                                                                                                                            </head>
                                                                                                                                            <body>
                                                                                                                                                ${printContent}
                                                                                                                                                <script>
                                                                                                                                                    window.onload = function() {
                                                                                                                                                        window.print();
                                                                                                                                                        setTimeout(function() {
                                                                                                                                                            window.close();
                                                                                                                                                        }, 500);
                                                                                                                                                    }
                                                                                                                                                <\/script>
                                                                                                                                            </body>
                                                                                                                                            </html>
                                                                                                                                        `);

                printWindow.document.close();
            }

            // Auto-refresh page every 30 seconds if consultation is scheduled (not yet available)
            @if($consultation->status === 'scheduled' && !$consultation->isAvailable())
                let refreshInterval = setInterval(function () {
                    // Reload the page to check if consultation is now available
                    window.location.reload();
                }, 30000); // 30 seconds

                // Update countdown every second
                let countdownElement = document.getElementById('countdown-text');
                if (countdownElement) {
                    let scheduledTime = new Date('{{ $consultation->scheduled_for->toIso8601String() }}');

                    setInterval(function () {
                        let now = new Date();
                        let diff = scheduledTime - now;

                        // If time has passed or within 15 minutes before, reload page
                        if (diff <= 15 * 60 * 1000) {
                            window.location.reload();
                            return;
                        }

                        // Calculate time remaining
                        let minutes = Math.floor(diff / 60000);
                        let hours = Math.floor(minutes / 60);
                        let days = Math.floor(hours / 24);

                        if (days > 0) {
                            countdownElement.textContent = `Starts in ${days} day${days > 1 ? 's' : ''}`;
                        } else if (hours > 0) {
                            countdownElement.textContent = `Starts in ${hours} hour${hours > 1 ? 's' : ''}`;
                        } else if (minutes > 0) {
                            countdownElement.textContent = `Starts in ${minutes} minute${minutes > 1 ? 's' : ''}`;
                        } else {
                            countdownElement.textContent = 'Starting soon...';
                        }
                    }, 1000);
                }
            @endif

            // Show notification when consultation is available
            @if($consultation->isAvailable() && $consultation->status === 'scheduled')
                // Show browser notification if supported
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification('Video Consultation Ready', {
                        body: 'Consultation with {{ $consultation->user->name ?? "Patient" }} is ready to start!',
                        icon: '/images/logo.png',
                        tag: 'consultation-{{ $consultation->id }}'
                    });
                }
            @endif
        </script>
    @endpush
@endsection
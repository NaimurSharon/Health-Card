@extends('layouts.doctor')

@section('title', 'Add New Medical Record')

@section('content')
<div class="space-y-6">
    <!-- Medical Record Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Add New Medical Record</h3>
            <button type="submit" form="medical-record-form" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                <i class="fas fa-save mr-2"></i>Create Record
            </button>
        </div>
    </div>

    <!-- Medical Record Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="medical-record-form" action="{{ route('doctor.medical-records.store') }}" method="POST">
            @csrf

            <div class="space-y-8">
                <!-- Student Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Student Information</h4>
                    
                    <!-- Student Selection -->
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Select Student *</label>
                        <select name="student_id" id="student_id" required
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <option value="">Select a student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }} ({{ $student->student_id }}) - {{ $student->class->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Record Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Record Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Record Date -->
                        <div>
                            <label for="record_date" class="block text-sm font-medium text-gray-700 mb-2">Record Date *</label>
                            <input type="date" name="record_date" id="record_date" 
                                   value="{{ old('record_date', date('Y-m-d')) }}" required
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('record_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Record Type -->
                        <div>
                            <label for="record_type" class="block text-sm font-medium text-gray-700 mb-2">Record Type *</label>
                            <select name="record_type" id="record_type" required
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Type</option>
                                <option value="checkup" {{ old('record_type') == 'checkup' ? 'selected' : '' }}>Regular Checkup</option>
                                <option value="vaccination" {{ old('record_type') == 'vaccination' ? 'selected' : '' }}>Vaccination</option>
                                <option value="emergency" {{ old('record_type') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="routine" {{ old('record_type') == 'routine' ? 'selected' : '' }}>Routine Visit</option>
                                <option value="sickness" {{ old('record_type') == 'sickness' ? 'selected' : '' }}>Sickness</option>
                            </select>
                            @error('record_type')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Symptoms -->
                    <div>
                        <label for="symptoms" class="block text-sm font-medium text-gray-700 mb-2">Symptoms *</label>
                        <textarea name="symptoms" id="symptoms" rows="3" required
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Describe the symptoms presented by the student...">{{ old('symptoms') }}</textarea>
                        @error('symptoms')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Diagnosis -->
                    <div>
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-2">Diagnosis *</label>
                        <textarea name="diagnosis" id="diagnosis" rows="3" required
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter the diagnosis...">{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Treatment Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Treatment Information</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Prescription -->
                        <div>
                            <label for="prescription" class="block text-sm font-medium text-gray-700 mb-2">Prescription</label>
                            <textarea name="prescription" id="prescription" rows="3"
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                    placeholder="Enter prescribed medication and dosage...">{{ old('prescription') }}</textarea>
                            @error('prescription')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Medication -->
                        <div>
                            <label for="medication" class="block text-sm font-medium text-gray-700 mb-2">Medication Administered</label>
                            <textarea name="medication" id="medication" rows="3"
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                    placeholder="Enter any medication administered...">{{ old('medication') }}</textarea>
                            @error('medication')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Vital Signs Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Vital Signs</h4>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Height -->
                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-2">Height (cm)</label>
                            <input type="number" step="0.1" name="height" id="height" 
                                   value="{{ old('height') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Height">
                            @error('height')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Weight -->
                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                            <input type="number" step="0.1" name="weight" id="weight" 
                                   value="{{ old('weight') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Weight">
                            @error('weight')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Temperature -->
                        <div>
                            <label for="temperature" class="block text-sm font-medium text-gray-700 mb-2">Temperature (°C)</label>
                            <input type="number" step="0.1" name="temperature" id="temperature" 
                                   value="{{ old('temperature') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Temperature">
                            @error('temperature')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Blood Pressure -->
                        <div>
                            <label for="blood_pressure" class="block text-sm font-medium text-gray-700 mb-2">Blood Pressure</label>
                            <input type="text" name="blood_pressure" id="blood_pressure" 
                                   value="{{ old('blood_pressure') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="e.g., 120/80">
                            @error('blood_pressure')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Additional Information</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Follow-up Date -->
                        <div>
                            <label for="follow_up_date" class="block text-sm font-medium text-gray-700 mb-2">Follow-up Date</label>
                            <input type="date" name="follow_up_date" id="follow_up_date" 
                                   value="{{ old('follow_up_date') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('follow_up_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Doctor Notes -->
                    <div>
                        <label for="doctor_notes" class="block text-sm font-medium text-gray-700 mb-2">Doctor Notes</label>
                        <textarea name="doctor_notes" id="doctor_notes" rows="4"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter any additional notes, observations, or recommendations...">{{ old('doctor_notes') }}</textarea>
                        @error('doctor_notes')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('doctor.medical-records.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>Create Medical Record
                </button>
            </div>
        </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth interactions
        const inputs = document.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
            });
        });

        // Set today's date as default for record date
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('record_date').value = today;
    });
</script>
@endsection
@extends('layouts.app')

@section('title', $treatmentRequest->exists ? 'Edit Treatment Request' : 'New Treatment Request')

@section('content')
<div class="space-y-6">
    <!-- Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ $treatmentRequest->exists ? 'Edit Treatment Request' : 'New Treatment Request' }}</h3>
            <button type="submit" form="treatment-request-form" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>{{ $treatmentRequest->exists ? 'Update Request' : 'Create Request' }}
            </button>
        </div>
    </div>

    <!-- Treatment Request Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="treatment-request-form" action="{{ $treatmentRequest->exists ? route('admin.treatment-requests.update', $treatmentRequest) : route('admin.treatment-requests.store') }}" method="POST">
            @csrf
            @if($treatmentRequest->exists)
                @method('PUT')
            @endif

            <div class="space-y-8">
                <!-- Basic Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Request Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student *</label>
                            <select name="student_id" id="student_id" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                        {{ old('student_id', $treatmentRequest->student_id ?? '') == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->name }} ({{ $student->student_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Doctor -->
                        <div>
                            <label for="assigned_doctor" class="block text-sm font-medium text-gray-700 mb-2">Doctor *</label>
                            <select name="assigned_doctor" id="assigned_doctor"
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                        {{ old('assigned_doctor', $treatmentRequest->assigned_doctor ?? '') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }} ({{ $doctor->specialization }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_doctor')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                            <select name="priority" id="priority" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority', $treatmentRequest->priority ?? '') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $treatmentRequest->priority ?? '') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $treatmentRequest->priority ?? '') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="emergency" {{ old('priority', $treatmentRequest->priority ?? '') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                            </select>
                            @error('priority')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Request Date -->
                        <div>
                            <label for="requested_date" class="block text-sm font-medium text-gray-700 mb-2">Request Date *</label>
                            <input type="date" name="requested_date" id="requested_date" 
                                   value="{{ old('requested_date', $treatmentRequest->requested_date ?? now()->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('requested_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if($treatmentRequest->exists)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="pending" {{ old('status', $treatmentRequest->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ old('status', $treatmentRequest->status ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ old('status', $treatmentRequest->status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="completed" {{ old('status', $treatmentRequest->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Symptoms Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Symptoms & Notes</h4>
                    
                    <!-- Symptoms -->
                    <div>
                        <label for="symptoms" class="block text-sm font-medium text-gray-700 mb-2">Symptoms *</label>
                        <textarea name="symptoms" id="symptoms" rows="4"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Describe the symptoms in detail...">{{ old('symptoms', $treatmentRequest->symptoms ?? '') }}</textarea>
                        @error('symptoms')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Doctor Notes -->
                    @if($treatmentRequest->exists)
                    <div>
                        <label for="doctor_notes" class="block text-sm font-medium text-gray-700 mb-2">Doctor Notes</label>
                        <textarea name="notes" id="doctor_notes" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Doctor's observations and recommendations...">{{ old('notes', $treatmentRequest->notes ?? '') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.treatment-requests.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ $treatmentRequest->exists ? 'Update Request' : 'Create Request' }}
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
    });
</script>
@endsection
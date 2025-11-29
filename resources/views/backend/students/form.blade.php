@extends('layouts.app')

@section('title', $student->exists ? 'Edit Student' : 'Add New Student')

@section('content')
<div class="space-y-6">
    <!-- Student Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ $student->exists ? 'Edit Student' : 'Add New Student' }}</h3>
            <button type="submit" form="student-form" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors">
                <i class="fas fa-save mr-2"></i>{{ $student->exists ? 'Update Student' : 'Create Student' }}
            </button>
        </div>
    </div>

    <!-- Student Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="student-form" action="{{ $student->exists ? route('admin.students.update', $student) : route('admin.students.store') }}" method="POST">
            @csrf
            @if($student->exists)
                @method('PUT')
            @endif

            <div class="space-y-8">
                <!-- Basic Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Basic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $student->user->name ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $student->user->email ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student ID -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student ID *</label>
                            <input type="text" name="student_id" id="student_id" 
                                   value="{{ old('student_id', $student->student_id ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('student_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $student->exists ? 'New Password' : 'Password *' }}
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   {{ $student->exists ? '' : 'required' }}>
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $student->exists ? 'Confirm New Password' : 'Confirm Password *' }}
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   {{ $student->exists ? '' : 'required' }}>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" id="phone" 
                                   value="{{ old('phone', $student->user->phone ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Academic Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Academic Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Class -->
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                            <select name="class_id" id="class_id" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" 
                                        {{ old('class_id', $student->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Section -->
                        <div>
                            <label for="section_id" class="block text-sm font-medium text-gray-700 mb-2">Section *</label>
                            <select name="section_id" id="section_id" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" 
                                        data-class-id="{{ $section->class_id }}"
                                        {{ old('section_id', $student->section_id ?? '') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }} ({{ $section->class->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('section_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Roll Number -->
                        <div>
                            <label for="roll_number" class="block text-sm font-medium text-gray-700 mb-2">Roll Number *</label>
                            <input type="number" name="roll_number" id="roll_number" 
                                   value="{{ old('roll_number', $student->roll_number ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('roll_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Parent/Guardian -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Parent/Guardian</label>
                        <select name="parent_id" id="parent_id" 
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <option value="">Select Parent/Guardian</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" 
                                    {{ old('parent_id', $student->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }} ({{ $parent->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Personal Details Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Personal Details</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" 
                                   value="{{ old('date_of_birth', $student->user->date_of_birth ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('date_of_birth')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                            <select name="gender" id="gender" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $student->user->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $student->user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $student->user->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Blood Group -->
                        <div>
                            <label for="blood_group" class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                            <select name="blood_group" id="blood_group" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Blood Group</option>
                                <option value="A+" {{ old('blood_group', $student->blood_group ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_group', $student->blood_group ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_group', $student->blood_group ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_group', $student->blood_group ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('blood_group', $student->blood_group ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_group', $student->blood_group ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('blood_group', $student->blood_group ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_group', $student->blood_group ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                            @error('blood_group')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" id="address" rows="3"
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">{{ old('address', $student->user->address ?? '') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Medical Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Medical Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Emergency Contact -->
                        <div>
                            <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact *</label>
                            <input type="text" name="emergency_contact" id="emergency_contact" 
                                   value="{{ old('emergency_contact', $student->emergency_contact ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('emergency_contact')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Admission Date -->
                        <div>
                            <label for="admission_date" class="block text-sm font-medium text-gray-700 mb-2">Admission Date</label>
                            <input type="date" name="admission_date" id="admission_date" 
                                   value="{{ old('admission_date', $student->admission_date ?? now()->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('admission_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Allergies -->
                    <div>
                        <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">Allergies (comma separated)</label>
                        <input type="text" name="allergies" id="allergies" 
                               value="{{ old('allergies', $student->allergies ?? '') }}"
                               placeholder="e.g. Peanuts, Dust, Pollen"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        @error('allergies')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Medical Conditions -->
                    <div>
                        <label for="medical_conditions" class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions (comma separated)</label>
                        <input type="text" name="medical_conditions" id="medical_conditions" 
                               value="{{ old('medical_conditions', $student->medical_conditions ?? '') }}"
                               placeholder="e.g. Asthma, Diabetes, Epilepsy"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        @error('medical_conditions')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- School Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">School Information</h4>
                    
                    <div>
                        <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">School *</label>
                        <select name="school_id" id="school_id" 
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" 
                                    {{ old('school_id', $student->user->school_id ?? '') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }} ({{ $school->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('school_id')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.students.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ $student->exists ? 'Update Student' : 'Create Student' }}
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

        // Dynamic section filtering based on class selection
        const classSelect = document.getElementById('class_id');
        const sectionSelect = document.getElementById('section_id');
        
        if (classSelect && sectionSelect) {
            classSelect.addEventListener('change', function() {
                const classId = this.value;
                const sections = sectionSelect.querySelectorAll('option[data-class-id]');
                
                // Show all sections initially
                sections.forEach(option => {
                    option.style.display = 'block';
                });
                
                // Hide sections that don't belong to selected class
                if (classId) {
                    sections.forEach(option => {
                        if (option.getAttribute('data-class-id') !== classId) {
                            option.style.display = 'none';
                        }
                    });
                    
                    // Reset section selection if it doesn't belong to selected class
                    const selectedSection = sectionSelect.value;
                    const selectedOption = sectionSelect.querySelector(`option[value="${selectedSection}"]`);
                    if (selectedOption && selectedOption.getAttribute('data-class-id') !== classId) {
                        sectionSelect.value = '';
                    }
                }
            });

            // Trigger change event on page load if class is already selected
            if (classSelect.value) {
                classSelect.dispatchEvent(new Event('change'));
            }
        }
    });
</script>
@endsection
@extends('layouts.principal')

@section('title', isset($student) ? 'Edit Student' : 'Add New Student')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div class="table-header px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold">
                            {{ isset($student) ? 'Edit Student' : 'Add New Student' }}
                        </h3>
                        <p class="text-gray-200 mt-1">
                            {{ isset($student) ? 'Update student details' : 'Create a new student account' }}
                        </p>
                    </div>
                    <a href="{{ route('principal.students.index') }}"
                        class="text-gray-100 hover:text-blue-800 text-sm font-medium flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Students
                    </a>
                </div>
            </div>
        </div>

        <!-- Student Form -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <form
                action="{{ isset($student) ? route('principal.students.update', $student->id) : route('principal.students.store') }}"
                method="POST" class="space-y-6">
                @csrf
                @if(isset($student))
                    @method('PUT')
                @endif

                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="name" required value="{{ old('name', $student->user->name ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" required value="{{ old('email', $student->user->email ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" value="{{ old('phone', $student->user->phone ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                        <input type="date" name="date_of_birth" required
                            value="{{ old('date_of_birth', isset($student) && $student->user ? $student->user->date_of_birth : '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                        <select name="gender" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $student->user->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $student->user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <textarea name="address" required rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $student->user->address ?? '') }}</textarea>
                </div>

                <!-- Academic Information -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Academic Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                            <select name="class_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id', $student->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Section *</label>
                            <select name="section_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Section</option>
                                @if(isset($student))
                                    @foreach($student->class->sections ?? [] as $section)
                                        <option value="{{ $section->id }}" {{ old('section_id', $student->section_id ?? '') == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Roll Number *</label>
                            <input type="number" name="roll_number" required
                                value="{{ old('roll_number', $student->roll_number ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Parent Information -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Parent Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Father's Name *</label>
                            <input type="text" name="father_name" required
                                value="{{ old('father_name', $student->father_name ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mother's Name *</label>
                            <input type="text" name="mother_name" required
                                value="{{ old('mother_name', $student->mother_name ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Medical Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                            <select name="blood_group"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Blood Group</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group', $student->blood_group ?? '') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Allergies</label>
                            <input type="text" name="allergies" value="{{ old('allergies', $student->allergies ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact</label>
                            <input type="text" name="emergency_contact"
                                value="{{ old('emergency_contact', $student->emergency_contact ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions</label>
                        <textarea name="medical_conditions" rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('medical_conditions', $student->medical_conditions ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                    <a href="{{ route('principal.students.index') }}"
                        class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center">
                        Cancel
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                        {{ isset($student) ? 'Update Student' : 'Create Student' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const classSelect = document.querySelector('select[name="class_id"]');
            const sectionSelect = document.querySelector('select[name="section_id"]');

            function loadSections(classId, selectedSection = null) {
                sectionSelect.innerHTML = '<option value="">Select Section</option>';
                if (classId) {
                    fetch(`/principal/students/get-sections/${classId}`)
                        .then(res => res.json())
                        .then(sections => {
                            sections.forEach(section => {
                                const option = document.createElement('option');
                                option.value = section.id;
                                option.textContent = section.name;
                                if (selectedSection && selectedSection == section.id) option.selected = true;
                                sectionSelect.appendChild(option);
                            });
                        });
                }
            }

            classSelect.addEventListener('change', function () {
                loadSections(this.value);
            });

            @if(isset($student))
                loadSections('{{ $student->class_id }}', '{{ $student->section_id }}');
            @endif
        });
    </script>

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
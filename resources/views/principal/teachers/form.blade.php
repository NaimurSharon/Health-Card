@extends('layouts.principal')

@section('title', $teacher ? 'Edit Teacher' : 'Add New Teacher')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div class="table-header px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold">
                            {{ $teacher ? 'Edit Teacher' : 'Add New Teacher' }}
                        </h3>
                        <p class="text-gray-200 mt-1">
                            {{ $teacher ? 'Update teacher details' : 'Create a new teacher account' }}
                        </p>
                    </div>
                    <a href="{{ route('principal.teachers.index') }}"
                        class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Teachers
                    </a>
                </div>
            </div>
        </div>

        @if($errors->any())
            <div class="content-card rounded-lg p-4">
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Teacher Form -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <form action="{{ $teacher
        ? route('principal.teachers.update', $teacher->id)
        : route('principal.teachers.store') }}" method="POST" class="space-y-6">
                @csrf
                @if($teacher)
                    @method('PUT')
                @endif

                <!-- Personal Information -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Personal Information
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" required value="{{ old('name', $teacher->user->name ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                            <input type="email" name="email" required
                                value="{{ old('email', $teacher->user->email ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @if($teacher)
                                <p class="text-xs text-gray-500 mt-1">Email cannot be changed after creation</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', $teacher->user->phone ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                            @php
                                $dob = $teacher && $teacher->user && $teacher->user->date_of_birth
                                    ? \Carbon\Carbon::parse($teacher->user->date_of_birth)->format('Y-m-d')
                                    : old('date_of_birth', '');
                            @endphp
                            <input type="date" name="date_of_birth" required value="{{ $dob }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                            <select name="gender" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $teacher->user->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $teacher->user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                        <textarea name="address" required rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address', $teacher->user->address ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Professional
                        Information</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Specialization *</label>
                            <input type="text" name="specialization" required
                                value="{{ old('specialization', $teacher->user->specialization ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Qualifications *</label>
                            @php
                                $qualifications = '';
                                if ($teacher && $teacher->user) {
                                    $qualifications = is_array($teacher->user->qualifications)
                                        ? implode(', ', $teacher->user->qualifications)
                                        : ($teacher->user->qualifications ?? '');
                                }
                            @endphp
                            <input type="text" name="qualifications" required
                                value="{{ old('qualifications', $qualifications) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Teacher ID</label>
                            <input type="text" name="teacher_id" value="{{ old('teacher_id', $teacher->teacher_id ?? '') }}"
                                placeholder="Auto-generated if left empty"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Designation *</label>
                            <select name="designation" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Designation</option>
                                <option value="headmaster" {{ old('designation', $teacher->designation ?? '') == 'headmaster' ? 'selected' : '' }}>Headmaster</option>
                                <option value="assistant_headmaster" {{ old('designation', $teacher->designation ?? '') == 'assistant_headmaster' ? 'selected' : '' }}>Assistant Headmaster</option>
                                <option value="senior_teacher" {{ old('designation', $teacher->designation ?? '') == 'senior_teacher' ? 'selected' : '' }}>Senior Teacher</option>
                                <option value="assistant_teacher" {{ old('designation', $teacher->designation ?? '') == 'assistant_teacher' ? 'selected' : '' }}>Assistant Teacher</option>
                                <option value="guest_teacher" {{ old('designation', $teacher->designation ?? '') == 'guest_teacher' ? 'selected' : '' }}>Guest Teacher</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <input type="text" name="department" value="{{ old('department', $teacher->department ?? '') }}"
                            placeholder="e.g., Science, Mathematics, Arts"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Official Information -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Official Information
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NID Number</label>
                            <input type="text" name="nid_number" value="{{ old('nid_number', $teacher->nid_number ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Birth Certificate Number</label>
                            <input type="text" name="birth_certificate"
                                value="{{ old('birth_certificate', $teacher->birth_certificate ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Marital Status *</label>
                            <select name="marital_status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Status</option>
                                <option value="single" {{ old('marital_status', $teacher->marital_status ?? '') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('marital_status', $teacher->marital_status ?? '') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="divorced" {{ old('marital_status', $teacher->marital_status ?? '') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="widowed" {{ old('marital_status', $teacher->marital_status ?? '') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                            <select name="blood_group"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Blood Group</option>
                                <option value="A+" {{ old('blood_group', $teacher->blood_group ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_group', $teacher->blood_group ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_group', $teacher->blood_group ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_group', $teacher->blood_group ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="O+" {{ old('blood_group', $teacher->blood_group ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_group', $teacher->blood_group ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                                <option value="AB+" {{ old('blood_group', $teacher->blood_group ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_group', $teacher->blood_group ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Father's Name</label>
                            <input type="text" name="father_name"
                                value="{{ old('father_name', $teacher->father_name ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mother's Name</label>
                            <input type="text" name="mother_name"
                                value="{{ old('mother_name', $teacher->mother_name ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact</label>
                            <input type="text" name="emergency_contact"
                                value="{{ old('emergency_contact', $teacher->emergency_contact ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions</label>
                            @php
                                $medicalConditions = '';
                                if ($teacher && $teacher->medical_conditions) {
                                    $medicalConditions = is_array($teacher->medical_conditions)
                                        ? implode(', ', $teacher->medical_conditions)
                                        : $teacher->medical_conditions;
                                }
                            @endphp
                            <input type="text" name="medical_conditions"
                                value="{{ old('medical_conditions', $medicalConditions) }}"
                                placeholder="Comma separated (e.g., diabetes, hypertension)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Allergies</label>
                            @php
                                $allergies = '';
                                if ($teacher && $teacher->allergies) {
                                    $allergies = is_array($teacher->allergies)
                                        ? implode(', ', $teacher->allergies)
                                        : $teacher->allergies;
                                }
                            @endphp
                            <input type="text" name="allergies" value="{{ old('allergies', $allergies) }}"
                                placeholder="Comma separated (e.g., pollen, dust, penicillin)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Disabilities</label>
                            @php
                                $disabilities = '';
                                if ($teacher && $teacher->disabilities) {
                                    $disabilities = is_array($teacher->disabilities)
                                        ? implode(', ', $teacher->disabilities)
                                        : $teacher->disabilities;
                                }
                            @endphp
                            <input type="text" name="disabilities" value="{{ old('disabilities', $disabilities) }}"
                                placeholder="Comma separated (e.g., visual impairment, hearing loss)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Password Information (for create only) -->
                @if(!$teacher)
                    <div class="border-t pt-6">
                        <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Account Information
                        </h4>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-yellow-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Default password will be set to <strong>teacher@123</strong>. Teacher should change it
                                        after first login.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('principal.teachers.index') }}"
                        class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        Cancel
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>{{ $teacher ? 'Update Teacher' : 'Create Teacher' }}
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
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-format phone number
            const phoneInput = document.querySelector('input[name="phone"]');
            if (phoneInput) {
                phoneInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0) {
                        value = value.match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                        e.target.value = !value[2] ? value[1] : '(' + value[1] + ') ' + value[2] + (value[3] ? '-' + value[3] : '');
                    }
                });
            }

            // Auto-format emergency contact
            const emergencyInput = document.querySelector('input[name="emergency_contact"]');
            if (emergencyInput) {
                emergencyInput.addEventListener('input', function (e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length > 0) {
                        value = value.match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                        e.target.value = !value[2] ? value[1] : '(' + value[1] + ') ' + value[2] + (value[3] ? '-' + value[3] : '');
                    }
                });
            }
        });
    </script>
@endsection
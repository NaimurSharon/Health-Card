@extends('layouts.principal')

@section('title', $teacher->user->name . ' - Teacher Profile')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div
                class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">Teacher Profile</h3>
                    <p class="text-gray-200 mt-1">{{ $teacher->user->name }} -
                        {{ ucfirst(str_replace('_', ' ', $teacher->designation)) }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('principal.teachers.edit', $teacher->id) }}"
                        class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i>Edit Profile
                    </a>
                    <a href="{{ route('principal.teachers.index') }}"
                        class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Teachers
                    </a>
                </div>
            </div>
        </div>

        <!-- Teacher Profile Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Basic Info & Photo -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Profile Card -->
                <div class="content-card rounded-lg p-6">
                    <div class="flex flex-col items-center text-center">
                        <!-- Profile Photo -->
                        <div class="relative mb-4">
                            @if($teacher->user->profile_image)
                                <img src="{{ asset('storage/' . $teacher->user->profile_image) }}"
                                    alt="{{ $teacher->user->name }}"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                            @else
                                <div
                                    class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 border-4 border-white shadow-lg flex items-center justify-center">
                                    <i class="fas fa-user-tie text-4xl text-blue-600"></i>
                                </div>
                            @endif
                            <!-- Status Badge -->
                            <div class="absolute bottom-2 right-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $teacher->status == 'active' ? 'bg-green-100 text-green-800' :
        ($teacher->status == 'inactive' ? 'bg-red-100 text-red-800' :
            ($teacher->status == 'suspended' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Name & Designation -->
                        <h3 class="text-xl font-bold text-gray-900">{{ $teacher->user->name }}</h3>
                        <p class="text-sm text-blue-600 font-medium mt-1">
                            {{ ucfirst(str_replace('_', ' ', $teacher->designation)) }}
                        </p>
                        @if($teacher->department)
                            <p class="text-sm text-gray-600 mt-1">{{ $teacher->department }} Department</p>
                        @endif

                        <!-- Teacher ID -->
                        <div class="mt-4 px-4 py-2 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">Teacher ID</p>
                            <p class="text-sm font-bold text-gray-900">{{ $teacher->teacher_id }}</p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $teacher->subjects->count() }}</div>
                            <div class="text-xs text-gray-600">Subjects</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $teacher->sections->count() }}</div>
                            <div class="text-xs text-gray-600">Classes</div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="content-card rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Contact Information
                    </h4>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-envelope text-blue-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Email</p>
                                <p class="text-sm text-gray-600">{{ $teacher->user->email }}</p>
                            </div>
                        </div>
                        @if($teacher->user->phone)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-phone text-green-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Phone</p>
                                    <p class="text-sm text-gray-600">{{ $teacher->user->phone }}</p>
                                </div>
                            </div>
                        @endif
                        @if($teacher->emergency_contact)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-phone-alt text-red-600"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Emergency Contact</p>
                                    <p class="text-sm text-gray-600">{{ $teacher->emergency_contact }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-purple-600"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Address</p>
                                <p class="text-sm text-gray-600">{{ $teacher->user->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Detailed Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="content-card rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Personal Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Date of Birth</p>
                            <p class="text-sm text-gray-900 mt-1">
                                @if($teacher->user->date_of_birth)
                                    {{ \Carbon\Carbon::parse($teacher->user->date_of_birth)->format('F j, Y') }}
                                    ({{ $teacher->user->date_of_birth->age }} years)
                                @else
                                    <span class="text-gray-400">Not set</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Gender</p>
                            <p class="text-sm text-gray-900 mt-1">{{ ucfirst($teacher->user->gender) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Marital Status</p>
                            <p class="text-sm text-gray-900 mt-1">{{ ucfirst($teacher->marital_status) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Blood Group</p>
                            <p class="text-sm text-gray-900 mt-1">
                                @if($teacher->blood_group)
                                    <span
                                        class="px-2 py-1 bg-red-50 text-red-700 rounded text-xs font-medium">{{ $teacher->blood_group }}</span>
                                @else
                                    <span class="text-gray-400">Not set</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Family Information -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h5 class="text-md font-medium text-gray-900 mb-4">Family Information</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Father's Name</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $teacher->father_name ?: 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Mother's Name</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $teacher->mother_name ?: 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Official Information -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h5 class="text-md font-medium text-gray-900 mb-4">Official Information</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-700">NID Number</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $teacher->nid_number ?: 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Birth Certificate</p>
                                <p class="text-sm text-gray-900 mt-1">{{ $teacher->birth_certificate ?: 'Not provided' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="content-card rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Professional
                        Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Specialization</p>
                            <p class="text-sm text-gray-900 mt-1">{{ $teacher->user->specialization }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Qualifications</p>
                            <p class="text-sm text-gray-900 mt-1">{{ $teacher->user->qualifications_list }}</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-700">Department</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $teacher->department ?: 'Not assigned' }}</p>
                    </div>
                </div>

                <!-- Health Information -->
                <div class="content-card rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Health Information
                    </h4>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Medical Conditions</p>
                            @if($teacher->medical_conditions && count($teacher->medical_conditions) > 0)
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($teacher->medical_conditions as $condition)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            {{ $condition }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 mt-1">None reported</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Allergies</p>
                            @if($teacher->allergies && count($teacher->allergies) > 0)
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($teacher->allergies as $allergy)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ $allergy }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 mt-1">None reported</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700">Disabilities</p>
                            @if($teacher->disabilities && count($teacher->disabilities) > 0)
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($teacher->disabilities as $disability)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $disability }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 mt-1">None reported</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Assigned Classes & Subjects -->
                <div class="content-card rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-900">Assigned Classes & Subjects</h4>
                        <a href="{{ route('principal.teachers.assign-classes', $teacher->id) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                            <i class="fas fa-plus-circle mr-1"></i>Manage Assignments
                        </a>
                    </div>

                    @if($teacher->subjects->count() > 0)
                        <div class="space-y-4">
                            @php
                                $groupedSubjects = $teacher->subjects->groupBy(function ($item) {
                                    return $item->class->name . ' - ' . $item->section->name;
                                });
                            @endphp

                            @foreach($groupedSubjects as $classSection => $subjects)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div>
                                            <h5 class="font-medium text-gray-900">{{ $classSection }}</h5>
                                            <p class="text-xs text-gray-600">{{ $subjects->count() }} subject(s)</p>
                                        </div>
                                        @if($teacher->class_teacher_of)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-crown mr-1"></i>Class Teacher
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($subjects as $subject)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $subject->subject->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <i class="fas fa-book-open text-3xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">No classes or subjects assigned</p>
                            <a href="{{ route('principal.teachers.assign-classes', $teacher->id) }}"
                                class="inline-flex items-center mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-plus mr-2"></i>Assign Classes
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('principal.teachers.edit', $teacher->id) }}"
                        class="content-card rounded-lg p-4 text-center hover:bg-gray-50 transition-colors">
                        <div class="p-3 bg-blue-50 rounded-lg inline-flex">
                            <i class="fas fa-edit text-blue-600 text-xl"></i>
                        </div>
                        <h5 class="text-sm font-medium text-gray-900 mt-3">Edit Profile</h5>
                        <p class="text-xs text-gray-600 mt-1">Update teacher information</p>
                    </a>

                    <a href="{{ route('principal.teachers.assign-classes', $teacher->id) }}"
                        class="content-card rounded-lg p-4 text-center hover:bg-gray-50 transition-colors">
                        <div class="p-3 bg-green-50 rounded-lg inline-flex">
                            <i class="fas fa-chalkboard-teacher text-green-600 text-xl"></i>
                        </div>
                        <h5 class="text-sm font-medium text-gray-900 mt-3">Manage Classes</h5>
                        <p class="text-xs text-gray-600 mt-1">Assign subjects & classes</p>
                    </a>

                    <form action="{{ route('principal.teachers.destroy', $teacher->id) }}" method="POST"
                        class="content-card rounded-lg p-4 text-center hover:bg-gray-50 transition-colors"
                        onsubmit="return confirm('Are you sure you want to delete this teacher? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full">
                            <div class="p-3 bg-red-50 rounded-lg inline-flex">
                                <i class="fas fa-trash text-red-600 text-xl"></i>
                            </div>
                            <h5 class="text-sm font-medium text-gray-900 mt-3">Delete Teacher</h5>
                            <p class="text-xs text-gray-600 mt-1">Remove from system</p>
                        </button>
                    </form>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Format phone numbers
            function formatPhoneNumber(phone) {
                if (!phone) return '';
                const cleaned = ('' + phone).replace(/\D/g, '');
                const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
                if (match) {
                    return '(' + match[1] + ') ' + match[2] + '-' + match[3];
                }
                return phone;
            }

            // Format emergency contact numbers
            const emergencyElements = document.querySelectorAll('.emergency-contact');
            emergencyElements.forEach(el => {
                if (el.textContent.trim()) {
                    el.textContent = formatPhoneNumber(el.textContent.trim());
                }
            });

            // Format regular phone numbers
            const phoneElements = document.querySelectorAll('.phone-number');
            phoneElements.forEach(el => {
                if (el.textContent.trim()) {
                    el.textContent = formatPhoneNumber(el.textContent.trim());
                }
            });
        });
    </script>
@endsection
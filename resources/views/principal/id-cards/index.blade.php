@extends('layouts.principal')

@section('title', 'ID Cards Management')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div class="table-header px-4 py-4 sm:px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-bold">
                            ID Cards Management
                        </h3>
                        <p class="text-gray-200 mt-1">
                            Generate ID cards for students and teachers
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('principal.id-cards.templates') }}"
                            class="text-gray-100 hover:text-blue-800 text-sm font-medium flex items-center">
                            <i class="fas fa-templates mr-2"></i> View Templates
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Section -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-gray-900">Students</h4>
                <span class="text-sm text-gray-600">{{ $students->count() }} students</span>
            </div>

            @if($students->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all-students" class="rounded border-gray-300">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student ID
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Class
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Roll Number
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($students as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}"
                                            class="student-checkbox rounded border-gray-300">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                        {{ $student->student_id }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($student->user->profile_image_url)
                                                <img class="h-8 w-8 rounded-full mr-2" src="{{ $student->user->profile_image_url }}"
                                                    alt="{{ $student->user->name }}">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gray-200 mr-2 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-400 text-sm"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $student->user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->class->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->roll_number }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('principal.id-cards.generate-student', $student->id) }}"
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-id-card mr-1"></i> Generate ID
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Bulk Actions for Students -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg hidden" id="student-bulk-actions">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700" id="selected-student-count">0 students selected</span>
                        <div class="flex space-x-2">
                            <form action="{{ route('principal.id-cards.bulk-generate') }}" method="POST" id="bulk-student-form">
                                @csrf
                                <input type="hidden" name="type" value="student">
                                <input type="hidden" name="ids" id="selected-student-ids">
                                <input type="hidden" name="template_id" value="1"> <!-- Default template -->
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                    <i class="fas fa-print mr-1"></i> Generate Selected
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">No students found</p>
                </div>
            @endif
        </div>

        <!-- Teachers Section -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <div class="flex items-center justify-between mb-6">
                <h4 class="text-lg font-semibold text-gray-900">Teachers</h4>
                <span class="text-sm text-gray-600">{{ $teachers->count() }} teachers</span>
            </div>

            @if($teachers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all-teachers" class="rounded border-gray-300">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Specialization
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($teachers as $teacher)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <input type="checkbox" name="teacher_ids[]" value="{{ $teacher->id }}"
                                            class="teacher-checkbox rounded border-gray-300">
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($teacher->profile_image_url)
                                                <img class="h-8 w-8 rounded-full mr-2" src="{{ $teacher->profile_image_url }}"
                                                    alt="{{ $teacher->name }}">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gray-200 mr-2 flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-400 text-sm"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $teacher->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $teacher->email }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                        {{ $teacher->specialization ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('principal.id-cards.generate-teacher', $teacher->id) }}"
                                            class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-id-card mr-1"></i> Generate ID
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Bulk Actions for Teachers -->
                <div class="mt-4 p-4 bg-gray-50 rounded-lg hidden" id="teacher-bulk-actions">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-700" id="selected-teacher-count">0 teachers selected</span>
                        <div class="flex space-x-2">
                            <form action="{{ route('principal.id-cards.bulk-generate') }}" method="POST" id="bulk-teacher-form">
                                @csrf
                                <input type="hidden" name="type" value="teacher">
                                <input type="hidden" name="ids" id="selected-teacher-ids">
                                <input type="hidden" name="template_id" value="1"> <!-- Default template -->
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                    <i class="fas fa-print mr-1"></i> Generate Selected
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-chalkboard-teacher text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">No teachers found</p>
                </div>
            @endif
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
            // Student bulk selection
            const selectAllStudents = document.getElementById('select-all-students');
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            const studentBulkActions = document.getElementById('student-bulk-actions');
            const selectedStudentCount = document.getElementById('selected-student-count');
            const selectedStudentIds = document.getElementById('selected-student-ids');

            selectAllStudents.addEventListener('change', function () {
                studentCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateStudentSelection();
            });

            studentCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateStudentSelection);
            });

            function updateStudentSelection() {
                const checkedBoxes = Array.from(studentCheckboxes).filter(cb => cb.checked);
                const count = checkedBoxes.length;

                if (count > 0) {
                    studentBulkActions.classList.remove('hidden');
                    selectedStudentCount.textContent = `${count} students selected`;
                    selectedStudentIds.value = checkedBoxes.map(cb => cb.value).join(',');
                } else {
                    studentBulkActions.classList.add('hidden');
                }

                // Update select all checkbox
                selectAllStudents.checked = count > 0 && count === studentCheckboxes.length;
                selectAllStudents.indeterminate = count > 0 && count < studentCheckboxes.length;
            }

            // Teacher bulk selection
            const selectAllTeachers = document.getElementById('select-all-teachers');
            const teacherCheckboxes = document.querySelectorAll('.teacher-checkbox');
            const teacherBulkActions = document.getElementById('teacher-bulk-actions');
            const selectedTeacherCount = document.getElementById('selected-teacher-count');
            const selectedTeacherIds = document.getElementById('selected-teacher-ids');

            selectAllTeachers.addEventListener('change', function () {
                teacherCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateTeacherSelection();
            });

            teacherCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTeacherSelection);
            });

            function updateTeacherSelection() {
                const checkedBoxes = Array.from(teacherCheckboxes).filter(cb => cb.checked);
                const count = checkedBoxes.length;

                if (count > 0) {
                    teacherBulkActions.classList.remove('hidden');
                    selectedTeacherCount.textContent = `${count} teachers selected`;
                    selectedTeacherIds.value = checkedBoxes.map(cb => cb.value).join(',');
                } else {
                    teacherBulkActions.classList.add('hidden');
                }

                // Update select all checkbox
                selectAllTeachers.checked = count > 0 && count === teacherCheckboxes.length;
                selectAllTeachers.indeterminate = count > 0 && count < teacherCheckboxes.length;
            }

            // Update form submission to handle arrays
            document.getElementById('bulk-student-form').addEventListener('submit', function (e) {
                const idsInput = document.getElementById('selected-student-ids');
                if (idsInput.value) {
                    this.querySelector('input[name="ids"]').value = idsInput.value.split(',');
                }
            });

            document.getElementById('bulk-teacher-form').addEventListener('submit', function (e) {
                const idsInput = document.getElementById('selected-teacher-ids');
                if (idsInput.value) {
                    this.querySelector('input[name="ids"]').value = idsInput.value.split(',');
                }
            });
        });
    </script>
@endsection
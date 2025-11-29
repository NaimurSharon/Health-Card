@extends('layouts.principal')

@section('title', 'Assign Teachers to Subjects')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold">Assign Teachers to Subjects</h3>
                <p class="text-gray-200 mt-1">Assign teachers to subjects for different classes and sections</p>
            </div>
            <a href="{{ route('principal.subjects.index') }}" 
               class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i>Back to Subjects
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="content-card rounded-lg p-4 bg-green-50 border border-green-200">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="content-card rounded-lg p-4 bg-red-50 border border-red-200">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Assignment Form -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        <form action="{{ route('principal.subjects.store-assign-teachers') }}" method="POST" class="space-y-6" id="assignmentForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                    <select name="class_id" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            id="classSelect">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section *</label>
                    <select name="section_id" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            id="sectionSelect" disabled>
                        <option value="">Select Section</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                    <select name="subject_id" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            id="subjectSelect">
                        <option value="">Select Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teacher *</label>
                    <select name="teacher_id" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            id="teacherSelect">
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center justify-center"
                        id="submitBtn">
                    <i class="fas fa-user-plus mr-2"></i>
                    Assign Teacher
                </button>
            </div>
        </form>
    </div>

    <!-- Current Assignments -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold text-gray-900">Current Assignments</h4>
            <span class="text-sm text-gray-500">{{ $assignments->count() }} assignments</span>
        </div>
        
        @if($assignments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Section</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teacher</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($assignments as $assignment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-blue-600 text-xs font-semibold">{{ $assignment->class->numeric_value ?? '' }}</span>
                                    </div>
                                    {{ $assignment->class->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $assignment->section->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fas fa-book text-gray-400 mr-2 text-xs"></i>
                                    {{ $assignment->subject->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <i class="fas fa-chalkboard-teacher text-gray-400 mr-2 text-xs"></i>
                                    {{ $assignment->teacher->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <form action="{{ route('principal.subjects.destroy-assignment', $assignment->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm flex items-center"
                                            onclick="return confirm('Are you sure you want to remove this assignment?')">
                                        <i class="fas fa-trash mr-1"></i>
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-chalkboard-teacher text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No teacher assignments found</p>
                <p class="text-sm text-gray-400 mt-2">Use the form above to assign teachers to subjects</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('classSelect');
    const sectionSelect = document.getElementById('sectionSelect');
    const subjectSelect = document.getElementById('subjectSelect');
    const teacherSelect = document.getElementById('teacherSelect');
    const submitBtn = document.getElementById('submitBtn');
    const assignmentForm = document.getElementById('assignmentForm');

    // Enable/disable submit button based on form validity
    function updateSubmitButton() {
        const isFormValid = classSelect.value && sectionSelect.value && subjectSelect.value && teacherSelect.value;
        submitBtn.disabled = !isFormValid;
        submitBtn.classList.toggle('opacity-50', !isFormValid);
        submitBtn.classList.toggle('cursor-not-allowed', !isFormValid);
    }

    // Load sections when class changes
    classSelect.addEventListener('change', function() {
        const classId = this.value;
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        sectionSelect.disabled = !classId;
        
        if (classId) {
            // Show loading state
            sectionSelect.disabled = true;
            sectionSelect.innerHTML = '<option value="">Loading sections...</option>';
            
            fetch(`/principal/subjects/get-sections/${classId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(sections => {
                    sectionSelect.innerHTML = '<option value="">Select Section</option>';
                    if (sections.length > 0) {
                        sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.id;
                            option.textContent = section.name;
                            sectionSelect.appendChild(option);
                        });
                        sectionSelect.disabled = false;
                    } else {
                        sectionSelect.innerHTML = '<option value="">No sections available</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading sections:', error);
                    sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                })
                .finally(() => {
                    updateSubmitButton();
                });
        } else {
            sectionSelect.disabled = true;
            updateSubmitButton();
        }
    });

    // Update submit button when any select changes
    [classSelect, sectionSelect, subjectSelect, teacherSelect].forEach(select => {
        select.addEventListener('change', updateSubmitButton);
    });

    // Form submission handling
    assignmentForm.addEventListener('submit', function(e) {
        if (!classSelect.value || !sectionSelect.value || !subjectSelect.value || !teacherSelect.value) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Assigning...';
    });

    // Initialize submit button state
    updateSubmitButton();
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

    @media (max-width: 768px) {
        .grid-cols-4 {
            grid-template-columns: 1fr;
        }
        
        table {
            font-size: 0.75rem;
        }
        
        .px-4 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
    }

    @media (max-width: 640px) {
        .text-sm {
            font-size: 0.7rem;
        }
    }
</style>
@endsection
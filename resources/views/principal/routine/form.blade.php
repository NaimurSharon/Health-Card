@extends('layouts.principal')

@section('title', isset($routine) ? 'Edit Routine' : 'Add New Routine')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">
                        {{ isset($routine) ? 'Edit Routine' : 'Add New Routine' }}
                    </h3>
                    <p class="text-gray-600 mt-1">
                        {{ isset($routine) ? 'Update class routine' : 'Create a new class routine' }}
                    </p>
                </div>
                <a href="{{ route('principal.routine.index') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Routine
                </a>
            </div>
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

    <!-- Routine Form -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        <form action="{{ isset($routine) ? route('principal.routine.update', $routine->id) : route('principal.routine.store') }}" 
              method="POST" class="space-y-6" id="routineForm">
            @csrf
            @if(isset($routine))
                @method('PUT')
            @endif
            
            <!-- Class and Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                    <select name="class_id" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            id="classSelect">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" 
                                {{ old('class_id', $routine->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section *</label>
                    <select name="section_id" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            id="sectionSelect" {{ isset($routine) ? '' : 'disabled' }}>
                        <option value="">Select Section</option>
                        @if(isset($routine) && $routine->class)
                            @foreach($routine->class->sections as $section)
                                <option value="{{ $section->id }}" 
                                    {{ old('section_id', $routine->section_id ?? '') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('section_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Subject and Teacher -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                    <select name="subject_id" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            id="subjectSelect" {{ isset($routine) ? '' : 'disabled' }}>
                        <option value="">Select Subject</option>
                        @if(isset($routine))
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" 
                                    {{ old('subject_id', $routine->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }} ({{ $subject->code }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('subject_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teacher *</label>
                    <select name="teacher_id" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            id="teacherSelect" {{ isset($routine) ? '' : 'disabled' }}>
                        <option value="">Select Teacher</option>
                        @if(isset($routine))
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" 
                                    {{ old('teacher_id', $routine->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('teacher_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Schedule Details -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Day of Week *</label>
                    <select name="day_of_week" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Day</option>
                        @foreach(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'] as $day)
                            <option value="{{ $day }}" 
                                {{ old('day_of_week', $routine->day_of_week ?? '') == $day ? 'selected' : '' }}>
                                {{ ucfirst($day) }}
                            </option>
                        @endforeach
                    </select>
                    @error('day_of_week')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Period *</label>
                    <select name="period" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Period</option>
                        @for($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" 
                                {{ old('period', $routine->period ?? '') == $i ? 'selected' : '' }}>
                                Period {{ $i }}
                            </option>
                        @endfor
                    </select>
                    @error('period')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                    <input type="time" name="start_time" required 
                           value="{{ old('start_time', isset($routine) ? \Carbon\Carbon::parse($routine->start_time)->format('H:i') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('start_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                    <input type="time" name="end_time" required 
                           value="{{ old('end_time', isset($routine) ? \Carbon\Carbon::parse($routine->end_time)->format('H:i') : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('end_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Room</label>
                    <input type="text" name="room" 
                           value="{{ old('room', $routine->room ?? '') }}"
                           placeholder="e.g., Room 101, Lab-1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('room')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Academic Year</label>
                    <input type="number" name="academic_year" 
                           value="{{ old('academic_year', $routine->academic_year ?? date('Y')) }}"
                           min="2000" max="2030"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('academic_year')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                <a href="{{ route('principal.routine.index') }}" 
                   class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center justify-center"
                        id="submitBtn">
                    <i class="fas fa-save mr-2"></i>
                    {{ isset($routine) ? 'Update Routine' : 'Create Routine' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('classSelect');
    const sectionSelect = document.getElementById('sectionSelect');
    const subjectSelect = document.getElementById('subjectSelect');
    const teacherSelect = document.getElementById('teacherSelect');
    const submitBtn = document.getElementById('submitBtn');
    const routineForm = document.getElementById('routineForm');

    // Enable/disable form elements based on dependencies
    function updateFormState() {
        const hasClass = classSelect.value;
        const hasSection = sectionSelect.value;
        const hasSubject = subjectSelect.value;
        
        sectionSelect.disabled = !hasClass;
        subjectSelect.disabled = !hasClass;
        teacherSelect.disabled = !hasSubject;
        
        // Update submit button state
        const isFormValid = hasClass && hasSection && hasSubject && teacherSelect.value;
        submitBtn.disabled = !isFormValid;
        submitBtn.classList.toggle('opacity-50', !isFormValid);
        submitBtn.classList.toggle('cursor-not-allowed', !isFormValid);
    }

    // Load sections when class changes
    classSelect.addEventListener('change', function() {
        const classId = this.value;
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        subjectSelect.innerHTML = '<option value="">Select Subject</option>';
        teacherSelect.innerHTML = '<option value="">Select Teacher</option>';
        
        if (classId) {
            // Show loading state for sections
            sectionSelect.disabled = true;
            sectionSelect.innerHTML = '<option value="">Loading sections...</option>';
            
            // Show loading state for subjects
            subjectSelect.disabled = true;
            subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';
            
            // Load sections
            fetch(`/principal/routine/get-sections/${classId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
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
                    updateFormState();
                });

            // Load subjects for this class
            fetch(`/principal/routine/get-class-subjects/${classId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(subjects => {
                    subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                    if (subjects.length > 0) {
                        subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = `${subject.name} (${subject.code})`;
                            subjectSelect.appendChild(option);
                        });
                        subjectSelect.disabled = false;
                    } else {
                        subjectSelect.innerHTML = '<option value="">No subjects available</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading subjects:', error);
                    subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
                })
                .finally(() => {
                    updateFormState();
                });
        } else {
            sectionSelect.disabled = true;
            subjectSelect.disabled = true;
            teacherSelect.disabled = true;
            updateFormState();
        }
    });

    // Load teachers when subject changes
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        teacherSelect.innerHTML = '<option value="">Select Teacher</option>';
        
        if (subjectId) {
            teacherSelect.disabled = true;
            teacherSelect.innerHTML = '<option value="">Loading teachers...</option>';
            
            fetch(`/principal/routine/get-teachers/${subjectId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(teachers => {
                    teacherSelect.innerHTML = '<option value="">Select Teacher</option>';
                    if (teachers.length > 0) {
                        teachers.forEach(teacher => {
                            const option = document.createElement('option');
                            option.value = teacher.id;
                            option.textContent = teacher.name;
                            teacherSelect.appendChild(option);
                        });
                        teacherSelect.disabled = false;
                    } else {
                        teacherSelect.innerHTML = '<option value="">No teachers available</option>';
                    }
                })
                .catch(error => {
                    console.error('Error loading teachers:', error);
                    teacherSelect.innerHTML = '<option value="">Error loading teachers</option>';
                })
                .finally(() => {
                    updateFormState();
                });
        } else {
            teacherSelect.disabled = true;
            updateFormState();
        }
    });

    // Update form state when any select changes
    [classSelect, sectionSelect, subjectSelect, teacherSelect].forEach(select => {
        select.addEventListener('change', updateFormState);
    });

    // Form submission handling
    routineForm.addEventListener('submit', function(e) {
        if (!classSelect.value || !sectionSelect.value || !subjectSelect.value || !teacherSelect.value) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    });

    // Initialize form state
    updateFormState();

    // Auto-populate if editing
    @if(isset($routine) && $routine->class_id)
        // Set the class first
        classSelect.value = "{{ $routine->class_id }}";
        
        // Trigger class change to load sections and subjects
        setTimeout(() => {
            classSelect.dispatchEvent(new Event('change'));
            
            // After a delay, set the section, subject, and teacher
            setTimeout(() => {
                // Set section
                if (sectionSelect.querySelector(`option[value="{{ $routine->section_id }}"]`)) {
                    sectionSelect.value = "{{ $routine->section_id }}";
                }
                
                // Set subject
                if (subjectSelect.querySelector(`option[value="{{ $routine->subject_id }}"]`)) {
                    subjectSelect.value = "{{ $routine->subject_id }}";
                    // Trigger teacher load
                    subjectSelect.dispatchEvent(new Event('change'));
                    
                    // Set teacher after another delay
                    setTimeout(() => {
                        if (teacherSelect.querySelector(`option[value="{{ $routine->teacher_id }}"]`)) {
                            teacherSelect.value = "{{ $routine->teacher_id }}";
                        }
                        updateFormState();
                    }, 800);
                }
                
                updateFormState();
            }, 1000);
        }, 100);
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

    @media (max-width: 768px) {
        .grid-cols-4 {
            grid-template-columns: 1fr;
        }
        
        .grid-cols-2 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
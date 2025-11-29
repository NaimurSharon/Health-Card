<<<<<<< HEAD
@extends('layouts.app')
=======
@extends('layouts.teacher')
>>>>>>> c356163 (video call ui setup)

@section('title', isset($homework) ? 'Edit Homework' : 'Add New Homework')

@section('content')
<div class="space-y-6">
    <!-- Homework Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ isset($homework) ? 'Edit Homework' : 'Add New Homework' }}</h3>
            <button type="submit" form="homework-form" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                <i class="fas fa-save mr-2"></i>{{ isset($homework) ? 'Update Homework' : 'Create Homework' }}
            </button>
        </div>
    </div>

    <!-- Homework Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="homework-form" 
              action="{{ isset($homework) ? route('teacher.homework.update', $homework->id) : route('teacher.homework.store') }}" 
              method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($homework))
                @method('PUT')
            @endif

            <div class="space-y-8">
                <!-- Class Information Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Class Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Class -->
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                            <select name="class_id" id="class_id" required
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Class</option>
                                @foreach($assignedClasses as $classId => $subjects)
                                <option value="{{ $classId }}" 
                                    {{ (isset($homework) && $homework->class_id == $classId) || old('class_id') == $classId ? 'selected' : '' }}>
                                    {{ $subjects->first()->class->name }}
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
                            <select name="section_id" id="section_id" required
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Section</option>
                                @if(isset($homework))
                                <option value="{{ $homework->section_id }}" selected>
                                    {{ $homework->section->name }}
                                </option>
                                @endif
                            </select>
                            @error('section_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                            <select name="subject_id" id="subject_id" required
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                           focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Subject</option>
                                @if(isset($homework))
                                <option value="{{ $homework->subject_id }}" selected>
                                    {{ $homework->subject->name }}
                                </option>
                                @endif
                            </select>
                            @error('subject_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Homework Details Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Homework Details</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Entry Date -->
                        <div>
                            <label for="entry_date" class="block text-sm font-medium text-gray-700 mb-2">Assignment Date *</label>
                            <input type="date" name="entry_date" id="entry_date" required
                                   value="{{ old('entry_date', isset($homework) ? $homework->entry_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('entry_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                            <input type="date" name="due_date" id="due_date"
                                   value="{{ old('due_date', isset($homework) && $homework->due_date ? $homework->due_date->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                          focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('due_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Homework Title -->
                    <div>
                        <label for="homework_title" class="block text-sm font-medium text-gray-700 mb-2">Homework Title *</label>
                        <input type="text" name="homework_title" id="homework_title" required
                               value="{{ old('homework_title', $homework->homework_title ?? '') }}"
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                               placeholder="Enter homework title">
                        @error('homework_title')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Homework Description -->
                    <div>
                        <label for="homework_description" class="block text-sm font-medium text-gray-700 mb-2">Homework Description *</label>
                        <textarea name="homework_description" id="homework_description" rows="6" required
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                       focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                placeholder="Enter detailed homework description...">{{ old('homework_description', $homework->homework_description ?? '') }}</textarea>
                        @error('homework_description')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status (for edit only) -->
                    @if(isset($homework))
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" required
                                class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                       focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <option value="active" {{ (isset($homework) && $homework->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ (isset($homework) && $homework->status == 'completed') || old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ (isset($homework) && $homework->status == 'cancelled') || old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <!-- Attachments -->
                    <div>
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Attachments</label>
                        <input type="file" name="attachments[]" id="attachments" multiple
                               class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm 
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <p class="mt-1 text-xs text-gray-500">You can select multiple files. Maximum file size: 10MB each.</p>
                        @error('attachments')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        
                        <!-- Existing Attachments -->
                        @if(isset($homework) && $homework->attachments && count($homework->attachments) > 0)
                        <div class="mt-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Attachments</label>
                            <div class="space-y-2">
                                @foreach($homework->attachments as $index => $attachment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-paperclip text-gray-400 mr-3"></i>
                                        <span class="text-sm text-gray-700">{{ $attachment['name'] }}</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $attachment['path']) }}" 
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 text-sm">
                                        View
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('teacher.homework.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ isset($homework) ? 'Update Homework' : 'Create Homework' }}
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
        const classSelect = document.getElementById('class_id');
        const sectionSelect = document.getElementById('section_id');
        const subjectSelect = document.getElementById('subject_id');

        // Load sections when class is selected
        classSelect.addEventListener('change', function() {
            const classId = this.value;
            
            // Clear previous options
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            
            if (classId) {
                fetch(`/teacher/homework/get-sections/${classId}`)
                    .then(response => response.json())
                    .then(sections => {
                        sections.forEach(section => {
                            if (section) {
                                const option = document.createElement('option');
                                option.value = section.id;
                                option.textContent = section.name;
                                sectionSelect.appendChild(option);
                            }
                        });
                    });
            }
        });

        // Load subjects when section is selected
        sectionSelect.addEventListener('change', function() {
            const classId = classSelect.value;
            const sectionId = this.value;
            
            // Clear previous options
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            
            if (classId && sectionId) {
                fetch(`/teacher/homework/get-subjects/${classId}/${sectionId}`)
                    .then(response => response.json())
                    .then(subjects => {
                        subjects.forEach(subject => {
                            if (subject) {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                option.textContent = subject.name;
                                subjectSelect.appendChild(option);
                            }
                        });
                    });
            }
        });

        // Set due date minimum to entry date
        const entryDate = document.getElementById('entry_date');
        const dueDate = document.getElementById('due_date');

        entryDate.addEventListener('change', function() {
            dueDate.min = this.value;
        });

        // Initialize due date min if entry date is already set
        if (entryDate.value) {
            dueDate.min = entryDate.value;
        }
    });
</script>
@endsection
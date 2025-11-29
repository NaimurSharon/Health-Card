@extends('layouts.principal')

@section('title', isset($homework) ? 'Edit Homework' : 'Add Homework')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">{{ isset($homework) ? 'Edit Homework' : 'Add Homework' }}</h3>
                    <p class="text-gray-600 mt-1">{{ isset($homework) ? 'Update homework assignment' : 'Create a new homework assignment' }}</p>
                </div>
                <a href="{{ route('principal.homework.index') }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Homework
                </a>
            </div>
        </div>
    </div>

    <!-- Homework Form -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        <form action="{{ isset($homework) ? route('principal.homework.update', $homework->id) : route('principal.homework.store') }}" 
              method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($homework)) @method('PUT') @endif

            <!-- Teacher & Class -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teacher *</label>
                    <select name="teacher_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id', $homework->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                    <select name="class_id" required id="classSelect" 
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id', $homework->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Section & Subject -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section *</label>
                    <select name="section_id" id="sectionSelect" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Section</option>
                        @if(isset($sections))
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ old('section_id', $homework->section_id ?? '') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('section_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                    <select name="subject_id" id="subjectSelect" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Subject</option>
                        @if(isset($subjects))
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $homework->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('subject_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Entry Date *</label>
                    <input type="date" name="entry_date" required value="{{ old('entry_date', isset($homework) ? $homework->entry_date->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('entry_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date', isset($homework) && $homework->due_date ? $homework->due_date->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('due_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Title & Description -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Homework Title *</label>
                <input type="text" name="homework_title" required
                       value="{{ old('homework_title', $homework->homework_title ?? '') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Enter homework title">
                @error('homework_title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Homework Description *</label>
                <textarea name="homework_description" required rows="6"
                          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Enter detailed homework description">{{ old('homework_description', $homework->homework_description ?? '') }}</textarea>
                @error('homework_description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Attachments -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Attachments</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6">
                    <div class="text-center">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
                        <p class="text-sm text-gray-600 mb-2">Drag and drop files here, or click to select</p>
                        <input type="file" name="attachments[]" multiple class="hidden" id="fileInput">
                        <button type="button" onclick="document.getElementById('fileInput').click()" 
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            Select Files
                        </button>
                        <p class="text-xs text-gray-500 mt-2">Supported formats: JPG, PNG, PDF, DOC, DOCX (Max: 2MB each)</p>
                    </div>

                    <!-- Existing attachments (edit mode) -->
                    @if(isset($homework) && $homework->attachments)
                        <div class="mt-4 space-y-2" id="existingAttachments">
                            @foreach(json_decode($homework->attachments, true) as $index => $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file text-gray-400"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ basename($file) }}</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeExistingAttachment({{ $index }})" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div id="fileList" class="mt-4 space-y-2 hidden"></div>
                </div>
            </div>

            <!-- Status (only for edit) -->
            @if(isset($homework))
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="active" {{ $homework->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ $homework->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $homework->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            @endif

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                <a href="{{ route('principal.homework.index') }}" 
                   class="w-full sm:w-auto px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-center">
                    Cancel
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors">
                    {{ isset($homework) ? 'Update Homework' : 'Create Homework' }}
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
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');

    // Populate sections if class already selected (edit)
    function loadSections(classId, selectedSection = null) {
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        subjectSelect.innerHTML = '<option value="">Select Subject</option>';
        if (!classId) return;

        fetch(`/principal/homework/get-sections/${classId}`)
            .then(res => res.json())
            .then(sections => {
                sections.forEach(section => {
                    const opt = document.createElement('option');
                    opt.value = section.id;
                    opt.textContent = section.name;
                    if (selectedSection && selectedSection == section.id) opt.selected = true;
                    sectionSelect.appendChild(opt);
                });
            });
    }

    // Populate subjects if section already selected (edit)
    function loadSubjects(classId, sectionId, selectedSubject = null) {
        subjectSelect.innerHTML = '<option value="">Select Subject</option>';
        if (!classId || !sectionId) return;

        fetch(`/principal/homework/get-subjects/${classId}/${sectionId}`)
            .then(res => res.json())
            .then(subjects => {
                subjects.forEach(subject => {
                    const opt = document.createElement('option');
                    opt.value = subject.id;
                    opt.textContent = subject.name;
                    if (selectedSubject && selectedSubject == subject.id) opt.selected = true;
                    subjectSelect.appendChild(opt);
                });
            });
    }

    // On class change
    classSelect.addEventListener('change', function() {
        loadSections(this.value);
    });

    // On section change
    sectionSelect.addEventListener('change', function() {
        loadSubjects(classSelect.value, this.value);
    });

    // Preload sections and subjects for edit
    @if(isset($homework))
        loadSections('{{ $homework->class_id }}', '{{ $homework->section_id }}');
        loadSubjects('{{ $homework->class_id }}', '{{ $homework->section_id }}', '{{ $homework->subject_id }}');
    @endif

    // File input
    fileInput.addEventListener('change', function() {
        fileList.innerHTML = '';
        if (this.files.length > 0) {
            fileList.classList.remove('hidden');
            Array.from(this.files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
                fileItem.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-file text-gray-400"></i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">${file.name}</p>
                            <p class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile(${index})" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                fileList.appendChild(fileItem);
            });
        } else {
            fileList.classList.add('hidden');
        }
    });

    function removeFile(index) {
        const files = Array.from(fileInput.files);
        files.splice(index, 1);
        const dt = new DataTransfer();
        files.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
        fileInput.dispatchEvent(new Event('change'));
    }

    window.removeExistingAttachment = function(index) {
        const homeworkId = '{{ $homework->id ?? 0 }}';
        fetch(`/principal/homework/remove-attachment/${homeworkId}/${index}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
            .then(() => location.reload());
    }
});
</script>

<style>
    .content-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }
    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }
</style>
@endsection

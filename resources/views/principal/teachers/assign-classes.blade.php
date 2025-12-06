@extends('layouts.principal')

@section('title', 'Assign Classes - ' . $teacher->user->name)

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div
                class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">Assign Classes & Subjects</h3>
                    <p class="text-gray-600 mt-1">{{ $teacher->user->name }} -
                        {{ ucfirst(str_replace('_', ' ', $teacher->designation)) }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('principal.teachers.show', $teacher->id) }}"
                        class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Current Assignments -->
        @if($assignedClasses->count() > 0)
            <div class="content-card rounded-lg p-4 sm:p-6">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Current Assignments</h4>
                <div class="space-y-4">
                    @php
                        $groupedAssignments = $assignedClasses->groupBy(function ($item) {
                            if ($item->class && $item->section) {
                                return $item->class->name . ' - ' . $item->section->name;
                            }
                            return 'Unknown Class';
                        });
                    @endphp

                    @foreach($groupedAssignments as $classSection => $assignments)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-50 rounded-lg mr-3">
                                        <i class="fas fa-door-open text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-medium text-gray-900">{{ $classSection }}</h5>
                                        <p class="text-xs text-gray-600">{{ $assignments->count() }} subject(s)</p>
                                    </div>
                                </div>
                                <button type="button" onclick="removeClassAssignment('{{ $classSection }}')"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                                    <i class="fas fa-times mr-1"></i>Remove All
                                </button>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($assignments as $assignment)
                                    <div
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <span>{{ $assignment->subject->name ?? 'Unknown Subject' }}</span>
                                        <button type="button" onclick="removeSubjectAssignment({{ $assignment->id }})"
                                            class="ml-2 text-green-600 hover:text-green-900">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="content-card rounded-lg p-6 text-center">
                <i class="fas fa-book-open text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">No classes or subjects assigned yet</p>
                <p class="text-sm text-gray-400 mt-1">Start by adding assignments below</p>
            </div>
        @endif

        <!-- Assignment Form -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Assign New Class & Subject
            </h4>

            <form id="assignmentForm" method="POST"
                action="{{ route('principal.teachers.store-assign-classes', $teacher->id) }}" class="space-y-6">
                @csrf

                <!-- Class and Section Selection -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
                        <select id="classSelect" name="class_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            onchange="loadSections(this.value)">
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Section *</label>
                        <select id="sectionSelect" name="section_id" required disabled
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            onchange="loadSubjects(this.value)">
                            <option value="">Select Section</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                        <select id="subjectSelect" name="subject_id" required disabled
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                </div>

                <!-- Current Assignments Preview -->
                <div id="assignmentsPreview" class="hidden">
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                        <h5 class="text-sm font-medium text-gray-900 mb-3">Assignments to be Added:</h5>
                        <div id="previewList" class="space-y-2"></div>
                    </div>
                </div>

                <!-- Add to List Button -->
                <div class="flex justify-end">
                    <button type="button" onclick="addToAssignmentList()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-plus mr-2"></i>Add to Assignment List
                    </button>
                </div>

                <!-- Hidden Input for All Assignments -->
                <div id="assignmentsContainer" class="hidden">
                    <!-- This will be populated by JavaScript -->
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('principal.teachers.show', $teacher->id) }}"
                        class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        Cancel
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>Save All Assignments
                    </button>
                </div>
            </form>
        </div>

        <!-- Bulk Assignment -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Bulk Assignment</h4>
            <p class="text-sm text-gray-600 mb-4">Assign multiple subjects to multiple classes at once.</p>

            <form id="bulkAssignmentForm" class="space-y-4">
                <!-- Class and Subject Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Classes</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto p-2 border border-gray-300 rounded-lg">
                            @foreach($classes as $class)
                                <div class="flex items-center">
                                    <input type="checkbox" id="bulkClass{{ $class->id }}" value="{{ $class->id }}"
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="bulkClass{{ $class->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $class->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Subjects</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto p-2 border border-gray-300 rounded-lg">
                            @php
                                $allSubjects = [];
                                if ($classes && count($classes) > 0) {
                                    foreach ($classes as $class) {
                                        // Check if subjects exist and is iterable
                                        if ($class->subjects && (is_array($class->subjects) || $class->subjects instanceof \Countable)) {
                                            foreach ($class->subjects as $subject) {
                                                if ($subject && !in_array($subject->id, array_column($allSubjects, 'id'))) {
                                                    $allSubjects[] = $subject;
                                                }
                                            }
                                        }
                                    }
                                }
                            @endphp

                            @if(count($allSubjects) > 0)
                                @foreach($allSubjects as $subject)
                                    @if($subject)
                                        <div class="flex items-center">
                                            <input type="checkbox" id="bulkSubject{{ $subject->id }}" value="{{ $subject->id }}"
                                                class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                            <label for="bulkSubject{{ $subject->id }}" class="ml-2 text-sm text-gray-700">
                                                {{ $subject->name ?? 'Unknown Subject' }}
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <p class="text-gray-500 text-sm">No subjects available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sections for Selected Classes -->
                <div id="bulkSectionsContainer" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Sections</label>
                    <div id="bulkSectionsList"
                        class="space-y-2 max-h-48 overflow-y-auto p-2 border border-gray-300 rounded-lg"></div>
                </div>

                <div class="flex justify-end">
                    <button type="button" onclick="addBulkAssignments()"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-layer-group mr-2"></i>Add Bulk Assignments
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
        // Store sections and subjects data
        let sectionsData = {};
        let subjectsData = {};
        let assignments = [];

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize sections data with null checks
            @foreach($classes as $class)
                sectionsData[{{ $class->id }}] = [
                    @if($class->sections && count($class->sections) > 0)
                        @foreach($class->sections as $section)
                            { id: {{ $section->id }}, name: '{{ addslashes($section->name) }}' },
                        @endforeach
                    @endif
                ];

                subjectsData[{{ $class->id }}] = [
                    @if($class->subjects && count($class->subjects) > 0)
                        @foreach($class->subjects as $subject)
                            { id: {{ $subject->id }}, name: '{{ addslashes($subject->name) }}' },
                        @endforeach
                    @endif
                ];
            @endforeach

            // Initialize assignments from existing ones
            @foreach($assignedClasses as $assignment)
                @if($assignment->class && $assignment->section && $assignment->subject)
                    assignments.push({
                        id: {{ $assignment->id }},
                        class_id: {{ $assignment->class_id }},
                        section_id: {{ $assignment->section_id }},
                        subject_id: {{ $assignment->subject_id }},
                        class_name: '{{ addslashes($assignment->class->name) }}',
                        section_name: '{{ addslashes($assignment->section->name) }}',
                        subject_name: '{{ addslashes($assignment->subject->name) }}'
                    });
                @endif
            @endforeach

            updateAssignmentsPreview();
        });

        // Load sections when class is selected
        function loadSections(classId) {
            const sectionSelect = document.getElementById('sectionSelect');
            const subjectSelect = document.getElementById('subjectSelect');

            // Clear previous options
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';
            subjectSelect.disabled = true;

            if (!classId) {
                sectionSelect.disabled = true;
                return;
            }

            // Enable and populate sections
            sectionSelect.disabled = false;
            const sections = sectionsData[classId] || [];

            if (sections.length > 0) {
                sections.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.name;
                    sectionSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No sections available';
                option.disabled = true;
                sectionSelect.appendChild(option);
            }

            // Reset subjects
            subjectSelect.disabled = true;
        }

        // Load subjects when section is selected
        function loadSubjects(sectionId) {
            const subjectSelect = document.getElementById('subjectSelect');
            const classId = document.getElementById('classSelect').value;

            // Clear previous options
            subjectSelect.innerHTML = '<option value="">Select Subject</option>';

            if (!classId || !sectionId) {
                subjectSelect.disabled = true;
                return;
            }

            // Enable and populate subjects
            subjectSelect.disabled = false;
            const subjects = subjectsData[classId] || [];

            if (subjects.length > 0) {
                subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    subjectSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No subjects available';
                option.disabled = true;
                subjectSelect.appendChild(option);
            }
        }

        // Add assignment to list
        function addToAssignmentList() {
            const classId = document.getElementById('classSelect').value;
            const sectionId = document.getElementById('sectionSelect').value;
            const subjectId = document.getElementById('subjectSelect').value;

            // Validate selection
            if (!classId || !sectionId || !subjectId) {
                alert('Please select class, section, and subject.');
                return;
            }

            const classSelect = document.getElementById('classSelect');
            const sectionSelect = document.getElementById('sectionSelect');
            const subjectSelect = document.getElementById('subjectSelect');

            // Check if options are valid
            if (classSelect.selectedOptions[0].disabled ||
                sectionSelect.selectedOptions[0].disabled ||
                subjectSelect.selectedOptions[0].disabled) {
                alert('Please select valid class, section, and subject.');
                return;
            }

            const className = classSelect.selectedOptions[0].textContent;
            const sectionName = sectionSelect.selectedOptions[0].textContent;
            const subjectName = subjectSelect.selectedOptions[0].textContent;

            // Check if assignment already exists
            const exists = assignments.some(assignment =>
                assignment.class_id == classId &&
                assignment.section_id == sectionId &&
                assignment.subject_id == subjectId
            );

            if (exists) {
                alert('This assignment already exists in the list.');
                return;
            }

            // Add to assignments array
            assignments.push({
                class_id: classId,
                section_id: sectionId,
                subject_id: subjectId,
                class_name: className,
                section_name: sectionName,
                subject_name: subjectName
            });

            // Update preview
            updateAssignmentsPreview();

            // Clear form
            document.getElementById('classSelect').value = '';
            document.getElementById('sectionSelect').value = '';
            document.getElementById('subjectSelect').value = '';
            document.getElementById('sectionSelect').disabled = true;
            document.getElementById('subjectSelect').disabled = true;
        }

        // Update assignments preview
        function updateAssignmentsPreview() {
            const previewContainer = document.getElementById('assignmentsPreview');
            const previewList = document.getElementById('previewList');
            const assignmentsContainer = document.getElementById('assignmentsContainer');

            if (assignments.length === 0) {
                previewContainer.classList.add('hidden');
                assignmentsContainer.classList.add('hidden');
                return;
            }

            // Show preview
            previewContainer.classList.remove('hidden');
            assignmentsContainer.classList.remove('hidden');

            // Clear preview
            previewList.innerHTML = '';
            assignmentsContainer.innerHTML = '';

            // Populate preview
            assignments.forEach((assignment, index) => {
                // Add to preview list
                const previewItem = document.createElement('div');
                previewItem.className = 'flex items-center justify-between p-2 bg-white rounded border border-gray-200';
                previewItem.innerHTML = `
                <div>
                    <span class="text-sm font-medium text-gray-900">${assignment.class_name} - ${assignment.section_name}</span>
                    <span class="ml-2 text-sm text-gray-600">(${assignment.subject_name})</span>
                </div>
                <button type="button" onclick="removeAssignment(${index})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            `;
                previewList.appendChild(previewItem);

                // Add hidden inputs to form
                const classInput = document.createElement('input');
                classInput.type = 'hidden';
                classInput.name = `class_subjects[${index}][class_id]`;
                classInput.value = assignment.class_id;

                const sectionInput = document.createElement('input');
                sectionInput.type = 'hidden';
                sectionInput.name = `class_subjects[${index}][section_id]`;
                sectionInput.value = assignment.section_id;

                const subjectInput = document.createElement('input');
                subjectInput.type = 'hidden';
                subjectInput.name = `class_subjects[${index}][subject_id]`;
                subjectInput.value = assignment.subject_id;

                assignmentsContainer.appendChild(classInput);
                assignmentsContainer.appendChild(sectionInput);
                assignmentsContainer.appendChild(subjectInput);
            });
        }

        // Remove assignment from list
        function removeAssignment(index) {
            if (confirm('Remove this assignment from the list?')) {
                assignments.splice(index, 1);
                updateAssignmentsPreview();
            }
        }

        // Remove all assignments for a class-section
        function removeClassAssignment(classSection) {
            if (confirm(`Remove all assignments for ${classSection}?`)) {
                assignments = assignments.filter(assignment =>
                    `${assignment.class_name} - ${assignment.section_name}` !== classSection
                );
                updateAssignmentsPreview();
            }
        }

        // Remove specific subject assignment (for existing assignments)
        function removeSubjectAssignment(assignmentId) {
            if (confirm('Remove this subject assignment?')) {
                fetch(`/principal/teachers/remove-assignment/${assignmentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            assignments = assignments.filter(assignment => assignment.id !== assignmentId);
                            updateAssignmentsPreview();
                            location.reload();
                        } else {
                            alert('Failed to remove assignment: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to remove assignment. Please try again.');
                    });
            }
        }

        // Bulk assignment functions
        function addBulkAssignments() {
            const selectedClasses = Array.from(document.querySelectorAll('#bulkAssignmentForm input[type="checkbox"][id^="bulkClass"]:checked'))
                .map(cb => cb.value);

            const selectedSubjects = Array.from(document.querySelectorAll('#bulkAssignmentForm input[type="checkbox"][id^="bulkSubject"]:checked'))
                .map(cb => cb.value);

            const selectedSections = Array.from(document.querySelectorAll('#bulkSectionsList input[type="checkbox"]:checked'))
                .map(cb => cb.value);

            if (selectedClasses.length === 0 || selectedSubjects.length === 0 || selectedSections.length === 0) {
                alert('Please select at least one class, one subject, and one section.');
                return;
            }

            let addedCount = 0;
            let duplicateCount = 0;

            // Add all combinations to assignments
            selectedClasses.forEach(classId => {
                const className = document.querySelector(`#bulkClass${classId}`)?.nextElementSibling?.textContent?.trim() || 'Unknown Class';

                selectedSections.forEach(sectionId => {
                    const sectionCheckbox = document.querySelector(`#bulkSection${sectionId}`);
                    if (sectionCheckbox && sectionCheckbox.dataset.classId == classId) {
                        const sectionName = sectionCheckbox.nextElementSibling?.textContent?.trim() || 'Unknown Section';

                        selectedSubjects.forEach(subjectId => {
                            const subjectElement = document.querySelector(`#bulkSubject${subjectId}`);
                            const subjectName = subjectElement?.nextElementSibling?.textContent?.trim() || 'Unknown Subject';

                            // Check if assignment already exists
                            const exists = assignments.some(assignment =>
                                assignment.class_id == classId &&
                                assignment.section_id == sectionId &&
                                assignment.subject_id == subjectId
                            );

                            if (!exists) {
                                assignments.push({
                                    class_id: classId,
                                    section_id: sectionId,
                                    subject_id: subjectId,
                                    class_name: className,
                                    section_name: sectionName,
                                    subject_name: subjectName
                                });
                                addedCount++;
                            } else {
                                duplicateCount++;
                            }
                        });
                    }
                });
            });

            // Update preview
            updateAssignmentsPreview();

            // Show notification
            if (addedCount > 0) {
                alert(`Added ${addedCount} assignment(s). ${duplicateCount > 0 ? ` ${duplicateCount} duplicate(s) were skipped.` : ''}`);
            } else if (duplicateCount > 0) {
                alert('All selected assignments already exist in the list.');
            }

            // Clear bulk form
            document.querySelectorAll('#bulkAssignmentForm input[type="checkbox"]:checked').forEach(cb => cb.checked = false);
            document.querySelectorAll('#bulkSectionsList input[type="checkbox"]:checked').forEach(cb => cb.checked = false);
            document.getElementById('bulkSectionsContainer').classList.add('hidden');
        }

        // Load sections when classes are selected for bulk assignment
        document.addEventListener('change', function (e) {
            if (e.target.id && e.target.id.startsWith('bulkClass')) {
                const selectedClasses = Array.from(document.querySelectorAll('#bulkAssignmentForm input[type="checkbox"][id^="bulkClass"]:checked'))
                    .map(cb => cb.value);

                const sectionsContainer = document.getElementById('bulkSectionsContainer');
                const sectionsList = document.getElementById('bulkSectionsList');

                if (selectedClasses.length === 0) {
                    sectionsContainer.classList.add('hidden');
                    sectionsList.innerHTML = '';
                    return;
                }

                // Show sections container
                sectionsContainer.classList.remove('hidden');
                sectionsList.innerHTML = '';

                // Add sections for selected classes
                selectedClasses.forEach(classId => {
                    const className = document.querySelector(`#bulkClass${classId}`)?.nextElementSibling?.textContent?.trim() || 'Unknown Class';
                    const sections = sectionsData[classId] || [];

                    if (sections.length > 0) {
                        sections.forEach(section => {
                            const sectionDiv = document.createElement('div');
                            sectionDiv.className = 'flex items-center';
                            sectionDiv.innerHTML = `
                            <input type="checkbox" id="bulkSection${section.id}" value="${section.id}"
                                   data-class-id="${classId}"
                                   class="h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <label for="bulkSection${section.id}" class="ml-2 text-sm text-gray-700">
                                ${className} - ${section.name}
                            </label>
                        `;
                            sectionsList.appendChild(sectionDiv);
                        });
                    } else {
                        const noSectionsDiv = document.createElement('div');
                        noSectionsDiv.className = 'text-center py-2';
                        noSectionsDiv.innerHTML = `<p class="text-xs text-gray-500">No sections for ${className}</p>`;
                        sectionsList.appendChild(noSectionsDiv);
                    }
                });
            }
        });

        // Form validation before submission
        document.getElementById('assignmentForm').addEventListener('submit', function (e) {
            if (assignments.length === 0) {
                e.preventDefault();
                alert('Please add at least one assignment before saving.');
                return false;
            }

            // Confirm submission
            if (!confirm(`Save ${assignments.length} assignment(s)? This will replace all existing assignments.`)) {
                e.preventDefault();
                return false;
            }

            return true;
        });
    </script>
@endsection
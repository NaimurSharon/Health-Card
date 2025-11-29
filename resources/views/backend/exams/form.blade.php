<form id="examForm" action="{{ $action }}" method="POST">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    
    <!-- Basic Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Exam Title *</label>
            <input type="text" id="title" name="title" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   value="{{ old('title', $exam->title ?? '') }}"
                   placeholder="Enter exam title">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class *</label>
            <select id="class_id" name="class_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ old('class_id', $exam->class_id ?? '') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
            @error('class_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
            <select id="subject_id" name="subject_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Subject</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ old('subject_id', $exam->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            @error('subject_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="exam_date" class="block text-sm font-medium text-gray-700 mb-2">Exam Date *</label>
            <input type="date" id="exam_date" name="exam_date" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   value="{{ old('exam_date', ($exam->exam_date ?? now())->format('Y-m-d')) }}">
            @error('exam_date')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


        <div>
            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Duration (Minutes) *</label>
            <input type="number" id="duration_minutes" name="duration_minutes" required min="1"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   value="{{ old('duration_minutes', $exam->duration_minutes ?? 60) }}"
                   placeholder="e.g., 60">
            @error('duration_minutes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
            <select id="status" name="status" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select Status</option>
                <option value="upcoming" {{ old('status', $exam->status ?? '') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="ongoing" {{ old('status', $exam->status ?? '') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="completed" {{ old('status', $exam->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ old('status', $exam->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>


        <div>
            <label for="total_marks" class="block text-sm font-medium text-gray-700 mb-2">Total Marks *</label>
            <input type="number" id="total_marks" name="total_marks" required min="1"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   value="{{ old('total_marks', $exam->total_marks ?? 100) }}"
                   placeholder="e.g., 100">
            @error('total_marks')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="passing_marks" class="block text-sm font-medium text-gray-700 mb-2">Passing Marks *</label>
            <input type="number" id="passing_marks" name="passing_marks" required min="1"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                   value="{{ old('passing_marks', $exam->passing_marks ?? 40) }}"
                   placeholder="e.g., 40">
            @error('passing_marks')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mb-6">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
        <textarea id="description" name="description" rows="3" required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Enter exam description">{{ old('description', $exam->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Questions Section -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold">Exam Questions</h4>
            <button type="button" onclick="addQuestion()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center">
                <i class="fas fa-plus mr-2"></i>Add Question
            </button>
        </div>

        <div id="questions-container" class="space-y-6">
            <!-- Questions will be added here dynamically -->
            @if(isset($exam) && $exam->questions)
                @php
                    $questions = json_decode($exam->questions, true);
                @endphp
                @foreach($questions as $index => $question)
                    <div class="question-card border border-gray-200 rounded-lg p-6" id="question-{{ $index + 1 }}">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="font-semibold">Question {{ $index + 1 }}</h5>
                            <button type="button" onclick="removeQuestion({{ $index + 1 }})" 
                                    class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Question Text *</label>
                            <textarea name="questions[{{ $index + 1 }}][question]" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      rows="2"
                                      placeholder="Enter the question">{{ $question['question'] }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Options *</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($question['options'] as $optionIndex => $option)
                                    <div class="flex items-center">
                                        <span class="mr-2 font-medium">{{ chr(65 + $optionIndex) }})</span>
                                        <input type="text" name="questions[{{ $index + 1 }}][options][{{ $optionIndex }}]" required
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="Option {{ chr(65 + $optionIndex) }}"
                                               value="{{ $option }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer *</label>
                                <select name="questions[{{ $index + 1 }}][correct_answer]" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Correct Option</option>
                                    <option value="0" {{ $question['correct_answer'] == 0 ? 'selected' : '' }}>Option A</option>
                                    <option value="1" {{ $question['correct_answer'] == 1 ? 'selected' : '' }}>Option B</option>
                                    <option value="2" {{ $question['correct_answer'] == 2 ? 'selected' : '' }}>Option C</option>
                                    <option value="3" {{ $question['correct_answer'] == 3 ? 'selected' : '' }}>Option D</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Marks *</label>
                                <input type="number" name="questions[{{ $index + 1 }}][marks]" required min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       value="{{ $question['marks'] ?? 1 }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Form Actions -->
    <div class="flex justify-end space-x-4 pt-6 border-t">
        <a href="{{ route('admin.exams.index') }}" 
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
            Cancel
        </a>
        <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
            {{ $exam ? 'Update Exam' : 'Create Exam' }}
        </button>
    </div>
</form>

<script>
    let questionCount = {{ isset($questions) ? count($questions) : 0 }};

    function addQuestion() {
        questionCount++;
        const container = document.getElementById('questions-container');
        
        const questionHtml = `
            <div class="question-card border border-gray-200 rounded-lg p-6" id="question-${questionCount}">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="font-semibold">Question ${questionCount}</h5>
                    <button type="button" onclick="removeQuestion(${questionCount})" 
                            class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Question Text *</label>
                    <textarea name="questions[${questionCount}][question]" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              rows="2"
                              placeholder="Enter the question"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Options *</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-center">
                            <span class="mr-2 font-medium">A)</span>
                            <input type="text" name="questions[${questionCount}][options][0]" required
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Option A">
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2 font-medium">B)</span>
                            <input type="text" name="questions[${questionCount}][options][1]" required
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Option B">
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2 font-medium">C)</span>
                            <input type="text" name="questions[${questionCount}][options][2]" required
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Option C">
                        </div>
                        <div class="flex items-center">
                            <span class="mr-2 font-medium">D)</span>
                            <input type="text" name="questions[${questionCount}][options][3]" required
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Option D">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer *</label>
                        <select name="questions[${questionCount}][correct_answer]" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Correct Option</option>
                            <option value="0">Option A</option>
                            <option value="1">Option B</option>
                            <option value="2">Option C</option>
                            <option value="3">Option D</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Marks *</label>
                        <input type="number" name="questions[${questionCount}][marks]" required min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="1">
                    </div>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', questionHtml);
    }

    function removeQuestion(id) {
        const element = document.getElementById(`question-${id}`);
        if (element) {
            element.remove();
            // Re-number remaining questions
            updateQuestionNumbers();
        }
    }

    function updateQuestionNumbers() {
        const questions = document.querySelectorAll('.question-card');
        questions.forEach((question, index) => {
            const questionNumber = index + 1;
            question.id = `question-${questionNumber}`;
            const heading = question.querySelector('h5');
            if (heading) {
                heading.textContent = `Question ${questionNumber}`;
            }
            
            // Update all input names
            const textareas = question.querySelectorAll('textarea[name]');
            textareas.forEach(textarea => {
                const name = textarea.getAttribute('name');
                const newName = name.replace(/questions\[\d+\]/, `questions[${questionNumber}]`);
                textarea.setAttribute('name', newName);
            });

            const inputs = question.querySelectorAll('input[name]');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                const newName = name.replace(/questions\[\d+\]/, `questions[${questionNumber}]`);
                input.setAttribute('name', newName);
            });

            const selects = question.querySelectorAll('select[name]');
            selects.forEach(select => {
                const name = select.getAttribute('name');
                const newName = name.replace(/questions\[\d+\]/, `questions[${questionNumber}]`);
                select.setAttribute('name', newName);
            });
        });
        questionCount = questions.length;
    }

    // Add first question if none exist
    document.addEventListener('DOMContentLoaded', function() {
        if (questionCount === 0) {
            addQuestion();
        }

        // Form validation
        const form = document.getElementById('examForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (questionCount === 0) {
                    e.preventDefault();
                    alert('Please add at least one question.');
                    return false;
                }

                // Validate total marks match
                const totalMarksInput = document.getElementById('total_marks');
                const totalMarks = parseInt(totalMarksInput.value) || 0;
                let calculatedMarks = 0;

                document.querySelectorAll('input[name$="[marks]"]').forEach(input => {
                    calculatedMarks += parseInt(input.value) || 0;
                });

                if (calculatedMarks !== totalMarks) {
                    e.preventDefault();
                    alert(`Total marks (${totalMarks}) do not match the sum of question marks (${calculatedMarks}). Please adjust accordingly.`);
                    return false;
                }
            });
        }
    });
</script>
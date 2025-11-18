@extends('layouts.app')

@section('title', 'Taking Exam - ' . $exam->title)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Exam Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $exam->title }}</h1>
                    <p class="text-gray-600">Question {{ $currentQuestionIndex + 1 }} of {{ $totalQuestions }}</p>
                </div>
                <div class="text-right">
                    <div id="timer" class="text-2xl font-mono font-bold 
                        {{ $timeRemaining <= 300 ? 'text-red-600' : 'text-gray-900' }}">
                        {{ gmdate('i:s', $timeRemaining) }}
                    </div>
                    <p class="text-sm text-gray-500">Time Remaining</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-2">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ (($currentQuestionIndex + 1) / $totalQuestions) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Content -->
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="content-card rounded-lg p-8 shadow-sm">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">
                    Question {{ $currentQuestionIndex + 1 }}
                </h2>
                <p class="text-lg text-gray-800 mb-2">{{ $currentQuestion['question'] }}</p>
                @if(isset($currentQuestion['marks']))
                    <p class="text-sm text-gray-500">Marks: {{ $currentQuestion['marks'] }}</p>
                @endif
            </div>

            <form id="answerForm" class="space-y-4">
                @csrf
                <input type="hidden" name="question_index" value="{{ $currentQuestionIndex }}">
                
                @foreach($currentQuestion['options'] as $index => $option)
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors option-item">
                        <input type="radio" 
                               id="option{{ $index }}" 
                               name="answer" 
                               value="{{ $index }}"
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <label for="option{{ $index }}" class="ml-3 block text-sm font-medium text-gray-700 cursor-pointer flex-1">
                            {{ chr(65 + $index) }}) {{ $option }}
                        </label>
                    </div>
                @endforeach
            </form>

            <div class="mt-8 flex justify-between">
                <div>
                    @if($currentQuestionIndex > 0)
                        <button onclick="showWarning('You cannot go back to previous questions.')" 
                                class="bg-gray-500 text-white px-6 py-2 rounded-lg text-sm font-medium opacity-50 cursor-not-allowed">
                            Previous
                        </button>
                    @endif
                </div>
                <button onclick="submitAnswer()" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg text-sm font-medium transition-colors">
                    {{ $currentQuestionIndex + 1 == $totalQuestions ? 'Submit Exam' : 'Next Question' }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Warning Modal -->
<div id="warningModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="content-card rounded-lg p-6 max-w-sm w-full mx-4">
        <div class="text-center">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-4"></i>
            <h3 class="text-lg font-semibold mb-2">Notice</h3>
            <p id="warningMessage" class="text-gray-600 mb-4"></p>
            <button onclick="closeWarning()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium">
                Understood
            </button>
        </div>
    </div>
</div>

<style>
    .content-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .option-item {
        transition: all 0.2s ease;
    }

    .option-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    input[type="radio"]:checked + label {
        color: #2563eb;
        font-weight: 600;
    }

    input[type="radio"]:checked ~ .option-item {
        border-color: #2563eb;
        background-color: #eff6ff;
    }
</style>

<script>
    let timeRemaining = {{ $timeRemaining }};
    let examSubmitted = false;

    // Timer function
    function updateTimer() {
        if (timeRemaining <= 0 || examSubmitted) {
            autoSubmitExam();
            return;
        }

        timeRemaining--;
        
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        const timerElement = document.getElementById('timer');
        
        timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        // Change color when less than 5 minutes remaining
        if (timeRemaining <= 300) {
            timerElement.classList.remove('text-gray-900');
            timerElement.classList.add('text-red-600');
        }
        
        // Flash when less than 1 minute
        if (timeRemaining <= 60) {
            timerElement.classList.toggle('text-red-600');
        }
    }

    // Submit answer and move to next question
    function submitAnswer() {
        const form = document.getElementById('answerForm');
        const formData = new FormData(form);
        const answer = formData.get('answer');

        if (!answer && answer !== '0') {
            showWarning('Please select an answer before proceeding.');
            return;
        }

        // Show loading state
        const submitBtn = document.querySelector('button[onclick="submitAnswer()"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        fetch(`/exam/attempt/{{ $attempt->id }}/answer`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                answer: parseInt(answer),
                question_index: {{ $currentQuestionIndex }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Move to next question or submit exam
                if ({{ $currentQuestionIndex + 1 }} >= {{ $totalQuestions }}) {
                    window.location.href = `/exam/attempt/{{ $attempt->id }}/submit`;
                } else {
                    window.location.reload();
                }
            } else {
                showWarning(data.message || 'Error submitting answer. Please try again.');
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showWarning('Error submitting answer. Please try again.');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    }

    // Auto-submit when time runs out
    function autoSubmitExam() {
        if (examSubmitted) return;
        
        examSubmitted = true;
        
        // Get all selected answers
        const answers = {};
        @if($attempt->answers)
            @foreach($attempt->answers as $index => $answer)
                answers[{{ $index }}] = {{ $answer }};
            @endforeach
        @endif

        fetch(`/exam/attempt/{{ $attempt->id }}/auto-submit`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ answers: answers })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect_url;
            } else {
                window.location.href = `/exam/result/{{ $attempt->id }}`;
            }
        })
        .catch(error => {
            window.location.href = `/exam/result/{{ $attempt->id }}`;
        });
    }

    // Modal functions
    function showWarning(message) {
        document.getElementById('warningMessage').textContent = message;
        document.getElementById('warningModal').classList.remove('hidden');
    }

    function closeWarning() {
        document.getElementById('warningModal').classList.add('hidden');
    }

    // Prevent leaving the page
    window.addEventListener('beforeunload', function (e) {
        if (!examSubmitted) {
            e.preventDefault();
            e.returnValue = 'Your exam progress will be lost if you leave this page. Are you sure?';
            return 'Your exam progress will be lost if you leave this page. Are you sure?';
        }
    });

    // Start timer
    setInterval(updateTimer, 1000);

    // Auto-submit when time runs out initially
    if (timeRemaining <= 0) {
        autoSubmitExam();
    }

    // Add click event to option items
    document.querySelectorAll('.option-item').forEach(item => {
        item.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Visual feedback
            document.querySelectorAll('.option-item').forEach(opt => {
                opt.classList.remove('border-blue-500', 'bg-blue-50');
            });
            this.classList.add('border-blue-500', 'bg-blue-50');
        });
    });
</script>
@endsection
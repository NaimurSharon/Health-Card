@extends('layouts.student')

@section('title', 'Taking Exam - ' . $exam->title)

@section('content')
@php
    // Calculate time per question
    $totalExamSeconds = $exam->duration_minutes * 60;
    $timePerQuestion = $totalQuestions > 0 ? ceil($totalExamSeconds / $totalQuestions) : 30;
    
    // Format time properly for display
    $minutes = floor($timeRemaining / 60);
    $seconds = $timeRemaining % 60;
    $formattedTime = sprintf('%02d:%02d', $minutes, $seconds);
@endphp

<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50">
    <!-- Exam Header -->
    <div class="content-card rounded-b-lg border-b-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="space-y-2">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $exam->title }}</h1>
                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                        <span class="flex items-center">
                            <i class="fas fa-list-ol mr-2 text-blue-600"></i>
                            Question {{ $currentQuestionIndex + 1 }} of {{ $totalQuestions }}
                        </span>
                        @if($exam->total_marks)
                        <span class="flex items-center">
                            <i class="fas fa-star mr-2 text-yellow-500"></i>
                            Total Marks: {{ $exam->total_marks }}
                        </span>
                        @endif
                        <span class="flex items-center">
                            <i class="fas fa-clock mr-2 text-green-600"></i>
                            {{ $exam->duration_minutes }} min exam
                        </span>
                    </div>
                </div>
                <div class="text-right space-y-2">
                    <div id="timer" class="text-2xl font-mono font-bold px-4 py-2 rounded-lg transition-all duration-300
                        {{ $timeRemaining <= 300 ? 'bg-red-100 text-red-700 border border-red-300' : 'bg-blue-100 text-blue-700 border border-blue-300' }}">
                        <i class="fas fa-clock mr-2"></i><span id="time-display">{{ $timePerQuestion }}</span>
                    </div>
                    <p class="text-sm text-gray-500">Time Remaining</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="content-card rounded-t-lg border-t-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Exam Progress</span>
                    <span class="text-sm font-medium text-blue-700">{{ round((($currentQuestionIndex + 1) / $totalQuestions) * 100) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-3 rounded-full transition-all duration-500 ease-out" 
                         style="width: {{ (($currentQuestionIndex + 1) / $totalQuestions) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Switch Warning -->
    <div id="tabSwitchWarning" class="hidden fixed inset-0 bg-red-600 bg-opacity-90 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-8 max-w-md mx-4 text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Warning: Tab Switch Detected</h3>
            <p class="text-gray-600 mb-4">You have switched tabs/windows. This is considered cheating.</p>
            <p class="text-red-600 font-semibold mb-4">Current question will be marked as wrong and you will be moved to next question.</p>
            <div id="tabSwitchCountdown" class="text-2xl font-mono text-red-600 mb-4">5</div>
            <button onclick="forceNextQuestion()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-medium">
                Continue Immediately
            </button>
        </div>
    </div>

    <!-- Question Content -->
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="content-card rounded-xl p-8 space-y-8">
            <!-- Question Header -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-sm font-medium mr-3">
                            Question {{ $currentQuestionIndex + 1 }}
                        </span>
                        <span>Answer the question below</span>
                    </h2>
                    <div class="flex items-center space-x-3">
                        @if(isset($currentQuestion['marks']))
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-lg text-sm font-medium">
                            <i class="fas fa-star mr-1"></i>{{ $currentQuestion['marks'] }} marks
                        </span>
                        @endif
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-lg text-sm font-medium">
                            <i class="fas fa-stopwatch mr-1"></i><span id="question-timer">{{ $timePerQuestion }}</span>s
                        </span>
                    </div>
                </div>
                
                <div class="bg-white/80 border border-gray-200/60 rounded-lg p-6">
                    <p class="text-lg text-gray-800 leading-relaxed">{{ $currentQuestion['question'] }}</p>
                </div>
            </div>

            <!-- Answer Options -->
            <form id="answerForm" class="space-y-4">
                @csrf
                <input type="hidden" name="question_index" value="{{ $currentQuestionIndex }}">
                
                <div class="space-y-3">
                    @foreach($currentQuestion['options'] as $index => $option)
                    <div class="option-item flex items-center p-5 bg-white/80 border border-gray-200/60 rounded-xl hover:shadow-md cursor-pointer transition-all duration-200" 
                         onclick="selectOption({{ $index }})">
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-700 font-medium mr-4 border border-gray-300 option-indicator">
                            {{ chr(65 + $index) }}
                        </div>
                        <input type="radio" 
                               id="option{{ $index }}" 
                               name="answer" 
                               value="{{ $index }}"
                               class="h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                        <label for="option{{ $index }}" class="ml-3 block text-base font-medium text-gray-800 cursor-pointer flex-1 leading-relaxed">
                            {{ $option }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </form>

            <!-- Navigation -->
            <div class="pt-6 border-t border-gray-200/60 flex justify-between items-center">
                <div>
                    @if($currentQuestionIndex > 0)
                        <button onclick="showWarning('You cannot go back to previous questions.')" 
                                class="bg-gray-400 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center opacity-50 cursor-not-allowed">
                            <i class="fas fa-arrow-left mr-2"></i>Previous
                        </button>
                    @endif
                </div>
                <button onclick="submitAnswer()" 
                        class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-8 py-3 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105 flex items-center shadow-lg">
                    <span id="submitText">
                        {{ $currentQuestionIndex + 1 == $totalQuestions ? 'Submit Exam' : 'Next Question' }}
                    </span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Warning Modal -->
<div id="warningModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 backdrop-blur-sm">
    <div class="content-card rounded-xl p-6 max-w-md w-full mx-4 transform transition-all duration-300 scale-95">
        <div class="text-center space-y-4">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="text-xl font-semibold text-gray-900">Notice</h3>
                <p id="warningMessage" class="text-gray-600 leading-relaxed"></p>
            </div>
            <button onclick="closeWarning()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-sm font-medium transition-colors w-full">
                Understood
            </button>
        </div>
    </div>
</div>

<style>
    .content-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .option-item {
        transition: all 0.3s ease;
    }

    .option-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .option-item.selected {
        border-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15);
        transform: translateY(-2px);
    }

    .option-item.selected .option-indicator {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border-color: #3b82f6;
    }

    .option-item.selected label {
        color: #1e40af;
        font-weight: 600;
    }

    #timer.flashing {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
</style>

<script>
    let timeRemaining = {{ $timeRemaining }};
    let examSubmitted = false;
    let timerInterval;
    let isSubmitting = false;
    let tabSwitchDetected = false;
    let tabSwitchCountdown = 5;
    let tabSwitchInterval;

    // Calculate time per question
    const totalExamSeconds = {{ $exam->duration_minutes * 60 }};
    const totalQuestions = {{ $totalQuestions }};
    const timePerQuestion = totalQuestions > 0 ? Math.ceil(totalExamSeconds / totalQuestions) : 30;
    let questionTimeRemaining = Math.min(timePerQuestion, timeRemaining);

    // Tab switch detection
    let hiddenTime;
    let visibilityChange;
    let tabSwitchWarningShown = false;
    
    if (typeof document.hidden !== "undefined") {
        visibilityChange = "visibilitychange";
    } else if (typeof document.msHidden !== "undefined") {
        visibilityChange = "msvisibilitychange";
    } else if (typeof document.webkitHidden !== "undefined") {
        visibilityChange = "webkitvisibilitychange";
    }

    function handleVisibilityChange() {
        if (document.hidden || document.msHidden || document.webkitHidden) {
            // Tab/window switched away
            if (!examSubmitted && !isSubmitting) {
                hiddenTime = Date.now();
                if (!tabSwitchWarningShown) {
                    // First time - show warning
                    tabSwitchWarningShown = true;
                    showTabSwitchWarning();
                } else {
                    // Subsequent times - immediate penalty
                    forceNextQuestion();
                }
            }
        }
    }

    if (visibilityChange) {
        document.addEventListener(visibilityChange, handleVisibilityChange, false);
    }

    function showTabSwitchWarning() {
        if (examSubmitted || isSubmitting) return;
        
        document.getElementById('tabSwitchWarning').classList.remove('hidden');
        tabSwitchCountdown = 5;
        document.getElementById('tabSwitchCountdown').textContent = tabSwitchCountdown;
        
        tabSwitchInterval = setInterval(() => {
            tabSwitchCountdown--;
            document.getElementById('tabSwitchCountdown').textContent = tabSwitchCountdown;
            
            if (tabSwitchCountdown <= 0) {
                forceNextQuestion();
            }
        }, 1000);
    }

    function forceNextQuestion() {
        clearInterval(tabSwitchInterval);
        document.getElementById('tabSwitchWarning').classList.add('hidden');
        
        // Submit wrong answer for current question due to tab switching
        isSubmitting = true;
        
        fetch(`{{ route('student.exam.answer', $attempt->id) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                answer: -1, // -1 indicates tab switch penalty
                question_index: {{ $currentQuestionIndex }},
                is_tab_switch_penalty: true
            })
        })
        .then(response => response.json())
        .then(data => {
            // Move to next question or submit exam
            const nextQuestionIndex = {{ $currentQuestionIndex }} + 1;
            if (nextQuestionIndex >= {{ $totalQuestions }}) {
                window.location.href = `{{ route('student.exam.submit', $attempt->id) }}`;
            } else {
                window.location.href = `{{ route('student.exam.take', $attempt->id) }}?question=${nextQuestionIndex}`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback redirect
            const nextQuestionIndex = {{ $currentQuestionIndex }} + 1;
            if (nextQuestionIndex >= {{ $totalQuestions }}) {
                window.location.href = `{{ route('student.exam.submit', $attempt->id) }}`;
            } else {
                window.location.href = `{{ route('student.exam.take', $attempt->id) }}?question=${nextQuestionIndex}`;
            }
        });
    }

    // Select option function
    function selectOption(index) {
        document.querySelectorAll('.option-item').forEach(item => {
            item.classList.remove('selected');
        });

        const selectedOption = document.querySelector(`#option${index}`).parentElement;
        selectedOption.classList.add('selected');
        document.querySelector(`#option${index}`).checked = true;
    }

    // Timer function - shows only minutes and seconds
    function updateTimer() {
        if (timeRemaining <= 0 || examSubmitted) {
            clearInterval(timerInterval);
            autoSubmitExam();
            return;
        }

        timeRemaining--;
        questionTimeRemaining--;

        // Update question timer display
        document.getElementById('question-timer').textContent = questionTimeRemaining;

        // Calculate minutes and seconds for main timer display
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        
        // Format as MM:SS
        const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        document.getElementById('time-display').textContent = timeString;

        // Change color when less than 5 minutes remaining
        if (timeRemaining <= 300) {
            const timerElement = document.getElementById('timer');
            timerElement.classList.remove('bg-blue-100', 'text-blue-700', 'border-blue-300');
            timerElement.classList.add('bg-red-100', 'text-red-700', 'border-red-300');
        }

        // Flash when less than 1 minute
        if (timeRemaining <= 60) {
            document.getElementById('timer').classList.add('flashing');
        }

        // Auto-move to next question if per-question time expires
        if (questionTimeRemaining <= 0 && !isSubmitting) {
            autoMoveToNextQuestion();
        }
    }

    // Auto-move to next question when per-question time expires
    function autoMoveToNextQuestion() {
        if (isSubmitting || examSubmitted) return;
        
        isSubmitting = true;
        
        // Get current answer if any
        const formData = new FormData(document.getElementById('answerForm'));
        const currentAnswer = formData.get('answer');

        // Submit current answer or mark as unanswered (null)
        const answerToSubmit = (currentAnswer || currentAnswer === '0') ? parseInt(currentAnswer) : null;

        fetch(`{{ route('student.exam.answer', $attempt->id) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                answer: answerToSubmit,
                question_index: {{ $currentQuestionIndex }}
            })
        })
        .then(response => response.json())
        .then(data => {
            // Move to next question or submit exam
            const nextQuestionIndex = {{ $currentQuestionIndex }} + 1;
            if (nextQuestionIndex >= {{ $totalQuestions }}) {
                window.location.href = `{{ route('student.exam.submit', $attempt->id) }}`;
            } else {
                window.location.href = `{{ route('student.exam.take', $attempt->id) }}?question=${nextQuestionIndex}`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Fallback redirect
            const nextQuestionIndex = {{ $currentQuestionIndex }} + 1;
            if (nextQuestionIndex >= {{ $totalQuestions }}) {
                window.location.href = `{{ route('student.exam.submit', $attempt->id) }}`;
            } else {
                window.location.href = `{{ route('student.exam.take', $attempt->id) }}?question=${nextQuestionIndex}`;
            }
        });
    }

    // Submit answer and move to next question
    function submitAnswer() {
        if (isSubmitting) return;
        
        const form = document.getElementById('answerForm');
        const formData = new FormData(form);
        const answer = formData.get('answer');

        if (!answer && answer !== '0') {
            showWarning('Please select an answer before proceeding.');
            return;
        }

        isSubmitting = true;
        const submitBtn = document.querySelector('button[onclick="submitAnswer()"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitBtn.disabled = true;

        fetch(`{{ route('student.exam.answer', $attempt->id) }}`, {
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
                    window.location.href = `{{ route('student.exam.submit', $attempt->id) }}`;
                } else {
                    window.location.href = `{{ route('student.exam.take', $attempt->id) }}?question={{ $currentQuestionIndex + 1 }}`;
                }
            } else {
                showWarning(data.message || 'Error submitting answer. Please try again.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                isSubmitting = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showWarning('Error submitting answer. Please try again.');
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            isSubmitting = false;
        });
    }

    // Auto-submit when time runs out
    function autoSubmitExam() {
        if (examSubmitted) return;
        
        examSubmitted = true;
        clearInterval(timerInterval);
        isSubmitting = true;

        // Get current answer if any
        const formData = new FormData(document.getElementById('answerForm'));
        const currentAnswer = formData.get('answer');

        // Submit current answer first if exists
        if (currentAnswer || currentAnswer === '0') {
            fetch(`{{ route('student.exam.answer', $attempt->id) }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    answer: parseInt(currentAnswer),
                    question_index: {{ $currentQuestionIndex }}
                })
            })
            .finally(() => {
                window.location.href = `{{ route('student.exam.submit', $attempt->id) }}`;
            });
        } else {
            window.location.href = `{{ route('student.exam.submit', $attempt->id) }}`;
        }
    }

    // Modal functions
    function showWarning(message) {
        document.getElementById('warningMessage').textContent = message;
        document.getElementById('warningModal').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('warningModal').classList.add('scale-100');
        }, 10);
    }

    function closeWarning() {
        document.getElementById('warningModal').classList.remove('scale-100');
        setTimeout(() => {
            document.getElementById('warningModal').classList.add('hidden');
        }, 300);
    }

    // Start timers
    timerInterval = setInterval(updateTimer, 1000);

    // Auto-submit when time runs out initially
    if (timeRemaining <= 0) {
        autoSubmitExam();
    }

    // Check if any option is already selected
    document.addEventListener('DOMContentLoaded', function() {
        const selectedRadio = document.querySelector('input[name="answer"]:checked');
        if (selectedRadio) {
            selectedRadio.parentElement.classList.add('selected');
        }
        
        // Initial timer display
        updateTimer();
    });
</script>
@endsection
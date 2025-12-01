@extends('layouts.global')

@section('title', 'Exam Result - ' . $exam->title)

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <h3 class="text-2xl font-bold">Exam Result</h3>
            <p class="text-gray-600 mt-1">{{ $exam->title }}</p>
        </div>
    </div>

    <!-- Result Summary -->
    <div class="content-card rounded-lg p-8 text-center">
        <div class="max-w-md mx-auto">
            <div class="mb-6">
                @if($passed)
                    <i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>
                    <h2 class="text-2xl font-bold text-green-600 mb-2">Congratulations!</h2>
                    <p class="text-gray-600">You have passed the exam</p>
                @else
                    <i class="fas fa-times-circle text-6xl text-red-500 mb-4"></i>
                    <h2 class="text-2xl font-bold text-red-600 mb-2">Exam Completed</h2>
                    <p class="text-gray-600">Better luck next time</p>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $attempt->score ?? 0 }}/{{ $exam->total_marks }}</div>
                    <div class="text-sm text-gray-500">Your Score</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ $exam->passing_marks }}</div>
                    <div class="text-sm text-gray-500">Passing Marks</div>
                </div>
            </div>

            <div class="space-y-3 text-left bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between">
                    <span class="text-gray-600">Exam Date:</span>
                    <span class="font-medium">{{ $attempt->submitted_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Duration:</span>
                    <span class="font-medium">{{ $exam->duration_minutes }} minutes</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Questions:</span>
                    <span class="font-medium">{{ $exam->total_questions }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span class="font-medium {{ $passed ? 'text-green-600' : 'text-red-600' }}">
                        {{ $passed ? 'Passed' : 'Failed' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Percentage:</span>
                    <span class="font-medium {{ $passed ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format(($attempt->score / $exam->total_marks) * 100, 1) }}%
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Results -->
    <div class="content-card rounded-lg p-6">
        <h4 class="text-lg font-semibold mb-4">Exam Summary</h4>
        <div class="space-y-4">
            @php
                $questions = json_decode($exam->questions, true) ?? [];
                $answers = $attempt->answers ?? [];
                $correctAnswers = 0;
            @endphp
            
            @foreach($questions as $index => $question)
                @php
                    $userAnswer = $answers[$index] ?? null;
                    $isCorrect = $userAnswer === $question['correct_answer'];
                    if ($isCorrect) $correctAnswers++;
                @endphp
                <div class="border border-gray-200 rounded-lg p-4 {{ $isCorrect ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    <div class="flex justify-between items-start mb-3">
                        <h5 class="font-semibold">Question {{ $index + 1 }}</h5>
                        <span class="text-sm {{ $isCorrect ? 'text-green-600' : 'text-red-600' }}">
                            {{ $isCorrect ? 'Correct' : 'Incorrect' }}
                        </span>
                    </div>
                    
                    <p class="text-gray-800 mb-3">{{ $question['question'] }}</p>
                    
                    <div class="space-y-2">
                        @foreach($question['options'] as $optionIndex => $option)
                            <div class="flex items-center p-2 rounded 
                                {{ $optionIndex == $question['correct_answer'] ? 'bg-green-100 border border-green-300' : 
                                   ($optionIndex == $userAnswer ? 'bg-red-100 border border-red-300' : 'bg-gray-100') }}">
                                <span class="font-medium mr-3 
                                    {{ $optionIndex == $question['correct_answer'] ? 'text-green-600' : 
                                       ($optionIndex == $userAnswer ? 'text-red-600' : 'text-gray-600') }}">
                                    {{ chr(65 + $optionIndex) }})
                                </span>
                                <span class="{{ $optionIndex == $question['correct_answer'] ? 'text-green-800 font-semibold' : 
                                               ($optionIndex == $userAnswer ? 'text-red-800' : 'text-gray-700') }}">
                                    {{ $option }}
                                    @if($optionIndex == $question['correct_answer'])
                                        <span class="ml-2 text-green-600 text-sm">(Correct Answer)</span>
                                    @endif
                                    @if($optionIndex == $userAnswer && !$isCorrect)
                                        <span class="ml-2 text-red-600 text-sm">(Your Answer)</span>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($isCorrect)
                        <div class="mt-2 text-sm text-green-600">
                            <i class="fas fa-check mr-1"></i>You earned {{ $question['marks'] ?? 1 }} mark(s)
                        </div>
                    @else
                        <div class="mt-2 text-sm text-red-600">
                            <i class="fas fa-times mr-1"></i>You didn't earn marks for this question
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ $correctAnswers }}</div>
                    <div class="text-sm text-gray-600">Correct Answers</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-red-600">{{ count($questions) - $correctAnswers }}</div>
                    <div class="text-sm text-gray-600">Incorrect Answers</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-purple-600">{{ count($questions) }}</div>
                    <div class="text-sm text-gray-600">Total Questions</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-green-600">{{ number_format(($correctAnswers / count($questions)) * 100, 1) }}%</div>
                    <div class="text-sm text-gray-600">Accuracy</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="content-card rounded-lg p-6">
        <div class="flex justify-center space-x-4">
            <a href="{{ route('student.exams') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                Back to Exams
            </a>
            <a href="{{ route('admin.dashboard') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                Go to Dashboard
            </a>
            @if(!$passed)
                <button onclick="retryExam()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                    Retry Exam
                </button>
            @endif
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
    function retryExam() {
        if (confirm('Are you sure you want to retry this exam? Your previous attempt will be archived.')) {
            window.location.href = "{{ route('exam.start', $exam->id) }}";
        }
    }
</script>
@endsection
@extends('layouts.app')

@section('title', 'Exam Details - ' . $exam->title)

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold">Exam Details</h3>
                    <p class="text-gray-600 mt-1">{{ $exam->title }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.exams.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Exams
                    </a>
                    <a href="{{ route('admin.exams.edit', $exam) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-edit mr-2"></i>Edit Exam
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Basic Info -->
        <div class="content-card rounded-lg p-6 lg:col-span-2">
            <h4 class="text-lg font-semibold mb-4">Exam Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Title</label>
                    <p class="text-sm text-gray-900">{{ $exam->title }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Description</label>
                    <p class="text-sm text-gray-900">{{ $exam->description }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Class</label>
                    <p class="text-sm text-gray-900">{{ $exam->class->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Subject</label>
                    <p class="text-sm text-gray-900">{{ $exam->subject->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Exam Date</label>
                    <p class="text-sm text-gray-900">{{ $exam->exam_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Duration</label>
                    <p class="text-sm text-gray-900">{{ $exam->duration_minutes }} minutes</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Total Marks</label>
                    <p class="text-sm text-gray-900">{{ $exam->total_marks }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Passing Marks</label>
                    <p class="text-sm text-gray-900">{{ $exam->passing_marks }}</p>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="content-card rounded-lg p-6">
            <h4 class="text-lg font-semibold mb-4">Exam Statistics</h4>
            <div class="space-y-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ $exam->total_questions }}</div>
                    <div class="text-sm text-gray-600">Total Questions</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ $exam->applicants_count }}</div>
                    <div class="text-sm text-gray-600">Total Applicants</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    @php
                        $statusColors = [
                            'upcoming' => 'bg-blue-100 text-blue-800',
                            'ongoing' => 'bg-green-100 text-green-800',
                            'completed' => 'bg-gray-100 text-gray-800',
                            'cancelled' => 'bg-red-100 text-red-800'
                        ];
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$exam->status] }}">
                        {{ ucfirst($exam->status) }}
                    </span>
                    <div class="text-sm text-gray-600 mt-1">Status</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions -->
    <div class="content-card rounded-lg p-6">
        <h4 class="text-lg font-semibold mb-4">Exam Questions ({{ $exam->total_questions }})</h4>
        
        @php
            // Safely decode questions with null check
            $questions = json_decode($exam->questions, true) ?? [];
        @endphp
        
        @if(count($questions) > 0)
            <div class="space-y-6">
                @foreach($questions as $index => $question)
                    <div class="border border-gray-200 rounded-lg p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h5 class="font-semibold text-lg">Question {{ $index + 1 }}</h5>
                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">
                                {{ $question['marks'] ?? 1 }} mark(s)
                            </span>
                        </div>
                        
                        <p class="text-gray-800 mb-4 text-lg">{{ $question['question'] ?? 'No question text' }}</p>

                        <div class="space-y-2">
                            @if(isset($question['options']) && is_array($question['options']))
                                @foreach($question['options'] as $optionIndex => $option)
                                    <div class="flex items-center p-3 border rounded-lg {{ isset($question['correct_answer']) && $question['correct_answer'] == $optionIndex ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                                        <span class="font-medium mr-3 {{ isset($question['correct_answer']) && $question['correct_answer'] == $optionIndex ? 'text-green-600' : 'text-gray-600' }}">
                                            {{ chr(65 + $optionIndex) }})
                                        </span>
                                        <span class="{{ isset($question['correct_answer']) && $question['correct_answer'] == $optionIndex ? 'text-green-800 font-semibold' : 'text-gray-700' }}">
                                            {{ $option ?? 'No option text' }}
                                            @if(isset($question['correct_answer']) && $question['correct_answer'] == $optionIndex)
                                                <span class="ml-2 text-green-600 text-sm">(Correct Answer)</span>
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-yellow-600 bg-yellow-50 p-3 rounded border border-yellow-200">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    No options available for this question.
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-question-circle text-4xl mb-4 text-gray-300"></i>
                <p class="text-lg">No questions found</p>
                <p class="text-sm mt-2">This exam doesn't have any questions yet.</p>
                <a href="{{ route('admin.exams.edit', $exam) }}" 
                   class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors">
                    Add Questions
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="content-card rounded-lg p-6">
        <h4 class="text-lg font-semibold mb-4">Quick Actions</h4>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.exams.applicants', $exam) }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                <i class="fas fa-users mr-2"></i>View Applicants ({{ $exam->applicants_count }})
            </a>
            <a href="{{ route('admin.exams.results', $exam) }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                <i class="fas fa-chart-bar mr-2"></i>View Results
            </a>
            <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center"
                        onclick="return confirm('Are you sure you want to delete this exam? This will also delete all related attempts.')">
                    <i class="fas fa-trash mr-2"></i>Delete Exam
                </button>
            </form>
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
@endsection
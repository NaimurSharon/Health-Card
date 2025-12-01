@extends('layouts.global')

@section('title', 'My Scholarship Exams')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <h3 class="text-2xl font-bold">My Scholarship Exams</h3>
            <p class="text-gray-600 mt-1">Available exams for today</p>
        </div>
    </div>

    <!-- Exams Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($exams as $exam)
            <div class="content-card rounded-lg p-6 shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-lg font-semibold text-gray-900">{{ $exam->title }}</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Active
                    </span>
                </div>
                
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $exam->description }}</p>
                
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Duration:</span>
                        <span class="font-medium">{{ $exam->duration_minutes }} minutes</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Total Marks:</span>
                        <span class="font-medium">{{ $exam->total_marks }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Passing Marks:</span>
                        <span class="font-medium">{{ $exam->passing_marks }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Questions:</span>
                        <span class="font-medium">{{ $exam->total_questions }}</span>
                    </div>
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('exam.start', $exam->id) }}" 
                       class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Start Exam
                    </a>
                    <button onclick="showExamDetails({{ $exam->id }})" 
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full content-card rounded-lg p-8 text-center">
                <i class="fas fa-graduation-cap text-4xl mb-4 text-gray-300"></i>
                <p class="text-lg text-gray-500">No active exams available</p>
                <p class="text-sm text-gray-400 mt-2">You don't have any scheduled exams for today.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($exams->hasPages())
        <div class="content-card rounded-lg p-4">
            {{ $exams->links() }}
        </div>
    @endif
</div>

<!-- Exam Details Modal -->
<div id="examModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="content-card rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold" id="modalTitle">Exam Details</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modalContent">
            <!-- Content will be loaded via AJAX -->
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

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    function showExamDetails(examId) {
        fetch(`/student/scholarship-exams/${examId}/details`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalTitle').textContent = data.title;
                document.getElementById('modalContent').innerHTML = `
                    <div class="space-y-3">
                        <p class="text-sm text-gray-600">${data.description}</p>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div><strong>Duration:</strong> ${data.duration_minutes} minutes</div>
                            <div><strong>Total Marks:</strong> ${data.total_marks}</div>
                            <div><strong>Passing Marks:</strong> ${data.passing_marks}</div>
                            <div><strong>Questions:</strong> ${data.total_questions}</div>
                        </div>
                        <div class="mt-4">
                            <strong class="block mb-2">Instructions:</strong>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>• Each question has 4 options</li>
                                <li>• Select only one answer per question</li>
                                <li>• Timer will be shown during the exam</li>
                                <li>• Exam auto-submits when time ends</li>
                                <li>• Cannot go back to previous questions</li>
                            </ul>
                        </div>
                    </div>
                `;
                document.getElementById('examModal').classList.remove('hidden');
            });
    }

    function closeModal() {
        document.getElementById('examModal').classList.add('hidden');
    }
</script>
@endsection
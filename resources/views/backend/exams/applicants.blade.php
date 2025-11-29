@extends('layouts.app')

@section('title', 'Exam Applicants - ' . $exam->title)

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold">Exam Applicants</h3>
                    <p class="text-gray-600 mt-1">{{ $exam->title }}</p>
                </div>
                <a href="{{ route('admin.exams.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Exams
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="content-card rounded-lg p-6 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $attempts->total() }}</div>
            <div class="text-sm text-gray-500">Total Applicants</div>
        </div>
        <div class="content-card rounded-lg p-6 text-center">
            <div class="text-2xl font-bold text-green-600">
                {{ $attempts->where('status', 'graded')->where('score', '>=', $exam->passing_marks)->count() }}
            </div>
            <div class="text-sm text-gray-500">Passed</div>
        </div>
        <div class="content-card rounded-lg p-6 text-center">
            <div class="text-2xl font-bold text-red-600">
                {{ $attempts->where('status', 'graded')->where('score', '<', $exam->passing_marks)->count() }}
            </div>
            <div class="text-sm text-gray-500">Failed</div>
        </div>
        <div class="content-card rounded-lg p-6 text-center">
            <div class="text-2xl font-bold text-yellow-600">
                {{ $attempts->where('status', 'in_progress')->count() }}
            </div>
            <div class="text-sm text-gray-500">In Progress</div>
        </div>
    </div>

    <!-- Applicants Table -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Started At</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted At</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60">
                    @forelse($attempts as $attempt)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $attempt->student->user->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $attempt->student->student_id ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attempt->started_at ? $attempt->started_at->format('M d, Y H:i') : 'Not started' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $attempt->submitted_at ? $attempt->submitted_at->format('M d, Y H:i') : 'Not submitted' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($attempt->score !== null)
                                    <span class="font-semibold {{ $attempt->score >= $exam->passing_marks ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $attempt->score }}/{{ $exam->total_marks }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'in_progress' => 'bg-yellow-100 text-yellow-800',
                                        'submitted' => 'bg-blue-100 text-blue-800',
                                        'graded' => $attempt->score >= $exam->passing_marks ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$attempt->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $attempt->status)) }}
                                    @if($attempt->status === 'graded')
                                        ({{ $attempt->score >= $exam->passing_marks ? 'Passed' : 'Failed' }})
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    @if($attempt->status === 'graded')
                                        <a href="{{ route('exam.result', $attempt) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors p-2 rounded-full hover:bg-blue-50" title="View Result">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    <form action="{{ route('admin.exams.attempts.destroy', $attempt) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-full hover:bg-red-50" 
                                                title="Delete Attempt"
                                                onclick="return confirm('Are you sure you want to delete this attempt?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-users text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg">No applicants found</p>
                                <p class="text-sm mt-2">No students have attempted this exam yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($attempts->hasPages())
            <div class="px-6 py-4 border-t border-gray-200/60">
                {{ $attempts->links() }}
            </div>
        @endif
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
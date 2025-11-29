@extends('layouts.app')

@section('title', 'Edit Scholarship Exam - ' . $exam->title)

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <h3 class="text-2xl font-bold">Edit Scholarship Exam</h3>
            <p class="text-gray-600 mt-1">Update exam details and questions</p>
        </div>
    </div>

    <!-- Exam Form -->
    <div class="content-card rounded-lg p-6">
        @include('backend.exams.form', [
            'exam' => $exam,
            'classes' => $classes,
            'subjects' => $subjects,
            'action' => route('admin.exams.update', $exam),
            'method' => 'PUT'
        ])
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
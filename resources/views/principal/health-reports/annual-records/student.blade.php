@extends('layouts.principal')

@section('title', 'Annual Records - ' . $student->user->name)

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div
                class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">Annual Health Records - {{ $student->user->name }}</h3>
                    <p class="text-gray-200 mt-1">View growth and development history</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('principal.health.annual-records.create') }}"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>Add Record
                    </a>
                    <a href="{{ route('principal.health.reports.index') }}"
                        class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Student Info -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <div class="text-xs font-medium text-blue-900 mb-1">Student ID</div>
                    <div class="text-base font-bold text-blue-700">{{ $student->student_id ?? 'N/A' }}</div>
                </div>

                <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                    <div class="text-xs font-medium text-green-900 mb-1">Class</div>
                    <div class="text-base font-bold text-green-700">{{ $student->class->name ?? 'N/A' }}</div>
                </div>

                <div class="bg-purple-50 rounded-lg p-3 border border-purple-200">
                    <div class="text-xs font-medium text-purple-900 mb-1">Roll Number</div>
                    <div class="text-base font-bold text-purple-700">{{ $student->roll_number ?? 'N/A' }}</div>
                </div>

                <div class="bg-orange-50 rounded-lg p-3 border border-orange-200">
                    <div class="text-xs font-medium text-orange-900 mb-1">Date of Birth</div>
                    <div class="text-base font-bold text-orange-700">
                        @if($student->user->date_of_birth)
                            {{ $student->user->date_of_birth->format('M j, Y') }}
                            ({{ $student->user->date_of_birth->age }} years)
                        @else
                            N/A
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Growth Chart -->
        @if($annualRecords->count() > 1)
            <div class="content-card rounded-lg p-4 sm:p-6">
                <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4 flex items-center">
                    <i class="fas fa-chart-line me-2"></i>Growth Chart
                </h4>
                <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                    <p class="text-gray-500 text-center">
                        <i class="fas fa-chart-bar text-3xl mb-2"></i><br>
                        Growth visualization would appear here<br>
                        <span class="text-sm">(Weight and height trends by age)</span>
                    </p>
                </div>
            </div>
        @endif

        <!-- Annual Records -->
        <div class="space-y-4">
            @forelse($annualRecords as $record)
                <div class="content-card rounded-lg p-4 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <!-- Record Info -->
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-3">
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">Age {{ $record->age }} Years</h4>
                                    <div class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
                                        <span class="flex items-center">
                                            <i class="fas fa-calendar-day mr-1"></i>
                                            {{ $record->created_at->format('M j, Y') }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-user-tie mr-1"></i>
                                            Recorded by: {{ $record->recordedBy->name ?? 'System' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 sm:mt-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                        {{ $record->general_health == 'good' || $record->general_health == 'excellent' ? 'bg-green-100 text-green-800' :
                ($record->general_health == 'fair' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($record->general_health) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Measurements -->
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-sm mb-4">
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <div class="text-xs font-medium text-blue-900 mb-1">Weight</div>
                                    <div class="text-base font-bold text-blue-700">{{ $record->weight }} kg</div>
                                </div>
                                <div class="bg-green-50 rounded-lg p-3">
                                    <div class="text-xs font-medium text-green-900 mb-1">Height</div>
                                    <div class="text-base font-bold text-green-700">{{ $record->height }} cm</div>
                                </div>
                                <div class="bg-purple-50 rounded-lg p-3">
                                    <div class="text-xs font-medium text-purple-900 mb-1">Head Circumference</div>
                                    <div class="text-base font-bold text-purple-700">{{ $record->head_circumference ?? 'N/A' }}
                                        cm</div>
                                </div>
                                <div class="bg-orange-50 rounded-lg p-3">
                                    <div class="text-xs font-medium text-orange-900 mb-1">BMI</div>
                                    <div class="text-base font-bold text-orange-700">
                                        @php
                                            if ($record->height > 0) {
                                                $heightInMeters = $record->height / 100;
                                                $bmi = $record->weight / ($heightInMeters * $heightInMeters);
                                                echo number_format($bmi, 1);
                                            } else {
                                                echo 'N/A';
                                            }
                                        @endphp
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="space-y-3">
                                @if($record->development_notes)
                                    <div>
                                        <div class="text-sm font-medium text-gray-700 mb-1">Development Notes</div>
                                        <div class="text-sm text-gray-600 bg-gray-50 rounded p-3">{{ $record->development_notes }}
                                        </div>
                                    </div>
                                @endif
                                @if($record->vaccination_status)
                                    <div>
                                        <div class="text-sm font-medium text-gray-700 mb-1">Vaccination Status</div>
                                        <div class="text-sm text-gray-600 bg-gray-50 rounded p-3">{{ $record->vaccination_status }}
                                        </div>
                                    </div>
                                @endif
                                @if($record->nutrition_notes)
                                    <div>
                                        <div class="text-sm font-medium text-gray-700 mb-1">Nutrition Notes</div>
                                        <div class="text-sm text-gray-600 bg-gray-50 rounded p-3">{{ $record->nutrition_notes }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex items-center justify-end lg:justify-start space-x-2 lg:flex-col lg:space-x-0 lg:space-y-2">
                            <a href="{{ route('principal.health.annual-records.edit', $record) }}"
                                class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                <i class="fas fa-edit text-sm"></i>
                                <span class="hidden lg:inline ml-2">Edit</span>
                            </a>
                            <form action="{{ route('principal.health.annual-records.destroy', $record) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="flex items-center justify-center w-8 h-8 lg:w-full lg:px-4 lg:py-2 text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                                    onclick="return confirm('Are you sure you want to delete this annual record?')">
                                    <i class="fas fa-trash text-sm"></i>
                                    <span class="hidden lg:inline ml-2">Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="content-card rounded-lg p-8 text-center">
                    <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No annual health records found</p>
                    <a href="{{ route('principal.health.annual-records.create') }}"
                        class="inline-flex items-center mt-4 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-plus mr-2"></i>Add first annual record
                    </a>
                </div>
            @endforelse
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
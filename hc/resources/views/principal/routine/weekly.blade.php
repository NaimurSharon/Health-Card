@extends('layouts.principal')

@section('title', 'Weekly Routine')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold">Weekly Class Routine</h3>
                <p class="text-gray-600 mt-1">View and manage weekly class schedules</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('principal.routine.index') }}" 
                   class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-calendar-alt mr-2"></i>Daily View
                </a>
                <a href="{{ route('principal.routine.create') }}" 
                   class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>Add Routine
                </a>
            </div>
        </div>
    </div>

    <!-- Class Selector -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        <form method="GET" action="{{ route('principal.routine.weekly') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                    <select name="class_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Section</label>
                    <select name="section_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" {{ !$selectedClass ? 'disabled' : '' }}>
                        <option value="">All Sections</option>
                        @if($selectedClass)
                            @foreach($classes->find($selectedClass)->sections ?? [] as $section)
                                <option value="{{ $section->id }}" {{ $selectedSection == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        Load Routine
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Weekly Routine Display -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        @if($selectedClass && count($routines) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            @foreach(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'] as $day)
                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ ucfirst($day) }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $timeSlots = [
                                '08:00-08:45', '08:45-09:30', '09:30-10:15', '10:15-10:45', 
                                '10:45-11:30', '11:30-12:15', '12:15-13:00'
                            ];
                        @endphp
                        
                        @foreach($timeSlots as $slotIndex => $timeSlot)
                        <tr>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $timeSlot }}
                            </td>
                            @foreach(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'] as $day)
                            <td class="px-3 py-3 text-center">
                                @php
                                    $dayRoutines = $routines->get($day, collect());
                                    $periodRoutine = null;
                                    
                                    if ($selectedSection && $dayRoutines->has($selectedSection)) {
                                        $periodRoutine = $dayRoutines[$selectedSection]->firstWhere('period', $slotIndex + 1);
                                    } else {
                                        // If no specific section or section not found, get first available
                                        foreach ($dayRoutines as $sectionRoutines) {
                                            $routine = $sectionRoutines->firstWhere('period', $slotIndex + 1);
                                            if ($routine) {
                                                $periodRoutine = $routine;
                                                break;
                                            }
                                        }
                                    }
                                @endphp
                                @if($periodRoutine)
                                <div class="bg-blue-50 border border-blue-200 rounded p-2 mx-1">
                                    <div class="font-medium text-blue-900 text-xs">{{ $periodRoutine->subject->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-blue-600">{{ $periodRoutine->teacher->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-blue-500">{{ $periodRoutine->room ?? 'N/A' }}</div>
                                    @if($periodRoutine->section && !$selectedSection)
                                    <div class="text-xs text-blue-400 mt-1">
                                        Sec: {{ $periodRoutine->section->name }}
                                    </div>
                                    @endif
                                </div>
                                @else
                                <div class="text-gray-400 text-xs">-</div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Legend -->
            @if(!$selectedSection)
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    Showing routines from all sections. Select a specific section to view detailed routine.
                </p>
            </div>
            @endif
        @elseif($selectedClass)
            <div class="text-center py-12">
                <i class="fas fa-calendar-week text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No routine found for the selected class</p>
                <a href="{{ route('principal.routine.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mt-4">
                    <i class="fas fa-plus mr-2"></i>Create Routine
                </a>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-week text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Select a class to view the weekly routine</p>
                <p class="text-sm text-gray-400 mt-2">Choose a class from the dropdown above</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.querySelector('select[name="class_id"]');
    const sectionSelect = document.querySelector('select[name="section_id"]');

    classSelect.addEventListener('change', function() {
        const classId = this.value;
        sectionSelect.innerHTML = '<option value="">All Sections</option>';
        sectionSelect.disabled = !classId;
        
        if (classId) {
            sectionSelect.disabled = true;
            sectionSelect.innerHTML = '<option value="">Loading sections...</option>';
            
            fetch(`/principal/routine/get-sections/${classId}`)
                .then(response => response.json())
                .then(sections => {
                    sectionSelect.innerHTML = '<option value="">All Sections</option>';
                    sections.forEach(section => {
                        const option = document.createElement('option');
                        option.value = section.id;
                        option.textContent = section.name;
                        sectionSelect.appendChild(option);
                    });
                    sectionSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error loading sections:', error);
                    sectionSelect.innerHTML = '<option value="">Error loading sections</option>';
                });
        }
    });
});
</script>

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

    @media (max-width: 768px) {
        .overflow-x-auto {
            font-size: 0.7rem;
        }
        
        .px-3 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        .py-3 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        
        .mx-1 {
            margin-left: 0.1rem;
            margin-right: 0.1rem;
        }
    }

    @media (max-width: 640px) {
        .text-xs {
            font-size: 0.65rem;
        }
    }
</style>
@endsection
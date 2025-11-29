@extends('layouts.principal')

@section('title', 'Class Routine')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold">Class Routine</h3>
                <p class="text-gray-600 mt-1">Manage class schedules and timetables</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('principal.routine.weekly') }}" 
                   class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-calendar-week mr-2"></i>Weekly View
                </a>
                <a href="{{ route('principal.routine.create') }}" 
                   class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>Add Routine
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="content-card rounded-lg p-4 bg-green-50 border border-green-200">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="content-card rounded-lg p-4 bg-red-50 border border-red-200">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
            <span class="text-red-800">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Class Selector -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        <form method="GET" action="{{ route('principal.routine.index') }}" class="space-y-4">
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

    <!-- Routine Display -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        @if($selectedClass && $routines->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day/Period</th>
                            @for($i = 1; $i <= 8; $i++)
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Period {{ $i }}
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach(['saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday'] as $day)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 capitalize">
                                {{ $day }}
                            </td>
                            @for($period = 1; $period <= 8; $period++)
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $routine = $routines->get($day, collect())->firstWhere('period', $period);
                                    @endphp
                                    @if($routine)
                                    <div class="bg-blue-50 border border-blue-200 rounded p-2 mx-1">
                                        <div class="font-medium text-blue-900 text-xs">{{ $routine->subject->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-blue-600">{{ $routine->teacher->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-blue-500">{{ $routine->room ?? 'N/A' }}</div>
                                        <div class="text-xs text-blue-400">
                                            {{ \Carbon\Carbon::parse($routine->start_time)->format('h:i A') }}
                                        </div>
                                        <div class="mt-1 flex justify-center space-x-1">
                                            <a href="{{ route('principal.routine.edit', $routine->id) }}" 
                                               class="text-green-600 hover:text-green-900 text-xs">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('principal.routine.destroy', $routine->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-xs"
                                                        onclick="return confirm('Are you sure you want to delete this routine?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @else
                                    <div class="text-gray-400 text-xs">-</div>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif($selectedClass)
            <div class="text-center py-12">
                <i class="fas fa-calendar-alt text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No routine found for the selected class</p>
                <a href="{{ route('principal.routine.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 mt-4">
                    <i class="fas fa-plus mr-2"></i>Create Routine
                </a>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-calendar-alt text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Select a class to view routine</p>
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
        
        .px-4 {
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
</style>
@endsection
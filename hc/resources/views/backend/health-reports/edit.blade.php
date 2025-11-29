@extends('layouts.app')

@section('title', 'Health Report - ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Health Report - {{ $user->name }}</h3>
            <div class="flex space-x-3">
                <button type="button" onclick="saveHealthReport()" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- Age-based Health Tracking Table -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
            <i class="fas fa-chart-line me-2"></i>Health Tracking by Age (4-18 years)
        </h4>
        
        @php
            // Get all health reports for this student
            $allHealthReports = \App\Models\StudentHealthReport::where('student_id', $student->id)
                ->with(['reportData.field'])
                ->orderBy('checkup_date', 'asc')
                ->get();
            
            // Calculate age for each report
            $reportsWithAge = [];
            foreach ($allHealthReports as $report) {
                $age = $user->date_of_birth ? 
                    $report->checkup_date->diffInYears(\Carbon\Carbon::parse($user->date_of_birth)) : 
                    null;
                
                if ($age && $age >= 4 && $age <= 18) {
                    $reportsWithAge[$age] = $report;
                }
            }
            
            ksort($reportsWithAge);
            $currentAge = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : null;
        @endphp

        <div class="mb-4 flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <strong>Current Age:</strong> 
                @if($currentAge)
                    {{ $currentAge }} years
                @else
                    Age not available
                @endif
            </div>
            <div class="text-sm text-gray-600">
                <strong>Tracking Period:</strong> 4-18 years
            </div>
        </div>

        @if(count($reportsWithAge) > 0)
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">Age (Years)</th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">Checkup Date</th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">ওজন (kg)</th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">উচ্চতা (cm)</th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @for($age = 4; $age <= 18; $age++)
                        @php
                            $report = $reportsWithAge[$age] ?? null;
                            $weight = $report ? ($report->getFieldValue('weight_kg') ?? $report->getFieldValue('weight')) : null;
                            $height = $report ? ($report->getFieldValue('height_cm') ?? $report->getFieldValue('height')) : null;
                        @endphp
                        <tr class="hover:bg-gray-50 {{ $report && $report->id == $healthReport->id ? 'bg-blue-50' : '' }}">
                            <td class="border border-gray-300 px-4 py-3 text-sm font-medium text-gray-900">
                                {{ $age }} years
                                @if($report && $report->id == $healthReport->id)
                                    <span class="ml-1 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Editing
                                    </span>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-sm text-gray-700">
                                @if($report)
                                    {{ $report->checkup_date->format('M j, Y') }}
                                @else
                                    <span class="text-gray-400">No data</span>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-sm text-gray-700">
                                @if($weight)
                                    <span class="font-semibold">{{ $weight }} kg</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-sm text-gray-700">
                                @if($height)
                                    <span class="font-semibold">{{ $height }} cm</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-sm text-gray-700">
                                @if($report)
                                    <a href="{{ route('admin.health-reports.edit', $report) }}" 
                                       class="text-blue-600 hover:text-blue-900 text-sm font-medium mr-3">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                    <button onclick="loadAgeData({{ $age }})" 
                                            class="text-green-600 hover:text-green-900 text-sm font-medium">
                                        <i class="fas fa-sync-alt mr-1"></i>Load
                                    </button>
                                @else
                                    <button onclick="createAgeReport({{ $age }})" 
                                            class="text-green-600 hover:text-green-900 text-sm font-medium">
                                        <i class="fas fa-plus mr-1"></i>Create
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
            <i class="fas fa-clipboard-list text-4xl text-gray-300 mb-3"></i>
            <h5 class="text-lg font-medium text-gray-900 mb-2">No Health Data Available</h5>
            <p class="text-gray-600">Health tracking data will appear here as annual checkups are recorded for ages 4-18.</p>
        </div>
        @endif
    </div>

    <!-- Student Information -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Student Information</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <div class="text-sm font-medium text-blue-900 mb-1">Student ID</div>
                <div class="text-lg font-bold text-blue-700">{{ $student->student_id ?? 'N/A' }}</div>
            </div>
            
            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <div class="text-sm font-medium text-green-900 mb-1">Class</div>
                <div class="text-lg font-bold text-green-700">{{ $student->class->name ?? 'N/A' }}</div>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                <div class="text-sm font-medium text-purple-900 mb-1">Roll Number</div>
                <div class="text-lg font-bold text-purple-700">{{ $student->roll_number ?? 'N/A' }}</div>
            </div>
            
            <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                <div class="text-sm font-medium text-orange-900 mb-1">Blood Group</div>
                <div class="text-lg font-bold text-orange-700">{{ $student->blood_group ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
    
    <!-- Rest of the admin form remains the same -->
    <!-- Health Card Status, Health Report Form sections... -->
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
    // Function to load data for a specific age
    function loadAgeData(age) {
        // Calculate the checkup date for this age
        const dob = '{{ $user->date_of_birth ? $user->date_of_birth->format("Y-m-d") : "" }}';
        if (!dob) {
            alert('Student date of birth is required to calculate age-based data.');
            return;
        }
        
        const checkupDate = new Date(dob);
        checkupDate.setFullYear(checkupDate.getFullYear() + age);
        
        // Set the checkup date in the form
        document.getElementById('checkup_date').value = checkupDate.toISOString().split('T')[0];
        
        // You could also load previous data for this age if it exists
        // This would require an API endpoint to fetch age-specific data
        console.log('Loading data for age:', age, 'Checkup date:', checkupDate.toISOString().split('T')[0]);
        
        // Scroll to the form
        document.getElementById('healthReportForm').scrollIntoView({ behavior: 'smooth' });
    }
    
    // Function to create a new report for a specific age
    function createAgeReport(age) {
        const dob = '{{ $user->date_of_birth ? $user->date_of_birth->format("Y-m-d") : "" }}';
        if (!dob) {
            alert('Student date of birth is required to create age-based report.');
            return;
        }
        
        const checkupDate = new Date(dob);
        checkupDate.setFullYear(checkupDate.getFullYear() + age);
        
        // Set the checkup date in the form
        document.getElementById('checkup_date').value = checkupDate.toISOString().split('T')[0];
        document.getElementById('checked_by').value = '{{ auth()->user()->name }}';
        
        // Clear other fields to start fresh for this age
        const inputs = document.querySelectorAll('#healthReportForm input, #healthReportForm select, #healthReportForm textarea');
        inputs.forEach(input => {
            if (input.id !== 'checkup_date' && input.id !== 'checked_by' && input.name !== '_token') {
                if (input.type === 'checkbox') {
                    input.checked = false;
                } else if (input.type === 'select-one') {
                    input.selectedIndex = 0;
                } else {
                    input.value = '';
                }
            }
        });
        
        // Scroll to the form
        document.getElementById('healthReportForm').scrollIntoView({ behavior: 'smooth' });
        
        alert(`Creating new health report for age ${age}. Fill in the data below and click "Save Changes".`);
    }

    // Rest of the existing JavaScript functions remain the same
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate BMI when weight or height changes
        function calculateBMI() {
            const weightInput = document.getElementById('weight_kg_{{ $weightField->id ?? '' }}');
            const heightInput = document.getElementById('height_cm_{{ $heightField->id ?? '' }}');
            
            if (weightInput && heightInput) {
                const weight = parseFloat(weightInput.value);
                const height = parseFloat(heightInput.value);
                
                if (weight && height) {
                    const heightInMeters = height / 100;
                    const bmi = weight / (heightInMeters * heightInMeters);
                    document.getElementById('bmiDisplay').textContent = bmi.toFixed(2);
                } else {
                    document.getElementById('bmiDisplay').textContent = '--';
                }
            }
        }
        
        // Add event listeners for weight and height inputs if they exist
        const weightInput = document.getElementById('weight_kg_{{ $weightField->id ?? '' }}');
        const heightInput = document.getElementById('height_cm_{{ $heightField->id ?? '' }}');
        
        if (weightInput) {
            weightInput.addEventListener('input', calculateBMI);
        }
        if (heightInput) {
            heightInput.addEventListener('input', calculateBMI);
        }
        
        // Initial BMI calculation if values exist
        @if($healthReport && $healthReport->getFieldValue('weight_kg') && $healthReport->getFieldValue('height_cm'))
            calculateBMI();
        @endif
    });
    
    // Save health report function remains the same
    async function saveHealthReport() {
        // ... existing saveHealthReport implementation ...
    }
    
    function showNotification(message, type) {
        // ... existing showNotification implementation ...
    }
</script>
@endsection
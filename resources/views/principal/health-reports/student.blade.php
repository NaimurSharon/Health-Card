@extends('layouts.principal')

@section('title', 'Health Report - ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold">Health Report - {{ $user->name }}</h3>
                <p class="text-gray-200 mt-1">Manage student health data and annual checkups</p>
            </div>
            <div class="flex space-x-3">
                <button type="button" onclick="saveHealthReport()" 
                       class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a href="{{ route('principal.health.reports.index') }}" 
                   class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- Annual Health Records -->
<div class="content-card rounded-lg p-4 sm:p-6">
    <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4 flex items-center justify-between">
        <span class="flex items-center">
            <i class="fas fa-chart-line me-2"></i>Annual Health Records
        </span>
        <a href="{{ route('principal.health.annual-records.create') }}" 
           class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
            <i class="fas fa-plus mr-1"></i>Add Record
        </a>
    </h4>
    
    @php
        $currentAge = $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->age : null;
    @endphp

    <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div class="text-sm text-gray-600">
            <strong>Student Age:</strong> 
            @if($currentAge)
                <span class="font-medium">{{ $currentAge }} years</span>
            @else
                <span class="text-yellow-600">Date of birth not set</span>
            @endif
        </div>
        <div class="text-sm text-gray-600">
            <strong>Total Records:</strong> 
            <span class="font-medium">{{ $annualRecords->count() }}</span>
        </div>
    </div>

    @if($annualRecords->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-50">
                    <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700">Age</th>
                    <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700">Record Date</th>
                    <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700">Weight</th>
                    <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700">Height</th>
                    <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700">Head Circ.</th>
                    <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700">Health Status</th>
                    <th class="border px-4 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($annualRecords as $record)
                <tr class="hover:bg-gray-50">
                    <td class="border px-4 py-3 text-sm font-medium text-gray-900">
                        {{ $record->age }} years
                    </td>
                    <td class="border px-4 py-3 text-sm text-gray-700">
                        {{ $record->created_at->format('M j, Y') }}
                    </td>
                    <td class="border px-4 py-3 text-sm text-gray-700">
                        <span class="font-semibold">{{ $record->weight }} kg</span>
                    </td>
                    <td class="border px-4 py-3 text-sm text-gray-700">
                        <span class="font-semibold">{{ $record->height }} cm</span>
                    </td>
                    <td class="border px-4 py-3 text-sm text-gray-700">
                        @if($record->head_circumference)
                            <span class="font-semibold">{{ $record->head_circumference }} cm</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="border px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $record->general_health == 'excellent' || $record->general_health == 'good' ? 'bg-green-100 text-green-800' : 
                               ($record->general_health == 'fair' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($record->general_health) }}
                        </span>
                    </td>
                    <td class="border px-4 py-3 text-sm text-gray-700">
                        <div class="flex space-x-2">
                            <a href="{{ route('principal.health.annual-records.edit', $record) }}" 
                               class="text-blue-600 hover:text-blue-900 text-sm font-medium inline-flex items-center"
                               title="Edit Record">
                                <i class="fas fa-edit mr-1"></i>
                            </a>
                            <form action="{{ route('principal.health.annual-records.destroy', $record) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900 text-sm font-medium inline-flex items-center"
                                        onclick="return confirm('Are you sure you want to delete this annual record?')"
                                        title="Delete Record">
                                    <i class="fas fa-trash mr-1"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Growth Summary -->
    @if($annualRecords->count() > 1)
    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
        <h5 class="text-sm font-medium text-gray-900 mb-3">Growth Summary</h5>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            @php
                $oldestRecord = $annualRecords->first();
                $latestRecord = $annualRecords->last();
                $ageDifference = $latestRecord->age - $oldestRecord->age;
                $weightGain = $latestRecord->weight - $oldestRecord->weight;
                $heightGain = $latestRecord->height - $oldestRecord->height;
                $weightPerYear = $ageDifference > 0 ? $weightGain / $ageDifference : 0;
                $heightPerYear = $ageDifference > 0 ? $heightGain / $ageDifference : 0;
            @endphp
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ number_format($weightGain, 1) }} kg</div>
                <div class="text-xs text-gray-600">Weight Gain ({{ $ageDifference }} years)</div>
                <div class="text-xs text-gray-500 mt-1">
                    Avg: {{ number_format($weightPerYear, 1) }} kg/year
                </div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ number_format($heightGain, 1) }} cm</div>
                <div class="text-xs text-gray-600">Height Gain ({{ $ageDifference }} years)</div>
                <div class="text-xs text-gray-500 mt-1">
                    Avg: {{ number_format($heightPerYear, 1) }} cm/year
                </div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $annualRecords->count() }}</div>
                <div class="text-xs text-gray-600">Records Available</div>
                <div class="text-xs text-gray-500 mt-1">
                    Ages {{ $oldestRecord->age }} to {{ $latestRecord->age }} years
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @else
    <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
        <i class="fas fa-heartbeat text-4xl text-gray-300 mb-3"></i>
        <h5 class="text-lg font-medium text-gray-900 mb-2">No Annual Health Records</h5>
        <p class="text-gray-600 mb-4">Annual health records track growth and development year by year.</p>
        <a href="{{ route('principal.health.annual-records.create') }}" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
            <i class="fas fa-plus mr-2"></i>Add First Annual Record
        </a>
    </div>
    @endif
</div>

    <!-- Student Information -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4">Student Information</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
                <div class="text-xs font-medium text-orange-900 mb-1">Blood Group</div>
                <div class="text-base font-bold text-orange-700">{{ $student->blood_group ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Health Card Status -->
    @php
        $healthCard = \App\Models\HealthCard::where('user_id', $user->id)->first();
    @endphp
    <div class="content-card rounded-lg p-4 sm:p-6">
        <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4 flex items-center justify-between">
            <span><i class="fas fa-id-card me-2"></i>Health Card Status</span>
            @if($healthCard)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $healthCard->status == 'active' ? 'bg-green-100 text-green-800' : 
                       ($healthCard->status == 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst($healthCard->status) }}
                </span>
            @endif
        </h4>
        
        @if($healthCard)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="text-sm font-medium text-gray-700 mb-1">Card Number</div>
                <div class="text-base font-semibold text-gray-900">{{ $healthCard->card_number }}</div>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-700 mb-1">Expiry Date</div>
                <div class="text-base font-semibold text-gray-900 {{ $healthCard->expiry_date < now() ? 'text-red-600' : '' }}">
                    {{ $healthCard->expiry_date->format('M j, Y') }}
                    @if($healthCard->expiry_date < now())
                        <span class="text-sm text-red-500">(Expired)</span>
                    @endif
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-4">
            <i class="fas fa-id-card text-3xl text-gray-300 mb-2"></i>
            <p class="text-gray-500">No health card issued yet</p>
        </div>
        @endif
    </div>

    <!-- Health Report Form -->
    <div class="content-card rounded-lg p-4 sm:p-6">
        <h4 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-3 mb-4 flex items-center">
            <i class="fas fa-clipboard-check me-2"></i>Health Report
        </h4>
        
        <form id="healthReportForm" method="POST" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="checkup_date" class="block text-sm font-medium text-gray-700 mb-2">Checkup Date</label>
                    <input type="date" id="checkup_date" name="checkup_date" 
                           value="{{ old('checkup_date', $healthReport->checkup_date ? $healthReport->checkup_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                
                <div>
                    <label for="checked_by" class="block text-sm font-medium text-gray-700 mb-2">Checked By</label>
                    <input type="text" id="checked_by" name="checked_by" 
                           value="{{ old('checked_by', $healthReport->checked_by ?? auth()->user()->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>

            <!-- Dynamic Fields by Category -->
            @foreach($categories as $category)
            <div class="border border-gray-200 rounded-lg p-4 sm:p-6">
                <h5 class="text-md font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200">
                    {{ $category->name }}
                    @if($category->description)
                        <p class="text-sm text-gray-600 font-normal mt-1">{{ $category->description }}</p>
                    @endif
                </h5>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($category->fields as $field)
                        @php
                            $fieldValue = $healthReport->reportData->where('field_id', $field->id)->first()->field_value ?? '';
                            $fieldId = $field->field_name . '_' . $field->id;
                        @endphp
                        
                        <div class="@if($field->field_type == 'textarea') md:col-span-2 @endif">
                            <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $field->label }}
                                @if($field->is_required)
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>
                            
                            @if($field->field_type == 'textarea')
                                <textarea id="{{ $fieldId }}" name="{{ $field->field_name }}" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                          placeholder="{{ $field->placeholder }}">{{ $fieldValue }}</textarea>
                            @elseif($field->field_type == 'select')
                                <select id="{{ $fieldId }}" name="{{ $field->field_name }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Select {{ $field->label }}</option>
                                    @if($field->options)
                                        @foreach($field->options as $option)
                                            <option value="{{ $option }}" {{ $fieldValue == $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            @elseif($field->field_type == 'checkbox')
                                <div class="flex items-center">
                                    <input type="checkbox" id="{{ $fieldId }}" name="{{ $field->field_name }}" 
                                           value="1" {{ $fieldValue == '1' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="{{ $fieldId }}" class="ml-2 text-sm text-gray-700">
                                        {{ $field->placeholder ?? 'Check if applicable' }}
                                    </label>
                                </div>
                            @elseif($field->field_type == 'date')
                                <input type="date" id="{{ $fieldId }}" name="{{ $field->field_name }}" 
                                       value="{{ $fieldValue }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="{{ $field->placeholder }}">
                            @elseif($field->field_type == 'number')
                                <input type="number" id="{{ $fieldId }}" name="{{ $field->field_name }}" 
                                       value="{{ $fieldValue }}" step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="{{ $field->placeholder }}">
                            @else
                                <input type="text" id="{{ $fieldId }}" name="{{ $field->field_name }}" 
                                       value="{{ $fieldValue }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="{{ $field->placeholder }}">
                            @endif
                            
                            @if($field->field_name == 'weight_kg' || $field->field_name == 'height_cm')
                                @php
                                    $otherField = $field->field_name == 'weight_kg' ? 'height_cm' : 'weight_kg';
                                    $otherValue = $healthReport->reportData->where('field.field_name', $otherField)->first()->field_value ?? '';
                                @endphp
                                @if($otherValue && $fieldValue)
                                    @php
                                        if($field->field_name == 'weight_kg') {
                                            $weight = $fieldValue;
                                            $height = $otherValue;
                                        } else {
                                            $weight = $otherValue;
                                            $height = $fieldValue;
                                        }
                                        
                                        if($height > 0) {
                                            $heightInMeters = $height / 100;
                                            $bmi = $weight / ($heightInMeters * $heightInMeters);
                                        }
                                    @endphp
                                    @if(isset($bmi))
                                    <div class="mt-2 text-sm">
                                        <span class="font-medium text-gray-700">BMI:</span>
                                        <span class="ml-2 font-bold 
                                            {{ $bmi < 18.5 ? 'text-yellow-600' : 
                                               ($bmi < 25 ? 'text-green-600' : 
                                               ($bmi < 30 ? 'text-orange-600' : 'text-red-600')) }}">
                                            {{ number_format($bmi, 1) }}
                                        </span>
                                        <span class="ml-2 text-gray-600">
                                            @if($bmi < 18.5) Underweight
                                            @elseif($bmi < 25) Normal
                                            @elseif($bmi < 30) Overweight
                                            @else Obese
                                            @endif
                                        </span>
                                    </div>
                                    @endif
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <button type="button" onclick="saveHealthReport()" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>Save Health Report
                </button>
                <a href="{{ route('principal.health.reports.index') }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
            </div>
        </form>
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
async function saveHealthReport() {
    const form = document.getElementById('healthReportForm');
    const formData = new FormData(form);
    
    try {
        const response = await fetch('{{ route("principal.health.reports.store-or-update", $user) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Health report saved successfully!', 'success');
            // Reload the page to show updated data
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showNotification(result.message || 'Failed to save health report.', 'error');
        }
    } catch (error) {
        showNotification('An error occurred while saving the health report.', 'error');
        console.error('Error:', error);
    }
}

function showNotification(message, type) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notification => notification.remove());
    
    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium flex items-center ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function loadAgeData(age) {
    const dob = '{{ $user->date_of_birth ? $user->date_of_birth->format("Y-m-d") : "" }}';
    if (!dob) {
        alert('Student date of birth is required to calculate age-based data.');
        return;
    }
    
    const checkupDate = new Date(dob);
    checkupDate.setFullYear(checkupDate.getFullYear() + age);
    
    document.getElementById('checkup_date').value = checkupDate.toISOString().split('T')[0];
    
    // Scroll to the form
    document.getElementById('healthReportForm').scrollIntoView({ behavior: 'smooth' });
}

function createAgeReport(age) {
    const dob = '{{ $user->date_of_birth ? $user->date_of_birth->format("Y-m-d") : "" }}';
    if (!dob) {
        alert('Student date of birth is required to create age-based report.');
        return;
    }
    
    const checkupDate = new Date(dob);
    checkupDate.setFullYear(checkupDate.getFullYear() + age);
    
    document.getElementById('checkup_date').value = checkupDate.toISOString().split('T')[0];
    document.getElementById('checked_by').value = '{{ auth()->user()->name }}';
    
    // Scroll to the form
    document.getElementById('healthReportForm').scrollIntoView({ behavior: 'smooth' });
    
    alert(`Creating new health report for age ${age}. Fill in the data below and click "Save Changes".`);
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate BMI when weight or height changes
    function calculateBMI() {
        const weightInput = document.querySelector('input[name*="weight"]');
        const heightInput = document.querySelector('input[name*="height"]');
        
        if (weightInput && heightInput) {
            const weight = parseFloat(weightInput.value);
            const height = parseFloat(heightInput.value);
            
            if (weight && height) {
                const heightInMeters = height / 100;
                const bmi = weight / (heightInMeters * heightInMeters);
                
                // Show BMI in a notification or update a display element
                console.log('BMI:', bmi.toFixed(2));
            }
        }
    }
    
    // Add event listeners for weight and height inputs
    const weightInput = document.querySelector('input[name*="weight"]');
    const heightInput = document.querySelector('input[name*="height"]');
    
    if (weightInput) {
        weightInput.addEventListener('input', calculateBMI);
    }
    if (heightInput) {
        heightInput.addEventListener('input', calculateBMI);
    }
});
</script>
@endsection
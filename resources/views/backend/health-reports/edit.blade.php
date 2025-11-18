@extends('layouts.app')

@section('title', 'Edit Health Report - ' . $healthReport->student->user->name)

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Edit Health Report - {{ $healthReport->student->user->name }}</h3>
            <div class="flex space-x-3">
                <button type="button" onclick="saveHealthReport()" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
                <a href="{{ route('admin.health-reports.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Student Information -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Student Information</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <div class="text-sm font-medium text-blue-900 mb-1">Student ID</div>
                <div class="text-lg font-bold text-blue-700">{{ $healthReport->student->student_id }}</div>
            </div>
            
            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <div class="text-sm font-medium text-green-900 mb-1">Class</div>
                <div class="text-lg font-bold text-green-700">{{ $healthReport->student->class->name ?? 'N/A' }}</div>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                <div class="text-sm font-medium text-purple-900 mb-1">Roll Number</div>
                <div class="text-lg font-bold text-purple-700">{{ $healthReport->student->roll_number ?? 'N/A' }}</div>
            </div>
            
            <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                <div class="text-sm font-medium text-orange-900 mb-1">Last Checkup</div>
                <div class="text-lg font-bold text-orange-700">{{ $healthReport->checkup_date->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Health Report Form -->
    <form id="healthReportForm" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Basic Information -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Basic Information</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="checkup_date" class="block text-sm font-medium text-gray-700 mb-2">Checkup Date</label>
                    <input type="date" name="checkup_date" id="checkup_date" 
                           value="{{ $healthReport->checkup_date->format('Y-m-d') }}"
                           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                </div>
                
                <div>
                    <label for="checked_by" class="block text-sm font-medium text-gray-700 mb-2">Checked By</label>
                    <input type="text" name="checked_by" id="checked_by" 
                           value="{{ $healthReport->checked_by }}"
                           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Doctor's name">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Created</label>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="text-sm text-gray-900">{{ $healthReport->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-gray-500">by {{ $healthReport->createdBy->name ?? 'System' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Fields -->
        @foreach($categories as $category)
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">{{ $category->name }}</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($category->fields as $field)
                    @php
                        $value = $healthReport->getFieldValue($field->field_name);
                    @endphp
                    
                    <div class="@if($field->field_type === 'textarea') md:col-span-2 lg:col-span-3 @endif">
                        <label for="{{ $field->field_name }}" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $field->label }}
                            @if($field->is_required) <span class="text-red-500">*</span> @endif
                        </label>
                        
                        @switch($field->field_type)
                            @case('text')
                            @case('number')
                            @case('date')
                                <input type="{{ $field->field_type }}" 
                                       name="{{ $field->field_name }}" 
                                       id="{{ $field->field_name }}"
                                       value="{{ $value ?? '' }}"
                                       class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                       placeholder="{{ $field->placeholder }}"
                                       @if($field->is_required) required @endif>
                                @break
                            
                            @case('select')
                                <select name="{{ $field->field_name }}" 
                                        id="{{ $field->field_name }}"
                                        class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                        @if($field->is_required) required @endif>
                                    <option value="">Select {{ $field->label }}</option>
                                    @if($field->options && is_array($field->options))
                                        @foreach($field->options as $option)
                                            <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>
                                                {{ ucfirst($option) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @break
                            
                            @case('textarea')
                                <textarea name="{{ $field->field_name }}" 
                                          id="{{ $field->field_name }}"
                                          rows="4"
                                          class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                          placeholder="{{ $field->placeholder }}"
                                          @if($field->is_required) required @endif>{{ $value ?? '' }}</textarea>
                                @break
                            
                            @case('checkbox')
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="{{ $field->field_name }}" 
                                           id="{{ $field->field_name }}"
                                           value="1"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ $value ? 'checked' : '' }}>
                                    <label for="{{ $field->field_name }}" class="ml-2 block text-sm text-gray-900">
                                        {{ $field->placeholder ?? 'Yes' }}
                                    </label>
                                </div>
                                @break
                        @endswitch
                    </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- BMI Calculator -->
        @php
            $weightField = $categories->flatMap->fields->firstWhere('field_name', 'weight_kg');
            $heightField = $categories->flatMap->fields->firstWhere('field_name', 'height_cm');
        @endphp
        @if($weightField && $heightField)
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">BMI Calculator</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="col-span-2">
                    <p class="text-sm text-gray-600">BMI will be automatically calculated when you enter weight and height.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">BMI Result</label>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <span id="bmiDisplay" class="text-sm font-medium text-gray-900">--</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </form>
</div>

<style>
    .content-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calculate BMI when weight or height changes
        function calculateBMI() {
            const weightInput = document.getElementById('weight_kg');
            const heightInput = document.getElementById('height_cm');
            
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
        const weightInput = document.getElementById('weight_kg');
        const heightInput = document.getElementById('height_cm');
        
        if (weightInput) {
            weightInput.addEventListener('input', calculateBMI);
        }
        if (heightInput) {
            heightInput.addEventListener('input', calculateBMI);
        }
        
        // Initial BMI calculation if values exist
        @if($healthReport->getFieldValue('weight_kg') && $healthReport->getFieldValue('height_cm'))
            calculateBMI();
        @endif
        
        // Add smooth interactions
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
            });
        });
    });
    
    // Save health report
    async function saveHealthReport() {
        const form = document.getElementById('healthReportForm');
        const formData = new FormData(form);
        
        // Show loading state
        const saveButton = document.querySelector('button[onclick="saveHealthReport()"]');
        const originalText = saveButton.innerHTML;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        saveButton.disabled = true;
        
        try {
            const response = await fetch('{{ route("admin.health-reports.update", $healthReport) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification('Health report updated successfully!', 'success');
            } else {
                showNotification('Error updating health report: ' + (result.message || 'Please check the form'), 'error');
            }
        } catch (error) {
            showNotification('Error updating health report. Please try again.', 'error');
            console.error('Error:', error);
        } finally {
            // Restore button state
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        }
    }
    
    function showNotification(message, type) {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.custom-notification');
        existingNotifications.forEach(notification => notification.remove());
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `custom-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endsection
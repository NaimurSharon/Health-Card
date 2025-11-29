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
    
    <!-- Health Card Status -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Health Card Status</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $healthCard = \App\Models\HealthCard::where('student_id', $student->id)->first();
            @endphp
            
            @if($healthCard)
            <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <div class="flex items-center">
                    <i class="fas fa-id-card text-green-600 text-xl mr-3"></i>
                    <div>
                        <div class="text-sm font-medium text-green-900">Health Card</div>
                        <div class="text-lg font-bold text-green-700">{{ $healthCard->card_number }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <div class="flex items-center">
                    <i class="fas fa-calendar text-blue-600 text-xl mr-3"></i>
                    <div>
                        <div class="text-sm font-medium text-blue-900">Expiry Date</div>
                        <div class="text-lg font-bold text-blue-700">{{ $healthCard->expiry_date->format('M d, Y') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-purple-600 text-xl mr-3"></i>
                    <div>
                        <div class="text-sm font-medium text-purple-900">Status</div>
                        <div class="text-lg font-bold text-purple-700 capitalize">{{ $healthCard->status }}</div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-span-3 bg-yellow-50 rounded-lg p-6 border border-yellow-200 text-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-3"></i>
                <div class="text-lg font-medium text-yellow-800">Health Card Not Found</div>
                <p class="text-yellow-700 mt-2">A health card will be automatically created when you save the health report.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Health Report Status Alert -->
    @if(!$healthReport)
    <div class="content-card rounded-lg p-6 shadow-sm border border-yellow-200 bg-yellow-50">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <h4 class="text-lg font-medium text-yellow-800">No Health Report Found</h4>
                <p class="text-yellow-700 mt-1">This student doesn't have a health report yet. Fill out the form below to create one.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Health Report Form -->
    <form id="healthReportForm" class="space-y-6">
        @csrf
        
        <!-- Basic Information (Checkup Date and Checked By) -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Checkup Information</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="checkup_date" class="block text-sm font-medium text-gray-700 mb-2">Checkup Date *</label>
                    <input type="date" name="checkup_date" id="checkup_date" 
                           value="{{ old('checkup_date', $healthReport->checkup_date ? $healthReport->checkup_date->format('Y-m-d') : date('Y-m-d')) }}"
                           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           required>
                </div>
                
                <div>
                    <label for="checked_by" class="block text-sm font-medium text-gray-700 mb-2">Checked By *</label>
                    <input type="text" name="checked_by" id="checked_by" 
                           value="{{ old('checked_by', $healthReport->checked_by ?? '') }}"
                           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Doctor's name"
                           required>
                </div>
            </div>
        </div>
        
        <!-- Dynamic Categories and Fields -->
        @foreach($categories as $category)
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">{{ $category->name }}</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($category->fields as $field)
                    @php
                        $value = $healthReport->getFieldValue($field->field_name);
                        $fieldId = $field->field_name . '_' . $field->id;
                    @endphp
                    
                    <div class="@if($field->field_type === 'textarea') md:col-span-2 lg:col-span-3 @endif">
                        <label for="{{ $fieldId }}" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $field->label }}
                            @if($field->is_required) <span class="text-red-500">*</span> @endif
                        </label>
                        
                        @switch($field->field_type)
                            @case('text')
                            @case('number')
                                <input type="{{ $field->field_type }}" 
                                       name="{{ $field->field_name }}" 
                                       id="{{ $fieldId }}"
                                       value="{{ old($field->field_name, $value ?? '') }}"
                                       class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                       placeholder="{{ $field->placeholder }}"
                                       @if($field->is_required) required @endif>
                                @break
                            
                            @case('date')
                                <input type="date" 
                                       name="{{ $field->field_name }}" 
                                       id="{{ $fieldId }}"
                                       value="{{ old($field->field_name, $value ?? '') }}"
                                       class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                       @if($field->is_required) required @endif>
                                @break
                            
                            @case('select')
                                <select name="{{ $field->field_name }}" 
                                        id="{{ $fieldId }}"
                                        class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                        @if($field->is_required) required @endif>
                                    <option value="">Select {{ $field->label }}</option>
                                    @if($field->options && is_array($field->options))
                                        @foreach($field->options as $option)
                                            <option value="{{ $option }}" {{ old($field->field_name, $value) == $option ? 'selected' : '' }}>
                                                {{ ucfirst($option) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @break
                            
                            @case('textarea')
                                <textarea name="{{ $field->field_name }}" 
                                          id="{{ $fieldId }}"
                                          rows="4"
                                          class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                          placeholder="{{ $field->placeholder }}"
                                          @if($field->is_required) required @endif>{{ old($field->field_name, $value ?? '') }}</textarea>
                                @break
                            
                            @case('checkbox')
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="{{ $field->field_name }}" 
                                           id="{{ $fieldId }}"
                                           value="1"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                           {{ old($field->field_name, $value) ? 'checked' : '' }}>
                                    <label for="{{ $fieldId }}" class="ml-2 block text-sm text-gray-900">
                                        {{ $field->placeholder ?? 'Yes' }}
                                    </label>
                                </div>
                                @break
                        @endswitch
                        
                        @error($field->field_name)
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- BMI Information -->
        @php
            $weightField = $categories->flatMap->fields->firstWhere('field_name', 'weight_kg');
            $heightField = $categories->flatMap->fields->firstWhere('field_name', 'height_cm');
        @endphp
        @if($weightField && $heightField && $healthReport)
            @php
                $weight = $healthReport->getFieldValue('weight_kg');
                $height = $healthReport->getFieldValue('height_cm');
                $bmi = null;
                $bmiCategory = '';
                if ($weight && $height) {
                    $heightInMeters = $height / 100;
                    $bmi = $weight / ($heightInMeters * $heightInMeters);
                    
                    if ($bmi < 18.5) {
                        $bmiCategory = 'Underweight';
                        $bmiColor = 'yellow';
                    } elseif ($bmi >= 18.5 && $bmi < 25) {
                        $bmiCategory = 'Normal weight';
                        $bmiColor = 'green';
                    } elseif ($bmi >= 25 && $bmi < 30) {
                        $bmiCategory = 'Overweight';
                        $bmiColor = 'orange';
                    } else {
                        $bmiCategory = 'Obese';
                        $bmiColor = 'red';
                    }
                }
            @endphp
            @if($bmi)
            <div class="mb-8 print:break-before-page">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-xl font-semibold text-gray-900">BMI Analysis</h4>
                    <div class="bg-gradient-to-r from-green-500 to-teal-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-chart-line mr-1"></i> Health Metrics
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 print:grid-cols-3">
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-100 rounded-xl p-6 border-l-4 border-blue-500 text-center shadow-sm">
                        <div class="bg-blue-500 text-white p-3 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                            <i class="fas fa-weight text-xl"></i>
                        </div>
                        <div class="text-xs font-medium text-blue-700 mb-1">WEIGHT</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $weight }} kg</div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl p-6 border-l-4 border-green-500 text-center shadow-sm">
                        <div class="bg-green-500 text-white p-3 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                            <i class="fas fa-ruler-vertical text-xl"></i>
                        </div>
                        <div class="text-xs font-medium text-green-700 mb-1">HEIGHT</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $height }} cm</div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-50 to-violet-100 rounded-xl p-6 border-l-4 border-purple-500 text-center shadow-sm">
                        <div class="bg-purple-500 text-white p-3 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                            <i class="fas fa-calculator text-xl"></i>
                        </div>
                        <div class="text-xs font-medium text-purple-700 mb-1">BMI RESULT</div>
                        <div class="text-2xl font-bold text-gray-900 mb-1">{{ number_format($bmi, 1) }}</div>
                        <div class="text-sm font-semibold px-3 py-1 rounded-full 
                            @if($bmiColor === 'green') bg-green-100 text-green-800
                            @elseif($bmiColor === 'yellow') bg-yellow-100 text-yellow-800
                            @elseif($bmiColor === 'orange') bg-orange-100 text-orange-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $bmiCategory }}
                        </div>
                    </div>
                </div>
                
                <!-- BMI Chart Visualization -->
                <div class="mt-6 bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h5 class="text-lg font-semibold text-gray-900">BMI Scale Reference</h5>
                        <div class="text-sm text-gray-500">WHO Standards</div>
                    </div>
                    <div class="flex h-8 bg-gradient-to-r from-blue-400 via-green-400 via-yellow-400 via-orange-400 to-red-400 rounded-full overflow-hidden relative">
                        <div class="absolute top-0 left-0 w-full h-full flex justify-between px-2 items-center text-xs font-bold text-white">
                            <span>Underweight</span>
                            <span>Normal</span>
                            <span>Overweight</span>
                            <span>Obese</span>
                        </div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-600 mt-1">
                        <span>&lt; 18.5</span>
                        <span>18.5 - 24.9</span>
                        <span>25 - 29.9</span>
                        <span>30+</span>
                    </div>
                </div>
            </div>
            @endif
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

    .table-header {
        border-bottom: 1px solid rgba(229, 231, 235, 0.6);
    }
</style>

<script>
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
            const response = await fetch('{{ route("admin.health-reports.store-or-update", $user) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification('Health report saved successfully!', 'success');
                // Remove the "no health report" alert if it exists
                const alert = document.querySelector('.bg-yellow-50');
                if (alert) {
                    alert.remove();
                }
            } else {
                showNotification('Error saving health report: ' + (result.message || 'Please check the form'), 'error');
            }
        } catch (error) {
            showNotification('Error saving health report. Please try again.', 'error');
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
    
    // Auto-save when fields change (optional)
    let saveTimeout;
    document.addEventListener('input', function(e) {
        clearTimeout(saveTimeout);
        saveTimeout = setTimeout(saveHealthReport, 7000); // Auto-save after 2 seconds of inactivity
    });
</script>
@endsection
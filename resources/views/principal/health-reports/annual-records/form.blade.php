@extends('layouts.principal')

@section('title', isset($annualHealthRecord) ? 'Edit Annual Record' : 'Add Annual Record')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="content-card rounded-lg overflow-hidden">
            <div
                class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold">
                        {{ isset($annualHealthRecord) ? 'Edit Annual Health Record' : 'Add Annual Health Record' }}
                    </h3>
                    <p class="text-gray-600 mt-1">
                        {{ isset($annualHealthRecord) ? 'Update student growth data' : 'Record student growth and development' }}
                    </p>
                </div>
                <a href="{{ route('principal.health.annual-records.index') }}"
                    class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="content-card rounded-lg p-4">
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <div class="content-card rounded-lg p-4 sm:p-6">
            <form
                action="{{ isset($annualHealthRecord) ? route('principal.health.annual-records.update', $annualHealthRecord) : route('principal.health.annual-records.store') }}"
                method="POST" class="space-y-6">
                @csrf
                @if(isset($annualHealthRecord))
                    @method('PUT')
                @endif

                <!-- Student Selection (only for create) -->
                @if(!isset($annualHealthRecord))
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student *</label>
                        <select id="student_id" name="student_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }} - {{ $student->class->name ?? 'N/A' }} (Roll:
                                    {{ $student->roll_number ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Student Information</h4>
                        <div class="text-base font-bold text-blue-700">{{ $annualHealthRecord->student->user->name }}</div>
                        <div class="text-sm text-gray-600">
                            Class: {{ $annualHealthRecord->student->class->name ?? 'N/A' }} •
                            Roll: {{ $annualHealthRecord->student->roll_number ?? 'N/A' }} •
                            Current Age: {{ $annualHealthRecord->age }} years
                        </div>
                    </div>
                @endif

                <!-- Age -->
                <div>
                    <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Age (Years) *</label>
                    <input type="number" id="age" name="age" value="{{ old('age', $annualHealthRecord->age ?? '') }}"
                        required min="3" max="18"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Enter age in years">
                </div>

                <!-- Measurements -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Weight (kg) *</label>
                        <input type="number" id="weight" name="weight" step="0.01"
                            value="{{ old('weight', $annualHealthRecord->weight ?? '') }}" required min="10" max="100"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="e.g., 25.5">
                    </div>
                    <div>
                        <label for="height" class="block text-sm font-medium text-gray-700 mb-2">Height (cm) *</label>
                        <input type="number" id="height" name="height" step="0.01"
                            value="{{ old('height', $annualHealthRecord->height ?? '') }}" required min="50" max="200"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="e.g., 120.5">
                    </div>
                    <div>
                        <label for="head_circumference" class="block text-sm font-medium text-gray-700 mb-2">Head
                            Circumference (cm)</label>
                        <input type="number" id="head_circumference" name="head_circumference" step="0.01"
                            value="{{ old('head_circumference', $annualHealthRecord->head_circumference ?? '') }}" min="30"
                            max="60"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="e.g., 45.5">
                    </div>
                </div>

                <!-- BMI Display -->
                @if(isset($annualHealthRecord) && $annualHealthRecord->weight && $annualHealthRecord->height)
                        @php
                            $heightInMeters = $annualHealthRecord->height / 100;
                            $bmi = $annualHealthRecord->weight / ($heightInMeters * $heightInMeters);
                        @endphp
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-medium text-gray-700 mb-2">BMI Calculation</div>
                            <div class="flex items-center">
                                <div class="text-2xl font-bold 
                                                    {{ $bmi < 18.5 ? 'text-yellow-600' :
                    ($bmi < 25 ? 'text-green-600' :
                        ($bmi < 30 ? 'text-orange-600' : 'text-red-600')) }}">
                                    {{ number_format($bmi, 1) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        @if($bmi < 18.5) Underweight
                                        @elseif($bmi < 25) Normal weight
                                        @elseif($bmi < 30) Overweight
                                        @else Obese
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">Based on WHO standards</div>
                                </div>
                            </div>
                        </div>
                @endif

                <!-- Health Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="general_health" class="block text-sm font-medium text-gray-700 mb-2">General Health
                            *</label>
                        <select id="general_health" name="general_health" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select Health Status</option>
                            <option value="excellent" {{ old('general_health', $annualHealthRecord->general_health ?? '') == 'excellent' ? 'selected' : '' }}>Excellent</option>
                            <option value="good" {{ old('general_health', $annualHealthRecord->general_health ?? '') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('general_health', $annualHealthRecord->general_health ?? '') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ old('general_health', $annualHealthRecord->general_health ?? '') == 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                    </div>
                    <div>
                        <label for="vaccination_status" class="block text-sm font-medium text-gray-700 mb-2">Vaccination
                            Status *</label>
                        <select id="vaccination_status" name="vaccination_status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select Status</option>
                            <option value="up-to-date" {{ old('vaccination_status', $annualHealthRecord->vaccination_status ?? '') == 'up-to-date' ? 'selected' : '' }}>Up to Date</option>
                            <option value="partially-complete" {{ old('vaccination_status', $annualHealthRecord->vaccination_status ?? '') == 'partially-complete' ? 'selected' : '' }}>
                                Partially Complete</option>
                            <option value="not-vaccinated" {{ old('vaccination_status', $annualHealthRecord->vaccination_status ?? '') == 'not-vaccinated' ? 'selected' : '' }}>Not
                                Vaccinated</option>
                            <option value="needs-booster" {{ old('vaccination_status', $annualHealthRecord->vaccination_status ?? '') == 'needs-booster' ? 'selected' : '' }}>Needs
                                Booster</option>
                        </select>
                    </div>
                </div>

                <!-- Text Areas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="development_notes" class="block text-sm font-medium text-gray-700 mb-2">Development
                            Notes</label>
                        <textarea id="development_notes" name="development_notes" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Child development progress, milestones achieved...">{{ old('development_notes', $annualHealthRecord->development_notes ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="difficulties" class="block text-sm font-medium text-gray-700 mb-2">Difficulties</label>
                        <textarea id="difficulties" name="difficulties" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Any difficulties faced in development or health...">{{ old('difficulties', $annualHealthRecord->difficulties ?? '') }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-2">Special
                            Instructions</label>
                        <textarea id="special_instructions" name="special_instructions" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Special care instructions, medications, etc...">{{ old('special_instructions', $annualHealthRecord->special_instructions ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="nutrition_notes" class="block text-sm font-medium text-gray-700 mb-2">Nutrition
                            Notes</label>
                        <textarea id="nutrition_notes" name="nutrition_notes" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Diet, nutrition, feeding habits...">{{ old('nutrition_notes', $annualHealthRecord->nutrition_notes ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('principal.health.annual-records.index') }}"
                        class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        Cancel
                    </a>
                    <button type="submit"
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>{{ isset($annualHealthRecord) ? 'Update Record' : 'Create Record' }}
                    </button>
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
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-calculate BMI when weight or height changes
            const weightInput = document.getElementById('weight');
            const heightInput = document.getElementById('height');
            const bmiDisplay = document.createElement('div');
            bmiDisplay.className = 'mt-2 text-sm';

            function calculateBMI() {
                const weight = parseFloat(weightInput.value);
                const height = parseFloat(heightInput.value);

                if (weight && height && height > 0) {
                    const heightInMeters = height / 100;
                    const bmi = weight / (heightInMeters * heightInMeters);

                    bmiDisplay.innerHTML = `
                        <span class="font-medium text-gray-700">Estimated BMI:</span>
                        <span class="ml-2 font-bold ${getBMIColor(bmi)}">${bmi.toFixed(1)}</span>
                        <span class="ml-2 text-gray-600">(${getBMICategory(bmi)})</span>
                    `;

                    if (!bmiDisplay.parentNode) {
                        heightInput.parentNode.appendChild(bmiDisplay);
                    }
                } else if (bmiDisplay.parentNode) {
                    bmiDisplay.remove();
                }
            }

            function getBMIColor(bmi) {
                if (bmi < 18.5) return 'text-yellow-600';
                if (bmi < 25) return 'text-green-600';
                if (bmi < 30) return 'text-orange-600';
                return 'text-red-600';
            }

            function getBMICategory(bmi) {
                if (bmi < 18.5) return 'Underweight';
                if (bmi < 25) return 'Normal';
                if (bmi < 30) return 'Overweight';
                return 'Obese';
            }

            if (weightInput && heightInput) {
                weightInput.addEventListener('input', calculateBMI);
                heightInput.addEventListener('input', calculateBMI);

                // Calculate initial BMI if values exist
                if (weightInput.value && heightInput.value) {
                    calculateBMI();
                }
            }
        });
    </script>
@endsection
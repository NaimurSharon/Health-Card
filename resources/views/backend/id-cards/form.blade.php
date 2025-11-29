@extends('layouts.app')

@section('title', isset($idCard) ? 'Edit ID Card' : 'Create ID Card')

@section('content')
<div class="space-y-6">
    <!-- ID Card Form Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">{{ isset($idCard) ? 'Edit ID Card' : 'Create ID Card' }}</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.id-cards.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
                <button type="submit" form="idcard-form" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ isset($idCard) ? 'Update ID Card' : 'Create ID Card' }}
                </button>
            </div>
        </div>
    </div>

    <!-- ID Card Form -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form id="idcard-form" action="{{ isset($idCard) ? route('admin.id-cards.update', $idCard) : route('admin.id-cards.store') }}" method="POST">
            @csrf
            @if(isset($idCard))
                @method('PUT')
            @endif

            <div class="space-y-8">
                <!-- Card Type & Template Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Card Configuration</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Card Type *</label>
                            <select name="type" id="type" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Type</option>
                                <option value="student" {{ old('type', $idCard->type ?? '') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="teacher" {{ old('type', $idCard->type ?? '') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="staff" {{ old('type', $idCard->type ?? '') == 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="medical" {{ old('type', $idCard->type ?? '') == 'medical' ? 'selected' : '' }}>Medical</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Template -->
                        <div>
                            <label for="template_id" class="block text-sm font-medium text-gray-700 mb-2">Template *</label>
                            <select name="template_id" id="template_id" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Template</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" 
                                        {{ old('template_id', $idCard->template_id ?? '') == $template->id ? 'selected' : '' }}
                                        data-type="{{ $template->type }}">
                                        {{ $template->name }} ({{ $template->type }} - {{ $template->dimensions }})
                                    </option>
                                @endforeach
                            </select>
                            @error('template_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Card Holder Information -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Card Holder Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student Selection -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                            <select name="student_id" id="student_id" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" 
                                        {{ old('student_id', $idCard->student_id ?? '') == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->name }} ({{ $student->student_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Staff Selection -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Staff Member</label>
                            <select name="user_id" id="user_id" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="">Select Staff</option>
                                @foreach($staff as $user)
                                    <option value="{{ $user->id }}" 
                                        {{ old('user_id', $idCard->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ ucfirst($user->role) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Card Details Section -->
                <div class="space-y-4">
                    <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-2">Card Details</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Card Number -->
                        <div>
                            <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">Card Number *</label>
                            <input type="text" name="card_number" id="card_number" 
                                   value="{{ old('card_number', $idCard->card_number ?? '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter card number">
                            @error('card_number')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Issue Date -->
                        <div>
                            <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">Issue Date *</label>
                            <input type="date" name="issue_date" id="issue_date" 
                                   value="{{ old('issue_date', isset($idCard) ? $idCard->issue_date->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('issue_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expiry Date -->
                        <div>
                            <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date *</label>
                            <input type="date" name="expiry_date" id="expiry_date" 
                                   value="{{ old('expiry_date', isset($idCard) ? $idCard->expiry_date->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @error('expiry_date')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                <option value="active" {{ old('status', $idCard->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ old('status', $idCard->status ?? '') == 'expired' ? 'selected' : '' }}>Expired</option>
                                <option value="lost" {{ old('status', $idCard->status ?? '') == 'lost' ? 'selected' : '' }}>Lost</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                <a href="{{ route('admin.id-cards.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-save mr-2"></i>{{ isset($idCard) ? 'Update ID Card' : 'Create ID Card' }}
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
    document.addEventListener('DOMContentLoaded', function() {
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

        // Auto-filter templates based on selected type
        document.getElementById('type').addEventListener('change', function() {
            const selectedType = this.value;
            const templateSelect = document.getElementById('template_id');
            
            if (selectedType) {
                // Enable all options but show/hide based on type
                Array.from(templateSelect.options).forEach(option => {
                    if (option.value === '') return; // Keep "Select Template" option
                    
                    if (option.dataset.type === selectedType) {
                        option.style.display = '';
                    } else {
                        option.style.display = 'none';
                        if (option.selected) {
                            option.selected = false;
                            templateSelect.options[0].selected = true;
                        }
                    }
                });
            } else {
                // Show all options if no type selected
                Array.from(templateSelect.options).forEach(option => {
                    option.style.display = '';
                });
            }
        });

        // Disable both student and staff selects when one is selected
        function updateSelectStates() {
            const studentSelect = document.getElementById('student_id');
            const staffSelect = document.getElementById('user_id');
            
            if (studentSelect.value) {
                staffSelect.disabled = true;
                staffSelect.parentElement.classList.add('opacity-50');
            } else if (staffSelect.value) {
                studentSelect.disabled = true;
                studentSelect.parentElement.classList.add('opacity-50');
            } else {
                studentSelect.disabled = false;
                staffSelect.disabled = false;
                studentSelect.parentElement.classList.remove('opacity-50');
                staffSelect.parentElement.classList.remove('opacity-50');
            }
        }

        document.getElementById('student_id').addEventListener('change', updateSelectStates);
        document.getElementById('user_id').addEventListener('change', updateSelectStates);

        // Initialize on page load
        updateSelectStates();
        document.getElementById('type').dispatchEvent(new Event('change'));
    });
</script>
@endsection
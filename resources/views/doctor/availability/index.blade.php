@extends('layouts.doctor')

@section('title', 'Manage Availability')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <div>
                <h3 class="text-2xl font-bold">Manage Availability</h3>
                <p class="text-gray-600 mt-1">Set your weekly schedule and manage leave dates</p>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Availability Status -->
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-700">Status:</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $doctor->doctorDetail->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $doctor->doctorDetail->is_available ? 'Available' : 'Unavailable' }}
                    </span>
                </div>
                <form action="{{ route('doctor.availability.toggle') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        {{ $doctor->doctorDetail->is_available ? 'Mark as Unavailable' : 'Mark as Available' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Weekly Schedule - 2/3 width -->
        <div class="lg:col-span-2">
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-6">
                    Weekly Schedule
                </h4>

                <form action="{{ route('doctor.availability.update') }}" method="POST" id="availability-form">
                    @csrf
                    
                    <div class="space-y-4">
                        @foreach($daysOfWeek as $day => $dayName)
                        @php
                            $availability = $availabilities[$day];
                            $isEnabled = $availability['enabled'];
                        @endphp
                        <div class="availability-day bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" 
                                           name="availabilities[{{ $day }}][enabled]" 
                                           value="1" 
                                           id="day_{{ $day }}"
                                           {{ $isEnabled ? 'checked' : '' }}
                                           class="day-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                    <label for="day_{{ $day }}" class="text-lg font-medium text-gray-900 cursor-pointer">
                                        {{ $dayName }}
                                    </label>
                                </div>
                                <div class="availability-status">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isEnabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $isEnabled ? 'Available' : 'Not Available' }}
                                    </span>
                                </div>
                            </div>
                
                            <div class="availability-fields grid grid-cols-1 md:grid-cols-4 gap-4 {{ $isEnabled ? '' : 'hidden' }}">
                                <!-- Start Time -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Time *</label>
                                    <input type="time" 
                                           name="availabilities[{{ $day }}][start_time]" 
                                           value="{{ $availability['start_time'] }}"
                                           class="time-input w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                                           {{ $isEnabled ? 'required' : '' }}>
                                    @error("availabilities.{$day}.start_time")
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                
                                <!-- End Time -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">End Time *</label>
                                    <input type="time" 
                                           name="availabilities[{{ $day }}][end_time]" 
                                           value="{{ $availability['end_time'] }}"
                                           class="time-input w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                                           {{ $isEnabled ? 'required' : '' }}>
                                    @error("availabilities.{$day}.end_time")
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                
                                <!-- Slot Duration -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Slot Duration (min)</label>
                                    <select name="availabilities[{{ $day }}][slot_duration]" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="15" {{ $availability['slot_duration'] == 15 ? 'selected' : '' }}>15 min</option>
                                        <option value="30" {{ $availability['slot_duration'] == 30 ? 'selected' : '' }}>30 min</option>
                                        <option value="45" {{ $availability['slot_duration'] == 45 ? 'selected' : '' }}>45 min</option>
                                        <option value="60" {{ $availability['slot_duration'] == 60 ? 'selected' : '' }}>60 min</option>
                                    </select>
                                </div>
                
                                <!-- Max Appointments -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Appointments</label>
                                    <input type="number" 
                                           name="availabilities[{{ $day }}][max_appointments]" 
                                           value="{{ $availability['max_appointments'] }}"
                                           min="1" max="50"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                
                    <!-- Validation Error for no days selected -->
                    @error('availabilities')
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-600 text-sm">{{ $message }}</p>
                        </div>
                    @enderror
                
                    <!-- Action Buttons -->
                    <div class="mt-6 pt-6 border-t border-gray-200/60 flex justify-end space-x-4">
                        <button type="button" 
                                onclick="resetForm()"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors">
                            Reset
                        </button>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                            <i class="fas fa-save mr-2"></i>Save Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Leave Dates & Quick Stats - 1/3 width -->
        <div class="space-y-6">
            <!-- Add Leave Date -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">
                    Add Leave Date
                </h4>

                <form action="{{ route('doctor.availability.leave.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Leave Date -->
                        <div>
                            <label for="leave_date" class="block text-sm font-medium text-gray-700 mb-1">Leave Date *</label>
                            <input type="date" 
                                   name="leave_date" 
                                   id="leave_date"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                                   required>
                        </div>

                        <!-- Reason -->
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason *</label>
                            <input type="text" 
                                   name="reason" 
                                   id="reason"
                                   placeholder="e.g., Personal leave, Conference, etc."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm"
                                   required>
                        </div>

                        <!-- Full Day / Partial Day -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Leave Type</label>
                            <div class="flex space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_full_day" value="1" checked class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Full Day</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_full_day" value="0" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Partial Day</span>
                                </label>
                            </div>
                        </div>

                        <!-- Partial Day Times (hidden by default) -->
                        <div id="partial-day-times" class="hidden space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                                    <input type="time" 
                                           name="start_time" 
                                           id="start_time"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                                <div>
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                                    <input type="time" 
                                           name="end_time" 
                                           id="end_time"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Leave Date
                        </button>
                    </div>
                </form>
            </div>

            <!-- Upcoming Leave Dates -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">
                    Upcoming Leave Dates
                </h4>

                @if($leaveDates->count() > 0)
                <div class="space-y-3">
                    @foreach($leaveDates as $leave)
                    <div class="flex items-center justify-between p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div>
                            <p class="font-medium text-red-800">
                                {{ \Carbon\Carbon::parse($leave->leave_date)->format('M d, Y') }}
                            </p>
                            <p class="text-sm text-red-600">{{ $leave->reason }}</p>
                            @if(!$leave->is_full_day)
                            <p class="text-xs text-red-500">
                                {{ \Carbon\Carbon::parse($leave->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($leave->end_time)->format('g:i A') }}
                            </p>
                            @endif
                        </div>
                        <form action="{{ route('doctor.availability.leave.destroy', $leave) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-800 p-1 rounded"
                                    onclick="return confirm('Are you sure you want to delete this leave date?')">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-calendar-check text-gray-400 text-2xl mb-2"></i>
                    <p class="text-gray-500 text-sm">No upcoming leave dates</p>
                </div>
                @endif
            </div>

            <!-- Quick Stats -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">
                    Quick Stats
                </h4>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Available Days</span>
                        <span class="font-medium text-blue-600">
                            {{ collect($availabilities)->where('enabled', true)->count() }}/7
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Upcoming Leaves</span>
                        <span class="font-medium text-red-600">{{ $leaveDates->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Status</span>
                        <span class="font-medium {{ $doctor->doctorDetail->is_available ? 'text-green-600' : 'text-red-600' }}">
                            {{ $doctor->doctorDetail->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
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
        // Toggle availability fields when checkbox is clicked
        document.querySelectorAll('.day-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const fields = this.closest('.availability-day').querySelector('.availability-fields');
                const status = this.closest('.availability-day').querySelector('.availability-status span');
                const timeInputs = fields.querySelectorAll('.time-input');
                
                if (this.checked) {
                    fields.classList.remove('hidden');
                    status.textContent = 'Available';
                    status.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
                    timeInputs.forEach(input => {
                        input.setAttribute('required', 'required');
                        input.removeAttribute('disabled'); // Ensure inputs are enabled
                    });
                } else {
                    fields.classList.add('hidden');
                    status.textContent = 'Not Available';
                    status.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
                    timeInputs.forEach(input => {
                        input.removeAttribute('required');
                        input.setAttribute('disabled', 'disabled'); // Disable inputs to prevent submission
                        input.value = ''; // Clear values
                    });
                }
            });
    
            // Trigger change event on page load to set initial state
            checkbox.dispatchEvent(new Event('change'));
        });
    
        // Form validation before submit
        const form = document.getElementById('availability-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                let hasEnabledDay = false;
                
                document.querySelectorAll('.day-checkbox:checked').forEach(checkbox => {
                    const dayFields = checkbox.closest('.availability-day').querySelector('.availability-fields');
                    const startTime = dayFields.querySelector('input[name$="[start_time]"]').value;
                    const endTime = dayFields.querySelector('input[name$="[end_time]"]').value;
                    
                    if (startTime && endTime) {
                        hasEnabledDay = true;
                    }
                });
                
                if (!hasEnabledDay) {
                    e.preventDefault();
                    alert('Please enable at least one day and provide valid time slots.');
                    return false;
                }
            });
        }
    
        // Reset form function
        window.resetForm = function() {
            if (confirm('Are you sure you want to reset all changes?')) {
                document.getElementById('availability-form').reset();
                // Reset UI states
                document.querySelectorAll('.day-checkbox').forEach(checkbox => {
                    checkbox.dispatchEvent(new Event('change'));
                });
            }
        };
    });
</script>
@endsection
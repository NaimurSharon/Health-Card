@extends('layouts.app')

@section('title', 'Manage Doctor Leave Dates')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 py-4 sm:px-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-xl sm:text-2xl font-bold">Manage Leave Dates - Dr. {{ $doctor->name }}</h3>
                <p class="text-sm text-gray-100 mt-1">{{ $doctor->specialization }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('admin.doctors.show', $doctor) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2.5 text-sm font-medium transition-colors rounded-lg flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Doctor
                </a>
                <button type="button" onclick="openAddLeaveModal()"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-sm font-medium transition-colors rounded-lg flex items-center justify-center">
                    <i class="fas fa-plus mr-2"></i>Add Leave Date
                </button>
            </div>
        </div>
    </div>

    <!-- Leave Dates List -->
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <h4 class="text-lg sm:text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Upcoming Leave Dates</h4>
        
        @if($leaveDates->count() > 0)
            <div class="space-y-3">
                @foreach($leaveDates as $leave)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 sm:p-4 bg-yellow-50 border border-yellow-200 rounded-lg gap-3">
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                                <span class="text-sm font-medium text-gray-900 bg-white px-2 py-1 rounded border">
                                    {{ $leave->leave_date->format('M d, Y') }}
                                </span>
                                <span class="text-sm text-gray-700">{{ $leave->reason }}</span>
                                <span class="text-xs text-gray-500 bg-yellow-100 px-2 py-1 rounded">
                                    {{ $leave->formatted_time }}
                                </span>
                            </div>
                            <div class="mt-2 sm:mt-0 text-xs text-gray-500">
                                Added: {{ $leave->created_at?->diffForHumans() ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <form action="{{ route('admin.doctors.leave-dates.destroy', [$doctor, $leave]) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this leave date?');"
                                  class="flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 text-xs font-medium rounded-lg transition-colors flex items-center">
                                    <i class="fas fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($leaveDates->hasPages())
            <div class="mt-6">
                {{ $leaveDates->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-8">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No leave dates scheduled</p>
                <p class="text-gray-400 text-sm mt-1">Add leave dates to manage doctor's availability</p>
            </div>
        @endif
    </div>

    <!-- Past Leave Dates -->
    @if($leaveDates->where('leave_date', '<', now())->count() > 0)
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <h4 class="text-lg sm:text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Past Leave Dates</h4>
        <div class="space-y-3">
            @foreach($leaveDates->where('leave_date', '<', now())->sortByDesc('leave_date') as $pastLeave)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 sm:p-4 bg-gray-50 border border-gray-200 rounded-lg gap-3">
                    <div class="flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                            <span class="text-sm font-medium text-gray-500 line-through bg-white px-2 py-1 rounded border">
                                {{ $pastLeave->leave_date->format('M d, Y') }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $pastLeave->reason }}</span>
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">
                                {{ $pastLeave->formatted_time }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">Completed</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Add Leave Date Modal -->
<div id="addLeaveModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="p-4 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Add Leave Date</h3>
                <button type="button" onclick="closeAddLeaveModal()" 
                        class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form action="{{ route('admin.doctors.leave-dates.store', $doctor) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <!-- Leave Date -->
                    <div>
                        <label for="leave_date" class="block text-sm font-medium text-gray-700 mb-2">Leave Date *</label>
                        <input type="date" name="leave_date" id="leave_date" 
                               min="{{ now()->addDay()->format('Y-m-d') }}"
                               class="w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                               required>
                        @error('leave_date')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reason -->
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason *</label>
                        <input type="text" name="reason" id="reason" 
                               class="w-full px-3 py-2.5 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                               placeholder="e.g., Vacation, Conference, Sick Leave"
                               required>
                        @error('reason')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Leave Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Leave Type</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="is_full_day" value="1" checked 
                                       class="mr-3 rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                                       onchange="toggleLeaveTime()">
                                <span class="text-sm text-gray-700">Full Day</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="is_full_day" value="0"
                                       class="mr-3 rounded-full border-gray-300 text-blue-600 focus:ring-blue-500"
                                       onchange="toggleLeaveTime()">
                                <span class="text-sm text-gray-700">Partial Day</span>
                            </label>
                        </div>
                    </div>

                    <!-- Time Fields (Hidden by default) -->
                    <div id="timeFields" class="hidden space-y-3">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="start_time" class="block text-xs font-medium text-gray-600 mb-1">Start Time</label>
                                <input type="time" name="start_time" id="start_time"
                                       class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-200">
                            </div>
                            <div>
                                <label for="end_time" class="block text-xs font-medium text-gray-600 mb-1">End Time</label>
                                <input type="time" name="end_time" id="end_time"
                                       class="w-full px-3 py-2 bg-white border border-gray-300 rounded text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-200">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeAddLeaveModal()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>Add Leave Date
                    </button>
                </div>
            </form>
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
    function openAddLeaveModal() {
        document.getElementById('addLeaveModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAddLeaveModal() {
        document.getElementById('addLeaveModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function toggleLeaveTime() {
        const timeFields = document.getElementById('timeFields');
        const isFullDay = document.querySelector('input[name="is_full_day"]:checked').value === '1';
        
        if (isFullDay) {
            timeFields.classList.add('hidden');
            document.getElementById('start_time').required = false;
            document.getElementById('end_time').required = false;
        } else {
            timeFields.classList.remove('hidden');
            document.getElementById('start_time').required = true;
            document.getElementById('end_time').required = true;
        }
    }

    // Close modal when clicking outside
    document.getElementById('addLeaveModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddLeaveModal();
        }
    });

    // Initialize modal state
    document.addEventListener('DOMContentLoaded', function() {
        toggleLeaveTime();
    });
</script>
@endsection
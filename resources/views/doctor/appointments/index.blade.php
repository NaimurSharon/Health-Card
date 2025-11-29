@extends('layouts.app')

@section('title', 'Appointments Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Appointments Management</h3>
            <div class="text-sm text-gray-600">
                <span class="font-medium">{{ $appointmentStats['total'] }}</span> total appointments
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Scheduled</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $appointmentStats['scheduled'] }}</p>
                </div>
                <i class="fas fa-clock text-blue-400 text-xl"></i>
            </div>
        </div>
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Completed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $appointmentStats['completed'] }}</p>
                </div>
                <i class="fas fa-check-circle text-green-400 text-xl"></i>
            </div>
        </div>
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-red-600">Cancelled</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $appointmentStats['cancelled'] }}</p>
                </div>
                <i class="fas fa-times-circle text-red-400 text-xl"></i>
            </div>
        </div>
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $appointmentStats['total'] }}</p>
                </div>
                <i class="fas fa-calendar-alt text-gray-400 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form action="{{ route('doctor.appointments.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status" 
                            class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="scheduled" {{ $status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="no_show" {{ $status == 'no_show' ? 'selected' : '' }}>No Show</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" id="date" 
                           value="{{ $date }}"
                           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('doctor.appointments.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Appointments Table -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200/60">
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Symptoms</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-900 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $appointment->student->user->name }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $appointment->student->student_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $appointment->appointment_date->format('M j, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $appointment->appointment_time->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $appointment->reason }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 line-clamp-2">{{ $appointment->symptoms }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $appointment->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                       ($appointment->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($appointment->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('doctor.appointments.show', $appointment) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-calendar-times text-4xl mb-4 text-gray-300"></i>
                                <p class="text-lg">No appointments found</p>
                                <p class="text-sm mt-2">No appointments match your current filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($appointments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200/60">
                {{ $appointments->links() }}
            </div>
        @endif
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

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth interactions
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-blue-200', 'rounded-lg');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-blue-200', 'rounded-lg');
            });
        });
    });
</script>
@endsection
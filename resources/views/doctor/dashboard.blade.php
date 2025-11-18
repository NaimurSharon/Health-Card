@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">Welcome, Dr. {{ auth()->user()->name }}</h3>
                    <p class="text-gray-100">Here's what's happening today</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold">{{ now()->format('l, F j, Y') }}</p>
                    <p class="text-gray-600">{{ now()->format('g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Today's Appointments -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Today's Appointments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['today_appointments'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-day text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Weekly Appointments -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Weekly Appointments</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['weekly_appointments'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-week text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600">Pending Requests</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_requests'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-stethoscope text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Patients -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_patients'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-injured text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Appointments -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Today's Appointments</h4>
            
            @if($todaysAppointments->count() > 0)
                <div class="space-y-4">
                    @foreach($todaysAppointments as $appointment)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $appointment->student->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $appointment->appointment_time->format('g:i A') }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($appointment->reason) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-check text-4xl mb-4 text-gray-300"></i>
                    <p class="text-lg text-gray-500">No appointments for today</p>
                </div>
            @endif
        </div>

        <!-- Upcoming Appointments -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Upcoming Appointments</h4>
            
            @if($upcomingAppointments->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingAppointments as $appointment)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $appointment->student->user->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $appointment->appointment_date->format('M j') }} at {{ $appointment->appointment_time->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-plus text-4xl mb-4 text-gray-300"></i>
                    <p class="text-lg text-gray-500">No upcoming appointments</p>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pending Treatment Requests -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Pending Treatment Requests</h4>
            
            @if($pendingRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingRequests as $request)
                    <div class="p-4 bg-orange-50 rounded-lg border-l-4 border-orange-500">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-medium text-gray-900">{{ $request->student->user->name }}</p>
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                {{ $request->priority == 'emergency' ? 'bg-red-100 text-red-800' : 
                                   ($request->priority == 'high' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($request->priority) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $request->symptoms }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $request->created_at->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-stethoscope text-4xl mb-4 text-gray-300"></i>
                    <p class="text-lg text-gray-500">No pending treatment requests</p>
                </div>
            @endif
        </div>

        <!-- Recent Medical Records -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Recent Medical Records</h4>
            
            @if($recentRecords->count() > 0)
                <div class="space-y-4">
                    @foreach($recentRecords as $record)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-medium text-gray-900">{{ $record->student->user->name }}</p>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($record->record_type) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $record->diagnosis }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $record->record_date->format('M j, Y') }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-file-medical text-4xl mb-4 text-gray-300"></i>
                    <p class="text-lg text-gray-500">No recent medical records</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .content-card {
        /*background: rgba(255, 255, 255, 0.8);*/
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
@endsection
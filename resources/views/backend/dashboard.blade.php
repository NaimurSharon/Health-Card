@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-2xl font-bold">Welcome back, Admin! 👋</h3>
                    <p class="mt-1">Here's what's happening in your school today.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="flex items-center space-x-2 bg-blue-50 rounded-lg px-4 py-2 border border-blue-100">
                        <i class="fas fa-calendar-day text-blue-600"></i>
                        <span class="font-semibold text-blue-800">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Students -->
        <div class="content-card rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Students</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_students'] }}</p>
                    <div class="flex items-center mt-2 text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>{{ $todayOverview['new_students_this_month'] }} new this month</span>
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-blue-50 text-blue-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Teachers -->
        <div class="content-card rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Teaching Staff</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_teachers'] }}</p>
                    <div class="flex items-center mt-2 text-sm text-gray-500">
                        <i class="fas fa-chalkboard-teacher mr-1"></i>
                        <span>Active educators</span>
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-green-50 text-green-600">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Classes -->
        <div class="content-card rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Active Classes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_classes'] }}</p>
                    <div class="flex items-center mt-2 text-sm text-gray-500">
                        <i class="fas fa-door-open mr-1"></i>
                        <span>Running sessions</span>
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-purple-50 text-purple-600">
                    <i class="fas fa-school text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Medical Overview -->
        <div class="content-card rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Medical Alerts</p>
                    <p class="text-3xl font-bold text-red-600">{{ $medicalStats['emergencies_this_week'] }}</p>
                    <div class="flex items-center mt-2 text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        <span>{{ $medicalStats['pending_follow_ups'] }} follow-ups</span>
                    </div>
                </div>
                <div class="p-3 rounded-xl bg-red-50 text-red-600">
                    <i class="fas fa-heartbeat text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row: Today's Overview & Student Growth -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Overview -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold">Today's Overview</h3>
                <span class="text-sm text-gray-500">{{ now()->format('M d, Y') }}</span>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-100">
                    <div class="text-2xl font-bold text-green-600 mb-1">{{ $todayOverview['present_today'] }}</div>
                    <div class="text-sm text-green-700 font-medium">Present Today</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg border border-red-100">
                    <div class="text-2xl font-bold text-red-600 mb-1">{{ $todayOverview['absent_today'] }}</div>
                    <div class="text-sm text-red-700 font-medium">Absent Today</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="text-2xl font-bold text-blue-600 mb-1">{{ $todayOverview['new_students_this_month'] }}</div>
                    <div class="text-sm text-blue-700 font-medium">New Students</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-100">
                    <div class="text-2xl font-bold text-yellow-600 mb-1">{{ $todayOverview['pending_payments'] }}</div>
                    <div class="text-sm text-yellow-700 font-medium">Pending Payments</div>
                </div>
            </div>
        </div>

        <!-- Student Growth Chart -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">Student Growth (Last 6 Months)</h3>
            <div class="space-y-4">
                @foreach($monthlyGrowth as $growth)
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700">{{ $growth['month'] }}</span>
                    <div class="flex items-center space-x-3">
                        <div class="w-24 bg-gray-200 rounded-full h-2">
                            @php
                                $maxStudents = max(array_column($monthlyGrowth, 'students'));
                                $percentage = $maxStudents > 0 ? ($growth['students'] / $maxStudents) * 100 : 0;
                            @endphp
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-600 w-8 text-right">
                            {{ $growth['students'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Medical Records -->
    <div class="content-card rounded-lg shadow-sm">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold">Recent Medical Records</h3>
                <span class="text-sm">{{ $medicalStats['total_medical_records'] }} total records</span>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($recentActivities['medical_records'] as $record)
                <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition-colors duration-200 border border-gray-100">
                    <div class="flex-shrink-0">
                        @php
                            $isEmergency = $record->record_type == 'emergency';
                            $bgColor = $isEmergency ? 'red' : 'blue';
                            $icon = $isEmergency ? 'exclamation-triangle' : 'stethoscope';
                        @endphp
                        <div class="w-10 h-10 rounded-full bg-{{ $bgColor }}-100 flex items-center justify-center border border-{{ $bgColor }}-200">
                            <i class="fas fa-{{ $icon }} text-{{ $bgColor }}-600"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $record->student->user->name ?? 'Unknown Student' }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ Str::limit($record->diagnosis, 60) }}
                        </p>
                        <div class="flex items-center mt-2 text-xs text-gray-500">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ \Carbon\Carbon::parse($record->record_date)->format('M d, Y') }}
                            @if($isEmergency)
                                <span class="ml-3 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">
                                    Emergency
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 border border-gray-200 rounded-lg bg-gray-50">
                    <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-3"></i>
                    <p class="text-gray-500">No recent medical records found</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .content-card {
        /*  background: rgba(255, 255, 255, 0.8*/
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
        // Add smooth hover interactions
        const cards = document.querySelectorAll('.content-card');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.transition = 'all 0.2s ease-in-out';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endsection
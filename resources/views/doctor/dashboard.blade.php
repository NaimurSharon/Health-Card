@extends('layouts.doctor')

@section('title', 'Doctor Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">Welcome, {{ auth()->user()->name }}</h3>
                    <p class="text-gray-100">Here's what's happening today</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold">{{ now()->format('l, F j, Y') }}</p>
                    <p class="text-gray-200">{{ now()->format('g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Today's Video Consultations -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Today's Video Calls</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['today_consultations'] }}</p>
                    @if($ongoingConsultations->count() > 0)
                        <p class="text-sm text-green-600 mt-1">{{ $ongoingConsultations->count() }} ongoing</p>
                    @endif
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-video text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Weekly Consultations -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Weekly Consultations</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['weekly_consultations'] }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $stats['completed_consultations'] }} completed</p>
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
                    <p class="text-sm text-gray-600 mt-1">Unique patients</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-injured text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Video Consultations -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Today's Video Consultations</h4>
            
            @if($todaysConsultations->count() > 0)
                <div class="space-y-4">
                    @foreach($todaysConsultations as $consultation)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $consultation->user->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $consultation->scheduled_for->format('g:i A') }}
                                    @if($consultation->user->student)
                                        <span class="text-xs text-gray-500">({{ $consultation->user->student->roll_number ?? '' }})</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $consultation->type == 'emergency' ? 'bg-red-100 text-red-800' : 
                                   ($consultation->type == 'instant' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($consultation->type) }}
                            </span>
                            @if($consultation->canStartCall())
                                <a href="{{ route('doctor.video-consultation.join', $consultation->id) }}" 
                                   class="block mt-1 text-xs font-medium text-blue-600 hover:text-blue-800">
                                    Join Call
                                </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-video-slash text-4xl mb-4 text-gray-300"></i>
                    <p class="text-lg text-gray-500">No video consultations for today</p>
                </div>
            @endif
        </div>

        <!-- Upcoming Consultations -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Upcoming Consultations</h4>
            
            @if($upcomingConsultations->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingConsultations as $consultation)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $consultation->user->name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ $consultation->scheduled_for->format('M j, g:i A') }}
                                </p>
                                @if($consultation->user->student)
                                    <p class="text-xs text-gray-500">
                                        {{ $consultation->user->student->class->name ?? '' }} 
                                        {{ $consultation->user->student->section->name ?? '' }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ ucfirst($consultation->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-plus text-4xl mb-4 text-gray-300"></i>
                    <p class="text-lg text-gray-500">No upcoming consultations</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Ongoing Consultations -->
    @if($ongoingConsultations->count() > 0)
    <div class="content-card rounded-lg p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-xl font-semibold text-gray-900">Ongoing Video Calls</h4>
            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                {{ $ongoingConsultations->count() }} Active
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($ongoingConsultations as $consultation)
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                            <i class="fas fa-user-md text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $consultation->user->name }}</p>
                            <p class="text-sm text-gray-600">
                                Started: {{ $consultation->started_at->format('g:i A') }}
                            </p>
                        </div>
                    </div>
                    <div class="relative">
                        <span class="flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-700">{{ $consultation->symptoms ? Str::limit($consultation->symptoms, 40) : 'No symptoms noted' }}</p>
                        @if($consultation->duration)
                            <p class="text-xs text-gray-600 mt-1">Duration: {{ gmdate('H:i:s', $consultation->duration) }}</p>
                        @endif
                    </div>
                    <a href="{{ route('doctor.video-consultation.join', $consultation->id) }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                        Join Now
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

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
                                {{ $request->urgency_level == 'emergency' ? 'bg-red-100 text-red-800' : 
                                   ($request->urgency_level == 'high' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($request->urgency_level ?? 'normal') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $request->symptoms }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $request->created_at->diffForHumans() }}</p>
                        <div class="mt-3 flex space-x-2">
                            <a href="{{ route('doctor.treatment-requests.show', $request->id) }}" 
                               class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                View Details
                            </a>
                            <a href="{{ route('doctor.treatment-requests.show', $request->id) }}" 
                               class="text-xs text-green-600 hover:text-green-800 font-medium">
                                Respond to Request
                            </a>
                        </div>
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
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                {{ $record->record_type == 'emergency' ? 'bg-red-100 text-red-800' : 
                                   ($record->record_type == 'checkup' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($record->record_type) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $record->diagnosis ?? 'No diagnosis recorded' }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                            <span>{{ $record->record_date->format('M j, Y') }}</span>
                            @if($record->follow_up_date)
                                <span class="text-orange-600">Follow-up: {{ $record->follow_up_date->format('M j') }}</span>
                            @endif
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('doctor.medical-records.show', $record->id) }}" 
                               class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                View Full Record
                            </a>
                        </div>
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
@endsection
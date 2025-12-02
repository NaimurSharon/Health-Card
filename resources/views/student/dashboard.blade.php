@extends('layouts.student')

@section('title', 'Home')
@section('subtitle', 'Welcome to Student Health Portal')

@section('content')
<div class="space-y-8">
    <!-- Hero Section -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-blue-600 px-8 py-12 text-white">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to Your Student Portal</h1>
                <p class="text-xl mb-8 text-blue-100">Your health and academic success are our priority</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('student.health-report') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        View Health Report
                    </a>
                    <a href="{{ route('student.school-diary') }}" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-600 transition-colors">
                        Open School Diary
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Health Status -->
        <a href="{{ route('student.health-report') }}" class="content-card rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Health Status</p>
                    <p class="text-2xl font-bold text-gray-900">
                        @if($activeHealthCard)
                            <span class="text-green-600">Active</span>
                        @else
                            <span class="text-gray-500">No Card</span>
                        @endif
                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        @if($recentHealthRecords->count() > 0)
                            {{ $recentHealthRecords->count() }} recent records
                        @else
                            No recent records
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-heartbeat text-green-600 text-xl"></i>
                </div>
            </div>
        </a>

        <!-- Today's Schedule -->
        <a href="#" class="content-card rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Today's Classes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $todaysClassesCount }}</p>
                    <p class="text-sm text-gray-600 mt-1">
                        @if($currentPeriod)
                            Now: {{ $currentPeriod->subject->name ?? 'Class' }}
                        @else
                            No ongoing class
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                </div>
            </div>
        </a>

        <!-- Diary Status -->
        <a href="{{ route('student.school-diary') }}" class="content-card rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600">Weekly Diary</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $diaryEntriesCount }}/7</p>
                    <p class="text-sm text-gray-600 mt-1">
                        @if($todaysDiaryEntry)
                            <span class="text-green-600">Today filled</span>
                        @else
                            <span class="text-orange-600">Fill today's</span>
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-book text-orange-600 text-xl"></i>
                </div>
            </div>
        </a>
        
        <!-- Video Consultations -->
        <a href="{{ route('student.hello-doctor') }}" class="content-card rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600">Video Consultations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $todaysConsultations->count() + $upcomingConsultations->count() }}</p>
                    <p class="text-sm text-gray-600 mt-1">
                        @if($pendingTreatmentRequests > 0)
                            {{ $pendingTreatmentRequests }} pending requests
                        @else
                            No pending requests
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-video text-purple-600 text-xl"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Health Tips Section -->
    @php
        $healthTips = \App\Models\HealthTip::where('status', 'published')
            ->where(function($query) {
                $query->where('target_audience', 'all')
                      ->orWhere('target_audience', 'students');
            })
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    @endphp

    <!-- Main Content Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: School Information & Quick Actions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- School Information -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-school me-2"></i>School Information
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        @if($school && $school->logo)
                            <img src="{{ asset('public/storage/' . $school->logo) }}" alt="School Logo" class="w-16 h-16 rounded-lg">
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-school text-green-600 text-2xl"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $school->name ?? 'Your School' }}</h4>
                            <p class="text-sm text-gray-600">Class {{ $class->name ?? 'N/A' }}, Section {{ $section->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Class Teacher:</span>
                            <span class="font-medium text-gray-900">{{ $section->teacher->name ?? 'Not Assigned' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Room Number:</span>
                            <span class="font-medium text-gray-900">{{ $section->room_number ?? 'Not Assigned' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Roll Number:</span>
                            <span class="font-medium text-gray-900">{{ $student->roll_number ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600">Shift:</span>
                            <span class="font-medium text-gray-900 capitalize">{{ $class->shift ?? 'Morning' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <!--<div class="content-card rounded-lg p-6 shadow-sm">-->
            <!--    <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">-->
            <!--        <i class="fas fa-rocket me-2"></i>Quick Actions-->
            <!--    </h3>-->
                
            <!--    <div class="grid grid-cols-2 gap-4">-->
            <!--        <a href="{{ route('student.health-report') }}" class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-center group">-->
            <!--            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-blue-200 transition-colors">-->
            <!--                <i class="fas fa-file-medical text-blue-600"></i>-->
            <!--            </div>-->
            <!--            <span class="text-sm font-medium text-gray-900">Health Report</span>-->
            <!--        </a>-->

            <!--        <a href="{{ route('student.id-card') }}" class="p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors text-center group">-->
            <!--            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-green-200 transition-colors">-->
            <!--                <i class="fas fa-id-card text-green-600"></i>-->
            <!--            </div>-->
            <!--            <span class="text-sm font-medium text-gray-900">ID Card</span>-->
            <!--        </a>-->

            <!--        <a href="{{ route('student.school-diary') }}" class="p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors text-center group">-->
            <!--            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-purple-200 transition-colors">-->
            <!--                <i class="fas fa-book text-purple-600"></i>-->
            <!--            </div>-->
            <!--            <span class="text-sm font-medium text-gray-900">School Diary</span>-->
            <!--        </a>-->

            <!--        <a href="{{ route('student.hello-doctor') }}" class="p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors text-center group">-->
            <!--            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-orange-200 transition-colors">-->
            <!--                <i class="fas fa-user-md text-orange-600"></i>-->
            <!--            </div>-->
            <!--            <span class="text-sm font-medium text-gray-900">Hello Doctor</span>-->
            <!--        </a>-->
            <!--    </div>-->
            <!--</div>-->
        </div>

        <!-- Middle Column: Announcements & Schedule -->
        <div class="lg:col-span-2 space-y-6">
            <!-- City Corporation Notices -->
            @php
                $cityNotices = \App\Models\Notice::where('status', 'published')
                    ->where('target_roles', 'like', '%city_corporation%')
                    ->where(function($query) {
                        $query->where('expiry_date', '>=', now())
                              ->orWhereNull('expiry_date');
                    })
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();
            @endphp

            @if($cityNotices->count() > 0)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-city me-2"></i>City Corporation Notices
                    </h3>
                    <a href="{{ route('student.city-notices') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All
                    </a>
                </div>
                
                <div class="space-y-4">
                    @foreach($cityNotices as $notice)
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-semibold text-gray-900">{{ Str::limit($notice->title, 60) }}</h4>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                City Notice
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($notice->content, 120) }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $notice->created_at->format('M j, Y') }}</span>
                            @if($notice->expiry_date)
                                <span>Expires: {{ $notice->expiry_date->format('M j, Y') }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Today's Schedule & Health -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Today's Schedule -->
                <div class="content-card rounded-lg p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-calendar-day me-2"></i>Today's Schedule
                        </h4>
                        <span class="text-sm text-gray-600">{{ now()->format('M j') }}</span>
                    </div>
                    
                    @if($todaysSchedule->count() > 0)
                        <div class="space-y-3">
                            @foreach($todaysSchedule->take(4) as $period)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg 
                                {{ $currentPeriod && $currentPeriod->id == $period->id ? 'border-2 border-blue-500 bg-blue-50' : '' }}">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 {{ $currentPeriod && $currentPeriod->id == $period->id ? 'bg-blue-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                        <span class="text-xs font-semibold {{ $currentPeriod && $currentPeriod->id == $period->id ? 'text-blue-600' : 'text-gray-600' }}">
                                            {{ $period->period }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $period->subject->name ?? 'Free Period' }}</p>
                                        <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($period->start_time)->format('g:i A') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">{{ $period->room ?? 'Classroom' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($todaysSchedule->count() > 4)
                            <div class="mt-4 text-center">
                                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View Full Schedule
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times text-xl mb-2 text-gray-300"></i>
                            <p class="text-gray-500 text-sm">No classes scheduled for today</p>
                        </div>
                    @endif
                </div>

                <!-- Health & Consultations Overview -->
                <div class="content-card rounded-lg p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-heartbeat me-2"></i>Health & Consultations
                        </h4>
                        <a href="{{ route('student.hello-doctor') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Book Now
                        </a>
                    </div>
                    
                    <!-- Health Card Status -->
                    @if($activeHealthCard)
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-900">Health Card Active</p>
                                <p class="text-xs text-green-700">Expires: {{ $activeHealthCard->expiry_date->format('M j, Y') }}</p>
                            </div>
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Today's Consultations -->
                    <div class="mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Today's Video Calls</h5>
                        @if($todaysConsultations->count() > 0)
                            <div class="space-y-2">
                                @foreach($todaysConsultations->take(2) as $consultation)
                                <div class="flex items-center justify-between p-2 bg-white border border-gray-100 rounded">
                                    <div>
                                        <p class="text-xs font-medium text-gray-900">
                                            Dr. {{ $consultation->doctor->name ?? 'Medical Staff' }}
                                        </p>
                                        <p class="text-xs text-gray-600">
                                            {{ $consultation->scheduled_for->format('g:i A') }}
                                        </p>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded-full 
                                        {{ $consultation->status == 'ongoing' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ ucfirst($consultation->status) }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-500 text-center py-2">No consultations today</p>
                        @endif
                    </div>
                    
                    <!-- Upcoming Consultations -->
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Upcoming Consultations</h5>
                        @if($upcomingConsultations->count() > 0)
                            <div class="space-y-2">
                                @foreach($upcomingConsultations->take(2) as $consultation)
                                <div class="flex items-center justify-between p-2 bg-white border border-gray-100 rounded">
                                    <div>
                                        <p class="text-xs font-medium text-gray-900">
                                            Dr. {{ $consultation->doctor->name ?? 'Medical Staff' }}
                                        </p>
                                        <p class="text-xs text-gray-600">
                                            {{ $consultation->scheduled_for->format('M j, g:i A') }}
                                        </p>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ $consultation->type }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-500 text-center py-2">No upcoming consultations</p>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <!-- School & Class Announcements -->
            <div class="content-card rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bullhorn me-2"></i>Latest Announcements
                    </h3>
                    <a href="{{ route('student.school-notices') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All
                    </a>
                </div>
                
                @if($schoolNotices->count() > 0 || isset($classAnnouncements) && $classAnnouncements->count() > 0)
                    <div class="space-y-4">
                        @foreach($schoolNotices->take(3) as $notice)
                        <div class="p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="font-semibold text-gray-900">{{ Str::limit($notice->title, 50) }}</h4>
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $notice->priority == 'high' ? 'bg-red-100 text-red-800' : 
                                       ($notice->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($notice->priority) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($notice->content, 100) }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $notice->created_at->format('M j, Y') }}</span>
                                @if($notice->expiry_date)
                                    <span>Expires: {{ $notice->expiry_date->format('M j') }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        
                        @if(isset($classAnnouncements) && $classAnnouncements->count() > 0)
                            @foreach($classAnnouncements->take(2) as $announcement)
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-semibold text-gray-900">{{ Str::limit($announcement->title, 50) }}</h4>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">
                                        Class Specific
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ Str::limit($announcement->content, 100) }}</p>
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $announcement->created_at->format('M j, Y') }}</span>
                                    @if($announcement->expiry_date)
                                        <span>Expires: {{ $announcement->expiry_date->format('M j') }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-bell-slash text-3xl mb-3 text-gray-300"></i>
                        <p class="text-gray-500">No announcements at the moment</p>
                    </div>
                @endif
            </div>
            
            <!-- City Corporation Notices Section -->
            @if($cityCorporationNotices->count() > 0)
            <div class="content-card rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-xl font-semibold text-gray-900  pb-3 mb-4 flex items-center">
                        <i class="fas fa-city text-blue-600 me-2"></i>City Corporation Notices
                    </h4>
                    
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
                
                <div class="space-y-4">
                    @foreach($cityCorporationNotices as $notice)
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start justify-between mb-3">
                            <h5 class="text-lg font-semibold text-gray-900">{{ $notice->title }}</h5>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                City Corporation
                            </span>
                        </div>
                        <p class="text-gray-700 mb-3">{{ $notice->content }}</p>
                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span>Expires: {{ $notice->expiry_date->format('M j, Y') }}</span>
                            <span>Priority: <span class="font-medium capitalize">{{ $notice->priority }}</span></span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
    
    <!-- Upcoming Exams -->
    @if($upcomingExams && $upcomingExams->count() > 0)
    <div class="content-card rounded-lg p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-file-alt me-2"></i>Upcoming Exams
            </h3>
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($upcomingExams as $exam)
            <div class="p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <h5 class="font-semibold text-gray-900">{{ $exam->subject->name ?? 'General' }}</h5>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                        {{ $exam->exam_date->format('M j') }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-2">{{ $exam->title }}</p>
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>Duration: {{ $exam->duration_minutes }} mins</span>
                    <span>Marks: {{ $exam->total_marks }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    @if($healthTips->count() > 0)
    <div class="content-card rounded-lg p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-heart-circle-check me-2"></i>Health Tips & Advice
            </h3>
            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                View All Tips
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($healthTips as $tip)
            <div class="p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium capitalize">
                        {{ str_replace('_', ' ', $tip->category) }}
                    </span>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">{{ $tip->title }}</h4>
                <p class="text-sm text-gray-600 line-clamp-3">{{ Str::limit($tip->content, 120) }}</p>
                <div class="mt-3 text-xs text-gray-500">
                    {{ $tip->created_at->format('M j, Y') }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
.content-card {
    backdrop-filter: blur(12px);
    background:#fff;
    border: 1px solid rgba(229, 231, 235, 0.8);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
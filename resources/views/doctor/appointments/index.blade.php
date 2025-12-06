@extends('layouts.doctor')
@section('title', 'My Consultations - Doctor')
@section('content')
    <div class="max-w-md mx-auto lg:max-w-4xl">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Dr. {{ Auth::user()->name }}</h1>
            <p class="text-gray-500">Your consultation dashboard</p>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-4 gap-4 mb-8 text-center">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-xs text-gray-400 mb-1">Scheduled</p>
                <p class="text-lg font-bold text-blue-600">{{ $consultationStats['scheduled'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-xs text-gray-400 mb-1">Completed</p>
                <p class="text-lg font-bold text-green-600">{{ $consultationStats['completed'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-xs text-gray-400 mb-1">Ongoing</p>
                <p class="text-lg font-bold text-orange-600">{{ $consultationStats['ongoing'] }}</p>
            </div>
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-xs text-gray-400 mb-1">Total</p>
                <p class="text-lg font-bold text-gray-800">{{ $consultationStats['total'] }}</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('doctor.consultations.index', ['status' => 'scheduled']) }}" class="block group">
                    <div
                        class="bg-[#C4E7FF] p-6 rounded-3xl h-full transition-transform transform group-hover:scale-[1.02]">
                        <p class="text-sm text-gray-600 mb-1">View All</p>
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Scheduled Calls</h3>
                        <div class="flex -space-x-2 overflow-hidden">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-300 border-2 border-white flex items-center justify-center text-xs text-white">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div
                                class="w-8 h-8 rounded-full bg-green-300 border-2 border-white flex items-center justify-center text-xs text-white">
                                <i class="fas fa-video"></i>
                            </div>
                        </div>
                    </div>
                </a>
                <div class="bg-[#FFD8E4] p-6 rounded-3xl h-full">
                    <p class="text-sm text-gray-600 mb-1">Medical</p>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Patient Records</h3>
                    <div class="flex -space-x-2 overflow-hidden">
                        <div
                            class="w-8 h-8 rounded-full bg-orange-300 border-2 border-white flex items-center justify-center text-xs text-white">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <div
                            class="w-8 h-8 rounded-full bg-red-300 border-2 border-white flex items-center justify-center text-xs text-white">
                            <i class="fas fa-user-injured"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Schedule -->
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900">Today's Schedule</h2>
                <div class="flex space-x-2">
                    <span class="text-sm font-medium text-gray-600 self-center">{{ now()->format('F d, Y') }}</span>
                </div>
            </div>

            <!-- Filter Buttons -->
            <div class="flex space-x-2 mb-6 overflow-x-auto pb-2 no-scrollbar">
                <a href="{{ route('doctor.consultations.index', ['status' => 'all']) }}"
                    class="px-4 py-2 rounded-full text-sm font-medium {{ $status === 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                    All ({{ $consultationStats['total'] }})
                </a>
                <a href="{{ route('doctor.consultations.index', ['status' => 'scheduled']) }}"
                    class="px-4 py-2 rounded-full text-sm font-medium {{ $status === 'scheduled' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                    Scheduled ({{ $consultationStats['scheduled'] }})
                </a>
                <a href="{{ route('doctor.consultations.index', ['status' => 'ongoing']) }}"
                    class="px-4 py-2 rounded-full text-sm font-medium {{ $status === 'ongoing' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                    Ongoing ({{ $consultationStats['ongoing'] }})
                </a>
                <a href="{{ route('doctor.consultations.index', ['status' => 'completed']) }}"
                    class="px-4 py-2 rounded-full text-sm font-medium {{ $status === 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                    Completed ({{ $consultationStats['completed'] }})
                </a>
            </div>

            <!-- Date Filter -->
            <div class="mb-6">
                <form method="GET" action="{{ route('doctor.consultations.index') }}" class="flex items-center space-x-2">
                    <input type="date" name="date" value="{{ $date }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Filter
                    </button>
                    @if($date)
                        <a href="{{ route('doctor.consultations.index', ['status' => $status]) }}"
                            class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Consultation Cards -->
            <div class="space-y-4">
                @forelse($consultations as $consultation)
                    <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div class="text-xs font-medium px-3 py-1 rounded-full
                                                        @if($consultation->status === 'scheduled') bg-blue-100 text-blue-800
                                                        @elseif($consultation->status === 'ongoing') bg-orange-100 text-orange-800
                                                        @elseif($consultation->status === 'completed') bg-green-100 text-green-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                {{ $consultation->scheduled_for->format('h:i A') }}
                                @if($consultation->duration)
                                    - {{ $consultation->scheduled_for->addMinutes($consultation->duration)->format('h:i A') }}
                                @endif
                            </div>
                            <span class="text-sm font-medium capitalize 
                                                        @if($consultation->status === 'scheduled') text-blue-600
                                                        @elseif($consultation->status === 'ongoing') text-orange-600
                                                        @elseif($consultation->status === 'completed') text-green-600
                                                        @else text-gray-600 @endif">
                                {{ $consultation->status }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $consultation->user->name ?? 'Unknown Patient' }}
                                </h3>
                                <p class="text-sm text-gray-600">{{ ucfirst($consultation->patient_type) }}</p>
                            </div>
                            <div
                                class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold overflow-hidden">
                                @if($consultation->user && $consultation->user->profile_photo_url)
                                    <img src="{{ $consultation->user->profile_photo_url }}" alt="{{ $consultation->user->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    {{ substr($consultation->user->name ?? 'P', 0, 1) }}
                                @endif
                            </div>
                        </div>

                        @if($consultation->symptoms)
                            <p class="text-sm text-gray-600 mb-4">
                                <span class="font-medium">Symptoms:</span> {{ Str::limit($consultation->symptoms, 100) }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-500">Scheduled for</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $consultation->scheduled_for->format('M d, Y') }}
                                </p>
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('doctor.consultations.show', $consultation->id) }}"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition-colors">
                                    Details
                                </a>

                                @if($consultation->canStartCall())
                                    <a href="{{ route('video-consultation.join', $consultation->id) }}"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors flex items-center gap-2">
                                        <i class="fas fa-video text-xs"></i>
                                        {{ $consultation->status === 'ongoing' ? 'Join' : 'Start' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-50 p-8 rounded-3xl text-center border border-gray-100">
                        <div
                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <i class="fas fa-calendar-times text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">No Consultations Found</h3>
                        <p class="text-gray-500 mb-6">
                            @if($status !== 'all')
                                No {{ $status }} consultations found.
                            @else
                                You don't have any consultations scheduled.
                            @endif
                        </p>
                        @if($date || $status !== 'all')
                            <a href="{{ route('doctor.consultations.index') }}"
                                class="inline-block bg-gray-900 text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-800 transition-colors">
                                View All Consultations
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($consultations->hasPages())
                <div class="mt-6">
                    {{ $consultations->withQueryString()->links() }}
                </div>
            @endif
        </div>

        <!-- Upcoming Consultations -->
        @if($upcomingConsultations->count() > 0)
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900">Upcoming This Week</h2>
                    <a href="{{ route('doctor.consultations.index', ['status' => 'scheduled']) }}"
                        class="text-sm text-blue-600 font-medium hover:underline">View All</a>
                </div>

                <div class="space-y-3">
                    @foreach($upcomingConsultations as $consultation)
                        <div
                            class="bg-white p-4 rounded-2xl border border-gray-100 flex items-center justify-between hover:shadow-sm transition-shadow">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <i class="fas fa-video"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $consultation->user->name }}</h4>
                                    <p class="text-xs text-gray-500">
                                        {{ $consultation->scheduled_for->format('M d, h:i A') }} •
                                        <span class="font-medium">{{ $consultation->patient_type }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($consultation->canStartCall())
                                    <a href="{{ route('video-consultation.join', $consultation->id) }}"
                                        class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 hover:bg-green-200">
                                        <i class="fas fa-play text-xs"></i>
                                    </a>
                                @endif
                                <a href="{{ route('doctor.consultations.show', $consultation->id) }}"
                                    class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                                    <i class="fas fa-chevron-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection
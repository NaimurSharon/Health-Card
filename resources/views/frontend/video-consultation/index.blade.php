@extends('layouts.global')

@section('title', 'Video Consultations')

@section('content')
<div class="max-w-md mx-auto lg:max-w-4xl">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Welcome, {{ Auth::user()->name }}</h1>
        <p class="text-gray-500">How are you feeling today?</p>
    </div>

    <!-- Stats Section (Visual Placeholder to match design) -->
    <div class="grid grid-cols-3 gap-4 mb-8 text-center">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Consultations</p>
            <p class="text-lg font-bold text-gray-800">{{ $consultations->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Upcoming</p>
            <p class="text-lg font-bold text-gray-800">{{ $upcomingConsultations->count() }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400 mb-1">Completed</p>
            <p class="text-lg font-bold text-gray-800">{{ $consultations->where('status', 'completed')->count() }}</p>
        </div>
    </div>

    <!-- Our Specialists / Quick Actions -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-900">Quick Actions</h2>
            <a href="{{ route('hello-doctor') }}" class="text-sm text-blue-600 font-medium hover:underline">New Consultation</a>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('hello-doctor') }}" class="block group">
                <div class="bg-[#C4E7FF] p-6 rounded-3xl h-full transition-transform transform group-hover:scale-[1.02]">
                    <p class="text-sm text-gray-600 mb-1">Need a doctor?</p>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Book Consultation</h3>
                    <div class="flex -space-x-2 overflow-hidden">
                        <div class="w-8 h-8 rounded-full bg-blue-300 border-2 border-white flex items-center justify-center text-xs text-white"><i class="fas fa-user-md"></i></div>
                        <div class="w-8 h-8 rounded-full bg-green-300 border-2 border-white flex items-center justify-center text-xs text-white"><i class="fas fa-stethoscope"></i></div>
                        <div class="w-8 h-8 rounded-full bg-gray-800 border-2 border-white flex items-center justify-center text-xs text-white">+</div>
                    </div>
                </div>
            </a>
            <div class="bg-[#FFD8E4] p-6 rounded-3xl h-full">
                <p class="text-sm text-gray-600 mb-1">History</p>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Past Records</h3>
                <div class="flex -space-x-2 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-orange-300 border-2 border-white flex items-center justify-center text-xs text-white"><i class="fas fa-file-medical"></i></div>
                    <div class="w-8 h-8 rounded-full bg-red-300 border-2 border-white flex items-center justify-center text-xs text-white"><i class="fas fa-notes-medical"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Your Schedule -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">Your Schedule</h2>
            <div class="flex space-x-2">
                <button class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200"><i class="fas fa-chevron-left text-xs"></i></button>
                <span class="text-sm font-medium text-gray-600 self-center">{{ now()->format('F Y') }}</span>
                <button class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200"><i class="fas fa-chevron-right text-xs"></i></button>
            </div>
        </div>

        <!-- Calendar Strip -->
        <div class="flex justify-between mb-6 overflow-x-auto pb-2 no-scrollbar">
            @for($i = 0; $i < 5; $i++)
                @php 
                    $date = now()->addDays($i); 
                    $isActive = $i === 0; // Highlighting today for demo
                @endphp
                <div class="flex flex-col items-center min-w-[60px]">
                    <span class="text-xs text-gray-400 mb-2">{{ $date->format('D') }}</span>
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-sm font-bold {{ $isActive ? 'bg-[#C4E7FF] text-gray-900' : 'bg-white text-gray-600 border border-gray-100' }}">
                        {{ $date->format('d') }}
                    </div>
                </div>
            @endfor
        </div>

        <!-- Consultation Cards -->
        <div class="space-y-4">
            @forelse($upcomingConsultations as $consultation)
                <div class="bg-[#E8DEF8] p-5 rounded-3xl transition-transform hover:scale-[1.01]">
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-xs font-medium text-purple-800 bg-white/50 px-3 py-1 rounded-full">
                            {{ $consultation->scheduled_for->format('h:i A') }} - {{ $consultation->scheduled_for->addMinutes($consultation->duration ?? 30)->format('h:i A') }}
                        </div>
                        <button class="text-gray-500 hover:text-gray-700"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-1">Video Consultation</h3>
                    <p class="text-sm text-gray-600 mb-4">{{ Str::limit($consultation->symptoms, 50) }}</p>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-purple-200 flex items-center justify-center text-purple-700 font-bold overflow-hidden">
                                @if($consultation->doctor->profile_photo_url)
                                    <img src="{{ $consultation->doctor->profile_photo_url }}" alt="{{ $consultation->doctor->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($consultation->doctor->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Doctor</p>
                                <p class="text-sm font-bold text-gray-900">Dr. {{ $consultation->doctor->name }}</p>
                            </div>
                        </div>
                        
                        @if($consultation->isAvailable())
                            <a href="{{ route('video-consultation.join', $consultation->id) }}" class="bg-black text-white px-5 py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-800 transition-colors flex items-center gap-2">
                                <i class="fas fa-video text-xs"></i>
                                Join Now
                            </a>
                        @else
                            <a href="{{ route('video-consultation.show', $consultation->id) }}" class="bg-white/50 text-gray-900 px-4 py-2 rounded-xl text-sm font-bold hover:bg-white transition-colors">
                                Details
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 p-8 rounded-3xl text-center border border-gray-100">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">No Upcoming Consultations</h3>
                    <p class="text-gray-500 mb-6">You don't have any scheduled consultations at the moment.</p>
                    <a href="{{ route('hello-doctor') }}" class="inline-block bg-black text-white px-6 py-3 rounded-xl font-medium hover:bg-gray-800 transition-colors">
                        Book Appointment
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Past Consultations (Simplified List) -->
    @if($consultations->where('status', '!=', 'scheduled')->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Recent History</h2>
        <div class="space-y-3">
            @foreach($consultations->where('status', '!=', 'scheduled')->take(5) as $consultation)
            <div class="bg-white p-4 rounded-2xl border border-gray-100 flex items-center justify-between hover:shadow-sm transition-shadow">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-xl {{ $consultation->status == 'completed' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }} flex items-center justify-center text-xl">
                        <i class="fas {{ $consultation->status == 'completed' ? 'fa-check' : 'fa-history' }}"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">Dr. {{ $consultation->doctor->name }}</h4>
                        <p class="text-xs text-gray-500">{{ $consultation->created_at->format('M d, Y') }} • {{ ucfirst($consultation->status) }}</p>
                    </div>
                </div>
                <a href="{{ route('video-consultation.show', $consultation->id) }}" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                    <i class="fas fa-chevron-right text-xs"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>
@endsection

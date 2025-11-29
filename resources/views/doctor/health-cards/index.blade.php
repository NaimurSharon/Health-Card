<<<<<<< HEAD
@extends('layouts.app')
=======
@extends('layouts.doctor')
>>>>>>> c356163 (video call ui setup)

@section('title', 'Health Cards')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">Student Health Cards</h3>
            <div class="text-sm text-gray-600">
                <span class="font-medium">{{ $healthCards->total() }}</span> health cards
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <form action="{{ route('doctor.health-cards.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-3">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Health Cards</label>
                    <input type="text" name="search" id="search" 
                           value="{{ $search }}"
                           class="w-full px-4 py-3 bg-white/80 border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                           placeholder="Search by student name, card number...">
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    <a href="{{ route('doctor.health-cards.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg text-sm font-medium transition-colors flex items-center">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Health Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($healthCards as $healthCard)
            <div class="content-card rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-id-card text-green-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $healthCard->student->user->name }}</h4>
                            <p class="text-xs text-gray-600">Card: {{ $healthCard->card_number }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                        {{ $healthCard->status == 'active' ? 'bg-green-100 text-green-800' : 
                           ($healthCard->status == 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($healthCard->status) }}
                    </span>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Student ID:</span>
                        <span class="text-sm font-medium">{{ $healthCard->student->student_id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Class:</span>
                        <span class="text-sm font-medium">{{ $healthCard->student->class->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Issue Date:</span>
                        <span class="text-sm font-medium">{{ $healthCard->issue_date->format('M j, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Expiry Date:</span>
                        <span class="text-sm font-medium {{ $healthCard->expiry_date->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $healthCard->expiry_date->format('M j, Y') }}
                        </span>
                    </div>
                </div>

                <!-- Medical Summary Preview -->
                @if($healthCard->medical_summary)
                <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-xs font-medium text-gray-600 mb-1">Medical Summary</p>
                    <p class="text-xs text-gray-700 line-clamp-2">{{ $healthCard->medical_summary }}</p>
                </div>
                @endif

                <!-- Emergency Instructions -->
                @if($healthCard->emergency_instructions)
                <div class="mb-4 p-3 bg-red-50 rounded-lg border-l-4 border-red-400">
                    <p class="text-xs font-medium text-gray-600 mb-1">Emergency Instructions</p>
                    <p class="text-xs text-gray-700 line-clamp-2">{{ $healthCard->emergency_instructions }}</p>
                </div>
                @endif

                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <span class="text-xs text-gray-500">
                        @if($healthCard->expiry_date->isFuture())
                            Expires in {{ $healthCard->expiry_date->diffForHumans() }}
                        @else
                            Expired {{ $healthCard->expiry_date->diffForHumans() }}
                        @endif
                    </span>
                    <a href="{{ route('doctor.health-cards.show', $healthCard) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-medium transition-colors flex items-center">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                </div>
            </div>
        @empty
            <div class="md:col-span-3 content-card rounded-lg p-8 text-center">
                <i class="fas fa-id-card text-4xl mb-4 text-gray-300"></i>
                <p class="text-lg text-gray-500">No health cards found</p>
                <p class="text-sm mt-2">No health cards match your search criteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($healthCards->hasPages())
        <div class="content-card rounded-lg p-6">
            {{ $healthCards->links() }}
        </div>
    @endif
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
        const inputs = document.querySelectorAll('input');
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
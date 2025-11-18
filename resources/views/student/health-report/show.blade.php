@extends('layouts.student')

@section('title', 'Health Report Details')
@section('subtitle', 'Comprehensive view of your health checkup report')

@section('content')
<div class="space-y-6 lg:space-y-8">
    <!-- Header Section -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-blue-600 px-4 py-6 sm:px-8 sm:py-8 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">Health Report Details</h1>
                    <p class="text-blue-100 text-sm sm:text-base">Checkup conducted on {{ $healthReport->checkup_date->format('F j, Y') }}</p>
                </div>
                <a href="{{ route('student.health-report') }}" 
                   class="bg-white text-green-600 px-4 py-3 sm:px-6 sm:py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors w-full sm:w-auto text-center">
                    <i class="fas fa-arrow-left me-2"></i>Back to Reports
                </a>
            </div>
        </div>
    </div>

    <!-- Report Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
        <div class="content-card rounded-lg p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <i class="fas fa-calendar-check text-blue-600"></i>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-700">Checkup Date</p>
            <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $healthReport->checkup_date->format('M j, Y') }}</p>
        </div>

        <div class="content-card rounded-lg p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <i class="fas fa-user-md text-green-600"></i>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-700">Checked By</p>
            <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $healthReport->checked_by }}</p>
        </div>

        <div class="content-card rounded-lg p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <i class="fas fa-school text-purple-600"></i>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-700">School</p>
            <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $healthReport->school->name ?? 'Health Center' }}</p>
        </div>

        <div class="content-card rounded-lg p-4 sm:p-6 text-center">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-2 sm:mb-3">
                <i class="fas fa-chart-line text-orange-600"></i>
            </div>
            <p class="text-xs sm:text-sm font-medium text-gray-700">Metrics Recorded</p>
            <p class="text-base sm:text-lg font-semibold text-gray-900">{{ $healthReport->data->count() }}</p>
        </div>
    </div>

    <!-- Health Data by Category -->
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-2 sm:space-y-0">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-chart-bar me-2"></i>Health Checkup Data
            </h3>
            <span class="text-xs sm:text-sm text-gray-500">{{ $healthReport->data->count() }} metrics</span>
        </div>
        
        @if($healthReport->data->count() > 0)
            <div class="space-y-6 sm:space-y-8">
                @php
                    $currentCategory = null;
                    $categoryCount = 0;
                @endphp
                
                @foreach($healthReport->data as $index => $data)
                    @if($data->field->category->name != $currentCategory)
                        @if($currentCategory !== null)
                            </div>
                        @endif
                        @php 
                            $currentCategory = $data->field->category->name;
                            $categoryCount = 0;
                        @endphp
                        <div class="border-l-4 border-blue-500 pl-3 sm:pl-4">
                            <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                                <i class="fas fa-folder me-2 text-blue-500"></i>{{ $currentCategory }}
                            </h4>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 sm:gap-4 mb-3 sm:mb-4 p-3 bg-gray-50 rounded-lg">
                        <div class="md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700">{{ $data->field->label }}</label>
                        </div>
                        <div class="md:col-span-3">
                            <p class="text-gray-900 font-medium text-sm sm:text-base">{{ $data->field_value ?? 'Not Recorded' }}</p>
                            @if($data->field->description)
                                <p class="text-xs text-gray-500 mt-1">{{ $data->field->description }}</p>
                            @endif
                        </div>
                    </div>

                    @php $categoryCount++; @endphp
                    
                    @if($loop->last)
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="text-center py-8 sm:py-12">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <i class="fas fa-chart-bar text-xl sm:text-2xl text-gray-300"></i>
                </div>
                <h4 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No Health Data Recorded</h4>
                <p class="text-gray-500 text-sm sm:text-base">No health metrics were recorded during this checkup.</p>
            </div>
        @endif
    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        <!-- Download Report -->
        <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-download text-blue-600 text-lg sm:text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Download Report</h4>
                    <p class="text-xs sm:text-sm text-gray-600">Get a PDF copy for your records</p>
                </div>
            </div>
            <button class="w-full mt-3 sm:mt-4 bg-blue-600 text-white py-2 sm:py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm sm:text-base">
                <i class="fas fa-file-pdf me-2"></i>Download PDF Report
            </button>
        </div>

        <!-- Print Report -->
        <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
            <div class="flex items-center space-x-3 sm:space-x-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-print text-green-600 text-lg sm:text-xl"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 text-sm sm:text-base">Print Report</h4>
                    <p class="text-xs sm:text-sm text-gray-600">Print a physical copy if needed</p>
                </div>
            </div>
            <button onclick="window.print()" class="w-full mt-3 sm:mt-4 bg-green-600 text-white py-2 sm:py-3 rounded-lg hover:bg-green-700 transition-colors font-medium text-sm sm:text-base">
                <i class="fas fa-print me-2"></i>Print Report
            </button>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="content-card rounded-lg p-4 sm:p-6 shadow-sm">
        <h4 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
            <i class="fas fa-info-circle me-2"></i>Additional Information
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div class="space-y-3 sm:space-y-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Report Created By</label>
                    <p class="text-gray-900 text-sm sm:text-base">{{ $healthReport->createdBy->name ?? 'Medical Staff' }}</p>
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Report Status</label>
                    <span class="px-2 py-1 sm:px-3 sm:py-1 bg-green-100 text-green-800 rounded-full text-xs sm:text-sm font-medium">
                        Completed
                    </span>
                </div>
            </div>
            <div class="space-y-3 sm:space-y-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                    <p class="text-gray-900 text-sm sm:text-base">{{ $healthReport->updated_at->format('F j, Y \\a\\t g:i A') }}</p>
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Report ID</label>
                    <p class="text-gray-900 font-mono text-sm sm:text-base">#{{ str_pad($healthReport->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-card {
    backdrop-filter: blur(12px);
    border: 1px solid rgba(229, 231, 235, 0.8);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

@media print {
    .content-card {
        box-shadow: none;
        border: 1px solid #e5e7eb;
        break-inside: avoid;
    }
    
    .bg-gradient-to-r {
        background: #059669 !important;
    }
}
</style>
@endsection
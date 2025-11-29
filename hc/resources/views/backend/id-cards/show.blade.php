@extends('layouts.app')

@section('title', 'ID Card Details - ' . $idCard->card_number)

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4 flex justify-between items-center">
            <h3 class="text-2xl font-bold">ID Card Details</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.id-cards.print', $idCard) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center"
                   target="_blank">
                    <i class="fas fa-print mr-2"></i>Print
                </a>
                <a href="{{ route('admin.id-cards.edit', $idCard) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('admin.id-cards.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 text-sm font-medium transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Card Information -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Card Information</h4>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-id-card text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-lg font-bold text-gray-900">{{ $idCard->card_number }}</div>
                            <div class="text-sm text-gray-600">{{ $idCard->card_holder_name }}</div>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $idCard->type == 'student' ? 'bg-blue-100 text-blue-800' : 
                           ($idCard->type == 'teacher' ? 'bg-green-100 text-green-800' : 
                           ($idCard->type == 'staff' ? 'bg-purple-100 text-purple-800' : 'bg-orange-100 text-orange-800')) }}">
                        {{ ucfirst($idCard->type) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Template</span>
                        <span class="text-sm text-gray-900">{{ $idCard->template->name }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Issue Date</span>
                        <span class="text-sm text-gray-900">{{ $idCard->issue_date->format('M d, Y') }}</span>
                    </div>

                    <div class="flex justify-between items-center p-3 rounded-lg 
                        {{ $idCard->is_expired ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200' }}">
                        <span class="text-sm font-medium {{ $idCard->is_expired ? 'text-red-700' : 'text-green-700' }}">
                            Expiry Date
                        </span>
                        <div class="text-right">
                            <div class="text-sm {{ $idCard->is_expired ? 'text-red-600 font-medium' : 'text-green-600 font-medium' }}">
                                {{ $idCard->expiry_date->format('M d, Y') }}
                            </div>
                            <div class="text-xs {{ $idCard->is_expired ? 'text-red-500' : 'text-green-500' }}">
                                @if($idCard->is_expired)
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Expired
                                @else
                                    <i class="fas fa-check-circle mr-1"></i>{{ $idCard->days_until_expiry }} days remaining
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $idCard->status == 'active' ? 'bg-green-100 text-green-800' : 
                               ($idCard->status == 'expired' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            <i class="fas fa-{{ $idCard->status == 'active' ? 'check-circle' : ($idCard->status == 'expired' ? 'times-circle' : 'exclamation-triangle') }} mr-1"></i>
                            {{ ucfirst($idCard->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Template & Codes -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">Codes</h4>
            
            <div class="space-y-6">
                <!-- Template Preview -->
                <div>
                    <!--<h5 class="text-lg font-medium text-gray-900 mb-3">Template Preview</h5>-->
                    <!--@if($idCard->template->background_image)-->
                    <!--    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">-->
                    <!--        <img src="{{ asset('public/storage/' . $idCard->template->background_image) }}" -->
                    <!--             alt="{{ $idCard->template->name }}" -->
                    <!--             class="w-full h-48 object-cover rounded-lg shadow-sm">-->
                    <!--    </div>-->
                    <!--@else-->
                    <!--    <div class="bg-gray-50 rounded-lg p-8 border border-gray-200 text-center">-->
                    <!--        <i class="fas fa-image text-4xl text-gray-300 mb-3"></i>-->
                    <!--        <p class="text-gray-500">No template image available</p>-->
                    <!--    </div>-->
                    <!--@endif-->
                    
                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <div class="text-sm font-medium text-blue-700">Dimensions</div>
                            <div class="text-lg font-bold text-blue-900">{{ $idCard->template->dimensions }}</div>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <div class="text-sm font-medium text-green-700">Orientation</div>
                            <div class="text-lg font-bold text-green-900 capitalize">{{ $idCard->template->orientation }}</div>
                        </div>
                    </div>
                </div>

                <!-- QR Code & Barcode -->
                <div>
                    <h5 class="text-lg font-medium text-gray-900 mb-3">Identification Codes</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($idCard->qr_code)
                            <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                                <div class="text-sm font-medium text-gray-700 mb-2">QR Code</div>
                                <img src="{{ asset('public/storage/' . $idCard->qr_code) }}" 
                                     alt="QR Code" 
                                     class="mx-auto h-24 w-24 object-contain">
                                <div class="text-xs text-gray-500 mt-2">Scan to verify</div>
                            </div>
                        @endif
                        
                        @if($idCard->barcode)
                            <div class="text-center p-4 bg-white rounded-lg border border-gray-200">
                                <div class="text-sm font-medium text-gray-700 mb-2">Barcode</div>
                                <img src="{{ asset('public/storage/' . $idCard->barcode) }}" 
                                     alt="Barcode" 
                                     class="mx-auto h-16 w-full object-contain">
                                <div class="text-xs text-gray-500 mt-2">{{ $idCard->card_number }}</div>
                            </div>
                        @endif
                    </div>
                    
                    @if(!$idCard->qr_code && !$idCard->barcode)
                        <div class="text-center p-6 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-barcode text-3xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">No identification codes generated</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Holder Details -->
    @if($idCard->student || $idCard->user)
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4">
            {{ $idCard->student ? 'Student Details' : 'Staff Details' }}
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @if($idCard->student)
                <!-- Student Information -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-graduate text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-blue-900">Student ID</div>
                            <div class="text-lg font-bold text-blue-700">{{ $idCard->student->student_id ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-green-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-green-900">Grade/Class</div>
                            <div class="text-lg font-bold text-green-700">{{ $idCard->student->grade ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-purple-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-purple-900">Email</div>
                            <div class="text-sm font-medium text-purple-700 truncate">{{ $idCard->student->user->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-orange-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-orange-900">Phone</div>
                            <div class="text-sm font-medium text-orange-700">{{ $idCard->student->user->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

            @elseif($idCard->user)
                <!-- Staff Information -->
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-briefcase text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-blue-900">Role</div>
                            <div class="text-lg font-bold text-blue-700 capitalize">{{ $idCard->user->role }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 h-10 w-10 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-building text-green-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-green-900">Department</div>
                            <div class="text-lg font-bold text-green-700">{{ $idCard->user->department ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-envelope text-purple-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-purple-900">Email</div>
                            <div class="text-sm font-medium text-purple-700 truncate">{{ $idCard->user->email }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 h-10 w-10 bg-orange-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-phone text-orange-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-orange-900">Phone</div>
                            <div class="text-sm font-medium text-orange-700">{{ $idCard->user->phone ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Address -->
        @if(($idCard->student && $idCard->student->user->address) || ($idCard->user && $idCard->user->address))
        <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex items-start">
                <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center mt-1">
                    <i class="fas fa-map-marker-alt text-gray-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 mb-1">Address</div>
                    <div class="text-sm text-gray-700">
                        {{ $idCard->student ? ($idCard->student->user->address ?? 'N/A') : ($idCard->user->address ?? 'N/A') }}
                    </div>
                </div>
            </div>
        </div>
        @endif
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth interactions to all buttons and links
        const interactiveElements = document.querySelectorAll('a, button');
        interactiveElements.forEach(element => {
            element.addEventListener('mouseenter', function() {
                this.classList.add('transition-colors', 'duration-200');
            });
        });
    });
</script>
@endsection
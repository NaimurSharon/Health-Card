@extends('layouts.student')

@section('title', 'Health Reports')
@section('subtitle', 'View your medical history, prescriptions, and health records')

@section('content')
<div class="space-y-8">
    <!-- Header Section -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-blue-600 px-8 py-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">My Health Reports</h1>
                    <p class="text-gray-100">Comprehensive view of your medical history and health records</p>
                </div>
                <button class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors" 
                        onclick="openUploadModal()">
                    <i class="fas fa-upload me-2"></i>Upload Prescription
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Health Reports</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $healthReports->count() }}</p>
                    <p class="text-sm text-gray-600">Total checkups</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-medical text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">Medical Records</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $medicalRecords->count() }}</p>
                    <p class="text-sm text-gray-600">Visits & treatments</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-notes-medical text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600">Vaccinations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $allVaccinations->count() }}</p>
                    <p class="text-sm text-gray-600">Immunization records</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-syringe text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-orange-600">Health Card</p>
                    <p class="text-2xl font-bold text-gray-900">
                        @if($activeHealthCard)
                            <span class="text-green-600">Active</span>
                        @else
                            <span class="text-red-600">Inactive</span>
                        @endif
                    </p>
                    <p class="text-sm text-gray-600">Status</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-id-card text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Information & Health Card Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Student Information -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
                <i class="fas fa-user-graduate me-2"></i>Student Information
            </h4>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="text-sm font-medium text-blue-900 mb-1">Student ID</div>
                    <div class="text-lg font-bold text-blue-700">{{ $studentDetails->student_id ?? 'N/A' }}</div>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="text-sm font-medium text-green-900 mb-1">Class</div>
                    <div class="text-lg font-bold text-green-700">{{ $class->name ?? 'N/A' }}</div>
                </div>
                
                <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                    <div class="text-sm font-medium text-purple-900 mb-1">Roll Number</div>
                    <div class="text-lg font-bold text-purple-700">{{ $studentDetails->roll_number ?? 'N/A' }}</div>
                </div>
                
                <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                    <div class="text-sm font-medium text-orange-900 mb-1">Blood Group</div>
                    <div class="text-lg font-bold text-orange-700">{{ $studentDetails->blood_group ?? 'N/A' }}</div>
                </div>
            </div>

            <!-- Medical Conditions -->
            @if($studentDetails->medical_conditions || $studentDetails->allergies)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h5 class="text-lg font-semibold text-gray-900 mb-3">Medical Information</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($studentDetails->allergies)
                    <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-exclamation-triangle text-red-600 me-2"></i>
                            <div class="text-sm font-medium text-red-900">Allergies</div>
                        </div>
                        <div class="text-sm text-red-700">{{ $studentDetails->allergies }}</div>
                    </div>
                    @endif
                    
                    @if($studentDetails->medical_conditions)
                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-file-medical text-yellow-600 me-2"></i>
                            <div class="text-sm font-medium text-yellow-900">Medical Conditions</div>
                        </div>
                        <div class="text-sm text-yellow-700">{{ $studentDetails->medical_conditions }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Health Card Status -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
                <i class="fas fa-id-card me-2"></i>Health Card Status
            </h4>
            
            @if($activeHealthCard)
            <div class="space-y-4">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-medium text-green-100">Health Card Number</div>
                            <div class="text-2xl font-bold">{{ $activeHealthCard->card_number }}</div>
                        </div>
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-2xl text-white"></i>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-plus text-blue-600 text-lg me-3"></i>
                            <div>
                                <div class="text-sm font-medium text-blue-900">Issue Date</div>
                                <div class="text-lg font-bold text-blue-700">{{ $activeHealthCard->issue_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-check text-purple-600 text-lg me-3"></i>
                            <div>
                                <div class="text-sm font-medium text-purple-900">Expiry Date</div>
                                <div class="text-lg font-bold text-purple-700">{{ $activeHealthCard->expiry_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($activeHealthCard->medical_summary)
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="text-sm font-medium text-gray-900 mb-2">Medical Summary</div>
                    <div class="text-sm text-gray-700">{{ $activeHealthCard->medical_summary }}</div>
                </div>
                @endif

                @if($activeHealthCard->emergency_instructions)
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle text-red-600 me-2"></i>
                        <div class="text-sm font-medium text-red-900">Emergency Instructions</div>
                    </div>
                    <div class="text-sm text-red-700">{{ $activeHealthCard->emergency_instructions }}</div>
                </div>
                @endif
            </div>
            @else
            <div class="bg-yellow-50 rounded-lg p-8 border border-yellow-200 text-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
                <div class="text-xl font-medium text-yellow-800 mb-2">Health Card Not Found</div>
                <p class="text-yellow-700">Please contact the school administration to get your health card activated.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Health Checkup Reports -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-file-medical me-2"></i>Health Checkup Reports
            </h3>
            <span class="text-sm text-gray-500">{{ $healthReports->count() }} records</span>
        </div>
        
        @if($healthReports->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($healthReports as $report)
                <div class="bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-stethoscope text-blue-600 text-lg"></i>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                            {{ $report->checkup_date->format('M Y') }}
                        </span>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Health Checkup Report</h4>
                    <p class="text-sm text-gray-600 mb-3">Conducted on {{ $report->checkup_date->format('F j, Y') }}</p>
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>By: {{ $report->checked_by }}</span>
                        <span>79 metrics</span>
                    </div>
                    <a href="{{ route('student.health-report.show', $report->id) }}" 
                       class="w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition-colors block text-sm font-medium">
                        <i class="fas fa-eye me-2"></i>View Full Report
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-medical text-2xl text-gray-300"></i>
                </div>
                <h4 class="text-lg font-medium text-gray-900 mb-2">No Health Reports Yet</h4>
                <p class="text-gray-500 max-w-md mx-auto">Your health checkup reports will appear here after medical examinations at the school health center.</p>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Medical Records -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-notes-medical me-2"></i>Medical Records
                </h3>
                <span class="text-sm text-gray-500">{{ $medicalRecords->count() }} records</span>
            </div>
            
            @if($medicalRecords->count() > 0)
                <div class="space-y-4">
                    @foreach($medicalRecords->take(5) as $record)
                    <div class="p-4 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $record->record_type == 'emergency' ? 'bg-red-100 text-red-800' : 
                                   ($record->record_type == 'vaccination' ? 'bg-green-100 text-green-800' : 
                                   ($record->record_type == 'routine' ? 'bg-blue-100 text-blue-800' : 
                                   ($record->record_type == 'sickness' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800'))) }}">
                                {{ ucfirst($record->record_type) }}
                            </span>
                            <span class="text-sm text-gray-500">{{ $record->record_date->format('M j, Y') }}</span>
                        </div>
                        
                        <div class="space-y-2">
                            @if($record->symptoms)
                                <div>
                                    <p class="text-xs font-medium text-gray-700">Symptoms</p>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $record->symptoms }}</p>
                                </div>
                            @endif
                            
                            @if($record->diagnosis)
                                <div>
                                    <p class="text-xs font-medium text-gray-700">Diagnosis</p>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $record->diagnosis }}</p>
                                </div>
                            @endif

                            @if($record->doctor_notes)
                                <div>
                                    <p class="text-xs font-medium text-gray-700">Doctor Notes</p>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $record->doctor_notes }}</p>
                                </div>
                            @endif
                        </div>

                        @if($record->height || $record->weight || $record->temperature)
                            <div class="flex space-x-4 mt-3 pt-3 border-t border-gray-100 text-center">
                                @if($record->height)
                                    <div>
                                        <p class="text-xs text-gray-500">Height</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $record->height }} cm</p>
                                    </div>
                                @endif
                                @if($record->weight)
                                    <div>
                                        <p class="text-xs text-gray-500">Weight</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $record->weight }} kg</p>
                                    </div>
                                @endif
                                @if($record->temperature)
                                    <div>
                                        <p class="text-xs text-gray-500">Temperature</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $record->temperature }}°C</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($record->follow_up_date)
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <p class="text-xs font-medium text-gray-700">Follow-up Date</p>
                                <p class="text-sm text-blue-600">{{ $record->follow_up_date->format('M j, Y') }}</p>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                
                @if($medicalRecords->count() > 5)
                    <div class="mt-6 text-center">
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View All Medical Records ({{ $medicalRecords->count() }})
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-notes-medical text-xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-500">No medical records found</p>
                </div>
            @endif
        </div>

        <!-- Vaccination Records -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-syringe me-2"></i>Vaccination Records
                </h3>
                <span class="text-sm text-gray-500">{{ $allVaccinations->count() }} records</span>
            </div>
            
            @if($allVaccinations->count() > 0)
                <div class="space-y-4">
                    @foreach($allVaccinations->take(5) as $vaccine)
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-green-600"></i>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900">{{ $vaccine->vaccine_name ?? 'Vaccination' }}</h5>
                                    <p class="text-sm text-gray-600">
                                        @if(isset($vaccine->dose_number))
                                            Dose {{ $vaccine->dose_number }} • 
                                        @endif
                                        {{ isset($vaccine->vaccine_date) ? $vaccine->vaccine_date->format('M j, Y') : $vaccine->record_date->format('M j, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">By: {{ $vaccine->administered_by ?? 'Medical Staff' }}</span>
                            @if(isset($vaccine->next_due_date) && $vaccine->next_due_date)
                                <span class="text-green-700 font-medium">
                                    Next: {{ $vaccine->next_due_date->format('M j, Y') }}
                                </span>
                            @endif
                        </div>

                        @if(isset($vaccine->notes) && $vaccine->notes)
                            <div class="mt-2 pt-2 border-t border-green-200">
                                <p class="text-xs text-gray-600">{{ $vaccine->notes }}</p>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                
                @if($allVaccinations->count() > 5)
                    <div class="mt-6 text-center">
                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View All Vaccinations ({{ $allVaccinations->count() }})
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-syringe text-xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-500">No vaccination records found</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Upload Prescription Modal -->
<div id="uploadPrescriptionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeUploadModal()"></div>

        <!-- Modal panel -->
        <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Upload Prescription</h3>
                <button type="button" onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('student.health-report.upload-prescription') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prescription Name</label>
                    <input type="text" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                           name="prescription_name" required placeholder="Enter prescription name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload File</label>
                    <input type="file" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                           name="prescription_file" accept=".pdf,.jpg,.png" required>
                    <div class="text-sm text-gray-500 mt-1">Supported formats: PDF, JPG, PNG (Max: 5MB)</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200" 
                           name="prescription_date" value="{{ date('Y-m-d') }}" required>
                </div>
                
                <div class="flex space-x-3 pt-4">
                    <button type="button" onclick="closeUploadModal()" 
                            class="flex-1 bg-gray-500 text-white px-4 py-3 rounded-lg hover:bg-gray-600 transition-colors font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Upload Prescription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.content-card {
    backdrop-filter: blur(12px);
    border: 1px solid rgba(229, 231, 235, 0.8);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    background: #fff;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
// Modal functions
function openUploadModal() {
    document.getElementById('uploadPrescriptionModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUploadModal() {
    document.getElementById('uploadPrescriptionModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('uploadPrescriptionModal').addEventListener('click', function(e) {
    if (e.target.id === 'uploadPrescriptionModal') {
        closeUploadModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeUploadModal();
    }
});
</script>
@endsection
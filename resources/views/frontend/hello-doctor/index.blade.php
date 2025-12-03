@extends('layouts.global')

@section('title', 'Hello Doctor')
@section('subtitle', 'Get medical consultation and treatment')

@section('content')
<div class="space-y-6" id="hello-doctor-container">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-4 sm:px-6 py-4">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <div class="hidden sm:block mr-3">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            
                            <h3 class="text-lg sm:text-2xl font-bold text-white truncate">Hello, {{ Auth::user()->name ?? 'Student' }}</h3>
                            @if(Auth::check())
                            
                                @php
                                    $upcomingConsultations = \App\Models\VideoConsultation::where('user_id', Auth::user()->id)
                                        ->where('status', 'scheduled')
                                        ->where('scheduled_for', '>', now()->addDay()) // Tomorrow and beyond
                                        ->with('doctor')
                                        ->orderBy('scheduled_for')
                                        ->limit(5)
                                        ->get();
                                @endphp
                                <!-- Optional: Add a quick stats badge -->
                                @if(isset($upcomingConsultations) && $upcomingConsultations->count() > 0)
                                <div class="hidden sm:block">
                                    <div class="bg-white/20 backdrop-blur-sm border border-white/30 text-white px-3 py-2 rounded-lg text-sm font-medium">
                                        <span class="font-bold text-white">You have {{ $upcomingConsultations->count() }}</span> Upcoming
                                    </div>
                                </div>
                                @endif
                            @else
                                <p class="text-white/80 text-sm sm:text-base mt-0.5 sm:mt-1">
                                    {{ now()->format('l, F j, Y') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex-shrink-0 w-full sm:w-auto">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <!-- Mobile view: Smaller button -->
                        <a href="{{ route('video-consultation.index') }}" 
                        class="sm:hidden inline-flex items-center justify-center w-full px-4 py-2.5 bg-white hover:bg-gray-50 text-dark font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white">
                            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            My Appointments
                        </a>
                        
                        <!-- Desktop view: Full button -->
                        <a href="{{ route('video-consultation.index') }}" 
                        class="hidden sm:inline-flex items-center justify-center px-5 py-3 bg-white hover:bg-gray-50 text-dark font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white whitespace-nowrap">
                            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            My Appointments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Doctors List Section -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-6 flex items-center">
            <i class="fas fa-user-md me-2 text-green-600"></i>Our Medical Team
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($doctors as $doctor)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                <!-- Doctor Profile Image -->
                <div class="relative">
                    <div class="h-48 bg-gradient-to-br from-blue-50 to-green-50 rounded-t-lg flex items-center justify-center overflow-hidden">
                        @if($doctor->profile_image)
                            <img src="{{ asset('public/storage/' . $doctor->profile_image) }}" 
                                 alt="Dr. {{ $doctor->name }}"
                                 class="w-45 h-45 object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="w-32 h-32 bg-gradient-to-br from-blue-100 to-green-100 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                <i class="fas fa-user-md text-blue-400 text-4xl"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Availability Badge -->
                    <div class="absolute top-4 right-4">
                        @if($doctor->today_availability)
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium flex items-center">
                                <i class="fas fa-circle text-green-500 me-1 text-[8px]"></i>
                                Available
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium flex items-center">
                                <i class="fas fa-circle text-red-500 me-1 text-[8px]"></i>
                                Offline
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Doctor Information -->
                <div class="p-4">
                    <h5 class="font-bold text-gray-900 text-lg mb-1">Dr. {{ $doctor->name }}</h5>
                    
                    <p class="text-gray-600 text-sm mb-2 flex items-center">
                        <i class="fas fa-stethoscope me-2 text-blue-500"></i>
                        {{ $doctor->specialization ?? 'General Physician' }}
                    </p>
                    
                    @if($doctor->hospital)
                    <p class="text-gray-500 text-xs mb-3 flex items-center">
                        <i class="fas fa-hospital me-2 text-gray-400"></i>
                        {{ $doctor->hospital->name }}
                    </p>
                    @endif
                    
                    @if($doctor->doctorDetail && $doctor->doctorDetail->experience)
                    <p class="text-gray-500 text-xs mb-3 flex items-center">
                        <i class="fas fa-award me-2 text-yellow-500"></i>
                        {{ $doctor->doctorDetail->experience }} experience
                    </p>
                    @endif
                    
                    <!-- Fees Information -->
                    @if($doctor->doctorDetail && $doctor->doctorDetail->consultation_fee)
                    <div class="bg-blue-50 rounded-lg p-2 mb-3">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600">Consultation Fee:</span>
                            <span class="font-semibold text-green-600">
                                ৳{{ number_format($doctor->doctorDetail->consultation_fee, 2) }}
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Availability Info -->
                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600">Next Available:</span>
                            <span class="font-medium text-green-600">{{ $doctor->next_available_slot }}</span>
                        </div>
                        @if($doctor->today_availability)
                        <div class="flex items-center justify-between text-xs mt-1">
                            <span class="text-gray-600">Today's Hours:</span>
                            <span class="font-medium text-blue-600">
                                {{ \Carbon\Carbon::parse($doctor->today_availability->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($doctor->today_availability->end_time)->format('g:i A') }}
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <!-- Instant Call Button (Primary) -->
                        @if($doctor->today_availability)
                        <button onclick="initiateInstantCall({{ $doctor->id }}, '{{ $doctor->name }}', {{ $doctor->doctorDetail->consultation_fee ?? 500 }})" 
                                class="w-full bg-green-600 text-white py-2.5 px-4 rounded-lg hover:bg-green-700 transition-all duration-200 text-sm font-semibold flex items-center justify-center gap-2 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                                id="instant-call-btn-{{ $doctor->id }}">
                            <i class="fas fa-phone-alt text-sm"></i>
                            <span>Instant Call Now</span>
                            <span class="ml-auto bg-white/20 px-2 py-0.5 rounded text-xs">৳{{ $doctor->doctorDetail->consultation_fee ?? 500 }}</span>
                        </button>
                        @else
                        <button disabled
                                class="w-full bg-gray-300 text-gray-500 py-2.5 px-4 rounded-lg text-sm font-semibold flex items-center justify-center gap-2 cursor-not-allowed">
                            <i class="fas fa-phone-slash text-sm"></i>
                            <span>Currently Offline</span>
                        </button>
                        @endif
                        
                        <!-- Schedule Call Button (Secondary) -->
                        <div class="flex gap-2">
                            <button onclick="bookAppointment({{ $doctor->id }})" 
                                    class="flex-1 bg-blue-600 text-white py-2 px-3 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium flex items-center justify-center gap-1">
                                <i class="fas fa-calendar text-xs"></i>
                                Schedule Later
                            </button>
                            
                            <button onclick="showDoctorDetails({{ $doctor->id }})" 
                                    class="px-3 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm text-gray-600 flex items-center justify-center">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($doctors->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-user-md text-4xl mb-4 text-gray-300"></i>
            <h5 class="text-lg font-medium text-gray-500 mb-2">No Doctors Available</h5>
            <p class="text-gray-400">Please check back later for available medical staff.</p>
        </div>
        @endif
    </div>

    <!-- Video Consultation Booking Form Section -->
    <div id="appointment-form" class="content-card rounded-lg p-6 shadow-sm hidden">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-6 flex items-center">
            <i class="fas fa-video me-2 text-blue-600"></i>Schedule Video Consultation
        </h4>
        
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        <strong>Note:</strong> All consultations are conducted via video call. You'll be able to join the call from your consultations page at the scheduled time.
                    </p>
                </div>
            </div>
        </div>
        
        <form id="appointmentBookingForm" action="{{ route('hello-doctor.store-video-consultation') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor *</label>
                    <select name="doctor_id" id="form_doctor_id" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">Choose a Doctor</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} - {{ $doctor->specialization ?? 'General Physician' }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Date *</label>
                    <input type="date" name="scheduled_date" id="form_appointment_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required 
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Time *</label>
                    <select name="scheduled_time" id="form_appointment_time" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">Select Time Slot</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Fee</label>
                    <div class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg text-sm text-gray-600 flex items-center">
                        <i class="fas fa-tag text-green-600 mr-2"></i>
                        <span class="font-semibold text-green-600">FREE</span>
                        <span class="ml-2 text-xs text-gray-500">(Scheduled Consultations)</span>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Consultation *</label>
                <textarea name="reason" rows="3" required placeholder="Briefly describe the reason for your video consultation"
                          class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Symptoms (Optional)</label>
                <textarea name="symptoms" rows="2" placeholder="Describe any symptoms you're experiencing"
                          class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium text-lg">
                    <i class="fas fa-video me-2"></i>Schedule Video Consultation
                </button>
                <button type="button" onclick="closeForm()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- Emergency Form Section -->
    <div id="emergency-form" class="content-card rounded-lg p-6 shadow-sm hidden">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-6 flex items-center">
            <i class="fas fa-ambulance me-2 text-red-600"></i>Emergency Consultation
        </h4>
        
        <form id="emergencyForm" action="{{ route('student.hello-doctor.store-treatment-request') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Describe Your Symptoms *</label>
                <textarea name="symptoms" rows="4" required placeholder="Please describe your symptoms in detail..."
                          class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urgency Level *</label>
                    <select name="urgency" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="routine">Routine</option>
                        <option value="urgent">Urgent</option>
                        <option value="emergency">Emergency</option>
                    </select>
                </div>
                
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                <textarea name="notes" rows="2" placeholder="Any additional information..."
                          class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"></textarea>
            </div>
            
            <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition-colors font-medium text-lg">
                <i class="fas fa-bolt me-2"></i>Request Emergency Consultation
            </button>
        </form>
    </div>
</div>

<script>
// Doctor data passed from controller
const doctors = @json($doctors->keyBy('id'));

// Global function to handle appointment booking
function bookAppointment(doctorId) {
    @if(Auth::check())
        // User is logged in, proceed with booking
        openAppointmentForm(doctorId);
    @else
        // User is not logged in, show auth modal with appointment data
        const appointmentData = {
            doctor_id: doctorId,
            action: 'book_appointment'
        };
        showAuthModal(appointmentData);
    @endif
}

// Function to handle pending appointment after login
window.handlePendingAppointment = function() {
    if (window.pendingAppointmentData && window.pendingAppointmentData.doctor_id) {
        openAppointmentForm(window.pendingAppointmentData.doctor_id);
    }
};

function openAppointmentForm(doctorId) {
    const doctor = doctors[doctorId];
    if (!doctor) return;
    
    // Hide emergency form and show appointment form
    document.getElementById('emergency-form').classList.add('hidden');
    document.getElementById('appointment-form').classList.remove('hidden');
    
    // Set the doctor in the form
    document.getElementById('form_doctor_id').value = doctorId;
    
    // Generate time slots for today
    generateTimeSlots(doctorId, document.getElementById('form_appointment_date').value);
    
    // Scroll to the form with smooth animation
    setTimeout(() => {
        document.getElementById('appointment-form').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start',
            inline: 'nearest'
        });
    }, 100);
}

function closeForm() {
    document.getElementById('appointment-form').classList.add('hidden');
    document.getElementById('emergency-form').classList.add('hidden');
}

function generateTimeSlots(doctorId, selectedDate) {
    const doctor = doctors[doctorId];
    const timeSelect = document.getElementById('form_appointment_time');
    timeSelect.innerHTML = '<option value="">Select Time Slot</option>';
    
    if (!doctor || !doctor.today_availability) {
        timeSelect.innerHTML = '<option value="">No slots available</option>';
        return;
    }
    
    const startTime = doctor.today_availability.start_time;
    const endTime = doctor.today_availability.end_time;
    const slotDuration = doctor.today_availability.slot_duration || 30;
    
    let currentTime = new Date(`1970-01-01T${startTime}`);
    const end = new Date(`1970-01-01T${endTime}`);
    
    while (currentTime < end) {
        const timeString = currentTime.toTimeString().substring(0, 5);
        const option = document.createElement('option');
        option.value = timeString;
        option.textContent = formatTimeForDisplay(timeString);
        timeSelect.appendChild(option);
        
        // Add slot duration
        currentTime.setMinutes(currentTime.getMinutes() + slotDuration);
    }
}

function formatTimeForDisplay(timeString) {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

// Event listener for date change in form
document.getElementById('form_appointment_date').addEventListener('change', function() {
    const doctorId = document.getElementById('form_doctor_id').value;
    const selectedDate = this.value;
    if (doctorId) {
        generateTimeSlots(doctorId, selectedDate);
    }
});

// Event listener for doctor change in form
document.getElementById('form_doctor_id').addEventListener('change', function() {
    const doctorId = this.value;
    const selectedDate = document.getElementById('form_appointment_date').value;
    if (doctorId && selectedDate) {
        generateTimeSlots(doctorId, selectedDate);
    }
});

function showDoctorDetails(doctorId) {
    const doctor = doctors[doctorId];
    if (!doctor) return;
    
    // You can implement a doctor details modal here
    alert(`Dr. ${doctor.name}\nSpecialization: ${doctor.specialization || 'General Physician'}\nHospital: ${doctor.hospital ? doctor.hospital.name : 'Not specified'}`);
}

// Handle form submissions
document.getElementById('appointmentBookingForm').addEventListener('submit', function(e) {
    // Form will submit normally if user is logged in
    @if(!Auth::check())
    e.preventDefault();
    alert('Please log in to schedule a video consultation.');
    @endif
});

document.getElementById('emergencyForm').addEventListener('submit', function(e) {
    @if(!Auth::check())
    e.preventDefault();
    alert('Please log in to request emergency consultation.');
    @endif
});

// Function to show emergency form (if needed elsewhere)
function showEmergencyForm() {
    document.getElementById('appointment-form').classList.add('hidden');
    document.getElementById('emergency-form').classList.remove('hidden');
    
    setTimeout(() => {
        document.getElementById('emergency-form').scrollIntoView({ 
            behavior: 'smooth', 
            block: 'start',
            inline: 'nearest'
        });
    }, 100);
}

// Instant Call Functionality
async function initiateInstantCall(doctorId, doctorName, consultationFee) {
    @if(!Auth::check())
        // User is not logged in, show auth modal with appointment data
        const appointmentData = {
            doctor_id: doctorId,
            action: 'book_appointment'
        };
        showAuthModal(appointmentData);
    @endif

    const button = document.getElementById(`instant-call-btn-${doctorId}`);
    const originalHTML = button.innerHTML;
    
    try {
        // Disable button and show loading
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Checking availability...';
        
        // Check doctor availability first
        const availabilityResponse = await fetch(`/student/hello-doctor/check-availability/${doctorId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const availability = await availabilityResponse.json();
        
        if (!availability.available) {
            alert(availability.message);
            button.disabled = false;
            button.innerHTML = originalHTML;
            return;
        }
        
        // Show symptoms input modal
        const symptoms = await showSymptomsModal(doctorName, consultationFee);
        
        if (!symptoms) {
            button.disabled = false;
            button.innerHTML = originalHTML;
            return;
        }
        
        // Update button to show initiating
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Initiating call...';
        
        // Initiate the call
        const response = await fetch('/student/hello-doctor/instant-call', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                doctor_id: doctorId,
                symptoms: symptoms
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success message
            showNotification('success', result.message);
            
            // Redirect to video call page after a short delay
            setTimeout(() => {
                window.location.href = result.redirect_url;
            }, 1000);
        } else {
            alert(result.message || 'Failed to initiate call. Please try again.');
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
        
    } catch (error) {
        console.error('Instant call error:', error);
        alert('An error occurred. Please try again.');
        button.disabled = false;
        button.innerHTML = originalHTML;
    }
}

// Show symptoms modal
function showSymptomsModal(doctorName, fee) {
    return new Promise((resolve) => {
        // Create modal HTML
        const modalHTML = `
            <div id="symptoms-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Instant Call with ${doctorName}</h3>
                        <button onclick="closeSymptomsModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    
                    <div class="mb-4 bg-blue-50 border-l-4 border-blue-500 p-3 rounded">
                        <p class="text-sm text-blue-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            Consultation Fee: <strong>৳${fee}</strong>
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Please describe your symptoms: <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="symptoms-input" 
                            rows="4" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                            placeholder="E.g., Fever, headache, cough..."
                            maxlength="500"
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 500 characters</p>
                    </div>
                    
                    <div class="flex gap-3">
                        <button 
                            onclick="closeSymptomsModal()" 
                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold transition-colors">
                            Cancel
                        </button>
                        <button 
                            onclick="submitSymptoms()" 
                            class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-colors">
                            Start Call
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Add modal to page
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Focus on textarea
        setTimeout(() => {
            document.getElementById('symptoms-input').focus();
        }, 100);
        
        // Store resolve function
        window.symptomsModalResolve = resolve;
    });
}

// Close symptoms modal
function closeSymptomsModal() {
    const modal = document.getElementById('symptoms-modal');
    if (modal) {
        modal.remove();
    }
    if (window.symptomsModalResolve) {
        window.symptomsModalResolve(null);
    }
}

// Submit symptoms
function submitSymptoms() {
    const symptoms = document.getElementById('symptoms-input').value.trim();
    
    if (!symptoms) {
        alert('Please describe your symptoms before starting the call.');
        return;
    }
    
    if (symptoms.length < 10) {
        alert('Please provide more details about your symptoms (at least 10 characters).');
        return;
    }
    
    const modal = document.getElementById('symptoms-modal');
    if (modal) {
        modal.remove();
    }
    
    if (window.symptomsModalResolve) {
        window.symptomsModalResolve(symptoms);
    }
}

// Show notification
function showNotification(type, message) {
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const notificationHTML = `
        <div id="notification" class="fixed top-4 right-4 z-50 ${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center gap-3 transform transition-all">
            <i class="fas ${icon} text-xl"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', notificationHTML);
    
    setTimeout(() => {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.remove();
        }
    }, 3000);
}

</script>

<style>
.content-card {
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    background: #fff;
}

.table-header {
    background: #06AC73;
    border-bottom: 1px solid rgba(229, 231, 235, 0.6);
}

/* Smooth transitions for form display */
#appointment-form,
#emergency-form {
    transition: all 0.3s ease-in-out;
}

/* Responsive design */
@media (max-width: 640px) {
    .grid-cols-1 > div {
        margin-bottom: 1rem;
    }
}
</style>
@endsection
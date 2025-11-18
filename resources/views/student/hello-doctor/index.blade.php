@extends('layouts.student')

@section('title', 'Hello Doctor')
@section('subtitle', 'Get medical consultation and treatment')

@section('content')
<div class="space-y-6" id="hello-doctor-container">
    <!-- Header -->
    <div class="content-card rounded-lg overflow-hidden">
        <div class="table-header px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold text-white">Hello Doctor</h3>
                    <p class="text-blue-100">Get medical consultation and treatment</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Selection Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Schedule Appointment Card -->
        <div class="content-card rounded-lg p-6 shadow-sm cursor-pointer transition-all duration-300 hover:shadow-lg bg-white service-card transform hover:-translate-y-1 active:scale-95"
             id="appointment-card"
             onclick="selectService('appointment')">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4 transition-all duration-300 group-hover:scale-110">
                    <i class="fas fa-calendar-check text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-blue-700 transition-colors">Get a Schedule/Appointment</h3>
                <p class="text-gray-600 mb-4 group-hover:text-gray-700 transition-colors">Book an appointment with our doctors for routine checkups</p>
                <div class="space-y-2 text-sm text-gray-500">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-check text-green-500 me-2"></i>
                        <span>Choose preferred date & time</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <i class="fas fa-check text-green-500 me-2"></i>
                        <span>Select your preferred doctor</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <i class="fas fa-check text-green-500 me-2"></i>
                        <span>Free consultation</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <span class="text-blue-600 font-medium text-sm flex items-center justify-center">
                        Click to Book Appointment
                        <i class="fas fa-chevron-right text-xs ml-1 transform group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </div>
            </div>
        </div>

        <!-- Emergency Consultation Card -->
        <div class="content-card rounded-lg p-6 shadow-sm cursor-pointer transition-all duration-300 hover:shadow-lg bg-white service-card transform hover:-translate-y-1 active:scale-95"
             id="emergency-card"
             onclick="selectService('emergency')">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 transition-all duration-300 group-hover:scale-110">
                    <i class="fas fa-ambulance text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-red-700 transition-colors">Pay Now & Get Consult</h3>
                <p class="text-gray-600 mb-4 group-hover:text-gray-700 transition-colors">Immediate medical attention for urgent health issues</p>
                <div class="space-y-2 text-sm text-gray-500">
                    <div class="flex items-center justify-center">
                        <i class="fas fa-bolt text-yellow-500 me-2"></i>
                        <span>Instant consultation</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <i class="fas fa-shield-alt text-yellow-500 me-2"></i>
                        <span>Priority treatment</span>
                    </div>
                    <div class="flex items-center justify-center">
                        <i class="fas fa-credit-card text-yellow-500 me-2"></i>
                        <span>Secure payment</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <span class="text-red-600 font-medium text-sm flex items-center justify-center">
                        Click for Emergency Consult
                        <i class="fas fa-chevron-right text-xs ml-1 transform group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Video Consultation Options -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
                <i class="fas fa-video me-2 text-purple-600"></i>Video Consultation Options
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Instant Video Call -->
                <div class="bg-purple-50 border-2 border-purple-200 rounded-lg p-4 text-center cursor-pointer hover:border-purple-400 transition-all duration-300 transform hover:scale-[1.02]"
                     onclick="showInstantCallForm()">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-bolt text-purple-600 text-xl"></i>
                    </div>
                    <h5 class="font-semibold text-gray-900 mb-2">Instant Video Call</h5>
                    <p class="text-sm text-gray-600 mb-3">Connect immediately with available doctors</p>
                    <div class="text-xs text-purple-600 font-medium">
                        <i class="fas fa-clock me-1"></i>Connect in 2 minutes
                    </div>
                </div>
        
                <!-- Schedule Video Call -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 text-center cursor-pointer hover:border-blue-400 transition-all duration-300 transform hover:scale-[1.02]"
                     onclick="selectService('appointment')">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-plus text-blue-600 text-xl"></i>
                    </div>
                    <h5 class="font-semibold text-gray-900 mb-2">Schedule Video Call</h5>
                    <p class="text-sm text-gray-600 mb-3">Book a video appointment for later</p>
                    <div class="text-xs text-blue-600 font-medium">
                        <i class="fas fa-video me-1"></i>Free consultation
                    </div>
                </div>
        
                <!-- Emergency Video Call -->
                <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4 text-center cursor-pointer hover:border-red-400 transition-all duration-300 transform hover:scale-[1.02]"
                     onclick="selectService('emergency')">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-ambulance text-red-600 text-xl"></i>
                    </div>
                    <h5 class="font-semibold text-gray-900 mb-2">Emergency Video Call</h5>
                    <p class="text-sm text-gray-600 mb-3">Immediate medical attention</p>
                    <div class="text-xs text-red-600 font-medium">
                        <i class="fas fa-shield-alt me-1"></i>Priority access
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Instant Video Call Form -->
        <div class="content-card rounded-lg p-6 shadow-sm hidden" id="instant-call-form">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
                <i class="fas fa-bolt me-2 text-purple-600"></i>Instant Video Call
            </h4>
            
            <form action="{{ route('student.hello-doctor.instant-video-call') }}" method="POST" class="space-y-4">
                @csrf
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-purple-600 mt-1 me-3"></i>
                        <div>
                            <p class="text-purple-800 font-medium">Instant Connection</p>
                            <p class="text-purple-700 text-sm mt-1">You'll be connected with the first available doctor within 2 minutes. Consultation fee: ৳ 500</p>
                        </div>
                    </div>
                </div>
        
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Preferred Doctor</label>
                        <select name="doctor_id" required 
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                            <option value="">Choose a doctor (or any available)</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} - {{ $doctor->specialization }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Urgency Level</label>
                        <select name="urgency" required 
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                            <option value="urgent">Urgent - Connect ASAP</option>
                            <option value="emergency">Emergency - Critical condition</option>
                        </select>
                    </div>
                </div>
        
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Describe Your Symptoms *</label>
                    <textarea name="symptoms" rows="3" required placeholder="Briefly describe your symptoms for the doctor"
                              class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200"></textarea>
                </div>
        
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                        <option value="">Select Payment Method</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                        <option value="card">Credit/Debit Card</option>
                    </select>
                </div>
        
                <button type="submit" class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition-colors font-medium text-lg transform hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-play-circle me-2"></i>Start Instant Video Call (৳ 5)
                </button>
            </form>
        </div>

    <!-- Appointment Form -->
    <div class="content-card rounded-lg p-6 shadow-sm hidden" id="appointment-form">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
            <i class="fas fa-calendar-plus me-2 text-blue-600"></i>Book Appointment
        </h4>
        
        <form action="{{ route('student.hello-doctor.store-appointment') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor</label>
                    <select name="doctor_id" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">Choose a doctor</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} - {{ $doctor->specialization }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Date</label>
                    <input type="date" name="appointment_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required 
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Type</label>
                    <select name="consultation_type" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="in_person">In-Person Visit</option>
                        <option value="video_call">Video Call Consultation</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Time</label>
                    <select name="appointment_time" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                        <option value="">Select Time</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="14:00">02:00 PM</option>
                        <option value="15:00">03:00 PM</option>
                        <option value="16:00">04:00 PM</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Visit</label>
                    <textarea name="reason" rows="3" required placeholder="Briefly describe the reason for your appointment"
                              class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"></textarea>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Symptoms (Optional)</label>
                <textarea name="symptoms" rows="2" placeholder="Describe any symptoms you're experiencing"
                          class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"></textarea>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium text-lg transform hover:scale-[1.02] active:scale-[0.98]">
                <i class="fas fa-calendar-check me-2"></i>Book Appointment
            </button>
        </form>
    </div>

    <!-- Emergency Consultation Form -->
    <div class="content-card rounded-lg p-6 shadow-sm hidden" id="emergency-form">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
            <i class="fas fa-ambulance me-2 text-red-600"></i>Emergency Consultation
        </h4>
        
        <form action="{{ route('student.hello-doctor.store-treatment-request') }}" method="POST" class="space-y-4">
            @csrf
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 me-3"></i>
                    <div>
                        <p class="text-yellow-800 font-medium">Emergency Service Notice</p>
                        <p class="text-yellow-700 text-sm mt-1">This service requires immediate payment for priority medical attention. Our doctors will contact you within 15 minutes.</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Describe Your Symptoms *</label>
                <textarea name="symptoms" rows="4" required placeholder="Please describe your symptoms in detail including when they started, severity, and any other relevant information"
                          class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Consultation Type</label>
                    <select name="consultation_type" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200">
                        <option value="in_person">In-Person Visit</option>
                        <option value="video_call">Emergency Video Call</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact Number *</label>
                    <input type="tel" name="emergency_contact" required placeholder="Your contact number"
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                    <select name="payment_method" required 
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200">
                        <option value="">Select Payment Method</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                        <option value="card">Credit/Debit Card</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urgency Level *</label>
                    <div class="space-y-2">
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-red-50 cursor-pointer transition-colors">
                            <input type="radio" name="urgency" value="emergency" checked class="text-red-600 focus:ring-red-500">
                            <span class="ml-3">
                                <span class="block text-sm font-medium text-red-600">Emergency</span>
                                <span class="block text-xs text-red-500">Need immediate attention</span>
                            </span>
                        </label>
                        <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-orange-50 cursor-pointer transition-colors">
                            <input type="radio" name="urgency" value="urgent" class="text-orange-600 focus:ring-orange-500">
                            <span class="ml-3">
                                <span class="block text-sm font-medium text-orange-600">Urgent</span>
                                <span class="block text-xs text-orange-500">Within 24 hours</span>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                    <textarea name="notes" rows="4" placeholder="Any additional information about your condition, medications, or allergies"
                              class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200"></textarea>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h5 class="font-semibold text-gray-900 mb-2">Payment Information</h5>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">Consultation Fee</span>
                    <span class="font-semibold text-green-600">৳ 500</span>
                </div>
                <p class="text-xs text-gray-500 mt-2">Amount will be deducted after doctor confirmation</p>
            </div>
            
            <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition-colors font-medium text-lg transform hover:scale-[1.02] active:scale-[0.98]">
                <i class="fas fa-credit-card me-2"></i>Pay & Get Emergency Consult
            </button>
        </form>
    </div>
    
    <!-- My Video Consultations -->
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
            <i class="fas fa-video me-2 text-purple-600"></i>My Video Consultations
        </h4>
        
        @if($videoConsultations->count() > 0)
            <div class="space-y-3">
                @foreach($videoConsultations as $consultation)
                <div class="p-4 bg-white border border-gray-200 rounded-lg hover:border-purple-300 transition-colors cursor-pointer">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2">
                        <div>
                            <h5 class="font-semibold text-gray-900 text-sm">Dr. {{ $consultation->doctor->name ?? 'Medical Staff' }}</h5>
                            <p class="text-xs text-gray-600">
                                @if($consultation->scheduled_for)
                                    {{ $consultation->scheduled_for->format('M j, Y \\a\\t g:i A') }}
                                @else
                                    Instant call
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                {{ $consultation->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($consultation->status == 'ongoing' ? 'bg-blue-100 text-blue-800' : 
                                   ($consultation->status == 'scheduled' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($consultation->status) }}
                            </span>
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium capitalize">
                                {{ $consultation->type }}
                            </span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ $consultation->symptoms }}</p>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500">Call ID: {{ $consultation->call_id }}</p>
                        @if($consultation->isActive())
                        <a href="{{ route('student.video-consultation.join', $consultation->id) }}" 
                           class="bg-purple-600 text-white px-3 py-1 rounded-lg hover:bg-purple-700 transition-colors text-sm">
                            <i class="fas fa-play me-1"></i>Join Call
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-4">
                {{ $videoConsultations->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-video-slash text-3xl mb-3 text-gray-300"></i>
                <p class="text-gray-500">No video consultations yet</p>
                <p class="text-sm text-gray-400 mt-1">Schedule your first video call with a doctor</p>
            </div>
        @endif
    </div>

    <!-- My Appointments & Requests -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Appointments -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
                <i class="fas fa-calendar-alt me-2 text-green-600"></i>My Appointments
            </h4>
            
            @if($appointments->count() > 0)
                <div class="space-y-3">
                    @foreach($appointments as $appointment)
                    <div class="p-4 bg-white border border-gray-200 rounded-lg hover:border-green-300 transition-colors cursor-pointer">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2">
                            <div>
                                <h5 class="font-semibold text-gray-900 text-sm">Dr. {{ $appointment->doctor->name ?? 'Medical Staff' }}</h5>
                                <p class="text-xs text-gray-600">{{ $appointment->appointment_date->format('M j, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium self-start 
                                {{ $appointment->status == 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                   ($appointment->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $appointment->reason }}</p>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    {{ $appointments->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-calendar-times text-3xl mb-3 text-gray-300"></i>
                    <p class="text-gray-500">No appointments scheduled</p>
                </div>
            @endif
        </div>

        <!-- Treatment Requests -->
        <div class="content-card rounded-lg p-6 shadow-sm">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
                <i class="fas fa-clipboard-list me-2 text-orange-600"></i>Treatment Requests
            </h4>
            
            @if($treatmentRequests->count() > 0)
                <div class="space-y-3">
                    @foreach($treatmentRequests as $request)
                    <div class="p-4 bg-white border border-gray-200 rounded-lg hover:border-orange-300 transition-colors cursor-pointer">
                        <div class="flex flex-wrap gap-2 mb-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                {{ $request->urgency == 'emergency' ? 'bg-red-100 text-red-800' : 
                                   ($request->urgency == 'urgent' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst($request->urgency) }}
                            </span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium 
                                {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($request->status == 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 line-clamp-3 mb-2">{{ $request->symptoms }}</p>
                        <p class="text-xs text-gray-500">{{ $request->created_at->format('M j, Y \\a\\t g:i A') }}</p>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4">
                    {{ $treatmentRequests->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-file-medical text-3xl mb-3 text-gray-300"></i>
                    <p class="text-gray-500">No treatment requests submitted</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Health Tips -->
    @if($healthTips->count() > 0)
    <div class="content-card rounded-lg p-6 shadow-sm">
        <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
            <i class="fas fa-heart me-2 text-pink-600"></i>Health Tips
        </h4>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($healthTips as $tip)
            <div class="p-4 bg-pink-50 border border-pink-200 rounded-lg hover:border-pink-300 transition-colors cursor-pointer transform hover:scale-[1.02]">
                <div class="flex items-center justify-between mb-2">
                    <span class="px-2 py-1 bg-pink-100 text-pink-800 rounded-full text-xs font-medium capitalize">
                        {{ str_replace('_', ' ', $tip->category) }}
                    </span>
                </div>
                <h5 class="font-semibold text-gray-900 mb-2 text-sm">{{ $tip->title }}</h5>
                <p class="text-sm text-gray-600 line-clamp-3">{{ Str::limit($tip->content, 100) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
// Simple JavaScript for service selection
function selectService(service) {
    // Reset all service cards
    document.querySelectorAll('.service-card').forEach(card => {
        card.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50', 'ring-red-500', 'bg-red-50');
        card.classList.add('bg-white');
    });
    
    // Hide all forms
    document.getElementById('appointment-form').classList.add('hidden');
    document.getElementById('emergency-form').classList.add('hidden');
    
    // Activate selected service
    if (service === 'appointment') {
        document.getElementById('appointment-card').classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
        document.getElementById('appointment-form').classList.remove('hidden');
    } else if (service === 'emergency') {
        document.getElementById('emergency-card').classList.add('ring-2', 'ring-red-500', 'bg-red-50');
        document.getElementById('emergency-form').classList.remove('hidden');
    }
    
    // Scroll to form
    const formElement = document.getElementById(service + '-form');
    if (formElement) {
        formElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function showInstantCallForm() {
    // Hide other forms
    document.getElementById('appointment-form').classList.add('hidden');
    document.getElementById('emergency-form').classList.add('hidden');
    
    // Show instant call form
    document.getElementById('instant-call-form').classList.remove('hidden');
    
    // Scroll to form
    document.getElementById('instant-call-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// Update the existing selectService function
function selectService(service) {
    // Reset all service cards
    document.querySelectorAll('.service-card').forEach(card => {
        card.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50', 'ring-red-500', 'bg-red-50');
        card.classList.add('bg-white');
    });
    
    // Hide all forms
    document.getElementById('appointment-form').classList.add('hidden');
    document.getElementById('emergency-form').classList.add('hidden');
    document.getElementById('instant-call-form').classList.add('hidden');
    
    // Activate selected service
    if (service === 'appointment') {
        document.getElementById('appointment-card').classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
        document.getElementById('appointment-form').classList.remove('hidden');
    } else if (service === 'emergency') {
        document.getElementById('emergency-card').classList.add('ring-2', 'ring-red-500', 'bg-red-50');
        document.getElementById('emergency-form').classList.remove('hidden');
    }
    
    // Scroll to form
    const formElement = document.getElementById(service + '-form');
    if (formElement) {
        formElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Add click handlers for service cards
document.addEventListener('DOMContentLoaded', function() {
    // Add group class for hover effects
    document.querySelectorAll('.service-card').forEach(card => {
        card.classList.add('group');
    });
    
    // Add hover effects
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (!this.classList.contains('ring-2')) {
                this.classList.add('shadow-lg');
            }
        });
        
        card.addEventListener('mouseleave', function() {
            if (!this.classList.contains('ring-2')) {
                this.classList.remove('shadow-lg');
            }
        });
    });
    
    // Add touch support for mobile
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('touchstart', function() {
            this.classList.add('active-scale');
        });
        
        card.addEventListener('touchend', function() {
            this.classList.remove('active-scale');
        });
    });
});
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

/* Service card animations */
.service-card {
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s;
}

.service-card:hover::before {
    left: 100%;
}

.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
}

.service-card:active {
    transform: scale(0.98);
}

.service-card.active-scale {
    transform: scale(0.95);
}

/* Enhanced clickable indicators */
.service-card .fa-chevron-right {
    transition: transform 0.2s ease;
}

.service-card:hover .fa-chevron-right {
    transform: translateX(3px);
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .content-card {
        margin-left: 0.5rem;
        margin-right: 0.5rem;
        padding: 1rem;
    }
    
    .grid-cols-1 > div {
        margin-bottom: 1rem;
    }
    
    .service-card {
        padding: 1.5rem 1rem;
    }
    
    .service-card:active {
        transform: scale(0.95);
    }
}

/* Improved touch targets for mobile */
@media (max-width: 640px) {
    select, input, textarea {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    button {
        padding: 1rem;
        font-size: 1.1rem;
    }
    
    .service-card {
        min-height: 200px;
        display: flex;
        align-items: center;
    }
    
    /* Larger touch targets */
    .service-card {
        min-height: 220px;
    }
}

/* Pulse animation for attention */
@keyframes subtle-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.02); }
}

.service-card {
    animation: subtle-pulse 3s ease-in-out infinite;
}

/* Focus states for accessibility */
.service-card:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

</style>
@endsection
@extends('layouts.student')

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
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>

                                <h3 class="text-lg sm:text-2xl font-bold text-white truncate">Hello,
                                    {{ Auth::user()->name ?? 'Student' }}
                                </h3>
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
                                            <div
                                                class="bg-white/20 backdrop-blur-sm border border-white/30 text-white px-3 py-2 rounded-lg text-sm font-medium">
                                                <span class="font-bold text-white">You have
                                                    {{ $upcomingConsultations->count() }}</span> Upcoming
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
                                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                My Appointments
                            </a>

                            <!-- Desktop view: Full button -->
                            <a href="{{ route('video-consultation.index') }}"
                                class="hidden sm:inline-flex items-center justify-center px-5 py-3 bg-white hover:bg-gray-50 text-dark font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white whitespace-nowrap">
                                <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                My Appointments
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doctor Selection Cards - Centered Version -->
        <div class="flex flex-col items-center justify-center">
            <div class="w-full max-w-4xl">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 px-4 md:px-0">
                    <!-- Instant Call Card (Pay Now) -->
                    <div class="flex flex-col items-center">
                        <div
                            class="content-card rounded-xl overflow-hidden shadow-lg bg-white/80 backdrop-blur-md border border-white/20 transition-transform duration-300 hover:-translate-y-2 max-w-sm w-full">
                            <div class="h-56 overflow-hidden bg-gray-100">
                                @if(isset($assignedDoctor) && $assignedDoctor->profile_image)
                                    <img src="{{ asset('public/storage/' . $assignedDoctor->profile_image) }}" alt="Doctor"
                                        class="w-full h-full object-cover object-center">
                                @elseif(isset($assignedDoctor))
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($assignedDoctor->name) }}&size=512"
                                        alt="Doctor" class="w-full h-full object-cover object-center">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                        <i class="fas fa-user-md text-5xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5 text-center">
                                <h3 class="text-gray-600 text-lg mb-4">To Get Doctor Consultation</h3>
                                <button onclick="showInstantCallForm()"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition-colors text-sm">
                                    Pay Now & Get Consult
                                </button>
                            </div>
                        </div>
                        <p class="mt-3 text-red-600 text-xs text-center font-medium px-3 max-w-sm w-full tiro">
                            সুপ্রিয় শিক্ষার্থী, তুমি বিনামূল্যে চিকিৎসার আওতাভুক্ত নও, ফিতে বিশেষজ্ঞ ডাক্তারের পরামর্শ পেতে
                            Pay Now বাটনে ক্লিক কর মাসিক ৫টাকা হারে চার্জ করা হবে
                        </p>
                    </div>

                    <!-- Schedule Card -->
                    <div class="flex flex-col items-center">
                        <div
                            class="content-card rounded-xl overflow-hidden shadow-lg bg-white/80 backdrop-blur-md border border-white/20 transition-transform duration-300 hover:-translate-y-2 max-w-sm w-full">
                            <div class="h-56 overflow-hidden bg-gray-100">
                                @if(isset($assignedDoctor) && $assignedDoctor->profile_image)
                                    <img src="{{ asset('public/storage/' . $assignedDoctor->profile_image) }}" alt="Doctor"
                                        class="w-full h-full object-cover object-center">
                                @elseif(isset($assignedDoctor))
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($assignedDoctor->name) }}&size=512"
                                        alt="Doctor" class="w-full h-full object-cover object-center">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                        <i class="fas fa-user-md text-5xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5 text-center">
                                <h3 class="text-gray-600 text-lg mb-4">To Get Doctor Consultation</h3>
                                <button onclick="selectService('appointment')"
                                    class="w-full bg-[#00B074] hover:bg-[#009e68] text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition-colors text-sm">
                                    Get A Schedule
                                </button>
                            </div>
                        </div>
                        <p class="mt-3 text-dark text-xs text-center font-medium px-3 max-w-sm w-full tiro">
                            সুপ্রিয় শিক্ষার্থী, তুমি বিনামূল্যে চিকিৎসার আওতাভুক্ত
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instant Video Call Form -->
        <div class="content-card rounded-lg p-6 shadow-sm hidden" id="instant-call-form">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
                <i class="fas fa-bolt me-2 text-purple-600"></i>Instant Video Call
            </h4>

            <form action="{{ route('hello-doctor.instant-video-call') }}" method="POST" class="space-y-4">
                @csrf
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-purple-600 mt-1 me-3"></i>
                        <div>
                            <p class="text-purple-800 font-medium">Instant Connection</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Preferred Doctor</label>
                        <select name="doctor_id" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                            @if(isset($assignedDoctor))
                                <option value="{{ $assignedDoctor->id }}" selected>Dr. {{ $assignedDoctor->name }} (Assigned)
                                </option>
                            @else
                                <option value="">Choose a doctor (or any available)</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} - {{ $doctor->specialization }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                            <select name="payment_method" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                                <option value="">Select Payment Method</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">Nagad</option>
                                <option value="rocket">Rocket</option>
                                <option value="card">Credit/Debit Card</option>
                            </select>
                        </div> -->
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Describe Your Symptoms *</label>
                    <textarea name="symptoms" rows="3" required placeholder="Briefly describe your symptoms for the doctor"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-purple-600 text-white py-3 rounded-lg hover:bg-purple-700 transition-colors font-medium text-lg transform hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-play-circle me-2"></i>Start Instant Video Call (৳ 5)
                </button>
            </form>
        </div>

        <!-- Appointment Form -->
        <div class="content-card rounded-lg p-6 shadow-sm hidden" id="appointment-form">
            <h4 class="text-xl font-semibold text-gray-900 border-b border-gray-200/60 pb-3 mb-4 flex items-center">
                <i class="fas fa-calendar-plus me-2 text-blue-600"></i>Book Appointment
            </h4>

            <form action="{{ route('hello-doctor.store-video-consultation') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor</label>
                        <select name="doctor_id" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            @if(isset($assignedDoctor))
                                <option value="{{ $assignedDoctor->id }}" selected>Dr. {{ $assignedDoctor->name }} (Assigned)
                                </option>
                            @else
                                <option value="">Choose a doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }} - {{ $doctor->specialization }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Appointment Date</label>
                        <input type="date" name="scheduled_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Time</label>
                        <select name="scheduled_time" required
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                            <option value="">Select Time</option>
                            <option value="09:00">09:00 AM</option>
                            <option value="10:00">10:00 AM</option>
                            <option value="11:00">11:00 AM</option>
                            <option value="12:00">12:00 PM</option>
                            <option value="13:00">01:00 PM</option>
                            <option value="14:00">02:00 PM</option>
                            <option value="15:00">03:00 PM</option>
                            <option value="16:00">04:00 PM</option>
                            <option value="17:00">05:00 PM</option>
                            <option value="18:00">06:00 PM</option>
                            <option value="19:00">07:00 PM</option>
                            <option value="20:00">08:00 PM</option>
                            <option value="21:00">09:00 PM</option>
                            <option value="22:00">10:00 PM</option>
                            <!-- <option value="23:00">11:00 PM</option>
                                <option value="00:00">12:00 AM</option>
                                <option value="01:00">01:00 AM</option>
                                <option value="02:00">02:00 AM</option>
                                <option value="03:00">03:00 AM</option>
                                <option value="04:00">04:00 AM</option>
                                <option value="05:00">05:00 AM</option>
                                <option value="06:00">06:00 AM</option>
                                <option value="07:00">07:00 AM</option> -->
                            <!-- <option value="08:00">08:00 AM</option>
                                <option value="09:00">09:00 AM</option>
                                <option value="10:00">10:00 AM</option>
                                <option value="11:00">11:00 AM</option>
                                <option value="12:00">12:00 PM</option>
                                <option value="13:00">01:00 PM</option>
                                <option value="14:00">02:00 PM</option>
                                <option value="15:00">03:00 PM</option>
                                <option value="16:00">04:00 PM</option>
                                <option value="17:00">05:00 PM</option>
                                <option value="18:00">06:00 PM</option>
                                <option value="19:00">07:00 PM</option>
                                <option value="20:00">08:00 PM</option>
                                <option value="21:00">09:00 PM</option>
                                <option value="22:00">10:00 PM</option>
                                <option value="23:00">11:00 PM</option> -->
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Visit</label>
                        <textarea name="reason" rows="3" required
                            placeholder="Briefly describe the reason for your appointment"
                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Symptoms (Optional)</label>
                    <textarea name="symptoms" rows="2" placeholder="Describe any symptoms you're experiencing"
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"></textarea>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium text-lg transform hover:scale-[1.02] active:scale-[0.98]">
                    <i class="fas fa-calendar-check me-2"></i>Book Appointment
                </button>
            </form>
        </div>
    </div>

    <script>
        function selectService(service) {
            // Reset all service cards
            document.querySelectorAll('.service-card').forEach(card => {
                card.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50', 'ring-red-500', 'bg-red-50');
                card.classList.add('bg-white');
            });

            // Hide all forms
            document.getElementById('appointment-form').classList.add('hidden');
            document.getElementById('instant-call-form').classList.add('hidden');

            // Activate selected service
            if (service === 'appointment') {
                document.getElementById('appointment-form').classList.remove('hidden');
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

            // Show instant call form
            document.getElementById('instant-call-form').classList.remove('hidden');

            // Scroll to form
            document.getElementById('instant-call-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Add click handlers for service cards
        document.addEventListener('DOMContentLoaded', function () {
            // Add group class for hover effects
            document.querySelectorAll('.service-card').forEach(card => {
                card.classList.add('group');
            });

            // Add hover effects
            document.querySelectorAll('.service-card').forEach(card => {
                card.addEventListener('mouseenter', function () {
                    if (!this.classList.contains('ring-2')) {
                        this.classList.add('shadow-lg');
                    }
                });

                card.addEventListener('mouseleave', function () {
                    if (!this.classList.contains('ring-2')) {
                        this.classList.remove('shadow-lg');
                    }
                });
            });

            // Add touch support for mobile
            document.querySelectorAll('.service-card').forEach(card => {
                card.addEventListener('touchstart', function () {
                    this.classList.add('active-scale');
                });

                card.addEventListener('touchend', function () {
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
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
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

            .grid-cols-1>div {
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

            select,
            input,
            textarea {
                font-size: 16px;
                /* Prevents zoom on iOS */
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

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }
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
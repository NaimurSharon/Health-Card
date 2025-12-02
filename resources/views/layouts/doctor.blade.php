<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ setting('site_description') }}">
    
    <title>{{ setting('site_title') }} | @yield('title')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/storage/' . setting('site_favicon', 'logo/amardesh-shadhinotar-kotha-bole.webp')) }}" type="image/webp">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        gray: {
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            200: '#e5e7eb',
                            300: '#d1d5db',
                            400: '#9ca3af',
                            500: '#6b7280',
                            600: '#4b5563',
                            700: '#374151',
                            800: '#1f2937',
                            900: '#111827',
                        },
                        sidebar: {
                            bg: '#030708',
                            menu: '#1a1f2e',
                            active: '#3B82F6',
                            text: '#e5e7eb',
                            hover: '#374151'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    backdropBlur: {
                        xs: '2px',
                    }
                }
            }
        }
    </script>
    
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Exact styling from the image */
        body {
            background-color: #f9fafb;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow-x: hidden;
        }
        
        /* Soft Color Blobs Background */
       .color-ball-bg {
            position: fixed;
            top: 50%;
            left: 50%;
            width: 50%;
            height: 50%;
            transform: translate(-50%, -50%);
            z-index: -1;
            overflow: hidden;
            opacity: 1;
            background:
                radial-gradient(circle at 10% 20%, #55CEF5 0%, transparent 70%),   /* Cyan top-left */
                radial-gradient(circle at 90% 15%, #06AC73 0%, transparent 70%),   /* Green top-right */
                radial-gradient(circle at 85% 85%, #FFAF8B 0%, transparent 70%),   /* Orange bottom-right */
                radial-gradient(circle at 15% 85%, #9747FF 0%, transparent 70%),   /* Violet bottom-left */
                radial-gradient(circle at 50% 50%, #007AFF 0%, transparent 70%),   /* Blue center */
                radial-gradient(circle at 40% 70%, #534ED9 0%, transparent 70%);   /* Deep purple lower-mid */
            filter: blur(120px);
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            25% {
                transform: translateY(-20px) rotate(5deg);
            }
            50% {
                transform: translateY(0) rotate(0deg);
            }
            75% {
                transform: translateY(20px) rotate(-5deg);
            }
        }

        /* Call Notification Animations */
        @keyframes ring {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(5deg); }
            50% { transform: rotate(-5deg); }
            75% { transform: rotate(5deg); }
        }

        @keyframes pulse-glow {
            0%, 100% { 
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
            }
            50% { 
                box-shadow: 0 0 30px rgba(59, 130, 246, 0.8), 0 0 40px rgba(59, 130, 246, 0.4);
            }
        }

        .ringing {
            animation: ring 0.5s ease-in-out infinite;
        }

        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        .scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .scrollbar::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        .scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        input:focus, select:focus, textarea:focus {
            background: rgba(255, 255, 255, 0.95) !important;
            border-color: #3B82F6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            padding: 0.7rem 1rem !important;
        }
        
        input, select  {
            background: rgba(255, 255, 255, 0.95) !important;
            padding: 0.7rem 1rem !important;
        }
        
        .header-blur {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table-header {
            background: #06AC73;
            color:white;
            border-bottom: 1px solid #e5e7eb;
            backdrop-filter: blur(4px);
        }

        .table-row {
            border-bottom: 1px solid #f3f4f6;
        }
        
        .table-row:hover {
            background: rgba(249, 250, 251, 0.8);
        }
        
        /* Content cards with slight transparency to show background */
        .content-card {
            background: transparent;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(229, 231, 235, 0.8);
        }
        
        /* Custom scrollbar */
        .scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        
        .scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        .scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 2px;
        }
        
        .scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Dark scrollbar for sidebar */
        .sidebar .scrollbar::-webkit-scrollbar-track {
            background: #1a1f2e;
        }
        
        .sidebar .scrollbar::-webkit-scrollbar-thumb {
            background: #374151;
        }
        
        .sidebar .scrollbar::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }
        
        /* Mobile sidebar improvements */
        @media (max-width: 1023px) {
            .sidebar-mobile {
                position: fixed;
                z-index: 40;
            }
        }

        /* Call Notification Modal */
        .call-notification {
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        .call-avatar {
            width: 80px;
            height: 80px;
        }
    </style>
</head>
<body class="h-full bg-gray-50" x-data="doctorApp()">
    <!-- Color Ball Background -->
    <div class="color-ball-bg"></div>

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-30 bg-black/50 lg:hidden" x-transition.opacity></div>
    
    <div class="flex h-full">

        <!-- Sidebar -->
        @auth
            @if(auth()->user()->role === 'admin')
                @include('backend.inc.sidebar')
            @elseif(auth()->user()->role === 'teacher')  
                @include('teacher.inc.sidebar-teacher')
            @elseif(auth()->user()->role === 'doctor')
                @include('doctor.inc.sidebar-doctor')
            @endif
        @endauth

        <!-- Main Content Area -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Header -->
            <header class="header-blur sticky top-0 z-20">
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="mr-4 lg:hidden">
                            <i class="fas fa-bars text-gray-600"></i>
                        </button>
                        <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'Dashboard')</h1>
                    </div>
                    
                    <!-- Right side buttons -->
                    <div class="flex items-center space-x-4">
                        <!-- Call Status Indicator -->
                        <div x-show="incomingCall" class="flex items-center space-x-2 bg-orange-100 text-orange-800 px-3 py-1 rounded-full">
                            <i class="fas fa-phone-volume animate-pulse"></i>
                            <span class="text-sm font-medium">Incoming Call</span>
                        </div>

                        <div class="flex items-center space-x-3">
                            <button class="p-2 text-gray-600 hover:text-gray-900 transition-colors relative">
                                <i class="fas fa-bell"></i>
                                <span x-show="unreadNotifications > 0" class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                            </button>
                            <button class="p-2 text-gray-600 hover:text-gray-900 transition-colors relative">
                                <i class="fas fa-envelope"></i>
                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full"></span>
                            </button>
                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto scrollbar p-6">
                <!-- Notifications -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-600 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 me-3"></i>
                            <span class="text-green-700">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 me-3"></i>
                            <span class="text-red-700">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Modern Notification Panel - Top Right -->
    <div x-show="incomingCall || upcomingConsultations.length > 0" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full opacity-0"
         x-transition:enter-end="translate-x-0 opacity-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0 opacity-100"
         x-transition:leave-end="translate-x-full opacity-0"
         class="fixed top-24 right-8 z-50 w-[24rem] max-w-full space-y-4 font-sans">
        
        <!-- Incoming Call Card -->
        <div x-show="incomingCall" 
             class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(8,_112,_184,_0.7)] overflow-hidden border border-blue-100 relative">
            
            <!-- Decorative blur -->
            <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div>

            <!-- Header -->
            <div class="px-6 pt-6 pb-2 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <span class="absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75 animate-ping"></span>
                        <div class="relative bg-blue-50 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center">
                            <i class="fas fa-phone animate-bounce"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-lg leading-tight">Incoming Call</h3>
                        <p class="text-blue-500 text-xs font-medium tracking-wide uppercase">Video Consultation</p>
                    </div>
                </div>
                <div class="bg-red-50 text-red-600 text-xs font-bold px-3 py-1 rounded-full border border-red-100 flex items-center gap-1">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    LIVE
                </div>
            </div>

            <!-- Caller Info -->
            <div class="px-6 py-4">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center flex-shrink-0 border border-gray-100 shadow-sm">
                        <i class="fas fa-user text-gray-400 text-3xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xl font-bold text-gray-900 truncate" x-text="currentCall?.student_name || 'Student'"></h4>
                        <p class="text-gray-500 text-sm" x-text="currentCall?.student_class || 'School Student'"></p>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Symptoms</p>
                        <p class="text-gray-700 text-sm font-medium truncate" x-text="currentCall?.symptoms || 'General'"></p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Fee</p>
                        <p class="text-gray-700 text-sm font-medium">৳ <span x-text="currentCall?.fee || '0'"></span></p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-2 gap-4">
                    <button @click="rejectCall()" 
                            class="group bg-white border border-gray-200 text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-200 py-3.5 px-4 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2">
                        <i class="fas fa-times text-lg group-hover:rotate-90 transition-transform"></i>
                        <span>Decline</span>
                    </button>
                    <button @click="acceptCall()" 
                            class="bg-gray-900 text-white hover:bg-black py-3.5 px-4 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg shadow-gray-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-video"></i>
                        <span>Accept</span>
                    </button>
                </div>

                <!-- Timer -->
                <div class="mt-4 text-center">
                    <p class="text-gray-400 text-xs font-medium">
                        Auto-reject in <span x-text="callTimer" class="text-gray-600 w-4 inline-block text-center"></span>s
                    </p>
                </div>
            </div>
        </div>

        <!-- Upcoming Consultations Card -->
        <div x-show="upcomingConsultations.length > 0 && !incomingCall" 
             class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden border border-gray-100">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-white">
                <div>
                    <h3 class="font-bold text-gray-900 text-base">Up Next</h3>
                    <p class="text-gray-400 text-xs mt-0.5">Today's Schedule</p>
                </div>
                <button @click="upcomingConsultations = []" class="w-8 h-8 rounded-full hover:bg-gray-50 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Consultation List -->
            <div class="max-h-[20rem] overflow-y-auto scrollbar-thin">
                <template x-for="consultation in upcomingConsultations" :key="consultation.id">
                    <div class="p-4 border-b border-gray-50 hover:bg-gray-50/50 transition-colors group cursor-pointer">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center flex-shrink-0 text-gray-400 border border-gray-100 group-hover:border-blue-100 group-hover:bg-blue-50 group-hover:text-blue-500 transition-all">
                                <i class="fas fa-user text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold text-gray-900 text-sm truncate" x-text="consultation.student_name"></h4>
                                    <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded-full" x-text="consultation.time"></span>
                                </div>
                                <p class="text-xs text-gray-500 truncate mb-2" x-text="consultation.symptoms"></p>
                                <a :href="`/doctor/video-consultations/${consultation.id}`" 
                                   class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-700">
                                    View Details <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50/50 px-6 py-3 border-t border-gray-50">
                <a href="/doctor/video-consultations" class="text-gray-500 hover:text-gray-900 text-xs font-semibold flex items-center justify-center transition-colors">
                    View Full Schedule
                </a>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    
    <!-- Echo for Real-time -->
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.4.0/pusher.min.js"></script>

    <script>
        function doctorApp() {
            return {
                sidebarOpen: window.innerWidth >= 1024,
                incomingCall: false,
                currentCall: null,
                callTimer: 30,
                callTimerInterval: null,
                unreadNotifications: 0,
                ringtoneInterval: null,
                ringtoneAudio: null,
                pollingInterval: null,
                upcomingConsultations: [],
                isOnCallPage: window.location.pathname.includes('/video-consultations/') && window.location.pathname.includes('/join'),
    
                init() {
                    console.log('🚀 Initializing doctor app...');
                    console.log('📄 Current page:', window.location.pathname);
                    console.log('📞 Is on call page:', this.isOnCallPage);
                    
                    this.handleResize();
                    window.addEventListener('resize', this.handleResize.bind(this));
                    
                    // Only start polling if not on a call page
                    if (!this.isOnCallPage) {
                        this.startPolling();
                        this.fetchUpcomingConsultations();
                        setTimeout(() => this.checkForCalls(), 1000);
                    } else {
                        console.log('🛑 Skipping polling - already on call page');
                    }
                },
    
                handleResize() {
                    if (window.innerWidth >= 1024) {
                        this.sidebarOpen = true;
                    } else {
                        this.sidebarOpen = false;
                    }
                },

                async fetchUpcomingConsultations() {
                    try {
                        console.log('📅 Fetching upcoming consultations...');
                        const response = await fetch('/doctor/video-consultations', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.ok) {
                            const data = await response.json();
                            // Filter for scheduled consultations in the next 24 hours
                            const now = new Date();
                            const tomorrow = new Date(now.getTime() + 24 * 60 * 60 * 1000);
                            
                            this.upcomingConsultations = (data.consultations || [])
                                .filter(c => {
                                    const scheduledTime = new Date(c.scheduled_for);
                                    return c.status === 'scheduled' && scheduledTime > now && scheduledTime < tomorrow;
                                })
                                .slice(0, 5) // Show max 5
                                .map(c => ({
                                    id: c.id,
                                    student_name: c.student?.user?.name || 'Student',
                                    symptoms: c.symptoms || 'Consultation',
                                    time: new Date(c.scheduled_for).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }),
                                    type: c.type || 'Video Call'
                                }));
                            
                            console.log('📅 Upcoming consultations:', this.upcomingConsultations);
                        }
                    } catch (error) {
                        console.error('❌ Error fetching upcoming consultations:', error);
                    }
                },
    
                startPolling() {
                    console.log('🔄 Starting polling for calls every 10 seconds...');
                    // Clear any existing interval
                    this.stopPolling();
                    
                    // Poll for new calls every 10 seconds
                    this.pollingInterval = setInterval(() => {
                        // Double-check we're not on a call page
                        if (!this.isOnCallPage && !window.location.pathname.includes('/video-consultations/') && !window.location.pathname.includes('/join')) {
                            this.checkForCalls();
                        } else {
                            console.log('🛑 Polling skipped - on call page');
                            this.stopPolling();
                        }
                    }, 10000);
                    
                    // Initial check
                    if (!this.isOnCallPage) {
                        this.checkForCalls();
                    }
                },
    
                stopPolling() {
                    if (this.pollingInterval) {
                        clearInterval(this.pollingInterval);
                        this.pollingInterval = null;
                        console.log('🛑 Polling stopped');
                    }
                },
    
                async checkForCalls() {
                    // Don't check if we're on a call page
                    if (this.isOnCallPage || window.location.pathname.includes('/video-consultations/') && window.location.pathname.includes('/join')) {
                        console.log('🛑 Call check skipped - on call page');
                        this.stopPolling();
                        return;
                    }
    
                    try {
                        console.log('📞 Checking for pending calls...');
                        const response = await fetch('/api/doctor/pending-calls', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            credentials: 'same-origin'
                        });
                        
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        console.log('📋 Polling response:', data);
                        
                        if (data.hasCall && !this.incomingCall) {
                            console.log('🎯 New call detected!', data.call);
                            this.handleIncomingCall(data.call);
                        }
                    } catch (error) {
                        console.error('❌ Error checking for calls:', error);
                    }
                },
    
                handleIncomingCall(callData) {
                    if (this.incomingCall) {
                        console.log('⚠️ Already handling a call, ignoring new one');
                        return;
                    }
    
                    console.log('📞 Handling incoming call:', callData);
                    this.currentCall = callData;
                    this.incomingCall = true;
                    this.callTimer = 60;
                    
                    // Stop polling when we have an incoming call
                    this.stopPolling();
                    
                    // Play ringtone
                    this.playRingtone();
                    
                    // Start call timer
                    this.startCallTimer();
                    
                    // Show browser notification
                    this.showBrowserNotification();
                },
    
                playRingtone() {
                    try {
                        console.log('🔊 Playing ringtone...');
                        
                        // Stop any existing ringtone first
                        this.stopRingtone();
                        
                        // Create a simple beep using Web Audio API (works in most browsers)
                        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
    
                        const playBeep = () => {
                            const oscillator = audioContext.createOscillator();
                            const gainNode = audioContext.createGain();
                            
                            oscillator.connect(gainNode);
                            gainNode.connect(audioContext.destination);
                            
                            oscillator.type = 'sine';
                            oscillator.frequency.value = 800;
                            gainNode.gain.value = 0.1;
                            
                            oscillator.start();
                            
                            // Stop after 0.3 seconds for beep pattern
                            setTimeout(() => {
                                oscillator.stop();
                            }, 300);
                        };
    
                        // Play initial beep
                        playBeep();
                        
                        this.ringtoneInterval = setInterval(playBeep, 2000);
                        
                    } catch (error) {
                        console.error('🔇 Audio failed:', error);
                    }
                },
    
                stopRingtone() {
                    console.log('🔇 Stopping ringtone...');
                    if (this.ringtoneInterval) {
                        clearInterval(this.ringtoneInterval);
                        this.ringtoneInterval = null;
                    }
                },
    
                startCallTimer() {
                    console.log('⏰ Starting call timer (30 seconds)...');
                    this.stopCallTimer();
                    
                    this.callTimerInterval = setInterval(() => {
                        this.callTimer--;
                        console.log(`⏱️ Call timer: ${this.callTimer}s`);
                        
                        if (this.callTimer <= 0) {
                            console.log('⏰ Call timer expired, auto-rejecting...');
                            this.autoRejectCall();
                        }
                    }, 1000);
                },
    
                stopCallTimer() {
                    if (this.callTimerInterval) {
                        clearInterval(this.callTimerInterval);
                        this.callTimerInterval = null;
                    }
                },
    
                async acceptCall() {
                    try {
                        console.log('✅ Accepting call:', this.currentCall?.id);
                        this.stopRingtone();
                        this.stopCallTimer();
                        
                        const response = await fetch('/api/video-calls/accept', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                call_id: this.currentCall.id
                            })
                        });
    
                        const result = await response.json();
                        console.log('📨 Accept call response:', result);
    
                        if (response.ok && result.success) {
                            console.log('🔗 Redirecting to call...');
                            // Don't reset call state - let the redirect happen
                            // Polling will be stopped on the call page
                            window.location.href = `/doctor/video-consultations/${this.currentCall.id}/join`;
                        } else {
                            console.error('❌ Failed to accept call');
                            alert('Failed to accept call. Please try again.');
                            this.resetCallAndRestartPolling();
                        }
    
                    } catch (error) {
                        console.error('❌ Error accepting call:', error);
                        alert('Error accepting call. Please try again.');
                        this.resetCallAndRestartPolling();
                    }
                },
    
                async rejectCall() {
                    try {
                        console.log('❌ Rejecting call:', this.currentCall?.id);
                        this.stopRingtone();
                        this.stopCallTimer();
    
                        const response = await fetch('/api/video-calls/reject', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                call_id: this.currentCall.id
                            })
                        });
    
                        console.log('📨 Reject call response:', await response.json());
                        this.resetCallAndRestartPolling();
                        
                    } catch (error) {
                        console.error('❌ Error rejecting call:', error);
                        this.resetCallAndRestartPolling();
                    }
                },
    
                async autoRejectCall() {
                    try {
                        console.log('⏰ Auto-rejecting call:', this.currentCall?.id);
                        this.stopRingtone();
                        this.stopCallTimer();
                        
                        const response = await fetch('/api/video-calls/auto-reject', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                call_id: this.currentCall.id
                            })
                        });
    
                        console.log('📨 Auto-reject response:', await response.json());
                        this.resetCallAndRestartPolling();
                        
                    } catch (error) {
                        console.error('❌ Error auto-rejecting call:', error);
                        this.resetCallAndRestartPolling();
                    }
                },
    
                cancelCall(reason) {
                    console.log('📞 Call cancelled:', reason);
                    this.stopRingtone();
                    this.stopCallTimer();
                    if (reason) {
                        alert(reason);
                    }
                    this.resetCallAndRestartPolling();
                },
    
                resetCall() {
                    console.log('🔄 Resetting call state');
                    this.incomingCall = false;
                    this.currentCall = null;
                    this.callTimer = 30;
                    this.stopRingtone();
                    this.stopCallTimer();
                },
    
                resetCallAndRestartPolling() {
                    console.log('🔄 Resetting call state and restarting polling');
                    this.resetCall();
                    // Only restart polling if we're not on a call page
                    if (!this.isOnCallPage && !window.location.pathname.includes('/video-consultations/') && !window.location.pathname.includes('/join')) {
                        // Restart polling after a short delay
                        setTimeout(() => {
                            this.startPolling();
                        }, 2000);
                    } else {
                        console.log('🛑 Not restarting polling - on call page');
                    }
                },
    
                showBrowserNotification() {
                    if ('Notification' in window) {
                        if (Notification.permission === 'granted') {
                            this.createBrowserNotification();
                        } else if (Notification.permission === 'default') {
                            Notification.requestPermission().then(permission => {
                                if (permission === 'granted') {
                                    this.createBrowserNotification();
                                }
                            });
                        }
                    }
                },
    
                createBrowserNotification() {
                    try {
                        const notification = new Notification('📞 Incoming Video Call', {
                            body: `Student ${this.currentCall?.student_name} is calling for consultation`,
                            icon: '/favicon.ico',
                            tag: 'video-call',
                            requireInteraction: true
                        });
    
                        notification.onclick = () => {
                            window.focus();
                            notification.close();
                        };
    
                        // Auto close after 10 seconds
                        setTimeout(() => {
                            notification.close();
                        }, 10000);
    
                    } catch (error) {
                        console.log('🔕 Browser notification failed:', error);
                    }
                },
    
                get callTimerText() {
                    return `Auto-reject in ${this.callTimer}s`;
                }
            }
        }
    
        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('📄 DOM loaded, initializing doctor app...');
            
            // Request notification permission
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission().then(permission => {
                    console.log('🔔 Notification permission:', permission);
                });
            }
            
            // Global error handler
            window.addEventListener('error', function(e) {
                console.error('💥 Global error:', e.error);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>

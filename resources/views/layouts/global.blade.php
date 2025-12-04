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

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Bootstrap JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&family=Tiro+Bangla:ital@0;1&display=swap" rel="stylesheet">
    
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
        
        
        .noto {
          font-family: "Noto Serif Bengali",'Inter', serif;
          font-optical-sizing: auto;
          font-weight: <weight>;
          font-style: normal;
          font-variation-settings:
            "wdth" 100;
        }
        
        .tiro {
          font-family: "Tiro Bangla", 'Inter'!important;
          font-weight: 400;
          font-style: normal;
        }
        
        .tiro-italic {
          font-family: "Tiro Bangla", serif;
          font-weight: 400;
          font-style: italic;
        }
        
        .inter{
            font-family: 'Inter', sans-serif;
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
        
        /* Navigation styles */
        .nav-menu {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.8);
        }
        
        .nav-link {
            color: #4b5563;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .nav-link:hover {
            color: #3B82F6;
            background: rgba(59, 130, 246, 0.1);
        }
        
        .nav-link.active {
            color: #3B82F6;
            background: rgba(59, 130, 246, 0.1);
        }
        
        .mobile-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(229, 231, 235, 0.8);
        }

        /* Modal animations */
        .modal-enter {
            opacity: 0;
            transform: scale(0.95);
        }
        
        .modal-enter-active {
            opacity: 1;
            transform: scale(1);
            transition: opacity 300ms, transform 300ms;
        }
        
        .modal-exit {
            opacity: 1;
            transform: scale(1);
        }
        
        .modal-exit-active {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 300ms, transform 300ms;
        }
    </style>
    
    <script>
        // Define navbarState function before body tag uses it
        function navbarState() {
            return {
                mobileMenuOpen: false,
                notificationsOpen: false,
                messagesOpen: false,
                userMenuOpen: false,
                homeworkOpen: false,
                
                init() {
                    this.handleResize();
                    window.addEventListener('resize', this.handleResize.bind(this));
                },
                
                handleResize() {
                    if (window.innerWidth >= 1024) {
                        this.mobileMenuOpen = false;
                    }
                }
            }
        }

        // Make it globally available
        window.navbarState = navbarState;
    </script>
</head>
<body class="h-full bg-gray-50" x-data="navbarState()">
    <!-- Color Ball Background -->
    <div class="color-ball-bg"></div>

    <!-- Mobile menu backdrop -->
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" 
         class="fixed inset-0 z-40 bg-black/50 lg:hidden" x-transition.opacity></div>
    
    <div class="flex flex-col h-full">
        
        @php
            $role = Auth::user()->role ?? 'guest';
        @endphp

        @switch($role)
            @case('teacher')
                @include('teacher.inc.navbar')
                @break

            @case('student')
                @include('student.inc.navbar')
                @break

            @case('public')
                @include('inc.navbar')
                @break

            @default
                @include('inc.navbar')
        @endswitch


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

    <!-- Login/Registration Modal -->
    <div id="authModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" onclick="closeAuthModal()"></div>
            
            <!-- Modal panel -->
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <!-- Modal header -->
                <div class="bg-white px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-900" id="authModalTitle">Login to Continue</h3>
                        <button type="button" onclick="closeAuthModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Modal body -->
                <div class="bg-white px-6 py-6">
                    <form id="authForm" method="POST" action="{{ route('auth.login.submit') }}">
                        @csrf
                        
                        <!-- Phone Number Input -->
                        <div class="mb-6">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number *
                            </label>
                            <input type="tel" 
                                   name="phone" 
                                   id="phone" 
                                   required
                                   pattern="[0-9]{11}"
                                   maxlength="11"
                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="01XXXXXXXXX"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <p class="mt-1 text-xs text-gray-500">Enter your 11-digit phone number</p>
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>
                        </div>

                        <!-- Error Messages -->
                        <div id="authErrors" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 me-3"></i>
                                <span id="errorMessage" class="text-red-700 text-sm"></span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col space-y-3">
                            <button type="submit" 
                                    id="loginButton"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login
                            </button>
                            
                            <button type="button" 
                                    id="registerButton"
                                    onclick="switchToRegister()"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-user-plus mr-2"></i>
                                Create New Account
                            </button>
                        </div>
                    </form>

                    <form id="registerForm" method="POST" action="{{ route('global.register') }}" class="hidden">
                        @csrf
                    
                        <div class="mb-6">
                            <label for="register_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number *
                            </label>
                            <input type="tel" 
                                   name="phone" 
                                   id="register_phone" 
                                   required
                                   pattern="[0-9]{11}"
                                   maxlength="11"
                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="01XXXXXXXXX"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name *
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   required
                                   class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                                   placeholder="Enter your full name">
                        </div>
                    
                        <!-- Public User Fields (Hidden by default) -->
                        <div id="publicUserFields" class="space-y-4">
                            
                            <div class="grid grid-cols-2 gap-4 pb-3">
                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date of Birth
                                    </label>
                                    <input type="date" 
                                           name="date_of_birth" 
                                           id="date_of_birth"
                                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                </div>
                                
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                        Gender
                                    </label>
                                    <select name="gender" 
                                            id="gender"
                                            class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Registration Error Messages -->
                        <div id="registerErrors" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 me-3"></i>
                                <span id="registerErrorMessage" class="text-red-700 text-sm"></span>
                            </div>
                        </div>
                    
                        <div class="flex flex-col space-y-3">
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-user-plus mr-2"></i>
                                Create Account
                            </button>
                            
                            <button type="button" 
                                    onclick="switchToLogin()"
                                    class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg font-medium transition-colors flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Login
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    
    <script>
        // Global appointment data storage - accessible from all pages
        window.pendingAppointmentData = null;

        // Auth Modal Functions - available globally
        function showAuthModal(appointmentData = null) {
            if (appointmentData) {
                window.pendingAppointmentData = appointmentData;
            }
            
            const modal = document.getElementById('authModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Reset forms
            switchToLogin();
        }

        function closeAuthModal() {
            const modal = document.getElementById('authModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            window.pendingAppointmentData = null;
        }

        function switchToRegister() {
            document.getElementById('authForm').classList.add('hidden');
            document.getElementById('registerForm').classList.remove('hidden');
            document.getElementById('authModalTitle').textContent = 'Create New Account';
            hideErrors();
        }

        function switchToLogin() {
            document.getElementById('registerForm').classList.add('hidden');
            document.getElementById('authForm').classList.remove('hidden');
            document.getElementById('authModalTitle').textContent = 'Login to Continue';
            hideErrors();
        }

        function showError(message, isRegister = false) {
            const errorDiv = isRegister ? document.getElementById('registerErrors') : document.getElementById('authErrors');
            const errorMessage = isRegister ? document.getElementById('registerErrorMessage') : document.getElementById('errorMessage');
            
            errorMessage.textContent = message;
            errorDiv.classList.remove('hidden');
        }

        function hideErrors() {
            document.getElementById('authErrors').classList.add('hidden');
            document.getElementById('registerErrors').classList.add('hidden');
        }

        // Handle login form submission
        document.getElementById('authForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const loginButton = document.getElementById('loginButton');
            
            // Show loading state
            loginButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Logging in...';
            loginButton.disabled = true;
            hideErrors();

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Login successful
                    closeAuthModal();
                    
                    // If there was pending appointment data, let the page handle it
                    if (window.pendingAppointmentData && window.handlePendingAppointment) {
                        window.handlePendingAppointment();
                    } else {
                        window.location.reload();
                    }
                } else {
                    // Login failed
                    showError(data.message || 'Login failed. Please check your credentials.');
                }
            } catch (error) {
                showError('Network error. Please try again.');
            } finally {
                // Reset button state
                loginButton.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i>Login';
                loginButton.disabled = false;
            }
        });

        // Handle registration form submission
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const registerButton = this.querySelector('button[type="submit"]');
            
            // Show loading state
            registerButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';
            registerButton.disabled = true;
            hideErrors();

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    // Registration successful, automatically log them in
                    closeAuthModal();
                    
                    // If there was pending appointment data, let the page handle it
                    if (window.pendingAppointmentData && window.handlePendingAppointment) {
                        window.handlePendingAppointment();
                    } else {
                        window.location.reload();
                    }
                } else {
                    // Registration failed
                    showError(data.message || 'Registration failed. Please try again.', true);
                }
            } catch (error) {
                showError('Network error. Please try again.', true);
            } finally {
                // Reset button state
                registerButton.innerHTML = '<i class="fas fa-user-plus mr-2"></i>Create Account';
                registerButton.disabled = false;
            }
        });
        

        document.addEventListener('alpine:init', () => {
            // Alpine is already initialized by the CDN script
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAuthModal();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
<<<<<<< HEAD
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
=======
<!DOCTYPE html>
<html lang="en" class="h-full bg-gradient-to-br from-blue-50 to-green-50 dark:from-gray-900 dark:to-dark-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('site_title') }} | @yield('title')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/storage/' . setting('site_favicon', 'logo/amardesh-shadhinotar-kotha-bole.webp')) }}" type="image/webp">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&family=Tiro+Bangla:ital@0;1&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            800: '#1e293b',
                            700: '#334155',
                            600: '#475569',
                            900: '#0f172a',
                        },
                        primary: {
                            50: '#f0f9ff',
                            500: '#06AC73',
                            600: '#059669',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .content-card {
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.9);
        }
        .dark .content-card {
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .table-header {
            background: #06AC73;
            border-bottom: 1px solid rgba(229, 231, 235, 0.6);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #06AC73 0%, #059669 50%, #047857 100%);
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

    </style>
</head>
<body class="h-full inter">
    <div class="min-h-full flex items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6">
            <!-- Logo & Header -->
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-lg mb-4">
                    <i class="fas fa-user-plus text-2xl text-primary-500"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Student Registration</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Create your student account to access health records
                </p>
            </div>

            <!-- Registration Card -->
            <div class="content-card rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl">
                <div class="table-header px-6 py-4 text-center">
                    <h3 class="text-xl font-bold text-white">Create Account</h3>
                </div>

                <div class="p-6 sm:p-8">
                    <!-- Registration Form -->
                    <form id="registerForm" method="POST" action="{{ route('global.register') }}" class="space-y-6">
                        @csrf
                    
                        <div>
                            <label for="register_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input type="tel" 
                                       name="phone" 
                                       id="register_phone" 
                                       required
                                       pattern="[0-9]{11}"
                                       maxlength="11"
                                       class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:text-white transition-all duration-200"
                                       placeholder="01XXXXXXXXX"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Enter your 11-digit phone number without country code
                            </p>
                        </div>
                    
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Full Name *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       required
                                       class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:text-white transition-all duration-200"
                                       placeholder="Enter your full name">
                            </div>
                        </div>

                        <!-- Public User Fields -->
                        <div id="publicUserFields" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Date of Birth
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-calendar text-gray-400"></i>
                                        </div>
                                        <input type="date" 
                                               name="date_of_birth" 
                                               id="date_of_birth"
                                               class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:text-white transition-all duration-200">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Gender
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-venus-mars text-gray-400"></i>
                                        </div>
                                        <select name="gender" 
                                                id="gender"
                                                class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:text-white transition-all duration-200">
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Registration Error Messages -->
                        <div id="registerErrors" class="hidden p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                                <span id="registerErrorMessage" class="text-red-700 dark:text-red-300 text-sm"></span>
                            </div>
                        </div>
                    
                        <div class="flex flex-col space-y-3">
                            <button type="submit" 
                                    class="w-full gradient-bg text-white py-3 px-4 rounded-lg font-semibold text-sm transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5 flex items-center justify-center">
                                <i class="fas fa-user-plus mr-2"></i>
                                Create Account
                            </button>
                            
                            <button type="button" 
                                    onclick="switchToLogin()"
                                    class="w-full bg-gray-500 hover:bg-gray-600 text-white py-3 px-4 rounded-lg font-semibold text-sm transition-all duration-200 flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Back to Login
                            </button>
                        </div>
                    </form>

                    <!-- Help Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Need help with registration?
                            </p>
                            <div class="mt-2 flex justify-center space-x-4">
                                <a href="#" class="text-xs text-primary-500 hover:text-primary-600 dark:text-primary-400 dark:hover:text-primary-300 flex items-center">
                                    <i class="fas fa-question-circle mr-1"></i>
                                    Help Center
                                </a>
                                <a href="#" class="text-xs text-primary-500 hover:text-primary-600 dark:text-primary-400 dark:hover:text-primary-300 flex items-center">
                                    <i class="fas fa-phone mr-1"></i>
                                    Contact Support
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                    <i class="fas fa-heartbeat text-primary-500 text-lg mb-2"></i>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Health Records</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                    <i class="fas fa-book-medical text-primary-500 text-lg mb-2"></i>
                    <p class="text-xs text-gray-600 dark:text-gray-400">School Diary</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                    <i class="fas fa-calendar-check text-primary-500 text-lg mb-2"></i>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Appointments</p>
                </div>
            </div>

            <!-- Switch to Regular Login -->
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Already have an account?
                    <a href="#" id="loginLink" 
                       class="font-medium text-primary-500 hover:text-primary-600 dark:text-primary-400 dark:hover:text-primary-300">
                        Login here
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Phone number formatting
            const phoneInput = document.getElementById('register_phone');
            
            phoneInput.addEventListener('input', function(e) {
                // Remove non-numeric characters
                let value = e.target.value.replace(/\D/g, '');
                
                // Limit to 11 digits
                if (value.length > 11) {
                    value = value.slice(0, 11);
                }
                
                e.target.value = value;
            });

            // Add focus effects
            const inputs = document.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.classList.add('ring-2', 'ring-primary-200');
                });
                
                input.addEventListener('blur', function() {
                    this.classList.remove('ring-2', 'ring-primary-200');
                });
            });

            // Form submission handling
            const registerForm = document.getElementById('registerForm');
            const registerErrors = document.getElementById('registerErrors');
            const registerErrorMessage = document.getElementById('registerErrorMessage');
            
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Basic validation
                const phone = document.getElementById('register_phone').value;
                const name = document.getElementById('name').value;
                
                if (phone.length !== 11) {
                    showError('Please enter a valid 11-digit phone number');
                    return;
                }
                
                if (name.trim() === '') {
                    showError('Please enter your full name');
                    return;
                }
                
                // If validation passes, submit the form
                // In a real application, you would submit to the server
                // For demo purposes, we'll just show a success message
                registerErrors.classList.add('hidden');
                // alert('Registration successful! In a real application, this would submit to the server.');
                
                // Uncomment the line below to actually submit the form
                this.submit();
            });
            
            function showError(message) {
                registerErrorMessage.textContent = message;
                registerErrors.classList.remove('hidden');
            }
            
            // Login link functionality
            document.getElementById('loginLink').addEventListener('click', function(e) {
                e.preventDefault();
                switchToLogin();
            });
        });
        
        function switchToLogin() {
            // In a real application, this would redirect to the login page
            // For demo purposes, we'll just show an alert
            
            // Uncomment the line below to actually redirect
            window.location.href = "{{ route('login') }}";
        }
    </script>
</body>
</html>
>>>>>>> c356163 (video call ui setup)

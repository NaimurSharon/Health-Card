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
                    <i class="fas fa-graduation-cap text-2xl text-primary-500"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Student Portal</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Enter your phone number to access your account
                </p>
            </div>

            <!-- Login Card -->
            <div class="content-card rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-xl">
                <div class="table-header px-6 py-4 text-center">
                    <h3 class="text-xl font-bold text-white">Student Login</h3>
                </div>

                <div class="p-6 sm:p-8">
                    @if(session('status'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-2"></i>
                            <span class="text-sm text-green-700 dark:text-green-300">{{ session('status') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mb-4 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                            <span class="text-sm text-red-700 dark:text-red-300">
                                {{ $errors->first() }}
                            </span>
                        </div>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('auth.login.submit') }}" class="space-y-6">
                        @csrf

                        <!-- Phone Number -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Phone Number *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-phone text-gray-400"></i>
                                </div>
                                <input id="phone" 
                                    class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:text-white transition-all duration-200"
                                    type="tel" 
                                    name="phone" 
                                    value="{{ old('phone') }}" 
                                    required 
                                    autofocus 
                                    placeholder="018********"
                                    pattern="[0-9]{11}"
                                    maxlength="11"
                                >
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Enter your 11-digit phone number without country code
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full gradient-bg text-white py-3 px-4 rounded-lg font-semibold text-sm transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5 flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Access My Account
                        </button>
                    </form>

                    <!-- Help Section -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Need help accessing your account?
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
                    Are you a teacher or staff?
                    <a href="{{ route('auth.login') }}" 
                       class="font-medium text-primary-500 hover:text-primary-600 dark:text-primary-400 dark:hover:text-primary-300">
                        Use regular login
                    </a>
                </p>
            </div>
            <!-- Switch to Regular Login -->
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Create a New Account
                    <a href="{{ route('global.signup') }}" 
                       class="font-medium text-primary-500 hover:text-primary-600 dark:text-primary-400 dark:hover:text-primary-300">
                        Register
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Phone number formatting
            const phoneInput = document.getElementById('phone');
            
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
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-primary-200', 'rounded-lg');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-primary-200', 'rounded-lg');
                });
            });

            // Auto-format phone number on blur
            // phoneInput.addEventListener('blur', function(e) {
            //     let value = e.target.value.replace(/\D/g, '');
            //     if (value.length === 11) {
            //         e.target.value = value.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
            //     }
            // });
        });
    </script>
</body>
</html>
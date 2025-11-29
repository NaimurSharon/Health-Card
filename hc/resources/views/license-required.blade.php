<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="{{ setting('site_description', 'Centralized License Server') }}">
    <meta name="keywords" content="{{ setting('site_keywords') }}">
    <title>License Required | Support Center</title>
    <link rel="icon" href="{{ asset('public/storage/' . setting('site_favicon', '/images/favicon.ico')) }}" type="image/x-icon">

    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        }
        .license-card {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }
        .btn-contact {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        .btn-contact:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3), 0 4px 6px -2px rgba(59, 130, 246, 0.15);
        }
        .feature-icon {
            transition: all 0.3s ease;
        }
        .contact-card:hover .feature-icon {
            transform: scale(1.1);
            color: #3b82f6;
        }
        .animate-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-3xl w-full bg-white rounded-xl license-card overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 py-6 px-8 text-center">
            <div class="flex items-center justify-center">
                <div class="bg-white/20 p-3 rounded-full mr-4">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white tracking-tight">
                    Domain is not registered!
                </h1>
            </div>
            <p class="mt-2 text-blue-100 text-lg">Please register your domain to access this content</p>
        </div>
        
        <!-- Main Content -->
        <div class="p-8">
            <!-- Alert Section -->
            <!--<div class="flex items-start mb-8 bg-blue-50 rounded-xl p-5 border border-blue-100">-->
            <!--    <div class="bg-blue-100 p-3 rounded-full mr-5">-->
            <!--        <i class="fas fa-exclamation-circle text-blue-600 text-xl"></i>-->
            <!--    </div>-->
            <!--    <div>-->
            <!--        <h2 class="text-xl font-semibold text-gray-800">Access Restricted</h2>-->
            <!--        <p class="text-gray-600 mt-2">-->
            <!--            This domain (<span class="font-medium text-blue-600">{{ request('domain') ?? parse_url(url()->current(), PHP_URL_HOST) }}</span>) requires a valid license to access premium content and features.-->
            <!--        </p>-->
            <!--    </div>-->
            <!--</div>-->
            
            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="contact-card bg-gray-50 p-5 rounded-lg border border-gray-200 hover:border-blue-300 transition duration-300">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-tags feature-icon text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800">Flexible Pricing</h3>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Starting from $199 with customizable plans to fit your needs and budget.
                    </p>
                </div>
                
                <div class="contact-card bg-gray-50 p-5 rounded-lg border border-gray-200 hover:border-blue-300 transition duration-300">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-star feature-icon text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800">Premium Features</h3>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Full content access, priority support, and regular feature updates included.
                    </p>
                </div>
                
                <div class="contact-card bg-gray-50 p-5 rounded-lg border border-gray-200 hover:border-blue-300 transition duration-300">
                    <div class="flex items-center mb-3">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-clock feature-icon text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-800">Free Trial</h3>
                    </div>
                    <p class="text-gray-600 text-sm">
                        7-day trial available to evaluate before purchasing a license.
                    </p>
                </div>
            </div>
            
            <!-- Contact Section -->
            <div class="bg-blue-50 rounded-xl p-6 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-5 text-center">Get Immediate Assistance</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <a href="mailto:{{ setting('contact_email') }}" class="flex items-center p-4 bg-white rounded-lg border border-gray-200 hover:border-blue-400 hover:shadow-md transition duration-300">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-envelope text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Email Support</h4>
                            <p class="text-sm text-gray-600">{{ setting('contact_email') }}</p>
                        </div>
                        <div class="ml-auto text-blue-400">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <a href="tel:{{ preg_replace('/[^0-9]/', '', setting('phone_number')) }}" class="flex items-center p-4 bg-white rounded-lg border border-gray-200 hover:border-blue-400 hover:shadow-md transition duration-300">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-phone text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Call Us</h4>
                            <p class="text-sm text-gray-600">{{ setting('phone_number') }}</p>
                        </div>
                        <div class="ml-auto text-blue-400">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                    
                    <a href="{{ setting('app_url') }}" target="_blank" class="flex items-center p-4 bg-white rounded-lg border border-gray-200 hover:border-blue-400 hover:shadow-md transition duration-300 md:col-span-2">
                        <div class="bg-blue-100 p-3 rounded-full mr-4">
                            <i class="fas fa-globe text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">Visit Our Website</h4>
                            <p class="text-sm text-gray-600">{{ setting('app_url') }}</p>
                        </div>
                        <div class="ml-auto text-blue-400">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- Quick Action -->
            <div class="text-center">
                <a href="{{ route('license.register.form') }}?domain={{ request('domain') ?? urlencode(parse_url(url()->current(), PHP_URL_HOST)) }}" 
                   class="btn-contact inline-flex items-center px-8 py-3 text-white font-medium rounded-lg text-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                   <i class="fas fa-key mr-2"></i> Register Your Domain Now
                </a>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-4 text-center border-t border-gray-200">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} {{ setting('site_title') }}. All rights reserved. Unauthorized access prohibited.
            </p>
        </div>
    </div>
</body>
</html>
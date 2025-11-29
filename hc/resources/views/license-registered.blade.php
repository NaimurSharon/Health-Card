<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    
    <meta name="description" content="{{ setting('site_description', 'Centralized License Server') }}">
    <meta name="keywords" content="{{ setting('site_keywords') }}">
    
    <link rel="icon" href="{{ asset('public/storage/' . setting('site_favicon', '/images/favicon.ico')) }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        }
        .success-card {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .btn-success {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .btn-success:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3), 0 2px 4px -1px rgba(16, 185, 129, 0.1);
        }
        .animate-check {
            animation: checkScale 0.5s ease;
        }
        @keyframes checkScale {
            0% { transform: scale(0.8); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl success-card overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-500 py-5 px-6">
            <div class="flex items-center">
                <div class="bg-white/20 p-2 rounded-lg mr-3 animate-check">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">
                    Registration Successful
                </h1>
            </div>
            <p class="mt-1 text-green-100 text-sm">Your domain has been successfully registered</p>
        </div>
        
        <div class="p-6">
            <div class="flex items-start mb-6">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Domain Activated</h2>
                    <p class="text-gray-600 mt-1 text-sm">
                        Your license is now active for <span class="font-medium text-gray-800">{{ $domain }}</span>
                    </p>
                </div>
            </div>
            
            <div class="bg-gray-50 p-5 rounded-lg border border-gray-100 mb-6">
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-key text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">License Key</p>
                            <p class="text-sm font-mono text-gray-800">{{ $license_key }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-globe text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Registered Domain</p>
                            <p class="text-sm text-gray-800">{{ $domain }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <div class="bg-amber-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-clock text-amber-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500">Expiration Date</p>
                            <p class="text-sm text-gray-800">{{ $expires_at }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-5">
                <p class="text-sm text-gray-600 mb-5">
                    You can now access your website. If you encounter any issues, please contact our support team.
                </p>
                <div class="grid grid-cols-1 gap-3">
                    <a href="https://{{ $domain }}" 
                       class="btn-success text-white font-medium py-3 px-4 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                       target="_blank">
                       <i class="fas fa-external-link-alt mr-2"></i> Go to Website
                    </a>
                    <a href="mailto:{{setting('contact_email')}}" 
                       class="border border-gray-300 text-gray-700 font-medium py-3 px-4 rounded-lg text-center hover:bg-gray-50 transition duration-150">
                       <i class="fas fa-envelope mr-2"></i> Contact Support
                    </a>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 text-center border-t border-gray-200">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Registration</title>
    
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
        .card {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .input-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
        .btn-primary {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3), 0 2px 4px -1px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-xl card overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 py-5 px-6">
            <div class="flex items-center">
                <div class="bg-white/20 p-2 rounded-lg mr-3">
                    <i class="fas fa-key text-white text-xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">
                    Domain Registration
                </h1>
            </div>
            <p class="mt-1 text-blue-100 text-sm">Register your domain</p>
        </div>
        
        <div class="p-6">
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-lg animate-fade-in">
                    <div class="flex">
                        <div class="flex-shrink-0 text-red-400">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('license.register.process') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label for="domain" class="block text-sm font-medium text-gray-700 mb-1.5">Website Domain</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-globe text-gray-400"></i>
                        </div>
                        <input type="text" id="domain" name="domain" value="{{ old('domain', $domain ?? '') }}" 
                               class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none transition duration-150" 
                               required readonly>
                    </div>
                </div>
                
                <div>
                    <label for="license_key" class="block text-sm font-medium text-gray-700 mb-1.5">License Key</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-400"></i>
                        </div>
                        <input type="text" id="license_key" name="license_key" value="{{ old('license_key') }}" 
                               class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none transition duration-150" 
                               placeholder="XXXX-XXXX-XXXX-XXXX" required>
                    </div>
                </div>
                
                <div>
                    <label for="api_key" class="block text-sm font-medium text-gray-700 mb-1.5">API Key</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-code text-gray-400"></i>
                        </div>
                        <input type="text" id="api_key" name="api_key" value="{{ old('api_key') }}" 
                               class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none transition duration-150" 
                               placeholder="64-character API key" required>
                    </div>
                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <i class="fas fa-info-circle mr-1.5 text-blue-400"></i>
                        You can find this in your client dashboard
                    </p>
                </div>
                
                <div class="flex items-center justify-between pt-2">
                    <button type="submit" class="btn-primary text-white font-medium py-2.5 px-6 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-paper-plane mr-2"></i> Register Domain
                    </button>
                    <a href="{{ route('license.required') }}" class="text-sm text-blue-600 hover:text-blue-500 font-medium transition duration-150">
                        Need help? <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </form>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 text-center border-t border-gray-200">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} All rights reserved. Unauthorized access prohibited.
            </p>
        </div>
    </div>
</body>
</html>
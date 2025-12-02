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
    </style>
</head>
<body class="h-full bg-gray-50" x-data="navbarState()">
    <!-- Color Ball Background -->
    <div class="color-ball-bg"></div>

    <!-- Mobile menu backdrop -->
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false" 
         class="fixed inset-0 z-40 bg-black/50 lg:hidden" x-transition.opacity></div>
    
    <div class="flex flex-col h-full">
        
        @include('inc.navbar')

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

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    
    <script>
        function navbarState() {
            return {
                mobileMenuOpen: false,
                
                init() {
                    // Handle resize events
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

        document.addEventListener('alpine:init', () => {
            // Alpine is already initialized by the CDN script
        });
    </script>
    
    @stack('scripts')
</body>
</html>
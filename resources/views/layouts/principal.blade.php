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
    </style>
</head>
<body class="h-full bg-gray-50" x-data="sidebarState()">
    <!-- Color Ball Background -->
    <div class="color-ball-bg"></div>

    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-30 bg-black/50 lg:hidden" x-transition.opacity></div>
    
    <div class="flex h-full">

        <!-- Sidebar -->
        @auth
            @include('principal.inc.sidebar')
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
                        <div class="flex items-center space-x-3">
                            <button class="p-2 text-gray-600 hover:text-gray-900 transition-colors">
                                <i class="fas fa-bell"></i>
                            </button>
                            <button class="p-2 text-gray-600 hover:text-gray-900 transition-colors">
                                <i class="fas fa-envelope"></i>
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

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    
    <script>
        function sidebarState() {
            return {
                sidebarOpen: window.innerWidth >= 1024,
                
                init() {
                    // Close sidebar on mobile when clicking a link
                    this.handleResize();
                    window.addEventListener('resize', this.handleResize.bind(this));
                },
                
                handleResize() {
                    if (window.innerWidth >= 1024) {
                        this.sidebarOpen = true;
                    } else {
                        this.sidebarOpen = false;
                    }
                },
                
                handleMobileClick() {
                    if (window.innerWidth < 1024) {
                        this.sidebarOpen = false;
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
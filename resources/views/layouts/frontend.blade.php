<!DOCTYPE html>
<html lang="bn" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ setting('site_description', 'বাংলাদেশের শীর্ষ ই-পেপার') }}">
    <meta name="keywords" content="{{ setting('site_keywords', 'বাংলাদেশ, ই-পেপার, ডিজিটাল সংবাদপত্র') }}">
    
    <title>{{ $title ?? setting('site_title', 'বাংলাদেশ ই-পেপার | Digital Newspaper') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/storage/' . setting('site_favicon', '/images/favicon.ico'))}}" type="image/x-icon">

    <!-- Dark mode preload script -->
    <script>
        (function () {
            const savedMode = localStorage.getItem('darkMode');
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = savedMode ? savedMode === 'true' : systemDark;

            if (isDark) {
                document.documentElement.classList.add('dark');
                document.documentElement.style.colorScheme = 'dark';
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.style.colorScheme = 'light';
            }
            window.__initialDarkMode = isDark;
        })();
    </script>

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Noto Sans Bengali', 'sans-serif'],
                    },
                    colors: {
                        primary: '{{ setting("primary_color", "#f42a41") }}',
                    }
                }
            }
        };
    </script>

    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Custom styles -->
    <style>
        .logo-text span {
            color: {{ setting('primary_color', '#f42a41') }};
        }
        
        /* Toast notifications */
        .toast-container {
            position: fixed;
            z-index: 9999;
            top: 1rem;
            right: 1rem;
            width: 100%;
            max-width: 400px;
            pointer-events: none;
        }
        
        .toast {
            pointer-events: auto;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-between;
            animation: slideIn 0.3s ease-out forwards;
            transition: all 0.3s ease;
            background: white;
            color: #222;
            border-left: 4px solid;
        }
        
        .toast.dark {
            background: #1f2937;
            color: #f3f4f6;
        }
        
        .toast.success {
            border-left-color: #10b981;
        }
        
        .toast.error {
            border-left-color: #ef4444;
        }
        
        .toast.warning {
            border-left-color: #f59e0b;
        }
        
        .toast.info {
            border-left-color: #3b82f6;
        }
        
        .toast-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .toast-icon {
            font-size: 1.25rem;
        }
        
        .toast-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
            font-size: 1rem;
        }
        
        .toast-close:hover {
            opacity: 1;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }
        
        .toast.hide {
            animation: fadeOut 0.3s ease-out forwards;
        }
        
        @if(setting('custom_css'))
            {!! setting('custom_css') !!}
        @endif
    </style>

    @stack('styles')
</head>
<body class="h-full flex flex-col dark:bg-gray-900 text-[#222] dark:text-gray-200 transition-colors" 
      x-data="{
    darkMode: window.__initialDarkMode,
    toast: {
        toasts: [],
        show(message, type = 'info', duration = 5000) {
            const toast = {
                message,
                type,
                visible: true
            };
            
            this.toasts.push(toast);
            
            setTimeout(() => {
                const index = this.toasts.indexOf(toast);
                if (index >= 0) {
                    this.toasts.splice(index, 1);
                }
            }, duration);
        },
        removeToast(index) {
            if (index >= 0 && index < this.toasts.length) {
                this.toasts.splice(index, 1);
            }
        },
        getIcon(type) {
            const icons = {
                'success': '<i class="fas fa-check-circle"></i>',
                'error': '<i class="fas fa-exclamation-circle"></i>',
                'warning': '<i class="fas fa-exclamation-triangle"></i>',
                'info': '<i class="fas fa-info-circle"></i>'
            };
            return icons[type] || icons['info'];
        }
    },
    initTheme() {
        if (localStorage.getItem('darkMode') === null) {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                this.darkMode = e.matches;
                this.applyTheme();
            });
        }
    },
    applyTheme() {
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
            document.documentElement.style.colorScheme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.style.colorScheme = 'light';
        }
        localStorage.setItem('darkMode', this.darkMode);
    }
}" x-init="initTheme(); 
@if(session()->has('success')) toast.show('{{ session('success') }}', 'success'); @endif
@if(session()->has('error')) toast.show('{{ session('error') }}', 'error'); @endif
@if(session()->has('warning')) toast.show('{{ session('warning') }}', 'warning'); @endif
@if(session()->has('info')) toast.show('{{ session('info') }}', 'info'); @endif
@if($errors->any()) @foreach($errors->all() as $error) toast.show('{{ $error }}', 'error'); @endforeach @endif" 
@dark-mode-update.window="applyTheme()">

    <!-- Toast notifications container -->
    <div class="toast-container">
        <template x-for="(toast, index) in toast.toasts" :key="index">
            <div class="toast" 
                 :class="[toast.type, darkMode ? 'dark' : '']" 
                 x-show="toast.visible"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-full"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-full">
                <div class="toast-content">
                    <span class="toast-icon" x-html="toast.getIcon(toast.type)"></span>
                    <span x-text="toast.message"></span>
                </div>
                <button class="toast-close" @click="toast.removeToast(index)">&times;</button>
            </div>
        </template>
    </div>

    <!-- Navbar -->
    @include('frontend.partials.navbar')

    <!-- Main Content -->
    <main class="flex-grow container mx-auto py-6 px-4">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('frontend.partials.footer', [
        'footerText' => setting('footer_text', '© ' . date('Y') . ' বাংলাদেশ ই-পেপার. সকল স্বত্ব সংরক্ষিত।'),
        'socialLinks' => [
            'facebook' => setting('facebook_url'),
            'twitter' => setting('twitter_url'),
            'youtube' => setting('youtube_url')
        ]
    ])

    <!-- Optional Dark Mode Toggle Button -->
    @if(setting('dark_mode_toggle', true))
    <div class="fixed bottom-6 right-6 z-50">
        <button @click="darkMode = !darkMode; $dispatch('dark-mode-update')"
                class="w-16 h-16 flex items-center justify-center rounded-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
            <span x-show="!darkMode"><i class="fas fa-moon text-2xl text-gray-800"></i></span>
            <span x-show="darkMode"><i class="fas fa-sun text-2xl text-yellow-400"></i></span>
        </button>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    
    <!-- Toast notification global function -->
    <script>
        function showToast(message, type = 'info', duration = 5000) {
            const event = new CustomEvent('show-toast', {
                detail: { message, type, duration }
            });
            window.dispatchEvent(event);
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('show-toast', (e) => {
                const alpineComponent = document.querySelector('[x-data]').__x;
                if (alpineComponent && alpineComponent.$data.toast) {
                    alpineComponent.$data.toast.show(e.detail.message, e.detail.type, e.detail.duration);
                }
            });
        });
    </script>
    
    <!-- Custom JavaScript -->
    @if(setting('custom_js'))
        <script>
            {!! setting('custom_js') !!}
        </script>
    @endif
    
    @stack('scripts')
</body>
</html>
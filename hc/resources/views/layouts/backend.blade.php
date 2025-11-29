<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ setting('site_description', 'E-Paper Administration Panel') }}">
    
    <title>{{ setting('site_title', 'E-Paper Admin') }} | @yield('title')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('public/storage/' . setting('site_favicon', 'logo/amardesh-shadhinotar-kotha-bole.webp')) }}" type="image/webp">

    <!-- Critical theme script that runs before rendering -->
    <script>
        (function() {
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
    
    <!-- Modern Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        dark: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Inter Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom styles -->
    <style>
        [x-cloak] { display: none !important; }
        
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
        
        .dark .scrollbar::-webkit-scrollbar-track {
            background: #1e293b;
        }
        .dark .scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }
        .dark .scrollbar::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        
        * {
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }
        
        .no-transition * {
            transition: none !important;
        }
        
        input, select, textarea {
            padding: 0.7rem 1rem !important;
        }

        
        /* Admin header with site logo */
        .admin-brand {
            background-image: url('{{ asset('public/storage/' . setting("site_logo", "logo/amardesh-shadhinotar-kotha-bole.webp")) }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: left center;
            padding-left: 40px;
            min-height: 32px;
        }
    </style>
    
    @stack('styles')
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 font-sans antialiased text-gray-800 dark:text-gray-200 no-transition"
      x-data="{
          sidebarOpen: window.innerWidth >= 1024,
          darkMode: window.__initialDarkMode,
          initTheme() {
              setTimeout(() => {
                  document.body.classList.remove('no-transition');
              }, 50);
              
              if (localStorage.getItem('darkMode') === null) {
                  window.matchMedia('(prefers-color-scheme: dark)')
                      .addEventListener('change', e => {
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
      }"
      x-init="initTheme()"
      @dark-mode-update.window="applyTheme()">
    
    <!-- Mobile sidebar backdrop -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-20 bg-black/50 lg:hidden" x-transition.opacity></div>
    
    <div class="flex h-full overflow-hidden">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col border-r border-gray-200 bg-white shadow-xl transition-all duration-300 ease-in-out dark:border-gray-700 dark:bg-gray-800 lg:static"
               :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            @include('backend.inc.sidebar')
        </aside>

        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Header -->
            <header class="sticky top-0 z-20 border-b border-gray-200 bg-white/80 backdrop-blur dark:border-gray-700 dark:bg-gray-800/80">
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen" class="mr-4 lg:hidden">
                            <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
                        </button>
                    </div>
                    @include('backend.inc.navbar')
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto scrollbar p-6 bg-gray-50 dark:bg-gray-900">
                <!-- Content Header -->
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-semibold bg-red ml-4">@yield('title')</h2>
                    <div class="flex items-center space-x-4">
                        <!-- Theme Toggle -->
                        <button @click="darkMode = !darkMode; $dispatch('dark-mode-update')"
                                class="flex items-center w-full text-gray-600 dark:text-gray-300 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-gray-700 dark:hover:text-blue-400 p-2 rounded transition-colors duration-150">
                            
                            <svg x-show="darkMode" class="w-5 h-5 mr-2 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                
                            <svg x-show="!darkMode" class="w-5 h-5 mr-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                
                            <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                        </button>
                
                        @yield('actions')
                    </div>
                </div>

                <!-- Content -->
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Modern JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/@floating-ui/core@1.2.1"></script>
    <script src="https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.2.1"></script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.directive('tooltip', (el, { expression }, { evaluateLater, effect }) => {
                let cleanup = () => {};
                
                effect(() => {
                    const text = evaluateLater(expression);
                    text(value => {
                        cleanup();
                        
                        const tooltip = Object.assign(document.createElement('div'), {
                            className: 'absolute z-50 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm dark:bg-gray-700',
                            textContent: value,
                        });
                        
                        document.body.appendChild(tooltip);
                        
                        const { computePosition, autoUpdate } = FloatingUIDOM;
                        const updatePosition = () => {
                            computePosition(el, tooltip, {
                                placement: 'top',
                                middleware: [FloatingUIDOM.offset(5)]
                            }).then(({x, y}) => {
                                Object.assign(tooltip.style, {
                                    left: `${x}px`,
                                    top: `${y}px`,
                                });
                            });
                        };
                        
                        const show = () => {
                            tooltip.style.display = 'block';
                            updatePosition();
                        };
                        
                        const hide = () => {
                            tooltip.style.display = 'none';
                        };
                        
                        const cleanupAutoUpdate = autoUpdate(el, tooltip, updatePosition);
                        
                        el.addEventListener('mouseenter', show);
                        el.addEventListener('mouseleave', hide);
                        
                        cleanup = () => {
                            cleanupAutoUpdate(); 
                            el.removeEventListener('mouseenter', show);
                            el.removeEventListener('mouseleave', hide);
                            tooltip.remove();
                        };
                    });
                });
                
                el._x_cleanups.push(cleanup);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
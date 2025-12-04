<!-- Header with Navigation -->
<header class="header-blur sticky top-0 z-30">
    <div class="flex items-center justify-between px-6 py-3">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('teacher.dashboard') }}" class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Teacher Portal</h1>
                    <div class="flex items-center space-x-2">
                        <span class="teacher-badge text-xs">Educator</span>
                        <span class="text-xs text-gray-500">{{ Auth::user()->name ?? 'Teacher' }}</span>
                    </div>
                </div>
            </a>
        </div>
        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center space-x-1">
            <a href="{{ route('teacher.health-card.index') }}"
                class="nav-link {{ request()->routeIs('teacher.health-card.index') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt mr-2"></i> Health Report
            </a>
            <a href="{{ route('teacher.homework.index') }}"
                class="nav-link {{ request()->routeIs('teacher.homework.index') ? 'active' : '' }}">
                <i class="fas fa-list mr-2"></i> All Diary updates
            </a>

            <a href="{{ route('hello-doctor') }}"
                class="nav-link {{ request()->routeIs('hello-doctor') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt mr-2"></i> Hello Doctor
            </a>
            <!-- <a href="{{ route('teacher.assigned-classes') }}" 
                class="nav-link {{ request()->routeIs('teacher.assigned-classes') ? 'active' : '' }}">
                <i class="fas fa-users mr-2"></i> My Classes
            </a> -->
            <!-- <a class="nav-link">
                    <i class="fas fa-user-circle mr-2"></i> Profile
            </a> -->
            <!--<a href="{{ route('student.scholarship') }}" -->
            <!--   class="nav-link {{ Request::is('student/scholarship*') ? 'active' : '' }}">-->
            <!--    <i class="fas fa-graduation-cap me-2"></i>স্কলারশিপ-->
            <!--</a>-->
        </nav>

        <!-- Right side buttons -->
        <div class="flex items-center space-x-4">
            <!-- Mobile menu button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-gray-600 hover:text-gray-900">
                <i class="fas fa-bars"></i>
            </button>

            <div class="flex items-center space-x-3">
                @auth
                    <!-- User dropdown - Logged in -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-700 hidden md:block">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                        </button>

                        <div x-show="open" @click.outside="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <a href="{{ route('video-consultation.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user me-2"></i>My Appointments
                            </a>
                            <div class="border-t border-gray-200"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Login button - Not logged in -->
                    <a href="{{ route('login') }}"
                        class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-sign-in-alt"></i>
                        <span class="hidden md:inline">Login</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Page Title Bar -->
    <!--<div class="border-t border-gray-200/60">-->
    <!--    <div class="px-6 py-3">-->
    <!--        <div class="flex items-center justify-between">-->
    <!--            <div>-->
    <!--                <h2 class="text-lg font-semibold text-gray-900">@yield('title', 'Dashboard')</h2>-->
    <!--                <p class="text-sm text-gray-600">@yield('subtitle', 'Welcome to your student portal')</p>-->
    <!--            </div>-->
    <!--            <div class="flex items-center space-x-2 text-sm text-gray-500">-->
    <!--                <i class="fas fa-calendar"></i>-->
    <!--                <span>{{ now()->format('l, F j, Y') }}</span>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
</header>

<!-- Mobile Navigation Menu -->
<div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    class="lg:hidden fixed top-20 inset-x-4 z-40 mobile-menu rounded-lg shadow-xl" style="display: none;">
    <div class="p-4 space-y-2">
        <a href="{{ route('teacher.health-card.index') }}"
            class="nav-link {{ request()->routeIs('teacher.homework.index') ? 'active' : '' }}"
            @click="mobileMenuOpen = false">
            <i class="fas fa-tachometer-alt mr-2"></i> Health Report
        </a>
        <a href="{{ route('student.health-report') }}"
            class="nav-link block {{ Request::is('student/health-report*') ? 'active' : '' }}"
            @click="mobileMenuOpen = false">
            <i class="fas fa-list mr-2"></i> All Diary updates
        </a>
        <a href="{{ route('student.id-card') }}"
            class="nav-link {{ request()->routeIs('hello-doctor') ? 'active' : '' }}" @click="mobileMenuOpen = false">
            <i class="fas fa-tachometer-alt mr-2"></i> Hello Doctor
        </a>
    </div>
</div>
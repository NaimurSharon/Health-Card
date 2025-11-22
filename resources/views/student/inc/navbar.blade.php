@php
    $todaystip = \App\Models\HealthTip::where('status', 'published')->latest()->first();
@endphp

<!-- Header with Navigation -->
<header class="header-blur sticky top-0 z-30">
    <div class="flex items-center justify-between px-6 py-3">
        <div class="flex items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ Auth::user()?->school->logo ? asset('public/storage/' . Auth::user()->school->logo) : asset('public/storage/' . setting('site_favicon')) }}" 
                     alt="School Logo" class="w-8 h-8 rounded">
                <div>
                    <h1 class="text-xl font-bold tiro text-gray-900">{{ Auth::user()->school->name ?? setting('site_title') }}</h1>
                    <p class="text-sm text-gray-600">Student Portal</p>
                </div>
            </div>
        </div>
        
        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center tiro space-x-1">
            <a href="{{ route('home') }}" 
               class="nav-link {{ Request::is('student/dashboard') ? 'active' : '' }}">
                <i class="fas fa-home me-2"></i>হোম
            </a>
            <a href="{{ route('student.health-report') }}" 
               class="nav-link {{ Request::is('student/health-report*') ? 'active' : '' }}">
                <i class="fas fa-file-medical me-2"></i>স্বাস্থ্য রিপোর্ট
            </a>
            <a href="{{ route('student.id-card') }}" 
               class="nav-link {{ Request::is('student/id-card*') ? 'active' : '' }}">
                <i class="fas fa-id-card me-2"></i>আইডি কার্ড
            </a>
            <a href="{{ route('student.school-notices') }}" 
               class="nav-link {{ Request::is('student/school-notices*') ? 'active' : '' }}">
                <i class="fas fa-school me-2"></i>স্কুল নোটিশ
            </a>
            <a href="{{ route('student.city-notices') }}" 
               class="nav-link {{ Request::is('student/city-notices*') ? 'active' : '' }}">
                <i class="fas fa-city me-2"></i>সিটি নোটিশ
            </a>
            <a href="{{ route('student.school-diary') }}" 
               class="nav-link {{ Request::is('student/school-diary*') ? 'active' : '' }}">
                <i class="fas fa-book me-2"></i>স্কুল ডায়েরি
            </a>
            <a href="{{ route('student.hello-doctor') }}" 
               class="nav-link {{ Request::is('student/hello-doctor*') ? 'active' : '' }}">
                <i class="fas fa-user-md me-2"></i><span class='inter'>Hello</span> ডাক্তার
            </a>
            <!--<a href="{{ route('student.scholarship') }}" -->
            <!--   class="nav-link {{ Request::is('student/scholarship*') ? 'active' : '' }}">-->
            <!--    <i class="fas fa-graduation-cap me-2"></i>স্কলারশিপ-->
            <!--</a>-->
            <a href="{{ route('student.scholarship.register') }}" 
               class="nav-link {{ Request::is('student/scholarship*') ? 'active' : '' }}">
                <i class="fas fa-graduation-cap me-2"></i>রেজিস্টার
            </a>
            
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
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 hidden md:block">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </button>
                    
                    <div x-show="open" @click.outside="open = false" 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user me-2"></i>My Profile
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                        <div class="border-t border-gray-200"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
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

<!-- Health Tip Banner -->
@if($todaystip)
<div id="healthTipBanner" class="bg-yellow-400 text-black px-3 py-2 flex items-center justify-between gap-4 transition-all tiro duration-300">
    <div class="flex items-center gap-4 flex-1">
        <span class="bg-red-600 text-white px-2 py-2 tiro rounded-full text-sm whitespace-nowrap">আজকের স্বাস্থ্য পরামর্শ</span>
        <marquee behavior="scroll" direction="left" scrollamount="9" class="font-medium flex-1">
            🩺 {{ $todaystip->title }} — {{ Str::limit(strip_tags($todaystip->content), 120) }}
        </marquee>
    </div>
    <button onclick="dismissHealthTip()" class="text-gray-700 hover:text-gray-900 transition-colors duration-200 flex-shrink-0">
        <i class="fas fa-times text-lg"></i>
    </button>
</div>
@endif

<!-- Mobile Navigation Menu -->
<div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="lg:hidden fixed top-20 inset-x-4 z-40 mobile-menu rounded-lg shadow-xl" 
     style="display: none;">
    <div class="p-4 space-y-2 tiro">
        <a href="{{ route('student.dashboard') }}" 
           class="nav-link block {{ Request::is('student/dashboard') ? 'active' : '' }}"
           @click="mobileMenuOpen = false">
            <i class="fas fa-home me-3"></i>হোম
        </a>
        <a href="{{ route('student.health-report') }}" 
           class="nav-link block {{ Request::is('student/health-report*') ? 'active' : '' }}"
           @click="mobileMenuOpen = false">
            <i class="fas fa-file-medical me-3"></i>স্বাস্থ্য রিপোর্ট
        </a>
        <a href="{{ route('student.id-card') }}" 
           class="nav-link block {{ Request::is('student/id-card*') ? 'active' : '' }}"
           @click="mobileMenuOpen = false">
            <i class="fas fa-id-card me-3"></i>আইডি কার্ড
        </a>
        <a href="{{ route('student.school-notices') }}" 
           class="nav-link block {{ Request::is('student/school-notices*') ? 'active' : '' }}"
           @click="mobileMenuOpen = false">
            <i class="fas fa-school me-3"></i>স্কুল নোটিশ
        </a>
        <a href="{{ route('student.city-notices') }}" 
           class="nav-link block {{ Request::is('student/city-notices*') ? 'active' : '' }}"
           @click="mobileMenuOpen = false">
            <i class="fas fa-city me-3"></i>সিটি নোটিশ
        </a>
        <a href="{{ route('student.school-diary') }}" 
           class="nav-link block {{ Request::is('student/school-diary*') ? 'active' : '' }}"
           @click="mobileMenuOpen = false">
            <i class="fas fa-book me-3"></i>স্কুল ডায়েরি
        </a>
        <a href="{{ route('student.hello-doctor') }}" 
           class="nav-link block {{ Request::is('student/hello-doctor*') ? 'active' : '' }}"
           @click="mobileMenuOpen = false">
            <i class="fas fa-user-md me-3"></i><span class='inter'>Hello</span> ডাক্তার
        </a>
        <a href="{{ route('student.scholarship') }}" 
           class="nav-link block {{ Request::is('student/scholarship*') ? 'active' : '' }}"
           @click="mobileMenuOpen = false">
            <i class="fas fa-graduation-cap me-3"></i>স্কলারশিপ
        </a>
        <a href="{{ route('student.scholarship.register') }}"
           class="nav-link block {{ Request::is('student/scholarship*') ? 'active' : '' }}"
           @click="mobileMenuOpen = false">
            <i class="fas fa-graduation-cap me-3"></i>রেজিস্টার
        </a>
        
        
    </div>
</div>

<style>
#healthTipBanner {
    transition: all 0.5s ease-in-out;
    max-height: 80px; /* banner height */
    overflow: hidden;
}
#healthTipBanner.hide-banner {
    opacity: 0;
    max-height: 0;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}

</style>

<script>
// Health Tip Banner Dismissal Functionality
function dismissHealthTip() {
    const banner = document.getElementById('healthTipBanner');
    if (banner) {
        banner.classList.add('hide-banner');

        // Remove after animation ends (0.5s)
        setTimeout(() => banner.remove(), 500);
    }
}


// Optional: Auto-dismiss after 1 minute (60 seconds)
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const banner = document.getElementById('healthTipBanner');
        if (banner && !document.cookie.includes('healthTipDismissed')) {
            dismissHealthTip();
        }
    }, 60000); // 1 minute
});
</script>
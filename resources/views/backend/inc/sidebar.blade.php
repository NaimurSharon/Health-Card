<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-30 flex w-80 flex-col sidebar transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static"
       :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
    
    <!-- Sidebar Header -->
    <div class="flex h-20 items-center justify-center border-b border-gray-700">
        <img src="{{ asset('public/storage/' . setting('site_logo')) }}" alt="Site Logo" class="h-16 w-auto object-contain">
    </div>

    <!-- Sidebar Content -->
    <div class="flex-1 overflow-y-auto scrollbar py-4">
        <nav class="space-y-1 px-4 flex flex-col gap-3">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span>Dashboard</span>
            </a>

            <!-- Treatment Request -->
            <a href="{{ route('admin.treatment-requests.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.treatment-requests.*') ? 'active' : '' }}">
                <span>Treatment Request</span>
            </a>

            <!-- Health Tips -->
            <a href="{{ route('admin.health-tips.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.health-tips.*') ? 'active' : '' }}">
                <span>Health Tips</span>
            </a>

            <!-- Notices -->
            <div x-data="{ open: {{ request()->routeIs('admin.notices.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="sidebar-item flex items-center justify-between w-full px-3 py-2 text-lg font-medium rounded-lg 
                        {{ request()->routeIs('admin.notices.*') ? 'active' : '' }}">
                    <div class="flex items-center">
                        <span>Notices</span>
                    </div>
                    <!-- Arrow Icons -->
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" 
                         class="h-5 w-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" 
                         class="h-5 w-5 transition-transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            
                <div x-show="open" x-collapse class="ml-8 mt-1 space-y-3">
                    <a href="{{ route('admin.notices.diary') }}" 
                       class="sidebar-item flex items-center px-3 py-4 text-lg rounded-lg 
                       {{ request()->routeIs('admin.notices.diary') ? 'active' : '' }}">
                        <span>Diary</span>
                    </a>
                    <a href="{{ route('admin.city-corporation-notices.index') }}" 
                       class="sidebar-item flex items-center px-3 py-4 text-lg rounded-lg 
                       {{ request()->routeIs('admin.city-corporation-notices.index') ? 'active' : '' }}">
                        <span>City Corporation Notices</span>
                    </a>
                    <a href="{{ route('admin.notices.homepage') }}" 
                       class="sidebar-item flex items-center px-3 py-4 text-lg rounded-lg 
                       {{ request()->routeIs('admin.notices.homepage') ? 'active' : '' }}">
                        <span>Home Page</span>
                    </a>
                </div>
            </div>


            <!-- Schools -->
            <a href="{{ route('admin.schools.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.schools.*') ? 'active' : '' }}">
                <span>Schools</span>
            </a>

            <!-- Organizations -->
            <a href="{{ route('admin.organizations.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.organizations.*') ? 'active' : '' }}">
                <span>Organizations</span>
            </a>

            <!-- Individual Members -->
            <a href="{{ route('admin.individual-members.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.individual-members.*') ? 'active' : '' }}">
                <span>Individual Members</span>
            </a>

            <!-- Hospital Listing -->
            <a href="{{ route('admin.hospitals.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.hospitals.*') ? 'active' : '' }}">
                <span>Hospital Listing</span>
            </a>

            <!-- Doctor Listing -->
            <a href="{{ route('admin.doctors.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <span>Doctor Listing</span>
            </a>

            <!-- ID Card -->
            <a href="{{ route('admin.id-cards.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.id-cards.*') ? 'active' : '' }}">
                <span>ID Card</span>
            </a>

            <!-- Scholarship Exam -->
            <a href="{{ route('admin.exams.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.scholarship-exams.*') ? 'active' : '' }}">
                <span>Exam</span>
            </a>
            
            <!-- Scholarship Registrations -->
            <a href="{{ route('admin.scholarship.registrations') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.scholarship.registrations.*') ? 'active' : '' }}">
                <span>Registrations</span>
                @php
                    $pendingCount = \App\Models\ScholarshipRegistration::where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
            
            <!-- Users -->
            <a href="{{ route('admin.users.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span>Users</span>
            </a>

            <!-- Settings -->
            <div x-data="{ open: {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.health-report-fields.*') ? 'true' : 'false' }} }" 
                 class="mt-8 pt-4 border-t border-gray-700 flex flex-col gap-3">
            
                <button @click="open = !open" 
                        class="sidebar-item flex items-center justify-between w-full px-3 py-2 text-lg font-medium rounded-lg 
                        {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.health-report-fields.*') ? 'active' : '' }}">
                    <div class="flex items-center">
                        <span>Settings</span>
                    </div>
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            
                <div x-show="open" x-collapse class="ml-8 mt-1 space-y-3">
                    <a href="{{ route('admin.settings.index') }}" 
                       class="sidebar-item flex items-center px-3 py-2 text-lg rounded-lg 
                       {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                        <span>General Settings</span>
                    </a>
                    <a href="{{ route('admin.health-report-fields.manage') }}" 
                       class="sidebar-item flex items-center px-3 py-2 text-lg rounded-lg 
                       {{ request()->routeIs('admin.health-report-fields.manage') ? 'active' : '' }}">
                        <span>Manage Health Report Fields</span>
                    </a>
                </div>
            </div>

        </nav>
    </div>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-700 bg-gray-900/50">
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-400">v1.0.0</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center text-gray-400 hover:text-blue-400 transition-colors duration-150 sidebar-item px-3 py-2 rounded-lg">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
        <div class="mt-2 text-xs text-gray-500 text-center">
            © {{ date('Y') }} {{ setting('site_title', 'Accounting System') }}. All rights reserved.
        </div>
    </div>
</aside>

<style>
    .sidebar {
        background: #000 !important;
        color: #fff;
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        height: 100vh;
        padding-top: 1rem;
        display: flex;
        flex-direction: column;
    }
    
    /* Sidebar Header */
    .sidebar .text-xl {
        font-size: 1.6rem;
        font-weight: 600;
    }
    
    /* Sidebar Items */
    .sidebar-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
        padding: 0.75rem 1rem;
        color: #aaa;
        border-radius: 1rem;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid transparent;
    }
    
    /* Hover and Active States */
    .sidebar-item:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-item.active {
        background: linear-gradient(to right, #2b2b2b, #1a1a1a);
        color: #fff;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.15);
    }
    
    /* Sidebar Icons */
    .sidebar-icon {
        color: #bbb;
        transition: color 0.2s ease;
    }
    
    .sidebar-item:hover .sidebar-icon,
    .sidebar-item.active .sidebar-icon {
        color: #fff;
    }
    
    /* Section Divider */
    .sidebar .border-t {
        border-color: rgba(255, 255, 255, 0.08);
    }
    
    /* Submenu */
    [x-cloak] {
        display: none;
    }
    
    .sidebar [x-show="open"] a {
        background: none;
        color: #aaa;
        border: 1px solid transparent;
    }
    
    .sidebar [x-show="open"] a:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    /* Scrollbar Hidden */
    .scrollbar::-webkit-scrollbar {
        width: 0;
    }
    
    /* Smooth Sidebar Collapse */
    .sidebar button {
        border: none;
        background: transparent;
        color: inherit;
        cursor: pointer;
    }
    
    /* Rounded Corners on the Left */
    .sidebar-item:first-child {
        margin-top: 0.5rem;
    }
    
    /* Theme Toggle Specific Styles */
    .sidebar .border-gray-700 {
        border-color: rgba(255, 255, 255, 0.08);
    }
    
    /* Footer Specific Styles */
    .sidebar .bg-gray-900\/50 {
        background: rgba(0, 0, 0, 0.5);
    }
    
    /* Logout Button */
    .sidebar form button {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid transparent;
    }
    
    .sidebar form button:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    /* Ensure proper spacing */
    .sidebar > div:last-child {
        margin-top: auto;
    }
</style>
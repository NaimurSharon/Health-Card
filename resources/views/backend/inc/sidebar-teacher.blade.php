<aside class="fixed inset-y-0 left-0 z-30 flex w-80 flex-col sidebar transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static"
       :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
    
    <div class="flex h-20 items-center justify-center border-b border-gray-700">
        <img src="{{ asset('public/storage/' . setting('site_logo')) }}" alt="Site Logo" class="h-16 w-auto object-contain">
    </div>

    <div class="flex-1 overflow-y-auto scrollbar py-4">
        <nav class="space-y-1 px-4 flex flex-col gap-3">
            <a href="{{ route('teacher.dashboard') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt sidebar-icon w-6 text-center"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('teacher.routine.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('teacher.routine.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt sidebar-icon w-6 text-center"></i>
                <span>Class Routine</span>
            </a>

            <div x-data="{ open: {{ request()->routeIs('teacher.homework.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="sidebar-item flex items-center justify-between w-full px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('teacher.homework.*') ? 'active' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-tasks sidebar-icon w-6 text-center"></i>
                        <span>Homework</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" x-cloak class="ml-4 mt-2 space-y-2">
                    <a href="{{ route('teacher.homework.index') }}" 
                       class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('teacher.homework.index') ? 'active' : '' }}">
                        <i class="fas fa-list sidebar-icon w-4 text-center"></i>
                        <span>All Homeworks</span>
                    </a>
                    <a href="{{ route('teacher.homework.create') }}" 
                       class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('teacher.homework.create') ? 'active' : '' }}">
                        <i class="fas fa-plus sidebar-icon w-4 text-center"></i>
                        <span>Add Homework</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('teacher.assigned-classes') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('teacher.assigned-classes') ? 'active' : '' }}">
                <i class="fas fa-users sidebar-icon w-6 text-center"></i>
                <span>My Classes</span>
            </a>

            <div class="mt-8 pt-4 border-t border-gray-700 flex flex-col gap-3">
                <a href="#"
                   class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('teacher.profile') ? 'active' : '' }}">
                    <i class="fas fa-user sidebar-icon w-6 text-center"></i>
                    <span>Profile</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" 
                            class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg w-full text-left">
                        <i class="fas fa-sign-out-alt sidebar-icon w-6 text-center"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </div>
</aside>

<style>
    .sidebar {
        background: #000 !important;
        color: #fff;
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        height: 100vh;
        padding-top: 1rem;
    }
    
    .sidebar .text-xl {
        font-size: 1.6rem;
        font-weight: 600;
    }
    
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
    }
    
    .sidebar-item:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
    }
    
    .sidebar-item.active {
        background: linear-gradient(to right, #2b2b2b, #1a1a1a);
        color: #fff;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.1);
    }
    
    .sidebar-icon {
        color: #bbb;
        transition: color 0.2s ease;
    }
    
    .sidebar-item:hover .sidebar-icon,
    .sidebar-item.active .sidebar-icon {
        color: #fff;
    }
    
    .sidebar .border-t {
        border-color: rgba(255, 255, 255, 0.08);
    }
    
    [x-cloak] {
        display: none;
    }
    
    .sidebar [x-show="open"] a {
        background: none;
        color: #aaa;
    }
    
    .sidebar [x-show="open"] a:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
    }
    
    .scrollbar::-webkit-scrollbar {
        width: 0;
    }
    
    .sidebar button {
        border: none;
        background: transparent;
        color: inherit;
        cursor: pointer;
    }
</style>
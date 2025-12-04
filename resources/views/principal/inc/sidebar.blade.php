<aside
    class="fixed inset-y-0 left-0 z-30 flex w-80 flex-col sidebar transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static"
    :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

    <div class="flex h-20 items-center justify-center border-b border-gray-700">
        <img src="{{ asset('public/storage/' . setting('site_logo')) }}" alt="School Logo"
            class="h-16 w-auto object-contain">
        <div class="ml-3">
            <h2 class="text-lg font-bold {{ detectLanguageClass('HealthCard BD') }} text-white">HealthCard BD</h2>
            <p class="text-sm text-gray-300">Principal Portal</p>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto scrollbar py-4">
        <nav class="space-y-1 px-4 flex flex-col gap-3">
            <!-- Dashboard -->
            <a href="{{ route('principal.dashboard') }}"
                class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('principal.dashboard') ? 'active' : '' }}">
                <span>Dashboard</span>
            </a>

            <!-- Students Management -->
            <div x-data="{ open: {{ request()->routeIs('principal.students.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="sidebar-item flex items-center justify-between w-full px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('principal.students.*') ? 'active' : '' }}">
                    <span>Students</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>

                <div x-show="open" x-cloak class="ml-4 mt-2 space-y-2">
                    <a href="{{ route('principal.students.index') }}"
                        class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.students.index') ? 'active' : '' }}">
                        <span>All Students</span>
                    </a>
                    <a href="{{ route('principal.students.create') }}"
                        class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.students.create') ? 'active' : '' }}">
                        <span>Add Student</span>
                    </a>
                </div>
            </div>

            <!-- Teachers Management -->
            <div x-data="{ open: {{ request()->routeIs('principal.teachers.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="sidebar-item flex items-center justify-between w-full px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('principal.teachers.*') ? 'active' : '' }}">
                    <span>Teachers</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>

                <div x-show="open" x-cloak class="ml-4 mt-2 space-y-2">
                    <a href="{{ route('principal.teachers.index') }}"
                        class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.teachers.index') ? 'active' : '' }}">
                        <span>All Teachers</span>
                    </a>
                    <a href="{{ route('principal.teachers.create') }}"
                        class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.teachers.create') ? 'active' : '' }}">
                        <span>Add Teacher</span>
                    </a>
                </div>
            </div>

            <!-- Academic Management -->
            <div
                x-data="{ open: {{ request()->routeIs('principal.classes.*') || request()->routeIs('principal.sections.*') || request()->routeIs('principal.subjects.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="sidebar-item flex items-center justify-between w-full px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('principal.classes.*') || request()->routeIs('principal.sections.*') || request()->routeIs('principal.subjects.*') ? 'active' : '' }}">
                    <span>Academic</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>

                <div x-show="open" x-cloak class="ml-4 mt-2 space-y-2">
                    <a href="{{ route('principal.classes.index') }}"
                        class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.classes.*') ? 'active' : '' }}">
                        <span>Classes</span>
                    </a>
                    <a href="{{ route('principal.sections.index') }}"
                        class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.sections.*') ? 'active' : '' }}">
                        <span>Sections</span>
                    </a>
                    <a href="{{ route('principal.subjects.index') }}"
                        class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.subjects.*') ? 'active' : '' }}">
                        <span>Subjects</span>
                    </a>
                </div>
            </div>

            <!-- Routine Management -->
            <!-- <div x-data="{ open: {{ request()->routeIs('principal.routine.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="sidebar-item flex items-center justify-between w-full px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('principal.routine.*') ? 'active' : '' }}">
                    <span>Routine</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" x-cloak class="ml-4 mt-2 space-y-2">
                    <a href="{{ route('principal.routine.index') }}" 
                       class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.routine.index') ? 'active' : '' }}">
                        <span>Class Routine</span>
                    </a>
                    <a href="{{ route('principal.routine.weekly') }}" 
                       class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.routine.weekly') ? 'active' : '' }}">
                        <span>Weekly View</span>
                    </a>
                    <a href="{{ route('principal.routine.create') }}" 
                       class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.routine.create') ? 'active' : '' }}">
                        <span>Add Routine</span>
                    </a>
                </div>
            </div> -->

            <!-- Homework Management -->
            <div x-data="{ open: {{ request()->routeIs('principal.homework.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                    class="sidebar-item flex items-center justify-between w-full px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('principal.homework.*') ? 'active' : '' }}">
                    <span>Homework</span>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>

                <div x-show="open" x-cloak class="ml-4 mt-2 space-y-2">
                    <a href="{{ route('principal.homework.index') }}"
                        class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.homework.index') ? 'active' : '' }}">
                        <span>All Homeworks</span>
                    </a>
                    <a href="{{ route('principal.homework.create') }}"
                        class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.homework.create') ? 'active' : '' }}">
                        <span>Add Homework</span>
                    </a>
                </div>
            </div>

            <!-- Notices Management -->
            <a href="{{ route('principal.notices.index') }}"
                class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('principal.notices.*') ? 'active' : '' }}">
                <span>Notices</span>
            </a>

            <!-- Health Records -->
            <a href="{{ route('principal.health.reports.index') }}"
                class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('principal.health.*') ? 'active' : '' }}">
                <span>Health Records</span>
            </a>

            <!-- ID Cards -->
            <a href="{{ route('principal.id-cards.index') }}"
                class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('principal.id-cards.*') ? 'active' : '' }}">
                <span>ID Cards</span>
            </a>

            <!-- My Classes -->
            <!-- <a href="{{ route('principal.assigned-classes') }}"
                class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('principal.assigned-classes') ? 'active' : '' }}">
                <span>My Classes</span>
            </a> -->

            <!-- Profile & Settings -->
            <div class="mt-8 pt-4 border-t border-gray-700 flex flex-col gap-3">
                <div
                    x-data="{ open: {{ request()->routeIs('principal.profile.*') || request()->routeIs('principal.school.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="sidebar-item flex items-center justify-between w-full px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('principal.profile.*') || request()->routeIs('principal.school.*') ? 'active' : '' }}">
                        <span>Settings</span>
                        <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>

                    <div x-show="open" x-cloak class="ml-4 mt-2 space-y-2">
                        <a href="{{ route('principal.profile.index') }}"
                            class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.profile.index') ? 'active' : '' }}">
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('principal.school.edit') }}"
                            class="sidebar-item flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('principal.school.*') ? 'active' : '' }}">
                            <span>School Info</span>
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg w-full text-left">
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
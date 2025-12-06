<aside
    class="fixed inset-y-0 left-0 z-30 flex w-80 flex-col sidebar transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static"
    :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

    <div class="flex h-20 items-center justify-center border-b border-gray-700">
        <img src="{{ asset('public/storage/' . setting('site_logo')) }}" alt="Site Logo"
            class="h-16 w-auto object-contain">
    </div>

    <div class="flex-1 overflow-y-auto scrollbar py-4">
        <nav class="space-y-1 px-4 flex flex-col gap-3">
            <a href="{{ route('doctor.dashboard') }}"
                class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt sidebar-icon w-6 text-center"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('doctor.consultations.index') }}"
                class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check sidebar-icon w-6 text-center"></i>
                <span>Appointments</span>
            </a>

            <a href="{{ route('doctor.patients.index') }}"
                class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('doctor.patients.*') ? 'active' : '' }}">
                <i class="fas fa-user-injured sidebar-icon w-6 text-center"></i>
                <span>Patients</span>
            </a>

            <a href="{{ route('doctor.medical-records.index') }}"
                class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('doctor.medical-records.*') ? 'active' : '' }}">
                <i class="fas fa-file-medical sidebar-icon w-6 text-center"></i>
                <span>Medical Records</span>
            </a>

            <!-- <a href="{{ route('doctor.treatment-requests.index') }}" 
               class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('doctor.treatment-requests.*') ? 'active' : '' }}">
                <i class="fas fa-stethoscope sidebar-icon w-6 text-center"></i>
                <span>Treatment Requests</span>
            </a> -->

            <!-- <a href="{{ route('doctor.health-cards.index') }}"
                class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('doctor.health-cards.*') ? 'active' : '' }}">
                <i class="fas fa-id-card sidebar-icon w-6 text-center"></i>
                <span>Health Cards</span>
            </a> -->

            <div class="mt-8 pt-4 border-t border-gray-700 flex flex-col gap-3">
                <a href="{{ route('doctor.profile.edit', auth()->user()) }}"
                    class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('doctor.profile') ? 'active' : '' }}">
                    <i class="fas fa-user sidebar-icon w-6 text-center"></i>
                    <span>Profile</span>
                </a>

                <a href="{{ route('doctor.availability.index', auth()->user()) }}"
                    class="sidebar-item flex items-center px-3 py-2 text-lg font-medium rounded-lg {{ request()->routeIs('doctor.availability.index') ? 'active' : '' }}">
                    <i class="fas fa-clock sidebar-icon w-6 text-center"></i>
                    <span>My Availability</span>
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
    }

    /* Hover and Active States */
    .sidebar-item:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
    }

    .sidebar-item.active {
        background: linear-gradient(to right, #2b2b2b, #1a1a1a);
        color: #fff;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.1);
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
    }

    .sidebar [x-show="open"] a:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
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
</style>
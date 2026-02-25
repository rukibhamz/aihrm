@php
    $activeMenu = '';
    if ((request()->routeIs('my-payslips.*') || request()->routeIs('announcements.*') || request()->routeIs('documents.*') || request()->routeIs('attendance.*') || request()->routeIs('leaves.*') || request()->routeIs('finance.*')) && !request()->routeIs('leaves.approvals') && !request()->routeIs('finance.approvals')) {
        $activeMenu = 'workspace';
    } elseif (request()->routeIs('employees.*') || request()->routeIs('admin.applications.*') || request()->routeIs('admin.departments.*') || request()->routeIs('admin.designations.*') || request()->routeIs('admin.grade-levels.*') || request()->routeIs('jobs.*')) {
        $activeMenu = 'people';
    } elseif (request()->routeIs('performance.goals.*') || request()->routeIs('performance.reviews.*') || request()->routeIs('lms.*')) {
        $activeMenu = 'performance';
    } elseif (request()->routeIs('admin.ai.*') || request()->routeIs('admin.assets.*') || request()->routeIs('admin.resignations.*') || request()->routeIs('admin.documents.*') || request()->routeIs('admin.audit-logs.*') || request()->routeIs('settings.*') || request()->routeIs('admin.leaves.*') || request()->routeIs('admin.attendance.*')) {
        $activeMenu = 'admin';
    } elseif (request()->routeIs('admin.salary.*') || request()->routeIs('admin.payroll.*') || request()->routeIs('admin.bonuses.*') || request()->routeIs('admin.loans.*') || request()->routeIs('admin.advances.*') || request()->routeIs('admin.payroll-reports.*')) {
        $activeMenu = 'finance';
    }
@endphp
<!-- Logo -->
<div class="flex items-center justify-start px-6 h-16 bg-neutral-950 border-b border-neutral-800 transition-all duration-300" :class="sidebarCollapsed ? 'px-4' : 'px-6'">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        <div class="w-8 h-8 bg-white rounded-md flex-shrink-0 flex items-center justify-center">
            <span class="text-neutral-900 font-bold text-sm">AI</span>
        </div>
        <span x-show="!sidebarCollapsed" x-cloak class="font-bold text-lg tracking-tight text-white whitespace-nowrap">AIHRM</span>
    </a>
</div>

<!-- Navigation -->
<div class="flex-1 flex flex-col overflow-y-auto pt-5 pb-4">
    <nav x-data="{ activeMenu: '{{ $activeMenu }}' }" class="mt-5 flex-1 px-2 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'sidebar-nav-link-active' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white sidebar-nav-link-hover' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
            <svg class="flex-shrink-0 h-6 w-6 {{ request()->routeIs('dashboard') ? '' : 'text-neutral-400 group-hover:text-white' }}" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span x-show="!sidebarCollapsed" x-cloak>Dashboard</span>
        </a>

        <!-- Self-Service -->
        <div class="space-y-1">
            <button @click="activeMenu = activeMenu === 'workspace' ? null : 'workspace'" 
                    :class="{ 'sidebar-nav-parent-active': activeMenu === 'workspace', 'justify-center': sidebarCollapsed }"
                    class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none transition-colors duration-200">
                <svg class="flex-shrink-0 h-6 w-6 text-neutral-400 group-hover:text-white" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span x-show="!sidebarCollapsed" x-cloak class="flex-1 text-left">My Workspace</span>
                <svg x-show="!sidebarCollapsed" x-cloak :class="{ 'rotate-90': activeMenu === 'workspace' }" class="ml-3 flex-shrink-0 h-4 w-4 transform transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="activeMenu === 'workspace'" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="space-y-1 mt-1">
                <a href="{{ route('my-payslips.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('my-payslips.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>My Payslips</span>
                </a>
                <a href="{{ route('announcements.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('announcements.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Announcements</span>
                </a>
                <a href="{{ route('documents.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('documents.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>My Documents</span>
                </a>
                <a href="{{ route('attendance.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('attendance.index') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Attendance</span>
                </a>
                <a href="{{ route('attendance.qr') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('attendance.qr') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Clock-in QR</span>
                </a>
                <a href="{{ route('leaves.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('leaves.index') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Leaves</span>
                </a>
                <a href="{{ route('leaves.relief-requests') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('leaves.relief-requests') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Relief Requests</span>
                </a>
                <a href="{{ route('finance.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('finance.index') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Claims</span>
                </a>
            </div>
        </div>

        <!-- People & Culture -->
        @role('Admin|HR')
        <div class="space-y-1">
            <button @click="activeMenu = activeMenu === 'people' ? null : 'people'" 
                    :class="{ 'sidebar-nav-parent-active': activeMenu === 'people', 'justify-center': sidebarCollapsed }"
                    class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none transition-colors duration-200">
                <svg class="flex-shrink-0 h-6 w-6 text-neutral-400 group-hover:text-white" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span x-show="!sidebarCollapsed" x-cloak class="flex-1 text-left">People & Culture</span>
                <svg x-show="!sidebarCollapsed" x-cloak :class="{ 'rotate-90': activeMenu === 'people' }" class="ml-3 flex-shrink-0 h-4 w-4 transform transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="activeMenu === 'people'" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="space-y-1 mt-1">
                @can('view employees')
                <a href="{{ route('employees.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('employees.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Employees</span>
                </a>
                @endcan
                @can('view employees')
                <a href="{{ route('jobs.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('jobs.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Jobs</span>
                </a>
                @endcan
                <a href="{{ route('admin.applications.kanban') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.applications.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Recruitment</span>
                </a>
                <a href="{{ route('admin.departments.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.departments.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Departments</span>
                </a>
                <a href="{{ route('admin.designations.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.designations.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Designations</span>
                </a>
                <a href="{{ route('admin.grade-levels.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.grade-levels.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Grade Levels</span>
                </a>
            </div>
        </div>
        @endrole


        <!-- Performance & Learning -->
        <div class="space-y-1">
            <button @click="activeMenu = activeMenu === 'performance' ? null : 'performance'" 
                    :class="{ 'sidebar-nav-parent-active': activeMenu === 'performance', 'justify-center': sidebarCollapsed }"
                    class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none transition-colors duration-200">
                <svg class="flex-shrink-0 h-6 w-6 text-neutral-400 group-hover:text-white" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span x-show="!sidebarCollapsed" x-cloak class="flex-1 text-left">Performance & Learning</span>
                <svg x-show="!sidebarCollapsed" x-cloak :class="{ 'rotate-90': activeMenu === 'performance' }" class="ml-3 flex-shrink-0 h-4 w-4 transform transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="activeMenu === 'performance'" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="space-y-1 mt-1">
                <a href="{{ route('performance.goals.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('performance.goals.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Goals</span>
                </a>
                <a href="{{ route('performance.reviews.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('performance.reviews.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Reviews</span>
                </a>
                <a href="{{ route('lms.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('lms.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Courses</span>
                </a>
            </div>
        </div>

        <!-- Administrative Tools -->
        @role('Admin')
        <div class="space-y-1">
            <button @click="activeMenu = activeMenu === 'admin' ? null : 'admin'" 
                    :class="{ 'sidebar-nav-parent-active': activeMenu === 'admin', 'justify-center': sidebarCollapsed }"
                    class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none transition-colors duration-200">
                <svg class="flex-shrink-0 h-6 w-6 text-neutral-400 group-hover:text-white" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span x-show="!sidebarCollapsed" x-cloak class="flex-1 text-left">Administrative Tools</span>
                <svg x-show="!sidebarCollapsed" x-cloak :class="{ 'rotate-90': activeMenu === 'admin' }" class="ml-3 flex-shrink-0 h-4 w-4 transform transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="activeMenu === 'admin'" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="space-y-1 mt-1">
                <a href="{{ route('admin.ai.compliance') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.ai.compliance') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Compliance Audit</span>
                </a>
                <a href="{{ route('admin.ai.performance') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.ai.performance') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>AI Performance</span>
                </a>
                <a href="{{ route('admin.assets.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.assets.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Asset Management</span>
                </a>
                <a href="{{ route('admin.leaves.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.leaves.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Leave Admin</span>
                </a>
                <a href="{{ route('admin.leave-types.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.leave-types.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Leave Types</span>
                </a>
                <a href="{{ route('admin.leave-balances.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.leave-balances.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Leave Balances</span>
                </a>
                <a href="{{ route('admin.attendance.scanner') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.attendance.scanner') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Gatekeeper Scanner</span>
                </a>
                <a href="{{ route('admin.resignations.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.resignations.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Resignations</span>
                </a>
                <a href="{{ route('admin.documents.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.documents.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Document Center</span>
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.audit-logs.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Audit Logs</span>
                </a>
                <a href="{{ route('settings.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('settings.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>System Settings</span>
                </a>
            </div>
        </div>
        @endrole

        <!-- Finance & Payroll -->
        <div class="space-y-1">
            <button @click="activeMenu = activeMenu === 'finance' ? null : 'finance'" 
                    :class="{ 'sidebar-nav-parent-active': activeMenu === 'finance', 'justify-center': sidebarCollapsed }"
                    class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none transition-colors duration-200">
                <svg class="flex-shrink-0 h-6 w-6 text-neutral-400 group-hover:text-white" :class="sidebarCollapsed ? '' : 'mr-3'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-show="!sidebarCollapsed" x-cloak class="flex-1 text-left">Finance & Payroll</span>
                <svg x-show="!sidebarCollapsed" x-cloak :class="{ 'rotate-90': activeMenu === 'finance' }" class="ml-3 flex-shrink-0 h-4 w-4 transform transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="activeMenu === 'finance'" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="space-y-1 mt-1">
                <a href="{{ route('admin.salary.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.salary.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Salary Structures</span>
                </a>
                <a href="{{ route('admin.payroll.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.payroll.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Payroll Process</span>
                </a>
                <a href="{{ route('admin.bonuses.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.bonuses.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Bonuses</span>
                </a>
                <a href="{{ route('admin.loans.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.loans.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Loans</span>
                </a>
                <a href="{{ route('admin.advances.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.advances.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Advances</span>
                </a>
                <a href="{{ route('admin.payroll-reports.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.payroll-reports.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover hover:bg-neutral-800' }}" :class="sidebarCollapsed ? 'justify-center' : ''">
                    <div class="w-6 flex justify-center" :class="sidebarCollapsed ? '' : 'mr-3'"><svg class="h-4 w-4 opacity-75 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    <span x-show="!sidebarCollapsed" x-cloak>Payroll Reports</span>
                </a>
            </div>
        </div>
    </nav>
</div>

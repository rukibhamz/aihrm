<!-- Logo -->
<div class="flex items-center justify-center h-16 bg-neutral-950 border-b border-neutral-800">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
        <div class="w-8 h-8 bg-white rounded-md flex items-center justify-center">
            <span class="text-neutral-900 font-bold text-sm">AI</span>
        </div>
        <span class="font-bold text-lg tracking-tight text-white">AIHRM</span>
    </a>
</div>

<!-- Navigation -->
<div class="flex-1 flex flex-col overflow-y-auto pt-5 pb-4">
    <nav class="mt-5 flex-1 px-2 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'sidebar-nav-link-active' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white sidebar-nav-link-hover' }}">
            <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('dashboard') ? '' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <!-- Self-Service -->
        <div x-data="{ open: {{ (request()->routeIs('my-payslips.*') || request()->routeIs('announcements.*') || request()->routeIs('documents.*') || request()->routeIs('attendance.*') || request()->routeIs('leaves.*') || request()->routeIs('finance.*')) && !request()->routeIs('leaves.approvals') && !request()->routeIs('finance.approvals') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" 
                    :class="{ 'sidebar-nav-parent-active': open }"
                    class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none transition-colors duration-200">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 text-neutral-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="flex-1 text-left">My Workspace</span>
                <svg :class="{ 'rotate-90': open }" class="ml-3 flex-shrink-0 h-4 w-4 transform transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="pl-9 space-y-1">
                <a href="{{ route('my-payslips.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('my-payslips.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> My Payslips
                </a>
                <a href="{{ route('announcements.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('announcements.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Announcements
                </a>
                <a href="{{ route('documents.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('documents.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> My Documents
                </a>
                <a href="{{ route('attendance.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('attendance.index') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Attendance
                </a>
                <a href="{{ route('attendance.qr') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('attendance.qr') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Clock-in QR
                </a>
                <a href="{{ route('leaves.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('leaves.index') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Leaves
                </a>
                <a href="{{ route('finance.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('finance.index') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Claims
                </a>
            </div>
        </div>

        <!-- People & Culture -->
        @role('Admin|HR')
        <div x-data="{ open: {{ (request()->routeIs('employees.*') || request()->routeIs('admin.applications.*') || request()->routeIs('admin.departments.*') || request()->routeIs('admin.designations.*') || request()->routeIs('admin.grade-levels.*') || request()->routeIs('jobs.*')) ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" 
                    :class="{ 'sidebar-nav-parent-active': open }"
                    class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none transition-colors duration-200">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 text-neutral-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="flex-1 text-left">People & Culture</span>
                <svg :class="{ 'rotate-90': open }" class="ml-3 flex-shrink-0 h-4 w-4 transform transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="pl-9 space-y-1">
                @can('view employees')
                <a href="{{ route('employees.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('employees.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Employees
                </a>
                @endcan
                @can('view employees')
                <a href="{{ route('jobs.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('jobs.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Jobs
                </a>
                @endcan
                <a href="{{ route('admin.applications.kanban') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.applications.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Recruitment
                </a>
                <a href="{{ route('admin.departments.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.departments.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Departments
                </a>
                <a href="{{ route('admin.designations.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.designations.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Designations
                </a>
                <a href="{{ route('admin.grade-levels.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.grade-levels.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Grade Levels
                </a>
            </div>
        </div>
        @endrole


        <!-- Performance & Learning -->
        <div x-data="{ open: {{ (request()->routeIs('performance.goals.*') || request()->routeIs('performance.reviews.*') || request()->routeIs('lms.*')) ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" 
                    :class="{ 'sidebar-nav-parent-active': open }"
                    class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none transition-colors duration-200">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 text-neutral-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span class="flex-1 text-left">Performance & Learning</span>
                <svg :class="{ 'rotate-90': open }" class="ml-3 flex-shrink-0 h-4 w-4 transform transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="pl-9 space-y-1">
                <a href="{{ route('performance.goals.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('performance.goals.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Goals
                </a>
                <a href="{{ route('performance.reviews.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('performance.reviews.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Reviews
                </a>
                <a href="{{ route('lms.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('lms.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Courses
                </a>
            </div>
        </div>

        <!-- Administrative Tools -->
        @role('Admin')
        <div x-data="{ open: {{ (request()->routeIs('admin.ai.*') || request()->routeIs('admin.assets.*') || request()->routeIs('admin.resignations.*') || request()->routeIs('admin.documents.*') || request()->routeIs('admin.audit-logs.*') || request()->routeIs('settings.*') || request()->routeIs('admin.leaves.*') || request()->routeIs('admin.attendance.*')) ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" 
                    :class="{ 'sidebar-nav-parent-active': open }"
                    class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none transition-colors duration-200">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 text-neutral-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="flex-1 text-left">Administrative Tools</span>
                <svg :class="{ 'rotate-90': open }" class="ml-3 flex-shrink-0 h-4 w-4 transform transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="pl-9 space-y-1">
                <a href="{{ route('admin.ai.compliance') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.ai.compliance') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Compliance Audit
                </a>
                <a href="{{ route('admin.ai.performance') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.ai.performance') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> AI Performance
                </a>
                <a href="{{ route('admin.assets.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.assets.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Asset Management
                </a>
                <a href="{{ route('admin.leaves.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.leaves.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Leave Admin
                </a>
                <a href="{{ route('admin.leave-types.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.leave-types.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Leave Types
                </a>
                <a href="{{ route('admin.leave-balances.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.leave-balances.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Leave Balances
                </a>
                <a href="{{ route('admin.attendance.scanner') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.attendance.scanner') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Gatekeeper Scanner
                </a>
                <a href="{{ route('admin.resignations.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.resignations.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Resignations
                </a>
                <a href="{{ route('admin.documents.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.documents.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Document Center
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.audit-logs.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Audit Logs
                </a>
                <a href="{{ route('settings.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('settings.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> System Settings
                </a>
            </div>
        </div>
        @endrole

        <!-- Finance & Payroll -->
        <div x-data="{ open: {{ (request()->routeIs('admin.salary.*') || request()->routeIs('admin.payroll.*') || request()->routeIs('admin.bonuses.*') || request()->routeIs('admin.loans.*') || request()->routeIs('admin.advances.*') || request()->routeIs('admin.payroll-reports.*')) ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" 
                    :class="{ 'sidebar-nav-parent-active': open }"
                    class="group w-full flex items-center px-2 py-2 text-sm font-medium rounded-md text-neutral-300 hover:bg-neutral-800 hover:text-white focus:outline-none transition-colors duration-200">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 text-neutral-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="flex-1 text-left">Finance & Payroll</span>
                <svg :class="{ 'rotate-90': open }" class="ml-3 flex-shrink-0 h-4 w-4 transform transition-transform duration-150" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="pl-9 space-y-1">
                <a href="{{ route('admin.salary.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.salary.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Salary Structures
                </a>
                <a href="{{ route('admin.payroll.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.payroll.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Payroll Process
                </a>
                <a href="{{ route('admin.bonuses.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.bonuses.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Bonuses
                </a>
                <a href="{{ route('admin.loans.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.loans.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Loans
                </a>
                <a href="{{ route('admin.advances.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.advances.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Advances
                </a>
                <a href="{{ route('admin.payroll-reports.index') }}" class="group flex items-center py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.payroll-reports.*') ? 'sidebar-sub-link-active' : 'text-neutral-400 hover:text-white sidebar-nav-link-hover' }}">
                    <span class="mr-2 opacity-50 text-xs">·</span> Payroll Reports
                </a>
            </div>
        </div>
    </nav>
</div>

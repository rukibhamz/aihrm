<div class="hidden md:flex md:flex-col md:w-64 md:fixed md:inset-y-0 bg-neutral-900 text-white">
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
            <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('my-payslips.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('my-payslips.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('my-payslips.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                My Payslips
            </a>

            <a href="{{ route('announcements.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('announcements.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('announcements.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.5 1.5 0 002 1.342l7.279-4.853A1.5 1.5 0 0022 14.24V9.76a1.5 1.5 0 00-.721-1.342L13 3.542a1.5 1.5 0 00-2 1.34zM3 12h.01"/>
                </svg>
                Announcements
            </a>

            <a href="{{ route('documents.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('documents.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('documents.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                My Documents
            </a>

            <!-- People -->
            <div class="pt-4 pb-1">
                <p class="px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">People</p>
            </div>
            @can('view employees')
            <a href="{{ route('employees.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('employees.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('employees.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Employees
            </a>
            @endcan
            <a href="{{ route('attendance.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('attendance.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('attendance.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Attendance
            </a>
            
            @role('Admin|HR')
            <a href="{{ route('admin.applications.kanban') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.applications.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.applications.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Recruitment
            </a>
            @endrole

            <a href="{{ route('attendance.qr') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('attendance.qr') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('attendance.qr') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Clock-in QR
            </a>
            <a href="{{ route('leaves.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('leaves.*') && !request()->routeIs('leaves.approvals') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('leaves.*') && !request()->routeIs('leaves.approvals') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Leaves
            </a>

            <!-- Finance -->
            <div class="pt-4 pb-1">
                <p class="px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Finance</p>
            </div>
            <a href="{{ route('finance.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('finance.*') && !request()->routeIs('finance.approvals') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('finance.*') && !request()->routeIs('finance.approvals') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Claims
            </a>
            <!-- Performance -->
            <div class="pt-4 pb-1">
                <p class="px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Performance</p>
            </div>
            <a href="{{ route('performance.goals.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('performance.goals.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('performance.goals.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Goals
            </a>
            <a href="{{ route('performance.reviews.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('performance.reviews.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('performance.reviews.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Reviews
            </a>

            <!-- Recruitment -->
            @can('view employees')
            <div class="pt-4 pb-1">
                <p class="px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Recruitment</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('jobs.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('jobs.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Jobs
            </a>
            @endcan

            <!-- Learning -->
            <div class="pt-4 pb-1">
                <p class="px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Learning</p>
            </div>
            <a href="{{ route('lms.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('lms.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('lms.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Courses
            </a>

            <!-- Admin -->
            @role('Admin')
            <div class="pt-4 pb-1">
                <p class="px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">AI Insights</p>
            </div>
            <a href="{{ route('admin.ai.compliance') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.ai.compliance') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.ai.compliance') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Compliance Audit
            </a>
            <a href="{{ route('admin.ai.performance') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.ai.performance') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.ai.performance') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Performance Insights
            </a>

            <div class="pt-4 pb-1">
                <p class="px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Admin</p>
            </div>
            <a href="{{ route('admin.assets.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.assets.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.assets.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
                Assets
            </a>
            <a href="{{ route('admin.resignations.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.resignations.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.resignations.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Resignations
            </a>
            <a href="{{ route('admin.documents.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.documents.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.documents.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                </svg>
                Document Center
            </a>
            <a href="{{ route('admin.audit-logs.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.audit-logs.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.audit-logs.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Audit Logs
            </a>
            <a href="{{ route('settings.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('settings.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('settings.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </a>
            <a href="{{ route('admin.departments.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.departments.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.departments.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Departments
            </a>
            <a href="{{ route('admin.designations.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.designations.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.designations.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                </svg>
                Designations
            </a>
            <a href="{{ route('admin.grade-levels.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.grade-levels.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.grade-levels.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Grade Levels
            </a>
            
            <div class="pt-4 pb-1">
                <p class="px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Leave Management</p>
            </div>
            <a href="{{ route('admin.leaves.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.leaves.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.leaves.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Overview
            </a>
            <a href="{{ route('admin.attendance.scanner') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.attendance.scanner') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.attendance.scanner') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Gatekeeper Scanner
            </a>
            <a href="{{ route('admin.leave-types.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.leave-types.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.leave-types.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Leave Types
            </a>
            <a href="{{ route('admin.leave-balances.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.leave-balances.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.leave-balances.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                </svg>
                Balances
            </a>
            
            <div class="pt-4 pb-1">
                <p class="px-2 text-xs font-semibold text-neutral-500 uppercase tracking-wider">Payroll</p>
            </div>
            <a href="{{ route('admin.salary.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.salary.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.salary.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Salary Structures
            </a>
            <a href="{{ route('admin.payroll.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.payroll.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.payroll.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Payroll
            </a>
            <a href="{{ route('admin.bonuses.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.bonuses.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.bonuses.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                </svg>
                Bonuses
            </a>
            <a href="{{ route('admin.loans.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.loans.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.loans.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Loans
            </a>
            <a href="{{ route('admin.advances.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.advances.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.advances.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Advances
            </a>
            <a href="{{ route('admin.payroll-reports.index') }}" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.payroll-reports.*') ? 'bg-neutral-800 text-white' : 'text-neutral-300 hover:bg-neutral-800 hover:text-white' }}">
                <svg class="mr-3 flex-shrink-0 h-6 w-6 {{ request()->routeIs('admin.payroll-reports.*') ? 'text-white' : 'text-neutral-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Reports
            </a>
            @endrole
        </nav>
    </div>
    
    <!-- User Profile (Bottom) -->
    <div class="flex-shrink-0 flex border-t border-neutral-800 p-4">
        <a href="{{ route('profile.edit') }}" class="flex-shrink-0 w-full group block">
            <div class="flex items-center">
                <div class="inline-block h-9 w-9 rounded-full bg-neutral-700 flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()?->name ?? 'U', 0, 2) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white group-hover:text-neutral-300">
                        {{ Auth::user()?->name ?? 'Guest' }}
                    </p>
                    <p class="text-xs font-medium text-neutral-400 group-hover:text-neutral-300">
                        View Profile
                    </p>
                </div>
            </div>
        </a>
    </div>
</div>

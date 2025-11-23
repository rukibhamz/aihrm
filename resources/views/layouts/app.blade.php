<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AIHRM') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * { font-family: 'Inter', sans-serif; }
        .nav-link { 
            position: relative;
            transition: all 0.2s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 0;
            height: 2px;
            background: #000;
            transition: width 0.2s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        .btn-primary {
            background: #000;
            color: #fff;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 600;
            font-size: 0.875rem;
            letter-spacing: -0.01em;
            transition: all 0.15s ease;
            border: 1px solid #000;
        }
        .btn-primary:hover {
            background: #1a1a1a;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-secondary {
            background: #fff;
            color: #000;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 600;
            font-size: 0.875rem;
            letter-spacing: -0.01em;
            transition: all 0.15s ease;
            border: 1px solid #e5e5e5;
        }
        .btn-secondary:hover {
            border-color: #000;
            transform: translateY(-1px);
        }
        .card {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }
        .card:hover {
            border-color: #d4d4d4;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        }
    </style>
</head>
<body class="bg-neutral-50 antialiased">
    <div class="min-h-screen flex flex-col">
        <!-- Top Navigation -->
        <nav class="bg-white border-b border-neutral-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center gap-8">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-black rounded-md flex items-center justify-center">
                                <span class="text-white font-bold text-sm">AI</span>
                            </div>
                            <span class="font-bold text-lg tracking-tight">AIHRM</span>
                        </a>

                        <!-- Main Navigation -->
                        <div class="hidden md:flex items-center gap-1">
                            <a href="{{ route('dashboard') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : 'text-neutral-600 hover:text-black' }}">
                                Dashboard
                            </a>
                            @can('view employees')
                            <a href="{{ route('employees.index') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('employees.*') ? 'active' : 'text-neutral-600 hover:text-black' }}">
                                Employees
                            </a>
                            @endcan
                            <a href="{{ route('leaves.index') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('leaves.*') && !request()->routeIs('leaves.approvals') ? 'active' : 'text-neutral-600 hover:text-black' }}">
                                Leave
                            </a>
                            <a href="{{ route('finance.index') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('finance.*') && !request()->routeIs('finance.approvals') ? 'active' : 'text-neutral-600 hover:text-black' }}">
                                Finance
                            </a>
                            @can('view employees')
                            <a href="{{ route('jobs.index') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('jobs.*') ? 'active' : 'text-neutral-600 hover:text-black' }}">
                                Recruitment
                            </a>
                            @endcan
                            @can('approve leave')
                            <a href="{{ route('leaves.approvals') }}" class="nav-link px-3 py-2 text-sm font-medium {{ request()->routeIs('*.approvals') ? 'active' : 'text-neutral-600 hover:text-black' }}">
                                Approvals
                            </a>
                            @endcan
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center gap-4">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-neutral-50 transition">
                                <div class="w-8 h-8 bg-neutral-900 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs font-semibold">{{ substr(Auth::user()->name ?? 'U', 0, 2) }}</span>
                                </div>
                                <span class="text-sm font-medium hidden md:block">{{ Auth::user()->name ?? 'User' }}</span>
                                <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-neutral-200 py-1" style="display: none;">
                                <div class="px-4 py-3 border-b border-neutral-100">
                                    <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-neutral-500">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('chatbot.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                    AI Assistant
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">Profile</a>
                                @role('Admin')
                                <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Settings
                                </a>
                                <div class="border-t border-neutral-100 my-1"></div>
                                <a href="{{ route('admin.departments.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    Departments
                                </a>
                                <a href="{{ route('admin.designations.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                    Designations
                                </a>
                                <div class="border-t border-neutral-100 my-1"></div>
                                <a href="{{ route('admin.salary.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Salary Structures
                                </a>
                                <a href="{{ route('admin.payroll.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                    Payroll Runs
                                </a>
                                @endrole
                                <a href="{{ route('payslips.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    My Payslips
                                </a>
                                <div class="border-t border-neutral-100 my-1"></div>
                                <a href="{{ route('performance.goals.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    My Goals (OKRs)
                                </a>
                                <a href="{{ route('performance.reviews.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Performance Reviews
                                </a>
                                <a href="{{ route('lms.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    Learning Center
                                </a>
                                @role('Admin')
                                <div class="border-t border-neutral-100 my-1"></div>
                                <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    Whistleblowing Reports
                                </a>
                                <a href="{{ route('admin.onboarding.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    Task Templates
                                </a>
                                @endrole
                                <a href="{{ route('onboarding.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    My Checklist
                                </a>
                                <div class="border-t border-neutral-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 py-8">
            <div class="max-w-7xl mx-auto px-6">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-neutral-200 py-6 mt-auto">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-center text-sm text-neutral-500">
                    <p>&copy; {{ date('Y') }} AIHRM. All rights reserved.</p>
                    <div class="flex gap-6">
                        <a href="#" class="hover:text-black transition">Privacy</a>
                        <a href="#" class="hover:text-black transition">Terms</a>
                        <a href="#" class="hover:text-black transition">Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

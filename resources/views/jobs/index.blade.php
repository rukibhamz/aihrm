<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers | AIHRM - Find your next career move</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; }
        .form-checkbox {
            appearance: none;
            background-color: #fff;
            margin: 0;
            font: inherit;
            color: currentColor;
            width: 1.15em;
            height: 1.15em;
            border: 1px solid #d1d5db;
            border-radius: 0.25em;
            display: grid;
            place-content: center;
        }
        .form-checkbox::before {
            content: "";
            width: 0.65em;
            height: 0.65em;
            transform: scale(0);
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em white;
            background-color: transform;
            transform-origin: center;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
        }
        .form-checkbox:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .form-checkbox:checked::before {
            transform: scale(1);
        }
    </style>
</head>
<body class="text-gray-900 flex flex-col min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900">AIHRM</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('login') }}" class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">Log In</a>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-gray-600 hover:text-gray-900 focus:outline-none p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="menu-icon-bars" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-100 absolute w-full z-50 shadow-xl">
        <div class="px-6 py-5 space-y-2 flex flex-col">
            <a href="{{ route('login') }}" class="w-full py-3 bg-blue-600 text-white text-center font-semibold rounded-lg hover:bg-blue-700 mt-4">Log In</a>
        </div>
    </div>

    <main class="flex-1 max-w-7xl mx-auto px-6 py-12 w-full">
        <!-- Hero Title -->
        <div class="mb-10 max-w-2xl">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">Find your next career move</h1>
            <p class="text-lg text-gray-600 leading-relaxed">
                Join the fastest-growing HR management platform. Browse through our open roles in Engineering, Design, Sales, and Marketing.
            </p>
        </div>

        <!-- Search Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2 mb-10 flex flex-col md:flex-row gap-2">
            <form action="{{ route('jobs.index') }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-2 w-full">
                <div class="relative flex-1 flex items-center bg-gray-50 rounded-lg px-4 border border-gray-100">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Job title, keywords, or company..." class="w-full bg-transparent border-none focus:ring-0 text-sm py-3 text-gray-900 placeholder-gray-400">
                </div>
                
                <div class="relative w-full md:w-64 flex items-center bg-gray-50 rounded-lg border border-gray-100 group">
                    <select name="location" class="w-full bg-transparent border-none focus:ring-0 text-sm py-3 px-4 text-gray-700 appearance-none cursor-pointer">
                        <option value="">Location</option>
                        <option value="Remote" {{ request('location') == 'Remote' ? 'selected' : '' }}>Remote</option>
                        <option value="San Francisco, CA" {{ request('location') == 'San Francisco, CA' ? 'selected' : '' }}>San Francisco, CA</option>
                        <option value="London, UK" {{ request('location') == 'London, UK' ? 'selected' : '' }}>London, UK</option>
                        <option value="Berlin, DE" {{ request('location') == 'Berlin, DE' ? 'selected' : '' }}>Berlin, DE</option>
                    </select>
                    <svg class="w-4 h-4 text-gray-400 absolute right-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>

                <div class="relative w-full md:w-64 flex items-center bg-gray-50 rounded-lg border border-gray-100 group">
                    <select name="type" class="w-full bg-transparent border-none focus:ring-0 text-sm py-3 px-4 text-gray-700 appearance-none cursor-pointer">
                        <option value="">Employment Type</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Contract">Contract</option>
                        <option value="Part-time">Part-time</option>
                    </select>
                    <svg class="w-4 h-4 text-gray-400 absolute right-4 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Search
                </button>
            </form>
        </div>

        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Sidebar -->
            <aside class="w-full lg:w-64 shrink-0">
                <div class="mb-8">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Categories</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 bg-blue-50 text-blue-700 rounded-lg font-medium text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                Engineering
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg font-medium text-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Human Resources
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg font-medium text-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                Marketing
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-100 rounded-lg font-medium text-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Sales & Finance
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Salary Range</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3">
                            <input type="checkbox" id="salary1" class="form-checkbox">
                            <label for="salary1" class="text-sm text-gray-600 cursor-pointer select-none">$50k - $80k</label>
                        </li>
                        <li class="flex items-center gap-3">
                            <input type="checkbox" id="salary2" class="form-checkbox" checked>
                            <label for="salary2" class="text-sm text-gray-600 cursor-pointer select-none">$80k - $120k</label>
                        </li>
                        <li class="flex items-center gap-3">
                            <input type="checkbox" id="salary3" class="form-checkbox">
                            <label for="salary3" class="text-sm text-gray-600 cursor-pointer select-none">$120k - $180k</label>
                        </li>
                        <li class="flex items-center gap-3">
                            <input type="checkbox" id="salary4" class="form-checkbox">
                            <label for="salary4" class="text-sm text-gray-600 cursor-pointer select-none">$180k+</label>
                        </li>
                    </ul>
                </div>

                <div class="bg-indigo-50/50 rounded-xl p-6 border border-indigo-100/50">
                    <h4 class="font-bold text-blue-700 text-sm mb-2">Job Alerts</h4>
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">Get notified immediately when new jobs matching your profile are posted.</p>
                    <button class="w-full bg-white text-blue-600 font-semibold text-sm py-2 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shadow-sm">
                        Set Alert
                    </button>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        {{ $jobs->total() }} open positions <span class="text-gray-400 font-normal text-sm">• {{ request('department') ?: 'Engineering' }}</span>
                    </h2>
                    <div class="flex items-center gap-2 text-sm text-gray-500 cursor-pointer hover:text-gray-700">
                        Sort by: <span class="font-bold text-gray-900">Newest</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($jobs as $job)
                        <div class="bg-white border border-gray-200 rounded-xl p-6 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                                <div class="flex gap-5">
                                    <div class="w-12 h-12 shrink-0 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center border border-blue-100">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $job->title }}</h3>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs font-medium text-gray-600 mb-3">
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                {{ $job->department ?? 'General' }}
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                {{ $job->location ?? 'Remote' }}
                                            </div>
                                            <div class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-[10px] font-bold uppercase tracking-wider">
                                                Remote
                                            </div>
                                            <div class="flex items-center gap-1.5 text-gray-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Full-time
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500 leading-relaxed max-w-2xl line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit($job->description, 150) }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-start md:items-end w-full md:w-auto shrink-0 gap-3 mt-4 md:mt-0">
                                    <span class="text-xs text-gray-400 font-medium whitespace-nowrap">Posted {{ $job->created_at->diffForHumans() }}</span>
                                    <a href="{{ route('jobs.show', $job) }}" class="px-5 py-2 bg-blue-50 text-blue-700 text-sm font-semibold rounded-lg hover:bg-blue-100 transition-colors w-full md:w-auto text-center">
                                        Apply Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-gray-200 rounded-xl p-12 text-center text-gray-500">
                            No open positions found.
                        </div>
                    @endforelse
                </div>

                <div class="mt-10 flex justify-center">
                    {{ $jobs->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white py-12 px-6 border-t border-gray-100 mt-auto">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-12">
            <div class="flex flex-col gap-4 max-w-sm">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-blue-600 rounded flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900 tracking-tight">AIHRM</span>
                </div>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Building the future of human resource management with powerful, integrated tools for modern businesses.
                </p>
            </div>
            
            <div class="grid grid-cols-2 gap-8 md:gap-24">
                <div>
                    <h4 class="font-bold text-gray-900 text-sm mb-4">Company</h4>
                    <ul class="space-y-3 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-blue-600">About Us</a></li>
                        <li><a href="#" class="hover:text-blue-600">Careers</a></li>
                        <li><a href="#" class="hover:text-blue-600">Press</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 text-sm mb-4">Support</h4>
                    <ul class="space-y-3 text-sm text-gray-500">
                        <li><a href="#" class="hover:text-blue-600">Help Center</a></li>
                        <li><a href="#" class="hover:text-blue-600">Contact</a></li>
                        <li><a href="#" class="hover:text-blue-600">Privacy</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} AIHRM Inc. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="#" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0z"/></svg>
                </a>
            </div>
        </div>
    </footer>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Mobile Menu Logic
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIconBars = document.getElementById('menu-icon-bars');
        const menuIconClose = document.getElementById('menu-icon-close');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                menuIconBars.classList.toggle('hidden');
                menuIconClose.classList.toggle('hidden');
            });
            
            // Close menu on link click
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    mobileMenu.classList.add('hidden');
                    menuIconBars.classList.remove('hidden');
                    menuIconClose.classList.add('hidden');
                });
            });
        }
    });
</script>
</html>

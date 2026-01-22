<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Careers | AIHRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-black text-white">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-black font-bold text-sm">AI</span>
                    </div>
                    <span class="font-bold text-xl">AIHRM</span>
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-white text-black rounded-lg font-semibold hover:bg-gray-100 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2 bg-white text-black rounded-lg font-semibold hover:bg-gray-100 transition">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-black text-white py-20">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h1 class="text-5xl md:text-6xl font-extrabold mb-6">Join Our Team</h1>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto mb-8">
                Explore open positions and find your next opportunity at AIHRM. We're building the future of workforce intelligence.
            </p>
            <div class="flex items-center justify-center gap-3 text-gray-400">
                <span class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    {{ $jobs->total() }} open positions
                </span>
            </div>
        </div>
    </div>

    <!-- Search Filter Section -->
    <div class="max-w-5xl mx-auto px-6 -mt-10 relative z-10 mb-16">
        <form action="{{ route('jobs.index') }}" method="GET" class="bg-white p-8 rounded-2xl border border-gray-200 shadow-xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Keyword</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search jobs..." 
                           class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-black focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Department</label>
                    <select name="department" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-black">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Location</label>
                    <select name="location" class="w-full border-gray-300 rounded-lg py-3 px-4 focus:ring-2 focus:ring-black">
                        <option value="">All Locations</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full py-4 bg-black text-white rounded-xl font-bold text-lg hover:bg-gray-800 transition">
                Search Jobs
            </button>
        </form>
    </div>

    <!-- Open Positions Header -->
    <div class="max-w-6xl mx-auto px-6 mb-10 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">Open Positions</h2>
        @if(request('search') || request('department') || request('location'))
            <a href="{{ route('jobs.index') }}" class="text-sm text-gray-500 hover:text-black transition">Clear all filters</a>
        @endif
    </div>

    <!-- Job Cards Grid -->
    <div class="max-w-6xl mx-auto px-6 pb-20 flex-1">
        @if($jobs->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($jobs as $job)
                    <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm hover:shadow-lg hover:border-gray-300 transition group">
                        <div class="flex items-start justify-between mb-6">
                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center group-hover:bg-black transition">
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @if($job->created_at->diffInDays() < 7)
                                <span class="px-3 py-1.5 bg-green-100 text-green-700 text-xs font-bold rounded-lg">New</span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $job->title }}</h3>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                {{ $job->department }}
                            </div>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $job->location }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                            <span class="text-xs text-gray-400">{{ $job->created_at->diffForHumans() }}</span>
                            <a href="{{ route('jobs.show', $job) }}" class="text-sm font-bold text-black hover:text-gray-600 flex items-center gap-1 transition">
                                View Details
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-16">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="bg-white p-16 rounded-2xl border border-gray-200 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No positions found</h3>
                <p class="text-gray-500 mb-6">Try adjusting your search or filter criteria.</p>
                <a href="{{ route('jobs.index') }}" class="inline-block px-6 py-3 bg-black text-white rounded-lg font-bold hover:bg-gray-800 transition">View all positions</a>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-8 text-center">
            <p class="text-sm text-gray-500">© {{ date('Y') }} AIHRM. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

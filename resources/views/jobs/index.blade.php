<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Careers | AIHRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
        .hero-section {
            background: #000000;
            color: #ffffff;
            padding-top: 120px;
            padding-bottom: 100px;
            text-align: center;
        }
        .main-title {
            font-size: 4rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 2rem;
        }
        .open-positions-title {
            font-size: 3.5rem;
            font-weight: 900;
            text-align: center;
            margin-top: 4rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .title-underline {
            width: 100px;
            height: 8px;
            background: #000000;
            margin: 0 auto 3rem auto;
            border-radius: 4px;
        }
        .filter-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 40px;
            max-width: 1000px;
            margin: -60px auto 80px auto;
            position: relative;
            z-index: 20;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-black text-white sticky top-0 z-50 border-b border-gray-800" style="padding: 1.25rem 0;">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <a href="/" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center">
                    <span class="text-black font-black text-base">AI</span>
                </div>
                <span class="font-bold text-2xl tracking-tighter">AIHRM</span>
            </a>
            <div class="flex items-center gap-8">
                <a href="{{ route('jobs.index') }}" class="text-sm font-semibold hover:text-gray-400 transition">Browse Job Board</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-white text-black rounded-xl font-black text-sm hover:bg-gray-100 transition shadow-xl">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-black rounded-xl font-black text-sm hover:bg-gray-100 transition shadow-xl">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="max-w-4xl mx-auto px-6">
            <h1 class="main-title">Join Our Team</h1>
            <p class="text-gray-400 text-xl font-medium leading-relaxed mb-8">
                We're building the future of workforce intelligence. Explore open opportunities and help us redefine the HR experience.
            </p>
            <div class="flex items-center justify-center gap-6">
                <div class="flex items-center gap-3 px-5 py-2.5 bg-white/10 rounded-full border border-white/20">
                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse shadow-[0_0_12px_rgba(34,197,94,0.8)]"></span>
                    <span class="text-sm font-black text-gray-200 tracking-wide">{{ $jobs->total() }} Roles Currently Open</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="px-6">
        <form action="{{ route('jobs.index') }}" method="GET" class="filter-card">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Job Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Title, keyword, etc..." 
                           class="w-full border-gray-200 bg-gray-50 rounded-xl py-4 px-5 focus:ring-0 focus:border-black transition font-semibold text-gray-900 shadow-sm">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Organization</label>
                    <select name="department" class="w-full border-gray-200 bg-gray-50 rounded-xl py-4 px-5 focus:ring-0 focus:border-black transition font-semibold text-gray-900 shadow-sm">
                        <option value="">All Categories</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Presence</label>
                    <select name="location" class="w-full border-gray-200 bg-gray-50 rounded-xl py-4 px-5 focus:ring-0 focus:border-black transition font-semibold text-gray-900 shadow-sm">
                        <option value="">All Locations</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="w-full py-5 bg-black text-white rounded-2xl font-black text-2xl hover:bg-gray-900 transition-all shadow-2xl active:scale-[0.98] tracking-tight">
                Search Opportunities
            </button>
        </form>
    </div>

    <!-- Open Positions Header -->
    <div class="max-w-6xl mx-auto px-6 flex flex-col items-center">
        <h2 class="open-positions-title">Open Positions</h2>
        <div class="title-underline"></div>
        @if(request('search') || request('department') || request('location'))
            <p class="mb-12 text-gray-500 font-semibold text-lg">
                Filtering by your criteria. <a href="{{ route('jobs.index') }}" class="text-black underline font-black ml-2 hover:text-gray-600 transition">Reset Search</a>
            </p>
        @endif
    </div>

    <!-- Job Cards Grid -->
    <div class="max-w-7xl mx-auto px-6 pb-40 flex-1">
        @if($jobs->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                @foreach ($jobs as $job)
                    <div class="bg-white p-12 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-3xl hover:-translate-y-2 transition-all duration-500 group flex flex-col h-full bg-gradient-to-br from-white to-gray-50/30">
                        <div class="flex items-start justify-between mb-10">
                            <div class="w-16 h-16 bg-gray-50 group-hover:bg-black rounded-2xl flex items-center justify-center transition-all duration-500 shadow-inner group-hover:rotate-6">
                                <svg class="w-8 h-8 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @if($job->created_at->diffInDays() < 7)
                                <span class="px-5 py-2 bg-green-50 text-green-700 text-[10px] font-black rounded-full uppercase tracking-[0.2em] border border-green-100 shadow-sm">Hot New</span>
                            @endif
                        </div>
                        
                        <h3 class="text-3xl font-black text-gray-900 mb-6 leading-tight group-hover:text-black tracking-tighter">{{ $job->title }}</h3>
                        
                        <div class="space-y-4 mb-12 flex-1">
                            <div class="flex items-center text-sm font-black text-gray-500 uppercase tracking-widest">
                                <span class="bg-gray-100/80 backdrop-blur-sm px-4 py-1.5 rounded-lg border border-gray-200/50">{{ $job->department }}</span>
                                <span class="mx-3 opacity-20">/</span>
                                <span class="flex items-center text-gray-400">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $job->location }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-10 border-t border-gray-100 mt-auto">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $job->created_at->diffForHumans() }}</span>
                            <a href="{{ route('jobs.show', $job) }}" class="flex items-center gap-3 group/btn">
                                <span class="text-sm font-black text-black group-hover/btn:tracking-widest transition-all duration-300">View Detail</span>
                                <svg class="w-6 h-6 text-black group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-24">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="bg-white p-24 rounded-[4rem] border border-gray-100 text-center shadow-2xl">
                <div class="w-28 h-28 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-10 shadow-inner">
                    <svg class="w-14 h-14 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-4xl font-black text-gray-900 mb-6 tracking-tighter">No openings matches your search</h3>
                <p class="text-gray-400 font-semibold mb-12 text-xl">We couldn't find any roles matching your current filters. Try resetting the criteria.</p>
                <a href="{{ route('jobs.index') }}" class="inline-block px-12 py-5 bg-black text-white rounded-[2rem] font-black text-xl hover:bg-gray-900 transition shadow-2xl transform hover:scale-105 active:scale-95">Refresh Listing</a>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 mt-auto py-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-4 mb-10">
                <div class="w-10 h-10 bg-black rounded-xl flex items-center justify-center shadow-lg transform -rotate-6">
                    <span class="text-white font-black text-sm">AI</span>
                </div>
                <span class="font-black text-gray-900 tracking-tighter text-3xl">AIHRM</span>
            </div>
            <p class="text-xs text-gray-400 font-black uppercase tracking-[0.4em]">© {{ date('Y') }} Enterprise Intelligence Layer.</p>
        </div>
    </footer>
</body>
</html>

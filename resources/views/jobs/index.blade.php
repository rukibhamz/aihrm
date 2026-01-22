<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Careers | AIHRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-section {
            background-color: #4f46e5;
            background-image: radial-gradient(circle at top right, #6366f1 0%, #4338ca 100%);
            position: relative;
            overflow: hidden;
            padding-top: 100px;
            padding-bottom: 150px;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -10%;
            width: 120%;
            height: 200px;
            background: #f9fafb;
            border-radius: 50% 50% 0 0;
            z-index: 1;
        }
        .nav-link { color: rgba(255,255,255,0.8); font-weight: 500; font-size: 0.875rem; transition: color 0.2s; }
        .nav-link:hover { color: #fff; }
        .search-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 50px -10px rgba(0,0,0,0.08);
            position: relative;
            z-index: 10;
            margin-top: -80px;
            padding: 35px;
            border: 1px solid #f3f4f6;
        }
        .category-card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            transition: all 0.3s;
            border: 1px solid #f3f4f6;
            cursor: pointer;
        }
        .category-card:hover { border-color: #4f46e5; box-shadow: 0 20px 40px -10px rgba(79,70,229,0.1); transform: translateY(-5px); }
        .count-badge { background: #eef2ff; color: #4f46e5; padding: 4px 12px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; }
        .primary-btn { background: #4f46e5; padding: 12px 28px; border-radius: 8px; font-weight: 600; transition: all 0.2s; box-shadow: 0 4px 14px 0 rgba(79,70,229,0.3); }
        .primary-btn:hover { background: #4338ca; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,0.4); }
        .nav-primary-btn { background: #ffffff; color: #4f46e5; padding: 8px 20px; border-radius: 8px; font-weight: 700; transition: all 0.2s; font-size: 0.875rem; }
        .nav-primary-btn:hover { background: #f3f4f6; transform: scale(1.02); }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Platform Consistent Header -->
    <header class="absolute top-0 left-0 w-full z-50">
        <nav class="max-w-7xl mx-auto flex items-center justify-between px-6 py-5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-lg transform -rotate-3 hover:rotate-0 transition-transform">
                    <span class="text-black font-black text-sm">AI</span>
                </div>
                <div class="text-white">
                    <div class="font-bold text-xl tracking-tight leading-none">AIHRM</div>
                    <div class="text-[10px] uppercase font-black tracking-widest opacity-70 mt-0.5">Careers Portal</div>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-8">
                <a href="/" class="nav-link">Home</a>
                <a href="{{ route('jobs.index') }}" class="nav-link">Browse Job</a>
                <a href="#" class="nav-link">Contact</a>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Log in</a>
                @endauth
                <a href="#" class="nav-primary-btn">Join Our Team</a>
            </div>
        </nav>
    </header>

    <!-- Brand Hero -->
    <section class="hero-section text-white flex items-center">
        <div class="max-w-7xl mx-auto px-6 w-full flex flex-col md:flex-row items-center gap-10">
            <div class="flex-1 text-center md:text-left relative z-10">
                <div class="inline-block bg-white/10 text-white/90 px-4 py-1.5 rounded-full text-xs font-bold mb-4 backdrop-blur-sm border border-white/10">
                    {{ $jobs->total() }}+ Open positions available
                </div>
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight tracking-tight">Find your <br> next <span class="text-indigo-200">Chapter</span>.</h1>
                <p class="text-white/80 max-w-lg mb-10 leading-relaxed text-sm font-medium">
                    Help us build the next generation of enterprise intelligence. Explore opportunities tailored to your skills.
                </p>
                <a href="#open-roles" class="bg-white text-indigo-600 px-8 py-4 rounded-xl font-black text-sm tracking-wide hover:bg-indigo-50 transition shadow-xl inline-block active:scale-95">Explore All Roles</a>
            </div>
            <div class="hidden md:block flex-1 relative">
                <!-- Platform Consistent Abstract Graphic -->
                <div class="relative h-[400px] flex items-center justify-center">
                    <div class="absolute w-80 h-80 bg-indigo-400/20 rounded-full blur-[80px]"></div>
                    <div class="relative bg-white/10 backdrop-blur-xl border border-white/20 p-8 rounded-[2rem] shadow-2xl rotate-3">
                        <div class="flex gap-4 mb-6">
                            <div class="w-10 h-10 bg-indigo-500 rounded-xl"></div>
                            <div class="space-y-2 flex-1">
                                <div class="w-3/4 h-3 bg-white/30 rounded-full"></div>
                                <div class="w-1/2 h-2 bg-white/10 rounded-full"></div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="w-full h-12 bg-white/5 rounded-xl border border-white/5"></div>
                            <div class="w-full h-12 bg-white/5 rounded-xl border border-white/5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Section -->
    <section id="open-roles" class="px-6 mb-32">
        <div class="max-w-6xl mx-auto">
            <form action="{{ route('jobs.index') }}" method="GET" class="search-card grid grid-cols-1 md:grid-cols-10 gap-6 items-center">
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Keyword</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. Designer, Engineer" class="w-full border-gray-100 border-x-0 border-t-0 border-b focus:ring-0 focus:border-indigo-500 py-3 text-sm font-bold text-gray-900 placeholder:text-gray-300">
                </div>
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Location</label>
                    <select name="location" class="w-full border-gray-100 border-x-0 border-t-0 border-b focus:ring-0 focus:border-indigo-500 py-3 text-sm font-bold text-gray-900 bg-transparent cursor-pointer">
                        <option value="">Anywhere</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Category</label>
                    <select name="department" class="w-full border-gray-100 border-x-0 border-t-0 border-b focus:ring-0 focus:border-indigo-500 py-3 text-sm font-bold text-gray-900 bg-transparent cursor-pointer">
                        <option value="">All Categories</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1 pt-4 md:pt-6">
                    <button type="submit" class="primary-btn text-white w-full h-14 flex items-center justify-center active:scale-95">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
            <div class="mt-8 flex flex-wrap items-center gap-4">
                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Popular:</span>
                @foreach(['Engineering', 'Marketing', 'Product'] as $pop)
                    <a href="{{ route('jobs.index', ['department' => $pop]) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">{{ $pop }}</a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular Categories -->
    <section class="max-w-6xl mx-auto px-6 mb-32">
        <div class="flex items-center gap-3 mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Popular Categories</h2>
            <div class="h-[2px] w-12 bg-indigo-100"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($departmentsByCount->take(8) as $dept)
                <div class="category-card flex flex-col group relative overflow-hidden">
                    <div class="font-bold text-lg text-gray-900 group-hover:text-indigo-600 transition mb-3">{{ $dept->department }}</div>
                    <div class="flex items-center gap-3">
                        <span class="count-badge">{{ $dept->total }}</span>
                        <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Positions</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Jobs Section -->
    <section class="max-w-6xl mx-auto px-6 pb-40">
        <div class="flex items-center justify-between mb-12 pb-6 border-b border-gray-100">
            <h2 class="text-2xl font-bold text-gray-900">Featured Roles</h2>
            <a href="{{ route('jobs.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-2 group">
                View All Jobs
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
        
        <div class="space-y-5">
            @forelse ($jobs as $job)
                <div class="bg-white p-6 md:p-10 rounded-2xl border border-gray-100 hover:border-indigo-100 transition-all hover:shadow-xl hover:shadow-indigo-500/[0.04] group/card">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                        <div class="flex items-center gap-6">
                            <div class="w-14 h-14 bg-gray-50 group-hover/card:bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 transition-colors">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 group-hover/card:text-indigo-600 transition cursor-pointer mb-1">{{ $job->title }}</h3>
                                <div class="flex flex-wrap items-center gap-y-2 gap-x-5 text-xs text-gray-400 font-bold uppercase tracking-wider">
                                    <span class="flex items-center gap-2 text-indigo-500">{{ $job->department }}</span>
                                    <span class="flex items-center gap-2"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $job->created_at->diffForHumans() }}</span>
                                    <span class="flex items-center gap-2">{{ $job->location }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <a href="{{ route('jobs.show', $job) }}" class="primary-btn text-white text-sm px-10">Job Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-24 text-center text-gray-400 bg-white rounded-3xl border border-dashed border-gray-200">
                    <p class="font-bold text-lg mb-2">No positions found</p>
                    <p class="text-sm">Try broadening your search or clearing filters.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-20">
            {{ $jobs->links() }}
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-16 mt-auto">
        <div class="max-w-7xl mx-auto px-6 text-center">
             <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-7 h-7 bg-black rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-[9px]">AI</span>
                </div>
                <span class="font-bold text-gray-900 tracking-tight text-xl">AIHRM</span>
            </div>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.4em]">Empowering Workforce Intelligence</p>
            <p class="text-xs text-gray-400 mt-4">© {{ date('Y') }} AIHRM. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

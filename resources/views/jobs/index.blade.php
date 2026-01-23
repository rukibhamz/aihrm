<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers | AIHRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('build/assets/app-CklSuxFb.css') }}">
    <script type="module" src="{{ asset('build/assets/app-CJy8ASEk.js') }}"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/90 backdrop-blur-sm border-b border-gray-200 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">AI</span>
                    </div>
                    <span class="font-bold text-xl">AIHRM</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ url('/') }}" class="text-sm font-medium text-gray-600 hover:text-black">Home</a>
                    <a href="{{ route('login') }}" class="px-6 py-2 bg-black text-white rounded-lg font-semibold hover:bg-neutral-900 transition">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1" style="padding-top: 73px;">
        <!-- Hero Section -->
        <div class="relative min-h-[400px] flex items-center justify-center bg-black text-white px-6 overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/careers_hero.png') }}" alt="Careers" class="w-full h-full object-cover grayscale opacity-50">
                <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-black/80"></div>
            </div>

            <div class="relative z-10 max-w-5xl mx-auto text-center py-16">
                <div class="inline-flex items-center gap-3 px-4 py-2 bg-white/10 backdrop-blur-md rounded-full border border-white/20 mb-8 animate-fade-in">
                    <span class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse shadow-[0_0_12px_rgba(34,197,94,0.6)]"></span>
                    <span class="text-[10px] font-black uppercase tracking-[0.25em] text-white">
                        {{ $jobs->total() }} Open Positions
                    </span>
                </div>
                
                <h1 class="text-4xl lg:text-7xl font-black mb-6 tracking-tighter leading-none">
                    Join Our Mission
                </h1>
                
                <p class="text-neutral-300 max-w-2xl mx-auto text-sm lg:text-xl font-medium leading-relaxed opacity-90">
                    We're building the future of workforce intelligence. Explore open opportunities and help us redefine the HR experience for everyone.
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 pb-20">
            <!-- Filter Section -->
            <div class="mt-[-40px] mb-32">
                <form action="{{ route('jobs.index') }}" method="GET" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 lg:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Job Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Title, keyword..."
                                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-1 focus:ring-black focus:border-black transition text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Organization</label>
                            <select name="department" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-1 focus:ring-black focus:border-black transition text-sm">
                                <option value="">All Categories</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Presence</label>
                            <select name="location" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-1 focus:ring-black focus:border-black transition text-sm">
                                <option value="">All Locations</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-6 py-4 bg-black text-white rounded-lg font-bold text-base hover:bg-neutral-900 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Search Opportunities
                    </button>
                </form>
            </div>

            <div class="text-center mb-24">
                <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 tracking-tighter uppercase">Open Positions</h2>
                <div class="w-12 h-1 bg-black mx-auto mt-4 rounded-full"></div>
            </div>

            <!-- Job Result Grid -->
            @if($jobs->isEmpty())
                <div class="text-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500 font-medium">No positions found matching your criteria.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($jobs as $job)
                        <div class="bg-white group rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col h-full transform hover:-translate-y-1.5">
                            <div class="p-6 flex flex-col h-full">
                                <div class="flex justify-between items-start mb-6">
                                    <div class="w-10 h-10 bg-gray-50 rounded-lg flex items-center justify-center border border-gray-100 group-hover:bg-black group-hover:text-white transition-colors duration-300 shadow-inner">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <span class="px-2 py-0.5 bg-green-50 text-green-600 text-[9px] font-black rounded-full uppercase tracking-widest border border-green-100 shadow-sm">
                                        New
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-bold text-gray-900 mb-2 tracking-tight group-hover:text-black transition-colors">{{ $job->title }}</h3>
                                
                                <div class="flex flex-wrap items-center gap-2 mb-6">
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-0.5 rounded border border-gray-100">
                                        {{ $job->department ?? 'General' }}
                                    </span>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-0.5 rounded border border-gray-100 italic">
                                        {{ $job->location ?? 'Remote' }}
                                    </span>
                                </div>

                                <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-tighter">
                                        Posted {{ $job->created_at->diffForHumans() }}
                                    </span>
                                    <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center text-[10px] font-bold text-black group/link">
                                        VIEW DETAIL
                                        <svg class="w-3 h-3 ml-1 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-16">
                    {{ $jobs->links() }}
                </div>
            @endif
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-4 text-center">
            <p class="text-xs text-gray-500">© 2025 AIHRM. All rights reserved.</p>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Enterprise Intelligence Layer.</p>
        </div>
    </footer>
</body>
</html>

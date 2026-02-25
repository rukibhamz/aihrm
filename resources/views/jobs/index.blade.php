<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Careers | AIHRM - Join the Intelligence Revolution</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('build/assets/app-CklSuxFb.css') }}">
    <script type="module" src="{{ asset('build/assets/app-CJy8ASEk.js') }}"></script>
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #8b5cf6;
        }
        * { font-family: 'Outfit', sans-serif; }
        
        body {
            background-color: #030712;
            color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .mesh-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: 
                radial-gradient(circle at 0% 0%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 100% 0%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-hover:hover {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.15);
            transform: translateY(-4px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .text-gradient {
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 20">
    <div class="mesh-gradient"></div>
    
    <!-- Navigation -->
    <nav class="fixed w-full z-50 transition-all duration-300 px-6 py-4" 
         :class="scrolled ? 'glass py-3' : 'bg-transparent'">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <span class="text-white font-black text-lg">AI</span>
                    </div>
                    <span class="font-bold text-2xl tracking-tight">AIHRM</span>
                </a>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="text-sm font-medium text-gray-400 hover:text-white transition">Home</a>
                <a href="{{ route('login') }}" class="px-6 py-2.5 glass text-white rounded-xl font-bold transition hover:bg-white/10">
                    Dashboard
                </a>
            </div>
        </div>
    </nav>

    <main class="flex-1 pt-32 pb-20">
        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-6 mb-20 text-center">
            <div class="inline-flex items-center gap-3 px-4 py-2 glass rounded-full mb-8">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(34,197,94,0.5)]"></span>
                <span class="text-[10px] font-black uppercase tracking-widest text-white/70">
                    {{ $jobs->total() }} Open Positions
                </span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold mb-6 tracking-tighter leading-none">
                Join Our <span class="text-gradient">Mission.</span>
            </h1>
            
            <p class="text-gray-400 max-w-2xl mx-auto text-lg md:text-xl leading-relaxed">
                We're building the intelligence layer for the modern workforce. 
                Redefine the HR experience with us.
            </p>
        </div>

        <div class="max-w-7xl mx-auto px-6">
            <!-- Filter Bar -->
            <div class="glass rounded-3xl p-6 lg:p-8 mb-16 border border-white/5 relative z-20">
                <form action="{{ route('jobs.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Search Role</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    placeholder="Software Engineer..."
                                    class="w-full pl-12 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition text-sm text-white placeholder-gray-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Department</label>
                            <select name="department" class="w-full px-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition text-sm text-white appearance-none outline-none">
                                <option value="" class="bg-[#030712]">All Areas</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }} class="bg-[#030712]">{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-bold transition shadow-xl shadow-indigo-500/20 transform hover:-translate-y-0.5">
                            Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Job Grid -->
            @if($jobs->isEmpty())
                <div class="glass rounded-3xl p-20 text-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-indigo-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 border border-white/10">
                            <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-xl font-medium text-gray-400 tracking-tight">No open positions found.</p>
                        <p class="text-sm text-gray-500 mt-2">Try adjusting your filters or search keywords.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($jobs as $job)
                        <div class="glass glass-hover p-8 rounded-3xl flex flex-col h-full group border-white/5">
                            <div class="flex justify-between items-start mb-8">
                                <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 group-hover:bg-indigo-600 group-hover:border-indigo-500 transition-all duration-300">
                                    <svg class="w-6 h-6 text-indigo-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @if($job->created_at->gt(now()->subDays(7)))
                                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-[9px] font-black rounded-full uppercase tracking-widest border border-emerald-500/20">
                                        New
                                    </span>
                                @endif
                            </div>
                            
                            <h3 class="text-2xl font-bold text-white mb-3 tracking-tight group-hover:text-indigo-400 transition-colors">{{ $job->title }}</h3>
                            
                            <div class="flex flex-wrap items-center gap-3 mb-8">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-white/5 px-2.5 py-1 rounded-lg border border-white/5">
                                    {{ $job->department ?? 'General' }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-white/5 px-2.5 py-1 rounded-lg border border-white/5 italic">
                                    {{ $job->location ?? 'Remote' }}
                                </span>
                            </div>

                            <div class="mt-auto flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mb-1">Posted</span>
                                    <span class="text-xs font-medium text-gray-400">{{ $job->created_at->diffForHumans() }}</span>
                                </div>
                                <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center gap-2 text-sm font-bold text-white group/link">
                                    <span>Learn More</span>
                                    <div class="w-8 h-8 rounded-full glass flex items-center justify-center group-hover/link:bg-white group-hover/link:text-black transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </div>
                                </a>
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
    <footer class="py-12 px-6 border-t border-white/5 bg-black/30 mt-auto">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8 text-center md:text-left">
                <div class="flex items-center gap-3 opacity-60">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-black text-sm">AI</span>
                    </div>
                    <span class="font-bold text-xl tracking-tight">AIHRM</span>
                </div>
                <div class="text-xs text-gray-500 font-medium">
                    © 2025 AIHRM. All rights reserved. <br class="md:hidden">
                    <span class="mx-2 hidden md:inline">•</span>
                    Enterprise Intelligence Layer.
                </div>
                <div class="flex gap-8 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                    <a href="#" class="hover:text-white transition">Privacy</a>
                    <a href="#" class="hover:text-white transition">Terms</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

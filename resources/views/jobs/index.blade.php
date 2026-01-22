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
        .hero-overlay {
            background: linear-gradient(to bottom, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.5) 50%, rgba(0,0,0,0.85) 100%);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col">
    <!-- Fixed Navigation (Updated for Contrast) -->
    <nav class="bg-black/20 backdrop-blur-xl border-b border-white/10 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <a href="/" class="flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-black font-black text-sm">AI</span>
                    </div>
                    <span class="font-extrabold text-xl tracking-tight text-white">AIHRM</span>
                </a>
                <span class="hidden sm:inline-flex px-2.5 py-1 bg-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-lg border border-white/20">Careers</span>
            </div>
            <div class="flex items-center gap-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-bold text-white/80 hover:text-white transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-7 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20 active:scale-95">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section (Fixed with Inline Height for Guaranteed Layout) -->
    <section style="height: 70vh; min-height: 600px;" class="relative flex items-center justify-center overflow-hidden bg-gray-900">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero_team.png') }}" alt="Join Our Team" class="w-full h-full object-cover">
            <div class="absolute inset-0 hero-overlay"></div>
        </div>
        
        <!-- Hero Content (Centered) -->
        <div class="relative z-10 text-center max-w-4xl px-6">
            <h1 class="text-4xl md:text-5xl lg:text-7xl font-extrabold text-white mb-8 leading-[1.1] tracking-tight">
                Join our mission to build the <span class="text-indigo-400">Future of Work</span>.
            </h1>
            <p class="text-lg md:text-xl text-white/90 mb-12 max-w-2xl mx-auto leading-relaxed font-medium">
                We're a team of visionaries and builders creating the next generation of human resources intelligence. Explore our open roles and find your place.
            </p>
            <div class="flex justify-center">
                <a href="#openings" class="px-12 py-5 bg-indigo-600 text-white rounded-2xl font-black text-lg hover:bg-indigo-700 transition shadow-2xl shadow-indigo-600/40 transform hover:-translate-y-1 active:scale-95">
                    View Open Positions
                </a>
            </div>
        </div>
    </section>

    <!-- Opportunities Hub -->
    <main id="openings" class="flex-1 bg-white">
        <!-- Section Header -->
        <div class="max-w-6xl mx-auto px-6 py-24 text-center">
            <h2 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Current Opportunities</h2>
            <p class="text-gray-500 font-semibold text-lg">Find the role that matches your skills and ambitions</p>
        </div>

        <!-- Job Cards (Standardized Premium Style) -->
        <div class="max-w-6xl mx-auto px-6 pb-32">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse ($jobs as $job)
                    <div class="bg-white p-10 rounded-[2.5rem] border border-gray-100 shadow-[0_4px_25px_-5px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_50px_-10px_rgba(79,70,229,0.15)] hover:border-indigo-100 transition-all duration-500 group flex flex-col h-full">
                        <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center mb-8 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 group-hover:scale-110">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        
                        <div class="mb-8 flex-1">
                            <h3 class="text-2xl font-extrabold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors">{{ $job->title }}</h3>
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[10px] font-black uppercase tracking-widest rounded-lg">{{ $job->department }}</span>
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-lg">Remote</span>
                            </div>
                        </div>
                        
                        <div class="pt-8 border-t border-gray-50 flex items-center justify-between">
                            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center text-xs font-black text-indigo-600 uppercase tracking-[0.2em] hover:translate-x-1 transition-transform">
                                View Role
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-32 text-center bg-gray-50/50 rounded-[3rem] border-2 border-dashed border-gray-100">
                        <p class="text-gray-400 font-bold text-lg">No open roles at the moment.</p>
                        <p class="text-gray-400 text-sm mt-1">We're constantly expanding. Check back soon.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Values Section (Modern Dark Mode) -->
        <section class="py-32 bg-slate-950 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(79,70,229,0.2),transparent_50%)]"></div>
            
            <div class="max-w-6xl mx-auto px-6 text-center relative z-10">
                <span class="text-indigo-400 text-xs font-black uppercase tracking-[0.5em] mb-6 block">Our Culture</span>
                <h2 class="text-5xl font-extrabold mb-24 tracking-tighter">Values that define us.</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-24">
                    <div class="group">
                        <div class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center mx-auto mb-10 border border-white/10 group-hover:border-indigo-500/50 group-hover:bg-indigo-500/10 transition-all duration-500 transform group-hover:-translate-y-2">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-xl font-black uppercase tracking-widest mb-4">Ownership</h3>
                        <p class="text-gray-500 font-medium leading-relaxed">Lead and decide from day one.</p>
                    </div>
                    <div class="group">
                        <div class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center mx-auto mb-10 border border-white/10 group-hover:border-indigo-500/50 group-hover:bg-indigo-500/10 transition-all duration-500 transform group-hover:-translate-y-2">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <h3 class="text-xl font-black uppercase tracking-widest mb-4">Innovation</h3>
                        <p class="text-gray-500 font-medium leading-relaxed">Challenge the status quo daily.</p>
                    </div>
                    <div class="group">
                        <div class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center mx-auto mb-10 border border-white/10 group-hover:border-indigo-500/50 group-hover:bg-indigo-500/10 transition-all duration-500 transform group-hover:-translate-y-2">
                            <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-black uppercase tracking-widest mb-4">Community</h3>
                        <p class="text-gray-500 font-medium leading-relaxed">Modern workplace, human-centric.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-3 mb-8">
                <div class="w-9 h-9 bg-black rounded-xl flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-[10px]">AI</span>
                </div>
                <span class="font-extrabold text-gray-900 tracking-tight text-2xl">AIHRM</span>
            </div>
            <p class="text-sm text-gray-400 font-extrabold uppercase tracking-[0.4em] mb-4">Empowering Workplace Intelligence</p>
            <p class="text-xs text-gray-400">© {{ date('Y') }} AIHRM Inc. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Careers | AIHRM - Modern Workplace Intelligence</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
        .hero-gradient { background: linear-gradient(135deg, rgba(15, 23, 42, 0.1) 0%, rgba(88, 28, 135, 0.05) 100%); }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-neutral-50 text-neutral-900 antialiased selection:bg-purple-100 selection:text-purple-900">
    <!-- Premium Navigation -->
    <header class="fixed w-full z-50 transition-all duration-300" id="main-header">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between glass mt-4 rounded-2xl border border-white/40 shadow-xl shadow-black/5 mx-4 md:mx-auto">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-black rounded-xl flex items-center justify-center group-hover:rotate-6 transition-transform duration-300">
                    <span class="text-white font-extrabold text-xs">AI</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-lg tracking-tight leading-none rotate-[-1deg]">AIHRM</span>
                    <span class="text-[10px] text-neutral-500 font-medium uppercase tracking-[0.2em]">Careers</span>
                </div>
            </a>
            <nav class="hidden md:flex items-center gap-8">
                <a href="#about" class="text-sm font-semibold text-neutral-600 hover:text-black transition">About Us</a>
                <a href="#benefits" class="text-sm font-semibold text-neutral-600 hover:text-black transition">Benefits</a>
                <a href="#openings" class="text-sm font-semibold text-neutral-600 hover:text-black transition">Open Roles</a>
            </nav>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2 rounded-xl bg-black text-white text-sm font-bold hover:bg-neutral-800 transition shadow-lg shadow-black/10">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-neutral-600 hover:text-black transition">Login</a>
                    <a href="#openings" class="hidden sm:inline-flex px-5 py-2 rounded-xl bg-black text-white text-sm font-bold hover:bg-neutral-800 transition shadow-lg shadow-black/10">View Jobs</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section with Premium BG -->
    <section class="relative pt-48 pb-32 overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-cover bg-center" style="background-image: url('{{ asset('images/careers-hero.png') }}'); opacity: 0.15; filter: blur(40px);"></div>
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-white via-white/50 to-neutral-50"></div>
        
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-16">
                <div class="md:w-3/5 text-center md:text-left">
                    <span class="inline-flex items-center gap-2 py-1 px-4 rounded-full bg-purple-50 text-purple-700 text-xs font-bold uppercase tracking-widest mb-6 border border-purple-100 animate-pulse">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                        We are hiring
                    </span>
                    <h1 class="text-6xl md:text-7xl lg:text-8xl font-black tracking-tighter mb-8 leading-[0.9]">
                        Build the <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-indigo-600 to-black">Future of Work</span>.
                    </h1>
                    <p class="text-xl text-neutral-500 max-w-xl leading-relaxed mb-10 font-medium">
                        Join a team of visionaries and engineers building the next generation of HR intelligence. We value craft, autonomy, and radical transparency.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <a href="#openings" class="w-full sm:w-auto px-10 py-4.5 bg-black text-white font-bold rounded-2xl hover:bg-neutral-800 transition-all transform hover:-translate-y-1 shadow-2xl shadow-black/20 text-center">
                            Explore Opportunities
                        </a>
                        <a href="#about" class="w-full sm:w-auto px-8 py-4.5 bg-white border border-neutral-200 text-neutral-700 font-bold rounded-2xl hover:bg-neutral-50 transition-all text-center">
                            Learn Our Story
                        </a>
                    </div>
                </div>
                <!-- Interactive Element / Visual -->
                <div class="md:w-2/5 relative animate-float">
                    <div class="w-72 h-72 md:w-96 md:h-96 rounded-3xl bg-white shadow-2xl shadow-black/10 overflow-hidden border border-white/50 relative p-4 rotate-3">
                         <img src="{{ asset('images/careers-hero.png') }}" class="w-full h-full object-cover rounded-2xl" alt="AIHRM Future">
                         <div class="absolute inset-x-0 bottom-0 p-8 glass border-t border-white/20">
                             <div class="flex items-center gap-4">
                                 <div class="flex -space-x-3">
                                     <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-200"></div>
                                     <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-300"></div>
                                     <div class="w-10 h-10 rounded-full border-2 border-white bg-gray-400"></div>
                                 </div>
                                 <div class="text-xs text-neutral-600 font-bold">Join 50+ Innovators</div>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Openings Section -->
    <section id="openings" class="py-24 max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
            <div>
                <h2 class="text-4xl md:text-5xl font-black tracking-tight mb-4">Open Roles</h2>
                <p class="text-neutral-500 font-medium italic">Finding the best talent to build the future of AIHRM.</p>
            </div>
            <!-- Search / Filter (Static UX Improvement) -->
            <div class="flex flex-wrap gap-4">
                 <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" placeholder="Search positions..." class="pl-12 pr-6 py-3 bg-white border border-neutral-200 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-purple-200 focus:border-purple-600 transition outline-none w-full sm:w-64">
                 </div>
            </div>
        </div>

        <div class="grid gap-4">
            @forelse ($jobs as $job)
            <a href="{{ route('jobs.show', $job) }}" class="group relative block p-8 rounded-3xl border border-neutral-200 bg-white hover:border-black hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] transition-all duration-500">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-4">
                            <h3 class="text-2xl font-black text-neutral-900 group-hover:translate-x-1 transition-transform">{{ $job->title }}</h3>
                            @if($job->created_at->diffInDays() < 7)
                                <span class="px-3 py-1 rounded-full text-[10px] font-black bg-purple-100 text-purple-700 uppercase tracking-widest">New</span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-3 text-sm text-neutral-500">
                            @if($job->department)
                                <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-neutral-100 text-neutral-700 font-bold group-hover:bg-purple-50 group-hover:text-purple-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    {{ $job->department }}
                                </span>
                            @endif
                            @if($job->location)
                                <span class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-neutral-100 text-neutral-700 font-bold group-hover:bg-indigo-50 group-hover:text-indigo-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $job->location }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-6">
                        <span class="text-sm font-bold text-neutral-400 group-hover:text-black transition">{{ $job->created_at->diffForHumans() }}</span>
                        <div class="w-14 h-14 rounded-2xl bg-neutral-100 flex items-center justify-center group-hover:bg-black group-hover:text-white transition-all transform group-hover:scale-110">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </div>
                </div>
                <!-- Animated bottom bar -->
                <div class="absolute bottom-0 left-8 right-8 h-1 bg-black transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500 rounded-t-full"></div>
            </a>
            @empty
            <div class="text-center py-32 rounded-[2rem] border-2 border-dashed border-neutral-200 bg-white">
                <div class="w-20 h-20 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-neutral-900 mb-2">No positions open right now</h3>
                <p class="text-neutral-500 max-w-xs mx-auto text-sm font-medium">We're constantly evolving. Check back soon or follow us for updates.</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- Culture Section -->
    <section id="about" class="py-24 bg-neutral-900 text-white overflow-hidden relative">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-purple-600/10 blur-[120px]"></div>
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="grid md:grid-cols-2 gap-20 items-center">
                <div>
                    <h2 class="text-5xl md:text-6xl font-black tracking-tight mb-8">Crafting a <br><span class="text-purple-400">Smater Future</span>.</h2>
                    <p class="text-xl text-neutral-400 leading-relaxed mb-10 font-medium">At AIHRM, we're not just building software; we're reimagining how teams work together. Our culture is built on deep trust, extreme ownership, and a pursuit of excellence.</p>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                             <div class="text-3xl font-black mb-1">100%</div>
                             <div class="text-neutral-500 text-xs font-bold uppercase tracking-widest">Remote First</div>
                        </div>
                        <div>
                             <div class="text-3xl font-black mb-1">Zero</div>
                             <div class="text-neutral-500 text-xs font-bold uppercase tracking-widest">Micromanagement</div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="aspect-square rounded-3xl bg-neutral-800 border border-neutral-700 p-2 transform rotate-[-4deg]">
                        <div class="w-full h-full rounded-2xl bg-neutral-700 animate-pulse"></div>
                    </div>
                    <div class="aspect-square rounded-3xl bg-neutral-800 border border-neutral-700 p-2 transform translate-y-8 rotate-[4deg]">
                        <div class="w-full h-full rounded-2xl bg-neutral-600 animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-12 bg-neutral-900 text-neutral-500 border-t border-neutral-800">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-neutral-800 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-[10px]">AI</span>
                </div>
                <span class="font-bold text-white tracking-tight">AIHRM</span>
            </div>
            <p class="text-sm font-medium">&copy; {{ date('Y') }} AIHRM Inc. All rights reserved.</p>
            <div class="flex gap-6 text-xs font-bold uppercase tracking-widest">
                <a href="#" class="hover:text-white transition">Twitter</a>
                <a href="#" class="hover:text-white transition">LinkedIn</a>
                <a href="#" class="hover:text-white transition">Dribbble</a>
            </div>
        </div>
    </footer>

    <script>
        window.onscroll = function() {
            var header = document.getElementById('main-header');
            if (window.pageYOffset > 50) {
                header.classList.remove('mt-4');
                header.classList.remove('mx-4');
            } else {
                header.classList.add('mt-4');
                header.classList.add('mx-4');
            }
        };
    </script>
</body>
</html>


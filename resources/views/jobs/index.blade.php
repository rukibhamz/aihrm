<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Careers | AIHRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
        .slide-transition { transition: opacity 0.7s ease-in-out; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col">
    <!-- Fixed Navigation (Exact copy from welcome.blade.php) -->
    <nav class="bg-white/90 backdrop-blur-sm border-b border-gray-200 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">AI</span>
                    </div>
                    <span class="font-bold text-xl">AIHRM</span>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-gray-600 hover:text-black transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Replicated structure from welcome.blade.php) -->
    <div class="relative min-h-[450px] max-h-[60vh] overflow-hidden flex flex-col">
        <!-- Background Image & Overlay -->
        <div class="absolute inset-0">
            <img src="{{ asset('images/hero_team.png') }}" alt="Join Our Team" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
        </div>
        
        <!-- Hero Content (Aligned with landing page pt-16 and centering) -->
        <div class="relative h-full flex-1 flex items-center justify-center px-6 pt-16">
            <div class="text-center max-w-4xl">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                    Join us in building the <span class="text-indigo-400">Future of Work</span>.
                </h1>
                <p class="text-base sm:text-lg text-white/90 mb-8 max-w-2xl mx-auto leading-relaxed">
                    We're a team of visionaries and builders creating the next generation of human resources intelligence. Explore our open roles and find your place.
                </p>
                
                <div class="flex flex-col gap-3 justify-center items-center">
                    <a href="#openings" class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-semibold text-base hover:bg-indigo-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        View Open Positions
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 bg-white">
        <!-- Opportunities Header -->
        <div class="max-w-6xl mx-auto px-6 pt-20 pb-10 text-center">
            <h2 id="openings" class="text-2xl font-bold text-gray-900 mb-2">Current Opportunities</h2>
            <p class="text-gray-600 text-sm">Everything you need, right at your fingertips</p>
        </div>

        <!-- Job Cards (Standardized Card Design) -->
        <div class="max-w-6xl mx-auto px-6 pb-24">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse ($jobs as $job)
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition group flex flex-col h-full">
                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center mb-4 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        
                        <div class="mb-4 flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-lg font-bold text-gray-900 truncate">{{ $job->title }}</h3>
                                @if($job->created_at->diffInDays() < 7)
                                    <span class="px-1.5 py-0.5 bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase tracking-wider rounded">New</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 font-medium flex items-center gap-2">
                                <span>{{ $job->department }}</span>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span>{{ $job->location }}</span>
                            </p>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-50 mt-auto">
                            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center text-xs font-bold text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition group/link">
                                View Details
                                <svg class="w-3.5 h-3.5 ml-1.5 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <p class="text-gray-500 font-medium italic">We're always growing. Check back soon for new opportunities.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Culture (Clean Minimalist) -->
        <section class="bg-gray-50 py-24 border-t border-gray-200">
            <div class="max-w-6xl mx-auto px-6 text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Work that matters.</h2>
                <p class="text-gray-600 text-sm max-w-xl mx-auto mb-16">Join a team where your voice is heard and your work reaches millions.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="space-y-3">
                        <h3 class="font-bold text-gray-900 text-xs uppercase tracking-widest">Autonomy</h3>
                        <p class="text-gray-500 text-sm">Work from anywhere. We value results over desks.</p>
                    </div>
                    <div class="space-y-3">
                        <h3 class="font-bold text-gray-900 text-xs uppercase tracking-widest">Curiosity</h3>
                        <p class="text-gray-500 text-sm">Continuous learning and professional evolution.</p>
                    </div>
                    <div class="space-y-3">
                        <h3 class="font-bold text-gray-900 text-xs uppercase tracking-widest">Impact</h3>
                        <p class="text-gray-500 text-sm">Build the foundation for intelligent workplaces worldwide.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer (Exact copy from welcome.blade.php) -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-4 text-center">
            <p class="text-xs text-gray-500">© {{ date('Y') }} AIHRM. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

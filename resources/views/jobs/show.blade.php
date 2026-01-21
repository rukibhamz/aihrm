<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $job->title }} | Careers at AIHRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); }
    </style>
</head>
<body class="bg-neutral-50 text-neutral-900 antialiased selection:bg-purple-100 selection:text-purple-900">
    <!-- Navbar -->
    <header class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between glass mt-2 rounded-2xl border border-white/40 shadow-xl shadow-black/5 mx-4 md:mx-auto">
            <a href="{{ route('jobs.index') }}" class="flex items-center gap-2 group">
                <div class="w-10 h-10 bg-neutral-100 rounded-xl flex items-center justify-center group-hover:bg-black group-hover:text-white transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                <span class="font-bold text-sm text-neutral-600 group-hover:text-black transition-colors">Back to Jobs</span>
            </a>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-5 py-2 rounded-xl bg-black text-white text-sm font-bold hover:bg-neutral-800 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-neutral-600 hover:text-black transition">Login</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="pt-40 pb-24 px-6">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                <!-- Left Content -->
                <div class="lg:col-span-8">
                    <div class="mb-12">
                        <div class="flex flex-wrap gap-3 mb-6">
                            @if($job->department)
                                <span class="px-4 py-1.5 rounded-full bg-purple-50 text-purple-700 text-xs font-black uppercase tracking-widest border border-purple-100">
                                    {{ $job->department }}
                                </span>
                            @endif
                             @if($job->location)
                                <span class="px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-black uppercase tracking-widest border border-indigo-100">
                                    {{ $job->location }}
                                </span>
                            @endif
                        </div>
                        <h1 class="text-5xl md:text-6xl font-black tracking-tighter text-neutral-900 mb-8 leading-tight">
                            {{ $job->title }}
                        </h1>
                        <div class="flex items-center gap-4 text-neutral-500 font-medium">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Posted {{ $job->created_at->diffForHumans() }}
                            </span>
                            <span class="w-1.5 h-1.5 rounded-full bg-neutral-300"></span>
                            <span>Full-time</span>
                        </div>
                    </div>

                    <div class="prose prose-neutral max-w-none">
                        <div class="space-y-16">
                            <section>
                                <h3 class="text-3xl font-black mb-6 text-neutral-900 flex items-center gap-3">
                                    <span class="w-8 h-1 bg-black rounded-full"></span>
                                    About the Role
                                </h3>
                                <div class="text-lg text-neutral-600 leading-relaxed font-medium whitespace-pre-line">
                                    {{ $job->description }}
                                </div>
                            </section>

                            <section class="p-10 bg-white rounded-[2.5rem] border border-neutral-200">
                                <h3 class="text-3xl font-black mb-6 text-neutral-900 flex items-center gap-3">
                                    <span class="w-8 h-1 bg-purple-600 rounded-full"></span>
                                    Requirements
                                </h3>
                                <div class="text-lg text-neutral-600 leading-relaxed font-medium whitespace-pre-line">
                                    {{ $job->requirements }}
                                </div>
                            </section>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar (Sticky) -->
                <div class="lg:col-span-4">
                    <div class="sticky top-40 space-y-6">
                        <div class="p-8 bg-neutral-900 text-white rounded-[2.5rem] shadow-2xl shadow-black/20 border border-neutral-800">
                            <h3 class="text-2xl font-black mb-4">Ready to Apply?</h3>
                            <p class="text-neutral-400 text-sm font-medium mb-8">Join a fast-growing team of innovators and help us build the future of workplace intelligence.</p>
                            
                            <a href="{{ route('applications.create', $job) }}" class="flex items-center justify-center w-full px-8 py-5 bg-white text-black font-black rounded-2xl hover:bg-neutral-100 transition shadow-xl shadow-black/20 transform hover:-translate-y-1">
                                Apply Now
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            
                            <button class="w-full mt-4 flex items-center justify-center gap-2 text-xs font-bold uppercase tracking-widest text-neutral-500 hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                                Share this role
                            </button>
                        </div>

                        <div class="p-8 bg-white rounded-[2.5rem] border border-neutral-200">
                             <h4 class="text-sm font-black uppercase tracking-[0.2em] text-neutral-400 mb-6">Internal Referral</h4>
                             <p class="text-sm text-neutral-600 font-medium mb-6">Know someone perfect for this? Refer them and get a bonus when they join.</p>
                             <a href="#" class="text-sm font-bold text-black border-b-2 border-black pb-1 hover:text-purple-600 hover:border-purple-600 transition">Read Policy &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="py-12 bg-neutral-900 text-neutral-500 mt-24">
        <div class="max-w-7xl mx-auto px-6 text-center">
             <p class="text-sm font-bold tracking-tight text-white mb-2">AIHRM Inc.</p>
             <p class="text-xs">&copy; {{ date('Y') }} All rights reserved.</p>
        </div>
    </footer>
</body>
</html>


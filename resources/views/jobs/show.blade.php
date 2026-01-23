<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $job->title }} | Careers | AIHRM</title>
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
                    <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-gray-600 hover:text-black">Careers</a>
                    <a href="{{ route('login') }}" class="px-6 py-2 bg-black text-white rounded-lg font-semibold hover:bg-neutral-900 transition">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 pb-20" style="padding-top: 80px;">
        <div class="max-w-7xl mx-auto px-6">
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-black transition mb-8 group">
                <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Careers
            </a>
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-2 py-0.5 bg-neutral-100 text-neutral-600 text-[10px] font-bold rounded uppercase tracking-widest border border-neutral-200">
                            {{ $job->department ?? 'General' }}
                        </span>
                        @if($job->location)
                            <span class="px-2 py-0.5 border border-gray-200 text-gray-500 text-[10px] font-bold rounded uppercase tracking-widest italic">
                                {{ $job->location }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 tracking-tight italic">{{ $job->title }}</h1>
                </div>
                
                <a href="{{ route('applications.create', $job) }}" class="px-10 py-4 bg-black text-white rounded-lg font-bold text-base hover:bg-neutral-900 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center">
                    Apply for this position
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-8 space-y-8">
                    <!-- Description -->
                    <div class="bg-white p-8 md:p-10 rounded-xl border border-gray-200 shadow-sm">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3 italic">
                            <div class="w-1.5 h-6 bg-black rounded-full"></div>
                            Role Description
                        </h2>
                        <div class="prose prose-neutral max-w-none text-gray-600 leading-relaxed font-medium text-sm">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="bg-white p-8 md:p-10 rounded-xl border border-gray-200 shadow-sm">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3 italic">
                            <div class="w-1.5 h-6 bg-black rounded-full"></div>
                            Requirements
                        </h2>
                        <div class="prose prose-neutral max-w-none text-gray-600 leading-relaxed font-medium text-sm">
                            {!! nl2br(e($job->requirements)) !!}
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-4 space-y-8">
                    <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm sticky top-32">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 italic">Ready to apply?</h3>
                        <p class="text-gray-500 text-sm mb-8 leading-relaxed font-medium">
                            Join a team of visionaries and builders creating the next generation of HR intelligence.
                        </p>
                        <a href="{{ route('applications.create', $job) }}" class="w-full flex items-center justify-center py-4 bg-black text-white rounded-lg font-bold hover:bg-neutral-900 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 group">
                            Apply Now
                            <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        
                        <div class="mt-8 pt-8 border-t border-gray-100">
                            <div class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 italic">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Posted {{ $job->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4 text-center">
            <p class="text-xs text-gray-500">© 2025 AIHRM. All rights reserved.</p>
            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest">Enterprise Intelligence Layer.</p>
        </div>
    </footer>
</body>
</html>

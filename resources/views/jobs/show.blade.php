<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $job->title }} | Careers | AIHRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f9fafb] flex flex-col min-h-screen text-gray-900">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900">AIHRM</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-gray-600 hover:text-black transition">Careers</a>
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">Log In</a>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-btn" class="text-gray-600 hover:text-gray-900 focus:outline-none p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="menu-icon-bars" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-100 absolute w-full z-50 shadow-xl">
        <div class="px-6 py-5 space-y-2 flex flex-col">
            <a href="{{ route('jobs.index') }}" class="text-gray-600 hover:text-black py-2 font-medium border-b border-gray-50">Careers</a>
            <a href="{{ route('login') }}" class="w-full py-3 bg-blue-600 text-white text-center font-semibold rounded-lg hover:bg-blue-700 mt-4 transition">Log In</a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 max-w-5xl mx-auto px-6 py-12 w-full">
        <!-- Back Button -->
        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center w-10 h-10 bg-indigo-50 text-blue-600 rounded-lg mb-8 hover:bg-indigo-100 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-8 mb-12">
            <div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 tracking-tight">{{ $job->title }}</h1>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-3 text-sm font-medium text-gray-600">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $job->department ?? 'General' }}
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $job->location ?? 'Remote' }}
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $job->job_type ?? 'Full-time' }}
                    </div>
                    <div class="flex items-center gap-2 text-gray-500">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Posted {{ $job->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            
            <a href="{{ route('applications.create', $job) }}" class="px-8 py-3.5 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition shadow-sm text-center shrink-0">
                Apply for this position
            </a>
        </div>

        <!-- 4-Column Stats Strip -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-0 md:divide-x divide-gray-100">
                <div class="md:px-6 first:pl-0 flex flex-col justify-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Salary Range</span>
                    <span class="font-bold text-gray-900 text-lg">
                        @if($job->min_salary && $job->max_salary)
                            {{ \App\Models\Setting::get('currency_symbol', '₦') }}{{ number_format($job->min_salary) }} - {{ \App\Models\Setting::get('currency_symbol', '₦') }}{{ number_format($job->max_salary) }}
                        @else
                            Negotiable
                        @endif
                    </span>
                </div>
                <div class="md:px-6 flex flex-col justify-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Experience</span>
                    <span class="font-bold text-gray-900 text-lg">{{ $job->experience_level ?? 'Any' }}</span>
                </div>
                <div class="md:px-6 flex flex-col justify-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Deadline</span>
                    <span class="font-bold text-gray-900 text-lg">
                        {{ $job->application_deadline ? $job->application_deadline->format('M d, Y') : 'Open' }}
                    </span>
                </div>
                <div class="md:px-6 flex flex-col justify-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Reporting To</span>
                    <span class="font-bold text-gray-900 text-lg">{{ $job->reporting_to ?? 'Hiring Manager' }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-14">
            <!-- Main Details (Left) -->
            <div class="md:col-span-2 space-y-12">
                
                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-5">Role Summary</h2>
                    <div class="prose prose-blue max-w-none text-gray-600 leading-relaxed text-[15px]">
                        {!! nl2br(e($job->description)) !!}
                    </div>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-gray-900 mb-5">Requirements</h2>
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                        @php
                            // Mocking tags from requirements text just for visual flair if possible, else standard tags
                            $stackStr = 'React,Node.js,PostgreSQL,TypeScript';
                            if (stripos($job->requirements, 'php') !== false || stripos($job->requirements, 'laravel') !== false) {
                                $stackStr = 'PHP,Laravel,Vue.js,MySQL';
                            }
                            $stack = explode(',', $stackStr);
                        @endphp
                        @foreach($stack as $tech)
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-full border border-blue-100">{{ $tech }}</span>
                        @endforeach
                    </div>

                    <div class="prose prose-blue max-w-none text-gray-600 leading-relaxed text-[15px]">
                        {!! nl2br(e($job->requirements)) !!}
                    </div>
                </section>

            </div>

            <!-- Sidebar (Right) -->
            <div class="md:col-span-1 space-y-6">
                <!-- About Department -->
                <div class="bg-white p-6 lg:p-8 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-3 text-lg">About the Department</h3>
                    <p class="text-sm text-gray-500 leading-relaxed mb-6">
                        The {{ $job->department ?? 'team' }} at AIHRM is on a mission to automate the complexities of workforce management. We value clean execution, peer learning, and building products that users truly love.
                    </p>
                    <div class="w-full h-32 bg-gray-200 rounded-lg mb-4 overflow-hidden">
                        <img src="{{ asset('images/hero_team.png') }}" class="w-full h-full object-cover" alt="Team Photo">
                    </div>
                    <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-700 w-full text-center block transition-colors">
                        Learn more about {{ $job->department ?? 'us' }}
                    </a>
                </div>

                <!-- Ready to Apply CTA -->
                <div class="bg-blue-600 p-6 lg:p-8 rounded-xl border border-blue-700 shadow-sm text-white">
                    <h3 class="font-bold text-xl mb-3">Ready to apply?</h3>
                    <p class="text-sm text-blue-100 leading-relaxed mb-8">
                        Join our team and help us shape the future of work.
                    </p>
                    <a href="{{ route('applications.create', $job) }}" class="w-full inline-block text-center py-3.5 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-50 transition shadow-sm">
                        Start Application
                    </a>
                </div>
            </div>
        </div>

        @if($similarJobs->count() > 0)
        <!-- Similar Jobs Section -->
        <div class="mt-20 pt-12 border-t border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Similar Jobs</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($similarJobs as $sJob)
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm hover:border-blue-300 hover:shadow-md transition group">
                    <a href="{{ route('jobs.show', $sJob) }}" class="block w-full h-full">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg group-hover:text-blue-600 transition">{{ $sJob->title }}</h3>
                                <div class="text-sm font-medium text-gray-500 mt-1 flex items-center gap-2">
                                    {{ $sJob->department ?? 'General' }} <span class="text-gray-300">•</span> {{ strpos($sJob->location, 'Remote') !== false ? 'Remote' : $sJob->location }}
                                </div>
                            </div>
                            <span class="shrink-0 px-2 py-1 bg-gray-50 text-gray-500 text-[10px] font-bold uppercase tracking-wider rounded border border-gray-100">
                                {{ mb_strtoupper($sJob->created_at->diffForHumans()) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-end mt-8">
                            <div class="font-bold text-gray-900 text-sm">
                                @if($sJob->min_salary && $sJob->max_salary)
                                    {{ \App\Models\Setting::get('currency_symbol', '₦') }}{{ number_format($sJob->min_salary) }} - {{ \App\Models\Setting::get('currency_symbol', '₦') }}{{ number_format($sJob->max_salary) }}
                                @else
                                    Negotiable
                                @endif
                            </div>
                            <svg class="w-5 h-5 text-blue-600 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-white py-10 px-6 mt-auto border-t border-gray-100">
        <div class="max-w-5xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-blue-600 rounded flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-lg font-bold text-gray-900 tracking-tight">AIHRM</span>
            </div>
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} AIHRM Inc. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Mobile Menu Logic
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIconBars = document.getElementById('menu-icon-bars');
            const menuIconClose = document.getElementById('menu-icon-close');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                    menuIconBars.classList.toggle('hidden');
                    menuIconClose.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>

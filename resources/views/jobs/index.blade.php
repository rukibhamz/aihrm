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
                    <a href="{{ route('login') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 pt-24">
        <!-- Hero Section -->
        <div class="bg-black text-white py-16 px-6">
            <div class="max-w-7xl mx-auto text-center">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4 tracking-tight">Join Our Mission</h1>
                <p class="text-gray-400 max-w-2xl mx-auto text-sm lg:text-base mb-8">
                    We're building the future of workforce intelligence. Explore open opportunities and help us redefine the HR experience.
                </p>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 rounded-full border border-white/20">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-300">
                        {{ $jobs->total() }} Open Positions
                    </span>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 pb-20">
            <!-- Filter Section -->
            <div class="mt-[-40px] mb-12">
                <form action="{{ route('jobs.index') }}" method="GET" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 lg:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Job Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Title, keyword..."
                                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 transition text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Organization</label>
                            <select name="department" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 transition text-sm">
                                <option value="">All Categories</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Presence</label>
                            <select name="location" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 transition text-sm">
                                <option value="">All Locations</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-6 py-3 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-700 transition shadow-sm">
                        Search Opportunities
                    </button>
                </form>
            </div>

            <!-- Job Result Grid -->
            @if($jobs->isEmpty())
                <div class="text-center py-20 bg-white rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500 font-medium">No positions found matching your criteria.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($jobs as $job)
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-6">
                                <div class="w-12 h-12 bg-gray-50 rounded-lg flex items-center justify-center border border-gray-100">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="px-2 py-0.5 bg-green-50 text-green-600 text-[10px] font-bold rounded uppercase tracking-widest border border-green-100">
                                    New
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 mb-2 italic">{{ $job->title }}</h3>
                            
                            <div class="flex flex-wrap items-center gap-3 mb-8">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50 px-2 py-1 rounded">
                                    {{ $job->department ?? 'General' }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border border-gray-100 px-2 py-1 rounded italic">
                                    {{ $job->location ?? 'Remote' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    {{ $job->created_at->diffForHumans() }}
                                </span>
                                <a href="{{ route('jobs.show', $job) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-bold flex items-center gap-1 group transition">
                                    View Detail
                                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12">
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

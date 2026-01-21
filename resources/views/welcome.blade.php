<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIHRM - Employee Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('build/assets/app-CklSuxFb.css') }}">
    <script type="module" src="{{ asset('build/assets/app-CJy8ASEk.js') }}"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        .slide-transition {
            transition: opacity 0.7s ease-in-out;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="main-content">
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
                    <a href="{{ route('login') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg font-semibold hover:bg-indigo-700 transition">
                        Login
                    </a>
                </div>
            </div>
        </nav>

        <!-- Hero Slider -->
        <div class="relative flex-1 min-h-[400px] max-h-[60vh] overflow-hidden" x-data="{ 
            currentSlide: 0,
            slides: [
                {
                    image: '{{ asset('images/hero_team.png') }}',
                    title: 'Empowering you, every step of the way.',
                    subtitle: 'Your personal HR hub for leaves, benefits, and growth.'
                },
                {
                    image: '{{ asset('images/hero_growth.png') }}',
                    title: 'Grow with us.',
                    subtitle: 'Explore internal opportunities and track your performance goals.'
                },
                {
                    image: '{{ asset('images/hero_finance.png') }}',
                    title: 'Your Finances, Simplified.',
                    subtitle: 'Access payslips and manage expense claims with ease.'
                }
            ]
        }" x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides.length }, 5000)">
            
            <!-- Slides -->
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="currentSlide === index" 
                     x-transition:enter="slide-transition"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="slide-transition"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0">
                    
                    <!-- Background Image -->
                    <div class="absolute inset-0">
                        <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-b from-black/50 via-black/40 to-black/60"></div>
                    </div>

                    <!-- Content -->
                    <div class="relative h-full flex items-center justify-center px-6 pt-16">
                        <div class="text-center max-w-4xl">
                            <h1 x-text="slide.title" class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight"></h1>
                            <p x-text="slide.subtitle" class="text-base sm:text-lg text-white/90 mb-8 max-w-2xl mx-auto"></p>
                            
                            <div class="flex flex-col gap-3 justify-center items-center">
                                <a href="{{ route('login') }}" class="px-8 py-3 bg-indigo-600 text-white rounded-lg font-semibold text-base hover:bg-indigo-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Login
                                </a>
                                <a href="{{ route('reports.create') }}" class="text-white/80 hover:text-white text-xs font-medium">
                                    Report misconduct? Visit our whistleblowing center
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Slide Indicators -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-2 z-10">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="currentSlide = index" 
                            :class="currentSlide === index ? 'bg-white w-8' : 'bg-white/50 w-2'"
                            class="h-2 rounded-full transition-all duration-300"></button>
                </template>
            </div>
        </div>

        <!-- Quick Access Cards -->
        <div class="max-w-6xl mx-auto px-6 py-6 w-full">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-1">Quick Access</h2>
                <p class="text-gray-600 text-sm">Everything you need, right at your fingertips</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Leave Management</h3>
                    <p class="text-sm text-gray-600">Request time off and track your leave balance</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Payroll & Expenses</h3>
                    <p class="text-sm text-gray-600">View payslips and submit expense claims</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Learning & Development</h3>
                    <p class="text-sm text-gray-600">Access courses and track your progress</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 mt-auto">
            <div class="max-w-7xl mx-auto px-6 py-3 text-center">
                <p class="text-xs text-gray-500">© 2025 AIHRM. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIHRM - Your Workforce, Fully Managed</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white">

    <!-- Navbar -->
    <nav class="border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900">AIHRM</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-medium text-gray-600 hover:text-gray-900">Features</a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-gray-900">Solutions</a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-gray-900">Resources</a>
                <a href="#" class="text-sm font-medium text-gray-600 hover:text-gray-900">Pricing</a>
                <a href="{{ route('login') }}" class="px-5 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700">Log In</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                
                <!-- Left Content -->
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 rounded-full mb-6">
                        <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                        <span class="text-xs font-semibold text-blue-700">Now: AI-Powered Analytics</span>
                    </div>
                    
                    <div id="hero-content" class="min-h-[220px] transition-opacity duration-300">
                        <h1 id="hero-title" class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                            Your Workforce,<br>
                            <span class="text-blue-600">Fully Managed.</span>
                        </h1>
                        
                        <p id="hero-desc" class="text-lg text-gray-600 mb-8 leading-relaxed">
                            Streamline your HR processes with AIHRM. From automated onboarding to complex payroll, manage your entire employee lifecycle in one secure, intuitive platform.
                        </p>
                    </div>
                    
                    <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 mb-10">
                        Access Dashboard
                    </a>
                    
                    <div class="flex items-center gap-2" id="hero-indicators">
                        <button class="h-2 w-4 bg-blue-600 rounded-full transition-all duration-300 indicator-btn" data-index="0" aria-label="Slide 1"></button>
                        <button class="h-2 w-2 bg-gray-300 rounded-full transition-all duration-300 indicator-btn" data-index="1" aria-label="Slide 2"></button>
                        <button class="h-2 w-2 bg-gray-300 rounded-full transition-all duration-300 indicator-btn" data-index="2" aria-label="Slide 3"></button>
                    </div>
                </div>
                
                <!-- Right Mockup -->
                <div>
                    <div class="rounded-2xl bg-gradient-to-br from-teal-400 via-cyan-400 to-blue-400 p-8 shadow-2xl">
                        <div class="bg-white rounded-xl p-6">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <div class="h-3 w-32 bg-gray-200 rounded mb-2"></div>
                                    <div class="h-2 w-48 bg-gray-100 rounded"></div>
                                </div>
                                <div class="w-2 h-2 bg-cyan-500 rounded-full"></div>
                            </div>
                            
                            <!-- List Items -->
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded"></div>
                                    <div class="flex-1">
                                        <div class="h-2 w-3/4 bg-gray-200 rounded mb-1.5"></div>
                                        <div class="h-2 w-1/2 bg-gray-100 rounded"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded"></div>
                                    <div class="flex-1">
                                        <div class="h-2 w-2/3 bg-gray-200 rounded mb-1.5"></div>
                                        <div class="h-2 w-1/3 bg-gray-100 rounded"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded"></div>
                                    <div class="flex-1">
                                        <div class="h-2 w-4/5 bg-gray-200 rounded mb-1.5"></div>
                                        <div class="h-2 w-2/5 bg-gray-100 rounded"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded"></div>
                                    <div class="flex-1">
                                        <div class="h-2 w-3/5 bg-gray-200 rounded mb-1.5"></div>
                                        <div class="h-2 w-2/5 bg-gray-100 rounded"></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded"></div>
                                    <div class="flex-1">
                                        <div class="h-2 w-1/2 bg-gray-200 rounded mb-1.5"></div>
                                        <div class="h-2 w-1/4 bg-gray-100 rounded"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Core Modules Section -->
    <section id="features" class="py-20 px-6 bg-gray-50">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-3">CORE MODULES</p>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Everything you need to manage your team</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Our comprehensive suite of tools ensures your HR department runs smoothly, efficiently, and compliantly.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Card 1 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Employee Management</h3>
                    <p class="text-sm text-gray-600">Centralized database for all employee records, documents, and history.</p>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Payroll Processing</h3>
                    <p class="text-sm text-gray-600">Automated payroll calculations, tax compliance, and direct deposit integration.</p>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Attendance & Leave</h3>
                    <p class="text-sm text-gray-600">Track time, attendance, and manage leave requests effortlessly.</p>
                </div>
                
                <!-- Card 4 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Performance Reviews</h3>
                    <p class="text-sm text-gray-600">360-degree feedback loops and goal tracking for continuous employee growth.</p>
                </div>
                
                <!-- Card 5 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Recruitment</h3>
                    <p class="text-sm text-gray-600">Streamlined hiring pipeline from application tracking to offer letter generation.</p>
                </div>
                
                <!-- Card 6 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Onboarding</h3>
                    <p class="text-sm text-gray-600">Digital onboarding checklists to welcome new hires fast and efficiently.</p>
                </div>
                
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-gray-900 rounded-3xl overflow-hidden">
                <div class="grid lg:grid-cols-2 items-center">
                    
                    <!-- Left Content -->
                    <div class="p-12 lg:p-16">
                        <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                            Ready to transform your HR?
                        </h2>
                        <p class="text-gray-400 text-lg mb-8">
                            Join thousands of companies using AIHRM to build better workplaces. Start your 14-day free trial today.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-white text-gray-900 font-semibold rounded-lg hover:bg-gray-100 text-center">
                                Get Started Now
                            </a>
                            <a href="#features" class="inline-flex items-center justify-center gap-2 text-white font-medium">
                                Learn more →
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right Chart -->
                    <div class="p-12 lg:p-16">
                        <div class="space-y-6">
                            <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full w-3/4 bg-blue-500 rounded-full"></div>
                            </div>
                            <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full w-1/2 bg-blue-400 rounded-full"></div>
                            </div>
                            <div class="flex items-end justify-center gap-3 h-40 pt-8">
                                <div class="w-12 bg-blue-600 rounded-t" style="height: 45%"></div>
                                <div class="w-12 bg-blue-500 rounded-t" style="height: 70%"></div>
                                <div class="w-12 bg-blue-600 rounded-t" style="height: 55%"></div>
                                <div class="w-12 bg-blue-500 rounded-t" style="height: 85%"></div>
                                <div class="w-12 bg-blue-600 rounded-t" style="height: 65%"></div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 px-6 border-t border-gray-100">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-lg font-bold text-gray-900">AIHRM</span>
            </div>
            
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} AIHRM Inc. All rights reserved.
            </p>
            
            <div class="flex items-center gap-5">
                <a href="#" class="text-gray-400 hover:text-blue-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                </a>
                <a href="#" class="text-gray-400 hover:text-blue-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0z"/></svg>
                </a>
            </div>
        </div>
    </footer>

</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const slides = [
            {
                title: 'Your Workforce,<br><span class="text-blue-600">Fully Managed.</span>',
                desc: 'Streamline your HR processes with AIHRM. From automated onboarding to complex payroll, manage your entire employee lifecycle in one secure, intuitive platform.'
            },
            {
                title: 'Data-Driven<br><span class="text-blue-600">HR Decisions.</span>',
                desc: 'Leverage advanced analytics to understand employee performance and improve retention. Make strategic workforce decisions backed by real-time data insights.'
            },
            {
                title: 'Seamless<br><span class="text-blue-600">Global Payroll.</span>',
                desc: 'Process payroll across multiple regions effortlessly. Handle tax compliance, multi-currency payments, and direct deposits with zero manual errors.'
            }
        ];

        let currentSlide = 0;
        const titleEl = document.getElementById('hero-title');
        const descEl = document.getElementById('hero-desc');
        const contentEl = document.getElementById('hero-content');
        const indicators = document.querySelectorAll('.indicator-btn');
        let slideInterval;

        function updateSlide(index) {
            contentEl.style.opacity = '0';
            
            setTimeout(() => {
                currentSlide = index;
                titleEl.innerHTML = slides[index].title;
                descEl.innerHTML = slides[index].desc;
                
                indicators.forEach((ind, i) => {
                    if (i === index) {
                        ind.classList.remove('bg-gray-300', 'w-2');
                        ind.classList.add('bg-blue-600', 'w-4');
                    } else {
                        ind.classList.remove('bg-blue-600', 'w-4');
                        ind.classList.add('bg-gray-300', 'w-2');
                    }
                });
                
                contentEl.style.opacity = '1';
            }, 300);
        }

        function nextSlide() {
            updateSlide((currentSlide + 1) % slides.length);
        }

        indicators.forEach(ind => {
            ind.addEventListener('click', (e) => {
                clearInterval(slideInterval);
                updateSlide(parseInt(e.target.dataset.index));
                startAutoSlide();
            });
        });

        function startAutoSlide() {
            slideInterval = setInterval(nextSlide, 5000);
        }

        startAutoSlide();
    });
</script>
</html>

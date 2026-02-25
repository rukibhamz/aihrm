<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIHRM - Intelligence Layer for Modern Workforce</title>
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
            overflow-x: hidden;
        }

        .mesh-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: 
                radial-gradient(circle at 0% 0%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 100% 0%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 0% 100%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
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

        .hero-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            filter: blur(80px);
            z-index: 1;
            pointer-events: none;
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
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <span class="text-white font-black text-lg">AI</span>
                </div>
                <span class="font-bold text-2xl tracking-tight">AIHRM</span>
            </div>
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-medium text-gray-400 hover:text-white transition">Features</a>
                <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-gray-400 hover:text-white transition">Careers</a>
                <a href="{{ route('login') }}" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold transition shadow-lg shadow-indigo-500/20 transform hover:-translate-y-0.5">
                    Sign In
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative pt-32 pb-20 px-6 overflow-hidden min-h-screen flex flex-col justify-center">
        <div class="hero-glow"></div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass mb-8 animate-fade-in">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-300">The Future of HR Tech is Here</span>
                </div>
                
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-extrabold mb-8 tracking-tighter leading-tight bg-clip-text text-transparent bg-gradient-to-b from-white to-gray-400">
                    Intelligence Layer for <br>
                    <span class="text-gradient">Modern Teams.</span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-400 max-w-3xl mx-auto mb-12 leading-relaxed">
                    A unified workspace designed to streamline workforce management, 
                    accelerate growth, and put people first. Experience HR that feels like magic.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-bold text-lg transition shadow-xl shadow-indigo-500/20 transform hover:-translate-y-1">
                        Get Started Free
                    </a>
                    <a href="{{ route('jobs.index') }}" class="w-full sm:w-auto px-10 py-4 glass text-white rounded-2xl font-bold text-lg hover:bg-white/10 transition transform hover:-translate-y-1">
                        Explore Careers
                    </a>
                </div>
            </div>
        </div>

        <!-- Dashboard Preview / Decorative Element -->
        <div class="mt-20 max-w-5xl mx-auto w-full relative group">
            <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-3xl blur opacity-25 group-hover:opacity-40 transition duration-1000"></div>
            <div class="relative glass rounded-3xl p-4 overflow-hidden aspect-[16/9] flex items-center justify-center">
                <div class="text-center">
                    <div class="w-20 h-20 bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/10">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <p class="text-white/60 font-medium">Interactive Employee Portal Preview</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Quick Access / Features -->
    <section id="features" class="py-24 px-6 relative z-10">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-5xl font-bold mb-4 tracking-tight">Everything you need.</h2>
                <p class="text-gray-400">Integrated tools to empower your workforce at every scale.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="glass glass-hover p-8 rounded-3xl border border-white/5 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-6 border border-emerald-500/20 group-hover:bg-emerald-500/20 transition-colors">
                        <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Leave Management</h3>
                    <p class="text-gray-400 leading-relaxed mb-6">Seamless request flows with automated balances and real-time approval status.</p>
                    <div class="h-1 w-0 group-hover:w-full bg-emerald-500 transition-all duration-500 rounded-full"></div>
                </div>

                <!-- Card 2 -->
                <div class="glass glass-hover p-8 rounded-3xl border border-white/5 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-indigo-500/10 rounded-2xl flex items-center justify-center mb-6 border border-indigo-500/20 group-hover:bg-indigo-500/20 transition-colors">
                        <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Payroll & Finance</h3>
                    <p class="text-gray-400 leading-relaxed mb-6">Automated salary computation, expense tracking, and instant payslip generation.</p>
                    <div class="h-1 w-0 group-hover:w-full bg-indigo-500 transition-all duration-500 rounded-full"></div>
                </div>

                <!-- Card 3 -->
                <div class="glass glass-hover p-8 rounded-3xl border border-white/5 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-purple-500/10 rounded-2xl flex items-center justify-center mb-6 border border-purple-500/20 group-hover:bg-purple-500/20 transition-colors">
                        <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Professional Growth</h3>
                    <p class="text-gray-400 leading-relaxed mb-6">Built-in LMS and performance tracking to foster a culture of continuous learning.</p>
                    <div class="h-1 w-0 group-hover:w-full bg-purple-500 transition-all duration-500 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why AIHRM Section (For Clients) -->
    <section class="py-24 px-6 relative bg-white/5 overflow-hidden">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/2">
                <h2 class="text-4xl md:text-5xl font-extrabold mb-8 tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-500">
                    Built for teams that <br>
                    value their people.
                </h2>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-gray-300 font-medium">90% Reduction in Administrative Overhead</p>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-gray-300 font-medium">Real-time Employee Engagement Analytics</p>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="mt-1 w-6 h-6 rounded-full bg-indigo-500/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-gray-300 font-medium">AI-Driven Talent Management & Matching</p>
                    </div>
                </div>
                <div class="mt-12 flex items-center gap-6">
                    <div>
                        <p class="text-3xl font-black text-white">500+</p>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mt-1">Teams Onboarded</p>
                    </div>
                    <div class="w-px h-12 bg-white/10"></div>
                    <div>
                        <p class="text-3xl font-black text-white">99%</p>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold mt-1">Customer Satisfaction</p>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 w-full">
                <!-- Visual highlight / Stats card -->
                <div class="glass p-1 rounded-3xl relative">
                    <div class="absolute inset-0 bg-indigo-500/10 blur-3xl rounded-full"></div>
                    <div class="relative bg-[#0a0f1d] rounded-3xl p-8 border border-white/10">
                        <div class="flex items-center justify-between mb-8">
                            <h4 class="font-bold text-lg">System Performance</h4>
                            <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 text-xs font-bold rounded-full border border-emerald-500/20">Active</span>
                        </div>
                        <!-- Mock chart / visualization -->
                        <div class="space-y-4">
                            <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full w-[85%] bg-indigo-500 rounded-full shadow-[0_0_15px_rgba(99,102,241,0.5)]"></div>
                            </div>
                            <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full w-[65%] bg-purple-500 rounded-full shadow-[0_0_15px_rgba(168,85,247,0.5)]"></div>
                            </div>
                            <div class="h-2 w-full bg-white/5 rounded-full overflow-hidden">
                                <div class="h-full w-[45%] bg-pink-500 rounded-full shadow-[0_0_15px_rgba(236,72,153,0.5)]"></div>
                            </div>
                        </div>
                        <div class="mt-8 pt-8 border-t border-white/5 grid grid-cols-2 gap-4">
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                                <p class="text-xs text-gray-500 mb-1 font-bold tracking-widest uppercase">Response Time</p>
                                <p class="text-xl font-bold">120ms</p>
                            </div>
                            <div class="bg-white/5 rounded-2xl p-4 border border-white/5">
                                <p class="text-xs text-gray-500 mb-1 font-bold tracking-widest uppercase">Compliance</p>
                                <p class="text-xl font-bold text-emerald-400">SOC-II Ready</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 px-6 border-t border-white/5 bg-black/30">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex items-center gap-3 opacity-60">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-black text-sm">AI</span>
                    </div>
                    <span class="font-bold text-xl tracking-tight">AIHRM</span>
                </div>
                <div class="flex items-center gap-8 text-sm text-gray-500">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                    <a href="{{ route('reports.create') }}" class="hover:text-white transition text-xs text-indigo-400 font-bold tracking-widest uppercase">Whistleblowing</a>
                </div>
                <p class="text-xs text-gray-500">© 2025 AIHRM. Empowering Global Teams.</p>
            </div>
        </div>
    </footer>
</body>
</html>

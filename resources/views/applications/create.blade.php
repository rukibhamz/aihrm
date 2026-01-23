<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for {{ $job->title }} | Careers | AIHRM</title>
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
    <main class="flex-1 pb-20" style="padding-top: 180px;">
        <div class="max-w-4xl mx-auto px-6">
            <div class="mb-12 text-center">
                <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-black transition mb-6 group">
                    <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Job Details
                </a>
                <h1 class="text-4xl font-bold text-gray-900 tracking-tight mb-4 italic">Apply for {{ $job->title }}</h1>
                <p class="text-gray-500 max-w-lg mx-auto leading-relaxed font-medium">Complete the form below to start your application. Our AI will analyze your profile against the role requirements.</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-8 md:p-12">
                <!-- AI Highlight Card -->
                <div class="mb-12 p-6 bg-black rounded-xl flex flex-col md:flex-row items-center gap-6 text-white shadow-lg">
                    <div class="w-16 h-16 bg-white/10 rounded-lg flex items-center justify-center shadow-sm text-white flex-shrink-0">
                        <svg class="w-8 h-8 font-bold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-1 italic">AI-Powered Screening</h3>
                        <p class="text-sm text-neutral-300 leading-relaxed font-medium">Our intelligence system automatically matches your skills and experience with our requirements to fast-track your application.</p>
                    </div>
                </div>

                <form action="{{ route('applications.store', $job) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                    @csrf
                    
                    <!-- Personal Info Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-sm">01</div>
                            <h2 class="text-xl font-bold text-gray-900 italic">Personal Information</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-11">
                            <div class="space-y-2">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400">First Name</label>
                                <input type="text" name="first_name" required 
                                    class="w-full px-5 py-3 border border-gray-200 bg-gray-50 rounded-lg focus:ring-1 focus:ring-black focus:border-black transition text-sm font-medium" 
                                    placeholder="John">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400">Last Name</label>
                                <input type="text" name="last_name" required 
                                    class="w-full px-5 py-3 border border-gray-200 bg-gray-50 rounded-lg focus:ring-1 focus:ring-black focus:border-black transition text-sm font-medium" 
                                    placeholder="Doe">
                            </div>
                            <div class="md:col-span-2 space-y-2">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-gray-400">Email Address</label>
                                <input type="email" name="email" required 
                                    class="w-full px-5 py-3 border border-gray-200 bg-gray-50 rounded-lg focus:ring-1 focus:ring-black focus:border-black transition text-sm font-medium" 
                                    placeholder="john@example.com">
                            </div>
                        </div>
                    </div>

                    <!-- Document Upload Section -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-sm">02</div>
                            <h2 class="text-xl font-bold text-gray-900 italic">Resume / CV</h2>
                        </div>
                        
                        <div class="pl-11">
                            <div class="relative group">
                                <input type="file" name="resume" required accept=".pdf,.doc,.docx" 
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="p-10 border-2 border-dashed border-gray-200 rounded-xl text-center group-hover:border-black group-hover:bg-neutral-50 transition duration-300">
                                    <div class="w-12 h-12 bg-white rounded-lg shadow-sm border border-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-400 group-hover:text-black transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    </div>
                                    <h3 class="text-sm font-bold text-gray-900 mb-1 italic">Upload your resume</h3>
                                    <p class="text-xs text-gray-400 font-medium">PDF, DOC, DOCX (Max 5MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 pl-11 flex flex-col sm:flex-row gap-6 items-center justify-between">
                        <p class="text-xs text-gray-400 max-w-xs font-medium italic">By clicking submit, you agree to our recruitment privacy policy.</p>
                        <button type="submit" class="px-12 py-4 bg-black text-white rounded-lg font-bold text-base hover:bg-neutral-900 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
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

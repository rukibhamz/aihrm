<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for {{ $job->title }} | Careers | AIHRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f9fafb] flex flex-col min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
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
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 max-w-4xl mx-auto px-6 py-12 w-full">
        <!-- Header -->
        <div class="mb-10">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-400 mb-4">
                <a href="{{ route('jobs.index') }}" class="hover:text-gray-600 transition">Job Board</a>
                <span>/</span>
                <a href="{{ route('jobs.show', $job) }}" class="hover:text-gray-600 transition">Job Detail</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Apply</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 tracking-tight mb-3">{{ $job->title }}</h1>
            <div class="flex items-center gap-2 text-sm font-medium text-gray-500">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                {{ $job->department ?? 'General' }} Department
            </div>
        </div>

        <!-- Progress Tracking -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
                <div>
                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest block mb-1">STEP 1 OF 4</span>
                    <h2 class="font-bold text-gray-900">Personal Information</h2>
                </div>
                <span class="text-sm font-semibold text-gray-500">25% Complete</span>
            </div>
            
            <div class="w-full bg-gray-100 rounded-full h-1.5 mb-4">
                <div class="bg-blue-600 h-1.5 rounded-full" style="width: 25%"></div>
            </div>
            
            <div class="flex items-center text-xs font-medium text-gray-400">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                Next: Professional Information
            </div>
        </div>

        <form action="{{ route('applications.store', $job) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            
            <!-- 1. Personal Details -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-1">1. Personal Details</h3>
                <p class="text-sm text-gray-500 mb-6 pb-6 border-b border-gray-50">Tell us who you are and how we can reach you.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700">Full Name</label>
                        <input type="text" name="candidate_name" required 
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm" 
                            placeholder="John Doe">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700">Email Address</label>
                        <input type="email" name="candidate_email" required 
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm" 
                            placeholder="john@example.com">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700">Phone Number</label>
                        <input type="text" name="candidate_phone" 
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm" 
                            placeholder="+1 (555) 000-0000">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700">Current City</label>
                        <input type="text" name="current_city" 
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm" 
                            placeholder="San Francisco, CA">
                    </div>
                </div>
            </div>

            <!-- 2. Professional Background -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-1">2. Professional Background</h3>
                <p class="text-sm text-gray-500 mb-6 pb-6 border-b border-gray-50">Your current status and expectations.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700">Current Job Title</label>
                        <input type="text" name="current_job_title" 
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm" 
                            placeholder="Software Engineer">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700">Years of Experience</label>
                        <select name="years_of_experience" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm bg-white">
                            <option value="">Select experience</option>
                            <option value="0-1">0-1 Years</option>
                            <option value="1-3">1-3 Years</option>
                            <option value="3-5">3-5 Years</option>
                            <option value="5-10">5-10 Years</option>
                            <option value="10+">10+ Years</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700">Expected Annual Salary ({{ \App\Models\Setting::get('currency_symbol', '₦') }})</label>
                        <input type="text" name="expected_salary" 
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm" 
                            placeholder="120,000">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700">Notice Period</label>
                        <input type="text" name="notice_period" 
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm" 
                            placeholder="e.g. 2 weeks, Immediate">
                    </div>
                </div>
            </div>

            <!-- 3. Documents & Cover Letter -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-1">3. Documents & Cover Letter</h3>
                <p class="text-sm text-gray-500 mb-6 pb-6 border-b border-gray-50">Upload your latest resume and introduce yourself.</p>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700">Resume/CV <span class="text-red-500">*</span></label>
                        <div class="relative group mt-1">
                            <input type="file" name="resume" required accept=".pdf,.doc,.docx" 
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="p-8 border border-dashed border-gray-300 rounded-lg text-center bg-gray-50 group-hover:border-blue-400 group-hover:bg-blue-50 transition duration-300">
                                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mx-auto mb-3 text-white shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 mb-1">Click to upload or drag and drop</h4>
                                <p class="text-[11px] text-gray-500 font-medium">PDF, DOCX (Max 5MB)</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700">Cover Letter</label>
                        <textarea name="cover_letter" rows="4" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm" 
                            placeholder="Tell us why you're a great fit for this role..."></textarea>
                    </div>
                </div>
            </div>

            <!-- 4. Additional Information -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-1">4. Additional Information</h3>
                <p class="text-sm text-gray-500 mb-6 pb-6 border-b border-gray-50">Final details to complete your application.</p>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-gray-700">Why do you want this position?</label>
                        <textarea name="motivation" rows="3" 
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm" 
                            placeholder="Share your motivation..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-gray-700">LinkedIn Profile</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </span>
                                <input type="url" name="linkedin_url" 
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm" 
                                    placeholder="linkedin.com/in/username">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-gray-700">Portfolio Website</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-gray-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                </span>
                                <input type="url" name="portfolio_url" 
                                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition text-sm" 
                                    placeholder="https://portfolio.com">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submission Area -->
            <div class="space-y-6 pt-4 pb-12">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" required class="mt-1 w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                    <span class="text-xs text-gray-500 leading-relaxed font-medium">
                        I agree to the <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>. I certify that the information provided is accurate.
                    </span>
                </label>

                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 flex items-center justify-center py-3.5 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 transition shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Submit Application
                    </button>
                    <button type="button" class="sm:px-8 py-3.5 bg-white text-gray-700 font-bold border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        Save for Later
                    </button>
                </div>
                
                <p class="text-center text-[11px] text-gray-400 italic font-medium">
                    Friendly tip: Our team usually responds within 3-5 business days. Good luck!
                </p>
            </div>
            
        </form>
    </main>
</body>
</html>


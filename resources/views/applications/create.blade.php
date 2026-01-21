<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <div class="mb-12 text-center">
            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-700 transition mb-6">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Job Details
            </a>
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-4">Apply for {{ $job->title }}</h1>
            <p class="text-gray-500 max-w-lg mx-auto leading-relaxed">Complete the form below to start your application. Our AI will analyze your profile against the role requirements.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-2xl p-8 md:p-12">
            <!-- AI Highlight Card -->
            <div class="mb-12 p-6 bg-indigo-50 rounded-2xl border border-indigo-100 flex flex-col md:flex-row items-center gap-6">
                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-sm text-indigo-600 flex-shrink-0">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-indigo-900 mb-1">AI-Powered Screening</h3>
                    <p class="text-sm text-indigo-700 leading-relaxed">Our intelligence system automatically matches your skills and experience with our requirements to fast-track your application.</p>
                </div>
            </div>

            <form action="{{ route('applications.store', $job) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf
                
                <!-- Personal Info Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center text-white font-bold text-sm">01</div>
                        <h2 class="text-xl font-bold text-gray-900">Personal Information</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-13">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">First Name</label>
                            <input type="text" name="first_name" required 
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-600 transition text-sm font-medium" 
                                placeholder="John">
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Last Name</label>
                            <input type="text" name="last_name" required 
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-600 transition text-sm font-medium" 
                                placeholder="Doe">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Email Address</label>
                            <input type="email" name="email" required 
                                class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-600 transition text-sm font-medium" 
                                placeholder="john@example.com">
                        </div>
                    </div>
                </div>

                <!-- Document Upload Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-900 rounded-lg flex items-center justify-center text-white font-bold text-sm">02</div>
                        <h2 class="text-xl font-bold text-gray-900">Resume / CV</h2>
                    </div>
                    
                    <div class="pl-13">
                        <div class="relative group">
                            <input type="file" name="resume" required accept=".pdf,.doc,.docx" 
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="p-10 border-2 border-dashed border-gray-200 rounded-[2rem] text-center group-hover:border-indigo-600 group-hover:bg-indigo-50/50 transition duration-300">
                                <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center mx-auto mb-4 text-gray-400 group-hover:text-indigo-600 transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 mb-1">Upload your resume</h3>
                                <p class="text-xs text-gray-500">PDF, DOC, DOCX (Max 5MB)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 pl-13 flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <p class="text-xs text-gray-400 max-w-xs">By clicking apply, you agree to our recruitment privacy policy.</p>
                    <button type="submit" class="px-12 py-5 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition shadow-2xl shadow-indigo-600/30 transform hover:-translate-y-1">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

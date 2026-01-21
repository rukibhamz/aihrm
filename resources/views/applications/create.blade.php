<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apply for {{ $job->title }} | AIHRM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-neutral-50 min-h-screen py-20 px-6 selection:bg-purple-100 selection:text-purple-900">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center gap-2 mb-8 group">
                <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center border border-neutral-200 group-hover:bg-black group-hover:text-white transition-all">
                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                <span class="text-xs font-bold uppercase tracking-widest text-neutral-500 group-hover:text-black transition">Back to Job Post</span>
            </a>
            
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-12 h-12 bg-black rounded-2xl flex items-center justify-center shadow-xl shadow-black/20">
                    <span class="text-white font-black text-sm">AI</span>
                </div>
                <span class="font-extrabold text-3xl tracking-tighter">AIHRM</span>
            </div>
            
            <h1 class="text-4xl font-black tracking-tight mb-2">Apply for {{ $job->title }}</h1>
            <p class="text-neutral-500 font-medium">{{ $job->department }} • {{ $job->location }}</p>
        </div>

        <!-- Application Form -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-black/5 border border-neutral-100 p-10 md:p-14 relative overflow-hidden">
            <!-- Decorative gradient -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-600/5 blur-3xl rounded-full"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-10">
                    <h2 class="text-2xl font-black">Submit Application</h2>
                    <div class="text-xs font-black text-purple-600 uppercase tracking-widest bg-purple-50 px-3 py-1 rounded-full">Step 1 of 1</div>
                </div>
                
                @if($errors->any())
                    <div class="mb-8 p-5 bg-red-50 border border-red-100 rounded-2xl text-sm text-red-800">
                        <div class="font-bold mb-2">Please fix the following:</div>
                        <ul class="list-disc list-inside space-y-1 font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('applications.store', $job) }}" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="col-span-full">
                            <label class="block text-sm font-bold text-neutral-900 mb-3 uppercase tracking-wider">Full Name *</label>
                            <input type="text" name="candidate_name" required value="{{ old('candidate_name') }}"
                                placeholder="e.g. Victor Alexander"
                                class="w-full px-6 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl focus:ring-4 focus:ring-purple-50 focus:border-purple-600 focus:bg-white transition-all font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-neutral-900 mb-3 uppercase tracking-wider">Email Address *</label>
                            <input type="email" name="candidate_email" required value="{{ old('candidate_email') }}"
                                placeholder="hello@company.com"
                                class="w-full px-6 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl focus:ring-4 focus:ring-purple-50 focus:border-purple-600 focus:bg-white transition-all font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-neutral-900 mb-3 uppercase tracking-wider">Phone Number</label>
                            <input type="tel" name="candidate_phone" value="{{ old('candidate_phone') }}"
                                placeholder="+1 (555) 000-0000"
                                class="w-full px-6 py-4 bg-neutral-50 border border-neutral-200 rounded-2xl focus:ring-4 focus:ring-purple-50 focus:border-purple-600 focus:bg-white transition-all font-medium">
                        </div>

                        <div class="col-span-full">
                            <label class="block text-sm font-bold text-neutral-900 mb-3 uppercase tracking-wider">Resume / CV *</label>
                            <div class="relative group">
                                <input type="file" name="resume" required accept=".pdf,.doc,.docx" id="resume-input"
                                    class="hidden">
                                <label for="resume-input" class="flex flex-col items-center justify-center w-full min-h-[160px] border-2 border-dashed border-neutral-200 rounded-[2rem] cursor-pointer bg-neutral-50 group-hover:bg-white group-hover:border-purple-600 transition-all p-8 text-center">
                                    <div class="w-12 h-12 bg-neutral-100 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-purple-600 group-hover:text-white transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    </div>
                                    <p class="text-sm font-bold text-neutral-900 mb-1" id="file-name">Click to upload or drag and drop</p>
                                    <p class="text-xs text-neutral-500 font-medium italic">PDF, DOC, DOCX up to 5MB</p>
                                </label>
                            </div>
                            <!-- AI Badge -->
                            <div class="mt-6 flex items-center gap-4 p-4 bg-purple-900 text-white rounded-2xl shadow-xl shadow-purple-900/10">
                                <div class="w-10 h-10 rounded-xl bg-purple-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                                </div>
                                <p class="text-xs font-bold leading-relaxed">
                                    Powered by WhisperAI: <span class="font-medium text-purple-300">Your profile will be automatically matched based on skills found in your CV.</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full bg-black text-white py-5 rounded-2xl font-black text-lg hover:bg-neutral-800 transition-all transform hover:-translate-y-1 shadow-2xl shadow-black/20 flex items-center justify-center gap-3">
                            Submit Application
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <p class="text-center text-xs font-bold text-neutral-400 mt-12 uppercase tracking-[0.3em]">&copy; {{ date('Y') }} AIHRM Inc.</p>
    </div>

    <script>
        const input = document.getElementById('resume-input');
        const fileName = document.getElementById('file-name');
        
        input.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                fileName.textContent = e.target.files[0].name;
                fileName.classList.add('text-purple-600');
            }
        });
    </script>
</body>
</html>


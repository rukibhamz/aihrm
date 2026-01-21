<x-app-layout>
    <div class="mb-12">
        <a href="{{ route('jobs.index') }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-700 transition mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Careers
        </a>
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-full">
                        {{ $job->department ?? 'General' }}
                    </span>
                    @if($job->location)
                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-black uppercase tracking-widest rounded-full">
                            {{ $job->location }}
                        </span>
                    @endif
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">{{ $job->title }}</h1>
            </div>
            
            <a href="{{ route('applications.create', $job) }}" class="px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/20 transform hover:-translate-y-0.5 text-center">
                Apply for this position
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Main Content -->
        <div class="lg:col-span-8 space-y-12">
            <!-- Description -->
            <div class="bg-white p-8 md:p-12 rounded-[2rem] border border-gray-100 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <div class="w-8 h-1 bg-indigo-600 rounded-full"></div>
                    Role Description
                </h2>
                <div class="prose prose-indigo max-w-none text-gray-600 leading-relaxed">
                    {!! nl2br(e($job->description)) !!}
                </div>
            </div>

            <!-- Requirements -->
            <div class="bg-white p-8 md:p-12 rounded-[2rem] border border-gray-100 shadow-sm">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <div class="w-8 h-1 bg-indigo-600 rounded-full"></div>
                    Requirements
                </h2>
                <div class="prose prose-indigo max-w-none text-gray-600 leading-relaxed">
                    {!! nl2br(e($job->requirements)) !!}
                </div>
            </div>
        </div>

        <!-- Sidebar / Extras -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Apply Card -->
            <div class="bg-indigo-600 rounded-[2rem] p-8 text-white shadow-2xl shadow-indigo-600/20 sticky top-32">
                <h3 class="text-2xl font-bold mb-4">Ready to apply?</h3>
                <p class="text-indigo-100 text-sm mb-8 leading-relaxed">
                    Join a team of visionaries and builders creating the next generation of HR intelligence.
                </p>
                <a href="{{ route('applications.create', $job) }}" class="flex items-center justify-center w-full px-8 py-4 bg-white text-indigo-600 font-bold rounded-xl hover:bg-indigo-50 transition shadow-lg">
                    Apply Now
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                
                <div class="mt-12 pt-8 border-t border-white/10 space-y-4">
                    <div class="flex items-center gap-3 text-xs font-bold uppercase tracking-widest text-indigo-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Posted {{ $job->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

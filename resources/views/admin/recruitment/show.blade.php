<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.applications.kanban') }}" class="text-sm text-neutral-500 hover:text-black transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Recruitment Board
                </a>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">{{ $application->candidate_name }}</h1>
            <p class="text-neutral-500">Applying for: <span class="font-semibold text-neutral-800">{{ $application->jobPosting->title }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 text-sm font-bold rounded-full uppercase tracking-wide
                {{ $application->status === 'hired' ? 'bg-green-100 text-green-800' : 
                   ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                {{ $application->status }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Details -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
                <h2 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Contact Information
                </h2>
                <div class="space-y-4">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Email Address</dt>
                        <dd class="text-neutral-900 font-medium">{{ $application->candidate_email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Phone Number</dt>
                        <dd class="text-neutral-900 font-medium">{{ $application->candidate_phone ?? 'N/A' }}</dd>
                    </div>
                    @if($application->linkedin_url)
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">LinkedIn</dt>
                        <dd><a href="{{ $application->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all font-medium">{{ $application->linkedin_url }}</a></dd>
                    </div>
                    @endif
                    @if($application->portfolio_url)
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Portfolio</dt>
                        <dd><a href="{{ $application->portfolio_url }}" target="_blank" class="text-blue-600 hover:underline text-sm break-all font-medium">{{ $application->portfolio_url }}</a></dd>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6">
                <h2 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Application Summary
                </h2>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Experience</dt>
                            <dd class="text-neutral-900 font-medium">{{ $application->years_of_experience ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Current City</dt>
                            <dd class="text-neutral-900 font-medium">{{ $application->current_city ?? 'N/A' }}</dd>
                        </div>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Current Role</dt>
                        <dd class="text-neutral-900 font-medium">{{ $application->current_job_title ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Expected Salary</dt>
                        <dd class="text-neutral-900 font-medium">{{ $application->expected_salary ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-1">Notice Period</dt>
                        <dd class="text-neutral-900 font-medium">{{ $application->notice_period ?? 'N/A' }}</dd>
                    </div>
                </div>
            </div>

            <div class="bg-black text-white rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-4">Pipeline Status</h2>
                <form action="{{ route('admin.applications.status', $application) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <p class="text-xs text-neutral-400">Move candidate to the next stage in the recruitment process.</p>
                    <div class="space-y-2">
                        @foreach(['applied', 'screening', 'interview', 'offer', 'hired', 'rejected'] as $status)
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-neutral-800 hover:bg-neutral-900 cursor-pointer transition {{ $application->status === $status ? 'bg-neutral-900 border-neutral-700' : '' }}">
                            <input type="radio" name="status" value="{{ $status }}" {{ $application->status === $status ? 'checked' : '' }} class="text-white focus:ring-offset-black">
                            <span class="text-sm font-medium capitalize">{{ $status }}</span>
                        </label>
                        @endforeach
                    </div>
                    <button type="submit" class="w-full bg-white text-black font-bold py-3 rounded-lg hover:bg-neutral-200 transition">Update Status</button>
                </form>
            </div>
        </div>

        <!-- Right Column: Content -->
        <div class="lg:col-span-2 space-y-6">
            {{-- Resume Preview --}}
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
                <div class="bg-neutral-50 px-6 py-4 border-b border-neutral-200 flex justify-between items-center">
                    <h2 class="text-sm font-bold text-neutral-900 uppercase tracking-widest">Resume / CV</h2>
                    <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                        Download Original
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                </div>
                <div class="aspect-[4/5] w-full bg-neutral-100">
                    <iframe src="{{ asset('storage/' . $application->resume_path) }}" class="w-full h-full" frameborder="0"></iframe>
                </div>
            </div>

            {{-- Cover Letter / Motivation --}}
            @if($application->cover_letter || $application->motivation)
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8">
                @if($application->cover_letter)
                <div class="mb-8">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-4">Cover Letter</h2>
                    <div class="prose prose-neutral max-w-none text-neutral-700 leading-relaxed italic">
                        {!! nl2br(e($application->cover_letter)) !!}
                    </div>
                </div>
                @endif

                @if($application->motivation)
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-widest text-neutral-400 mb-4">Motivation / Why Us?</h2>
                    <div class="bg-neutral-50 p-6 rounded-lg text-neutral-700 leading-relaxed border border-neutral-100">
                        {{ $application->motivation }}
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

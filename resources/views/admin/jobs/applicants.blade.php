<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-2">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.jobs.index') }}" class="text-sm text-neutral-500 hover:text-black transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Jobs
                </a>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Applicants for {{ $job->title }}</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage and filter candidates for this position.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.applications.kanban', ['job_posting_id' => $job->id]) }}" class="btn-secondary flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                </svg>
                View Kanban Board
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Applicants</div>
            <div class="text-2xl font-bold text-gray-900">{{ $job->applications->count() }}</div>
        </div>
        <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 shadow-sm">
            <div class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-1">Average AI Score</div>
            <div class="text-2xl font-bold text-blue-700">
                {{ number_format($job->applications->avg('ai_score'), 1) }}
            </div>
        </div>
        <div class="bg-green-50 p-6 rounded-xl border border-green-100 shadow-sm">
            <div class="text-xs font-bold text-green-400 uppercase tracking-widest mb-1">Screening Stage</div>
            <div class="text-2xl font-bold text-green-700">
                {{ $job->applications->where('status', 'screening')->count() }}
            </div>
        </div>
        <div class="bg-purple-50 p-6 rounded-xl border border-purple-100 shadow-sm">
            <div class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-1">Hired</div>
            <div class="text-2xl font-bold text-purple-700">
                {{ $job->applications->where('status', 'hired')->count() }}
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Search & Filters -->
        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
            <form action="{{ route('admin.jobs.applicants', $job) }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, city..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary sm:text-sm">
                </div>
                <div class="flex items-center gap-2">
                    <select name="sort" onchange="this.form.submit()" class="block w-full py-2 pl-3 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Applied</option>
                        <option value="ai_score" {{ request('sort') == 'ai_score' ? 'selected' : '' }}>AI Score (High to Low)</option>
                        <option value="experience" {{ request('sort') == 'experience' ? 'selected' : '' }}>Years of Experience</option>
                    </select>
                    <button type="submit" class="btn-primary px-6">Search</button>
                    @if(request()->anyFilled(['search', 'sort']))
                        <a href="{{ route('admin.jobs.applicants', $job) }}" class="text-sm text-gray-500 hover:text-black">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold border-b border-gray-200">Candidate</th>
                        <th class="px-6 py-4 font-semibold border-b border-gray-200">Location & Role</th>
                        <th class="px-6 py-4 font-semibold border-b border-gray-200 text-center">Experience</th>
                        <th class="px-6 py-4 font-semibold border-b border-gray-200 text-center">AI Score</th>
                        <th class="px-6 py-4 font-semibold border-b border-gray-200">Match Keywords</th>
                        <th class="px-6 py-4 font-semibold border-b border-gray-200">Status</th>
                        <th class="px-6 py-4 font-semibold border-b border-gray-200 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($applications as $application)
                        <tr class="hover:bg-gray-50/80 transition duration-150 group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-500 font-bold border border-neutral-200">
                                        {{ substr($application->candidate_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $application->candidate_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $application->candidate_email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $application->current_job_title ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 flex items-center gap-1 mt-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $application->current_city ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-bold bg-neutral-100 text-neutral-800 border border-neutral-200">
                                    {{ $application->years_of_experience ?? 0 }} yrs
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="relative pt-1 px-4">
                                    <div class="flex mb-2 items-center justify-between">
                                        <div>
                                            <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full {{ $application->ai_score >= 80 ? 'text-green-600 bg-green-200' : ($application->ai_score >= 50 ? 'text-yellow-600 bg-yellow-200' : 'text-red-600 bg-red-200') }}">
                                                {{ $application->ai_score ?? 0 }}%
                                            </span>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden h-1 mb-4 text-xs flex rounded bg-gray-100">
                                        <div style="width:{{ $application->ai_score ?? 0 }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center {{ $application->ai_score >= 80 ? 'bg-green-500' : ($application->ai_score >= 50 ? 'bg-yellow-500' : 'bg-red-500') }}"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        $keywords = [];
                                        $certs = [];
                                        if ($application->resumeAnalysis && isset($application->resumeAnalysis->extracted_data)) {
                                            $extracted = $application->resumeAnalysis->extracted_data;
                                            $keywords = array_slice($extracted['keywords'] ?? [], 0, 4);
                                            $certs = array_slice($extracted['certifications'] ?? [], 0, 2);
                                        }
                                    @endphp
                                    @foreach($keywords as $keyword)
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-medium rounded border border-blue-100">{{ $keyword }}</span>
                                    @endforeach
                                    @foreach($certs as $cert)
                                        <span class="px-2 py-0.5 bg-green-50 text-green-600 text-[10px] font-medium rounded border border-green-100">{{ $cert }}</span>
                                    @endforeach
                                    @if(empty($keywords) && empty($certs))
                                        <span class="text-xs text-gray-400 italic">No analysis data</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-semibold border
                                    {{ $application->status === 'applied' ? 'bg-blue-50 text-blue-700 border-blue-200' : '' }}
                                    {{ $application->status === 'screening' ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : '' }}
                                    {{ $application->status === 'interview' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : '' }}
                                    {{ $application->status === 'offer' ? 'bg-purple-50 text-purple-700 border-purple-200' : '' }}
                                    {{ $application->status === 'hired' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                                    {{ $application->status === 'rejected' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                                ">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.applications.show', $application) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                                    Profile
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No applicants found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($applications->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $applications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>


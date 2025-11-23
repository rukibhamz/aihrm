<x-app-layout>
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Job Postings</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage open positions and track applications</p>
        </div>
        @can('create employees')
        <a href="{{ route('jobs.create') }}" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Post New Job
        </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Jobs Grid -->
    <div class="grid grid-cols-1 gap-6">
        @forelse ($jobs as $job)
        <div class="card p-6 hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-semibold">{{ $job->title }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $job->status === 'open' ? 'bg-green-100 text-green-800' : ($job->status === 'draft' ? 'bg-neutral-100 text-neutral-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-neutral-500">
                        @if($job->department)
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $job->department }}
                        </span>
                        @endif
                        @if($job->location)
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $job->location }}
                        </span>
                        @endif
                        <span>{{ $job->applications_count ?? 0 }} applications</span>
                    </div>
                </div>
                <a href="{{ route('jobs.show', $job) }}" class="btn-secondary text-sm">View Details</a>
            </div>
            <p class="text-sm text-neutral-600 line-clamp-2">{{ Str::limit($job->description, 200) }}</p>
        </div>
        @empty
        <div class="card p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm font-medium text-neutral-900">No job postings yet</p>
            <p class="text-xs text-neutral-500 mt-1">Create your first job posting to start recruiting</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $jobs->links() }}
    </div>
</x-app-layout>

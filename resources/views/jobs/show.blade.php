<x-app-layout>
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('jobs.index') }}" class="text-neutral-600 hover:text-black">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-bold tracking-tight text-neutral-900">{{ $job->title }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $job->status === 'open' ? 'bg-green-100 text-green-800' : ($job->status === 'draft' ? 'bg-neutral-100 text-neutral-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($job->status) }}
                    </span>
                </div>
                <p class="mt-1 text-sm text-neutral-500">{{ $applications->count() }} applications received</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Job Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <h2 class="text-lg font-semibold mb-4">Job Description</h2>
                <p class="text-sm text-neutral-700 whitespace-pre-line">{{ $job->description }}</p>
            </div>

            <div class="card p-6">
                <h2 class="text-lg font-semibold mb-4">Requirements</h2>
                <p class="text-sm text-neutral-700 whitespace-pre-line">{{ $job->requirements }}</p>
            </div>

            <!-- Applications List -->
            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">Applications</h2>
                    <span class="text-sm text-neutral-500">Sorted by AI Score</span>
                </div>

                <div class="space-y-3">
                    @forelse ($applications as $app)
                    <div class="border border-neutral-200 rounded-lg p-4 hover:border-neutral-300 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-medium">{{ $app->candidate_name }}</h3>
                                <p class="text-sm text-neutral-500">{{ $app->candidate_email }}</p>
                                @if($app->resumeAnalysis)
                                <div class="mt-2 text-xs text-neutral-600">
                                    <p><strong>Strengths:</strong> {{ Str::limit($app->resumeAnalysis->strengths, 100) }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="text-right ml-4">
                                @if($app->ai_score)
                                <div class="text-2xl font-bold {{ $app->ai_score >= 80 ? 'text-green-600' : ($app->ai_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $app->ai_score }}
                                </div>
                                <div class="text-xs text-neutral-500">AI Score</div>
                                @else
                                <div class="text-sm text-neutral-400">Screening...</div>
                                @endif
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                        {{ $app->status === 'shortlisted' ? 'bg-green-100 text-green-800' : ($app->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-neutral-100 text-neutral-800') }}">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-neutral-400">
                        <p class="text-sm">No applications yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="font-semibold mb-4">Job Details</h3>
                <div class="space-y-3 text-sm">
                    @if($job->department)
                    <div>
                        <div class="text-neutral-500">Department</div>
                        <div class="font-medium">{{ $job->department }}</div>
                    </div>
                    @endif
                    @if($job->location)
                    <div>
                        <div class="text-neutral-500">Location</div>
                        <div class="font-medium">{{ $job->location }}</div>
                    </div>
                    @endif
                    <div>
                        <div class="text-neutral-500">Posted</div>
                        <div class="font-medium">{{ $job->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('jobs.edit', $job) }}" class="block w-full btn-secondary text-center">Edit Job</a>
                    <a href="{{ route('applications.create', $job) }}" target="_blank" class="block w-full btn-secondary text-center">View Application Form</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

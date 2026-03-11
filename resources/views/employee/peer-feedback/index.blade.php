<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">360-Degree Feedback</h1>
            <p class="mt-1 text-sm text-neutral-500">Request feedback from peers and complete reviews requested of you.</p>
        </div>
        <div class="flex gap-3">
            <button type="button" class="btn-primary" x-data="" x-on:click.prevent="$dispatch('open-modal', 'request-feedback')">
                Request Feedback
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Pending Reviews To Complete -->
        <div>
            <h2 class="text-xl font-semibold tracking-tight text-neutral-900 mb-4">Pending Requests For You</h2>
            <div class="card overflow-hidden">
                <ul class="divide-y divide-neutral-200">
                    @forelse($pendingReviews as $request)
                        <li class="p-6 hover:bg-neutral-50/50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-neutral-900">{{ $request->requester->name }}</h3>
                                    <p class="text-sm text-neutral-500 mt-1">Due by <span class="font-medium text-neutral-700">{{ $request->due_date->format('M d, Y') }}</span></p>
                                    @if($request->context)
                                        <p class="text-sm text-neutral-600 mt-2 p-3 bg-blue-50/50 rounded-md border border-blue-100 italic">"{{ Str::limit($request->context, 100) }}"</p>
                                    @endif
                                </div>
                                <a href="{{ route('employee.peer-feedback.show', $request) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Provide Feedback
                                </a>
                            </div>
                        </li>
                    @empty
                        <li class="p-8 text-center text-neutral-500">
                            <svg class="mx-auto h-12 w-12 text-neutral-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm">You have no pending feedback requests.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Requests Sent By ME -->
        <div>
            <h2 class="text-xl font-semibold tracking-tight text-neutral-900 mb-4">Requests You Have Sent</h2>
            <div class="card overflow-hidden">
                <ul class="divide-y divide-neutral-200">
                    @forelse($sentRequests as $request)
                        <li class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-neutral-100 flex items-center justify-center text-neutral-500 font-bold text-sm border border-neutral-200">
                                        {{ collect(explode(' ', $request->targetUser->name))->map(fn($s) => substr($s,0,1))->take(2)->join('') }}
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-sm font-medium text-neutral-900">{{ $request->targetUser->name }}</h3>
                                        <p class="text-xs text-neutral-500 mt-0.5">Requested on {{ $request->created_at->format('M d') }}</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                                        {{ $request->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                            </div>
                            @if($request->status === 'completed' && $request->responses->isNotEmpty())
                                <div class="mt-4 pt-4 border-t border-neutral-100 flex gap-4 text-sm">
                                    <div class="flex-1">
                                        <span class="text-neutral-500 text-xs uppercase tracking-wider font-semibold block mb-1">Collaboration</span>
                                        <div class="flex items-center">
                                            <span class="font-medium mr-1">{{ $request->responses->first()->collaboration_rating }}/5</span>
                                            <div class="flex text-yellow-400">
                                                @for($i=0; $i<$request->responses->first()->collaboration_rating; $i++)
                                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-neutral-500 text-xs uppercase tracking-wider font-semibold block mb-1">Communication</span>
                                        <div class="flex items-center">
                                            <span class="font-medium mr-1">{{ $request->responses->first()->communication_rating }}/5</span>
                                            <div class="flex text-yellow-400">
                                                @for($i=0; $i<$request->responses->first()->communication_rating; $i++)
                                                    <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ route('employee.peer-feedback.show', $request) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">View Full Details &rarr;</a>
                                    </div>
                                </div>
                            @endif
                        </li>
                    @empty
                        <li class="p-8 text-center text-neutral-500">
                            <p class="text-sm">You haven't requested any feedback from peers yet.</p>
                            <button type="button" class="mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors" x-data="" x-on:click.prevent="$dispatch('open-modal', 'request-feedback')">
                                Request Feedback Now
                            </button>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Request Feedback Modal -->
    <x-modal name="request-feedback" :show="false" maxWidth="lg">
        <form method="POST" action="{{ route('employee.peer-feedback.store') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-neutral-900 mb-6">
                Request Peer Feedback
            </h2>

            <div class="mb-4">
                <x-form.label for="target_user_id" value="Select Peer" required />
                <select id="target_user_id" name="target_user_id" class="form-input mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                    <option value="" disabled selected>Select a colleague...</option>
                    @foreach($peers as $peer)
                        <option value="{{ $peer->id }}">
                            {{ $peer->name }} ({{ $peer->employee->designation->title ?? 'Employee' }})
                        </option>
                    @endforeach
                </select>
                <x-form.error for="target_user_id" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-form.label for="context" value="Context / Focus Area" />
                <textarea id="context" name="context" rows="3" class="form-input mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="e.g. 'I would love some feedback on how I handled the Q3 presentation.' (Optional)"></textarea>
                <x-form.error for="context" class="mt-2" />
            </div>

            <div class="mb-6">
                <x-form.label for="due_date" value="Due Date" required />
                <x-form.input id="due_date" name="due_date" type="date" min="{{ now()->format('Y-m-d') }}" value="{{ now()->addDays(7)->format('Y-m-d') }}" class="mt-1 block w-full" required />
                <x-form.error for="due_date" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancel
                </x-secondary-button>
                <x-primary-button>
                    Send Request
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>

<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">My Checklist</h1>
        <p class="mt-1 text-sm text-neutral-500">Complete your assigned tasks</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Onboarding Section -->
        <div>
            <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                Onboarding
            </h2>
            <div class="space-y-4">
                @forelse($tasks['onboarding'] ?? [] as $task)
                <div class="card p-4 flex items-start gap-4 {{ $task->status === 'completed' ? 'opacity-60 bg-neutral-50' : '' }}">
                    <form action="{{ route('onboarding.complete', $task) }}" method="POST">
                        @csrf
                        <button type="submit" {{ $task->status === 'completed' ? 'disabled' : '' }} 
                            class="mt-1 w-5 h-5 rounded border {{ $task->status === 'completed' ? 'bg-green-500 border-green-500 text-white' : 'border-neutral-300 hover:border-black' }} flex items-center justify-center transition-colors">
                            @if($task->status === 'completed')
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </button>
                    </form>
                    <div>
                        <h3 class="font-medium text-neutral-900 {{ $task->status === 'completed' ? 'line-through text-neutral-500' : '' }}">{{ $task->task->title }}</h3>
                        @if($task->task->description)
                            <p class="text-sm text-neutral-500 mt-1">{{ $task->task->description }}</p>
                        @endif
                        @if($task->status === 'completed')
                            <p class="text-xs text-green-600 mt-2">Completed {{ $task->completed_at->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-sm text-neutral-400 italic">No onboarding tasks assigned.</div>
                @endforelse
            </div>
        </div>

        <!-- Offboarding Section -->
        <div>
            <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                Offboarding
            </h2>
            <div class="space-y-4">
                @forelse($tasks['offboarding'] ?? [] as $task)
                <div class="card p-4 flex items-start gap-4 {{ $task->status === 'completed' ? 'opacity-60 bg-neutral-50' : '' }}">
                    <form action="{{ route('onboarding.complete', $task) }}" method="POST">
                        @csrf
                        <button type="submit" {{ $task->status === 'completed' ? 'disabled' : '' }} 
                            class="mt-1 w-5 h-5 rounded border {{ $task->status === 'completed' ? 'bg-green-500 border-green-500 text-white' : 'border-neutral-300 hover:border-black' }} flex items-center justify-center transition-colors">
                            @if($task->status === 'completed')
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            @endif
                        </button>
                    </form>
                    <div>
                        <h3 class="font-medium text-neutral-900 {{ $task->status === 'completed' ? 'line-through text-neutral-500' : '' }}">{{ $task->task->title }}</h3>
                        @if($task->task->description)
                            <p class="text-sm text-neutral-500 mt-1">{{ $task->task->description }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-sm text-neutral-400 italic">No offboarding tasks assigned.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

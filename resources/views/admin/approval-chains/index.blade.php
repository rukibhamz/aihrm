<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Approval Workflows</h1>
            <p class="mt-1 text-sm text-neutral-500">Configure multi-level approval chains for company requests</p>
        </div>
        <a href="{{ route('admin.approval-chains.create') }}" class="btn-primary">
            Create New Chain
        </a>
    </div>

    <x-flash-messages
        successClass="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm"
        errorClass="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm"
    />

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($chains as $chain)
            <div class="card p-6 flex flex-col h-full bg-white hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                        {{ $chain->module_type }}
                    </span>
                    <form action="{{ route('admin.approval-chains.destroy', $chain) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this approval chain?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-neutral-400 hover:text-red-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
                
                <h3 class="text-xl font-bold text-neutral-900 mb-2">{{ $chain->name }}</h3>
                <p class="text-sm text-neutral-500 mb-6 flex-grow">{{ $chain->description ?? 'No description provided.' }}</p>
                
                <div class="flex items-center justify-between mt-auto pt-4 border-t border-neutral-100">
                    <span class="text-sm font-medium text-neutral-700">
                        {{ $chain->steps_count }} Levels
                    </span>
                    <a href="{{ route('admin.approval-chains.show', $chain) }}" class="text-sm font-semibold text-black hover:underline">
                        Configure Steps &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full card p-12 text-center">
                <div class="mx-auto w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-neutral-900">No Approval Workflows</h3>
                <p class="text-neutral-500 mb-6">Create your first approval chain to start automating requests.</p>
                <a href="{{ route('admin.approval-chains.create') }}" class="btn-primary">Create New Chain</a>
            </div>
        @endforelse
    </div>
</x-app-layout>

<x-app-layout>
    <div class="mb-8">
        <a href="{{ route('admin.approval-chains.index') }}" class="text-sm font-medium text-neutral-500 hover:text-black mb-4 inline-block">&larr; Back to Workflows</a>
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Create Approval Chain</h1>
        <p class="mt-1 text-sm text-neutral-500">Define which module this approval chain applies to</p>
    </div>

    <div class="max-w-2xl">
        <div class="card p-8">
            <form action="{{ route('admin.approval-chains.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Chain Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Standard Leave Approval"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-sm">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Module Type *</label>
                    <select name="module_type" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-sm">
                        <option value="">Select a module...</option>
                        @foreach($modules as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-neutral-500 italic">Note: Only one active chain can exist per module type.</p>
                    @error('module_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Description</label>
                    <textarea name="description" rows="3" placeholder="Explain the purpose of this chain..."
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-primary transition text-sm"></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full btn-primary py-3">Create Chain & Continue to Steps</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


<x-app-layout>
    <div class="mb-8">
        <a href="{{ route('admin.offboarding.index') }}" class="mb-3 inline-flex items-center gap-1 text-sm text-neutral-500 hover:text-neutral-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Offboarding Templates
        </a>
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Edit Offboarding Template</h1>
        <p class="mt-1 text-sm text-neutral-500">Update this template used for offboarding workflows.</p>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl">
        <div class="card p-6">
            <form method="POST" action="{{ route('admin.offboarding.update', $offboarding) }}">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <div>
                        <x-form.label for="title" value="Task Title" required />
                        <x-form.input
                            id="title"
                            name="title"
                            type="text"
                            required
                            class="mt-1"
                            value="{{ old('title', $offboarding->title) }}"
                        />
                        <x-form.error for="title" />
                    </div>

                    <div>
                        <x-form.label for="description" value="Description / Instructions" />
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="mt-1 form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        >{{ old('description', $offboarding->description) }}</textarea>
                        <x-form.error for="description" />
                    </div>

                    <div>
                        <input type="hidden" name="is_active" value="0">
                        <label class="inline-flex cursor-pointer items-center gap-2">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                class="h-4 w-4 rounded border-neutral-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                {{ old('is_active', $offboarding->is_active) ? 'checked' : '' }}
                            >
                            <span class="text-sm font-medium text-neutral-700">Active Template</span>
                        </label>
                        <p class="mt-1 text-xs text-neutral-500">Inactive templates are hidden from future offboarding task generation.</p>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end border-t border-neutral-200 pt-5">
                    <div class="flex gap-3">
                        <a href="{{ route('admin.offboarding.index') }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>

            <div class="mt-4 border-t border-neutral-100 pt-4">
                <form method="POST" action="{{ route('admin.offboarding.destroy', $offboarding) }}" onsubmit="return confirm('Delete this offboarding template?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800">Delete Template</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

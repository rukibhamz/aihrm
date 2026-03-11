<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.performance.objectives.index') }}" class="text-neutral-500 hover:text-neutral-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Create Company Objective</h1>
            </div>
            <p class="text-sm text-neutral-500 ml-8">Define a high-level goal for the organization.</p>
        </div>
    </div>

    <div class="max-w-4xl">
        <div class="card p-6">
            <form action="{{ route('admin.performance.objectives.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <x-form.label for="title" value="Objective Title" required />
                    <x-form.input id="title" name="title" type="text" value="{{ old('title') }}" required placeholder="e.g. Expand into European Markets" class="text-lg py-2" />
                    <x-form.error for="title" />
                </div>

                <div class="mb-6">
                    <x-form.label for="description" value="Description" />
                    <textarea id="description" name="description" rows="4" class="form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Provide context, key results needed, and why this matters...">{{ old('description') }}</textarea>
                    <x-form.error for="description" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pt-4 border-t border-neutral-100">
                    <div>
                        <x-form.label for="start_date" value="Start Date" required />
                        <x-form.input id="start_date" name="start_date" type="date" value="{{ old('start_date', now()->startOfQuarter()->format('Y-m-d')) }}" required />
                        <x-form.error for="start_date" />
                    </div>

                    <div>
                        <x-form.label for="end_date" value="End Date" required />
                        <x-form.input id="end_date" name="end_date" type="date" value="{{ old('end_date', now()->endOfQuarter()->format('Y-m-d')) }}" required />
                        <x-form.error for="end_date" />
                    </div>

                    <div>
                        <x-form.label for="status" value="Status" required />
                        <select id="status" name="status" class="form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        </select>
                        <x-form.error for="status" />
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-neutral-200 mt-8">
                    <a href="{{ route('admin.performance.objectives.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Create Objective</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

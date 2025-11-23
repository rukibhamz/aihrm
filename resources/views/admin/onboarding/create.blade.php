<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Create Task Template</h1>
        <p class="mt-1 text-sm text-neutral-500">Add a new task to the lifecycle checklist</p>
    </div>

    <div class="card p-8 max-w-xl">
        <form method="POST" action="{{ route('admin.onboarding.store') }}" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Task Title *</label>
                <input type="text" name="title" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Stage *</label>
                    <select name="stage" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                        <option value="onboarding">Onboarding</option>
                        <option value="offboarding">Offboarding</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Department</label>
                    <select name="department_id" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                        <option value="">All Departments</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-6 border-t border-neutral-200">
                <button type="submit" class="btn-primary w-full justify-center">Create Template</button>
            </div>
        </form>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Edit Job Posting</h1>
        <p class="mt-1 text-sm text-neutral-500">Update job details and status</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-4xl mx-auto">
        <form method="POST" action="{{ route('admin.jobs.update', $job) }}" class="card p-8 space-y-6 shadow-sm border border-neutral-100">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Job Title *</label>
                <input type="text" name="title" required value="{{ old('title', $job->title) }}" 
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"
                    placeholder="e.g., Senior Software Engineer">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Department</label>
                    <input type="text" name="department" value="{{ old('department', $job->department) }}"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"
                        placeholder="e.g., Engineering">
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Location</label>
                    <input type="text" name="location" value="{{ old('location', $job->location) }}"
                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"
                        placeholder="e.g., Lagos, Nigeria">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Job Description *</label>
                <textarea name="description" required rows="6"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"
                    placeholder="Describe the role, responsibilities...">{{ old('description', $job->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Requirements *</label>
                <textarea name="requirements" required rows="6"
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"
                    placeholder="List required skills, experience, education...">{{ old('requirements', $job->requirements) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Status *</label>
                <select name="status" required
                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    <option value="draft" {{ $job->status === 'draft' ? 'selected' : '' }}>Draft (not visible to candidates)</option>
                    <option value="open" {{ $job->status === 'open' ? 'selected' : '' }}>Open (accepting applications)</option>
                    <option value="closed" {{ $job->status === 'closed' ? 'selected' : '' }}>Closed (not accepting applications)</option>
                </select>
            </div>

            <div class="flex gap-3 pt-6 border-t border-neutral-200">
                <button type="submit" class="btn-primary">Update Job Posting</button>
                <a href="{{ route('jobs.show', $job) }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>

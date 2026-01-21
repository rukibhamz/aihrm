<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Announcement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.announcements.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                            <textarea name="content" id="content" rows="6" required
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Target Audience</label>
                            <select name="type" id="type" x-data="{ type: '{{ old('type', 'all') }}' }" x-model="type"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="all">All Employees</option>
                                <option value="department">Specific Department</option>
                            </select>
                        </div>

                        <div class="mb-4" x-data="{ showDept: '{{ old('type', 'all') }}' === 'department' }">
                            <template x-if="$el.previousElementSibling.querySelector('select').value === 'department'">
                                <div>
                                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                    <select name="department_id" id="department_id"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </template>
                        </div>

                        <div x-data="{ type: 'all' }" class="mb-4" id="department-selector" style="display: none;">
                            <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                            <select name="department_id" id="department_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <script>
                            document.getElementById('type').addEventListener('change', function() {
                                document.getElementById('department-selector').style.display = this.value === 'department' ? 'block' : 'none';
                            });
                            // Initial state
                            if(document.getElementById('type').value === 'department') {
                                document.getElementById('department-selector').style.display = 'block';
                            }
                        </script>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="pinned" value="1" {{ old('pinned') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Pin this announcement (appears at top)</span>
                            </label>
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="publish_now" value="1" checked
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Publish immediately</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Uncheck to save as a draft.</p>
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('admin.announcements.index') }}" class="text-gray-600 hover:text-gray-900">Cancel</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Create Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

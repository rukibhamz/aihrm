<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Offboarding Templates</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage standard tasks required for employee offboarding and clearance.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="document.getElementById('createTaskModal').showModal()" class="btn-primary">
                + Add Task Template
            </button>
        </div>
    </div>

    <x-flash-messages
        successClass="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm"
        errorClass="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm"
    />
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card overflow-hidden">
        <x-table>
            <x-slot name="head">
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider w-1/3">Task Title</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider w-1/3">Description</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
            </x-slot>

            <x-slot name="body">
                @forelse($tasks as $task)
                    <tr class="hover:bg-neutral-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-neutral-900 mb-1">{{ $task->title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-neutral-500 line-clamp-2">{{ $task->description ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($task->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <button onclick='editTask(@json($task))' class="text-blue-600 hover:text-blue-900 transition-colors">Edit</button>
                                <form action="{{ route('admin.offboarding.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this template? It will not affect past resignations.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-neutral-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-blue-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <p class="text-lg font-medium text-neutral-900">No Offboarding Tasks</p>
                                <p class="text-sm mt-1 mb-4 max-w-md">Create standard tasks (e.g., "Return company laptop", "Revoke access rights") to ensure a smooth offboarding process when an employee resigns.</p>
                                <button onclick="document.getElementById('createTaskModal').showModal()" class="btn-primary">Add First Task</button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table>
    </div>

    <!-- Create Modal -->
    <dialog id="createTaskModal" class="p-0 rounded-xl shadow-2xl backdrop:bg-black/40 w-full max-w-lg open:animate-fade-in-up">
        <div class="bg-white">
            <div class="px-6 py-4 border-b border-neutral-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-lg font-bold">New Offboarding Task</h3>
                <button onclick="document.getElementById('createTaskModal').close()" class="text-neutral-400 hover:text-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.offboarding.store') }}" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <x-form.label for="title" value="Task Title" required />
                        <x-form.input id="title" name="title" type="text" required class="mt-1" placeholder="e.g. Handover company ID badge" />
                    </div>
                    <div>
                        <x-form.label for="description" value="Description / Instructions" />
                        <textarea id="description" name="description" rows="3" class="mt-1 form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Optional details for clearing this task..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-neutral-100">
                    <button type="button" onclick="document.getElementById('createTaskModal').close()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Create Task</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Update Modal -->
    <dialog id="updateTaskModal" class="p-0 rounded-xl shadow-2xl backdrop:bg-black/40 w-full max-w-lg open:animate-fade-in-up">
        <div class="bg-white">
            <div class="px-6 py-4 border-b border-neutral-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-lg font-bold">Edit Offboarding Task</h3>
                <button onclick="document.getElementById('updateTaskModal').close()" class="text-neutral-400 hover:text-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" id="updateTaskForm" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <x-form.label for="edit_title" value="Task Title" required />
                        <x-form.input id="edit_title" name="title" type="text" required class="mt-1" />
                    </div>
                    <div>
                        <x-form.label for="edit_description" value="Description / Instructions" />
                        <textarea id="edit_description" name="description" rows="3" class="mt-1 form-input block w-full rounded-md border-neutral-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                    </div>
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer mt-2">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded border-neutral-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-5 w-5">
                            <span class="text-sm font-medium text-neutral-700">Active Task</span>
                        </label>
                        <p class="text-xs text-neutral-500 mt-1 ml-7">Inactive tasks will not be automatically generated for new resignations.</p>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-neutral-100">
                    <button type="button" onclick="document.getElementById('updateTaskModal').close()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Update Task</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function editTask(task) {
            document.getElementById('updateTaskForm').action = `{{ url('/admin/offboarding') }}/${task.id}`;
            document.getElementById('edit_title').value = task.title;
            document.getElementById('edit_description').value = task.description || '';
            document.getElementById('edit_is_active').checked = task.is_active;
            
            document.getElementById('updateTaskModal').showModal();
        }
    </script>
</x-app-layout>

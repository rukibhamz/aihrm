<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Employment Statuses</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage employment status types for the organization</p>
        </div>
        <button x-data="" @click="$dispatch('open-modal', 'create-employment-status')" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Status
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="card overflow-x-auto">
        <table class="w-full text-left text-sm text-neutral-600">
            <thead class="bg-neutral-50 border-b border-neutral-200 font-medium text-neutral-900">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Description</th>
                    <th class="px-6 py-4">Employees Count</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($statuses as $status)
                <tr class="hover:bg-neutral-50 transition">
                    <td class="px-6 py-4 font-medium text-neutral-900">{{ $status->name }}</td>
                    <td class="px-6 py-4 text-neutral-500">{{ $status->description ?? 'No description' }}</td>
                    <td class="px-6 py-4">{{ $status->employees_count }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button x-data="" @click="$dispatch('open-modal', 'edit-employment-status-{{ $status->id }}')" class="text-neutral-600 hover:text-black transition">Edit</button>
                            <form action="{{ route('admin.employment-statuses.destroy', $status) }}" method="POST" onsubmit="return confirm('Are you sure? This cannot be undone.');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition">Delete</button>
                            </form>
                        </div>

                        <!-- Edit Modal -->
                        <x-modal name="edit-employment-status-{{ $status->id }}" focusable maxWidth="md">
                            <form method="POST" action="{{ route('admin.employment-statuses.update', $status) }}">
                                @csrf
                                @method('PUT')
                                
                                <!-- Modal Header -->
                                <div class="px-6 pt-6 pb-4">
                                    <h2 class="text-xl font-semibold text-neutral-900">Edit Employment Status</h2>
                                </div>

                                <!-- Modal Body -->
                                <div class="px-6 py-4 space-y-4 text-left">
                                    <div>
                                        <label for="edit-name-{{ $status->id }}" class="block text-sm font-medium text-neutral-700 mb-1.5">Name</label>
                                        <input type="text" id="edit-name-{{ $status->id }}" name="name" value="{{ $status->name }}" required class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="edit-desc-{{ $status->id }}" class="block text-sm font-medium text-neutral-700 mb-1.5">Description</label>
                                        <textarea id="edit-desc-{{ $status->id }}" name="description" rows="3" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">{{ $status->description }}</textarea>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="px-6 py-4 flex justify-end gap-2 border-t border-neutral-100">
                                    <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 transition-colors">CANCEL</button>
                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-900 rounded-md hover:bg-neutral-800 transition-all">UPDATE</button>
                                </div>
                            </form>
                        </x-modal>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-neutral-500">No employment statuses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <x-modal name="create-employment-status" focusable maxWidth="md">
        <form method="POST" action="{{ route('admin.employment-statuses.store') }}">
            @csrf
            
            <!-- Modal Header -->
            <div class="px-6 pt-6 pb-4">
                <h2 class="text-xl font-semibold text-neutral-900">Add Employment Status</h2>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700 mb-1.5">Name</label>
                    <input type="text" id="name" name="name" required placeholder="e.g. Intern, Part-time" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-neutral-700 mb-1.5">Description (Optional)</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 flex justify-end gap-2 border-t border-neutral-100">
                <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 transition-colors">CANCEL</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-900 rounded-md hover:bg-neutral-800 transition-all">CREATE</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>

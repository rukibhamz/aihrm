<x-app-layout>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Departments</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage company departments</p>
        </div>
        <button onclick="document.getElementById('createModal').showModal()" class="btn-primary">
            Add Department
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Employees</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse($departments as $dept)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">{{ $dept->name }}</td>
                    <td class="px-6 py-4 text-sm text-neutral-500">{{ Str::limit($dept->description, 50) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                            {{ $dept->employees_count }} employees
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="editDepartment({{ $dept->id }}, '{{ $dept->name }}', '{{ $dept->description }}')" class="text-neutral-600 hover:text-black mr-3">Edit</button>
                        <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-neutral-500">No departments found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $departments->links() }}
    </div>

    <!-- Create Modal -->
    <dialog id="createModal" class="p-0 rounded-lg shadow-xl backdrop:bg-black/50 w-full max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-bold mb-4">Add Department</h3>
            <form method="POST" action="{{ route('admin.departments.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Name *</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('createModal').close()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Create</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Edit Modal -->
    <dialog id="editModal" class="p-0 rounded-lg shadow-xl backdrop:bg-black/50 w-full max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-bold mb-4">Edit Department</h3>
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Name *</label>
                        <input type="text" name="name" id="editName" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Description</label>
                        <textarea name="description" id="editDescription" rows="3" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('editModal').close()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Update</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function editDepartment(id, name, description) {
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editForm').action = `/admin/departments/${id}`;
            document.getElementById('editModal').showModal();
        }
    </script>
</x-app-layout>

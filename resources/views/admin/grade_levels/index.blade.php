<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Grade Levels</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage employee grade levels and salary ranges</p>
        </div>
        <button x-data="" @click="$dispatch('open-modal', 'create-grade-level')" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Grade Level
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="card overflow-hidden">
        <table class="w-full text-left text-sm text-neutral-600">
            <thead class="bg-neutral-50 border-b border-neutral-200 font-medium text-neutral-900">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Salary Range</th>
                    <th class="px-6 py-4">Employees</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($gradeLevels as $gradeLevel)
                    <tr class="hover:bg-neutral-50 transition">
                        <td class="px-6 py-4 font-medium text-neutral-900">{{ $gradeLevel->name }}</td>
                        <td class="px-6 py-4">{{ $gradeLevel->basic_salary_range ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $gradeLevel->employees_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button x-data="" @click="$dispatch('open-modal', 'edit-grade-level-{{ $gradeLevel->id }}')" class="text-neutral-600 hover:text-black transition">Edit</button>
                                <form action="{{ route('admin.grade-levels.destroy', $gradeLevel) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition">Delete</button>
                                </form>
                            </div>

                            <!-- Edit Modal -->
                            <x-modal name="edit-grade-level-{{ $gradeLevel->id }}" focusable maxWidth="md">
                                <form method="POST" action="{{ route('admin.grade-levels.update', $gradeLevel) }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <!-- Modal Header -->
                                    <div class="px-6 pt-6 pb-4">
                                        <h2 class="text-xl font-semibold text-neutral-900">Edit Grade Level</h2>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="px-6 py-4 space-y-4">
                                        <div>
                                            <label for="edit-name-{{ $gradeLevel->id }}" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                                Name
                                            </label>
                                            <input 
                                                type="text" 
                                                id="edit-name-{{ $gradeLevel->id }}" 
                                                name="name" 
                                                value="{{ $gradeLevel->name }}"
                                                required 
                                                class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all"
                                            >
                                        </div>
                                        <div>
                                            <label for="edit-salary-{{ $gradeLevel->id }}" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                                Salary Range (Optional)
                                            </label>
                                            <input 
                                                type="text" 
                                                id="edit-salary-{{ $gradeLevel->id }}" 
                                                name="basic_salary_range" 
                                                value="{{ $gradeLevel->basic_salary_range }}"
                                                placeholder="e.g. 100,000 - 150,000"
                                                class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all"
                                            >
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="px-6 py-4 flex justify-end gap-2 border-t border-neutral-100">
                                        <button 
                                            type="button" 
                                            @click="$dispatch('close')" 
                                            class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 transition-colors"
                                        >
                                            CANCEL
                                        </button>
                                        <button 
                                            type="submit" 
                                            class="px-4 py-2 text-sm font-medium text-white bg-neutral-900 rounded-md hover:bg-neutral-800 transition-all"
                                        >
                                            UPDATE
                                        </button>
                                    </div>
                                </form>
                            </x-modal>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-neutral-500">No grade levels found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $gradeLevels->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <x-modal name="create-grade-level" focusable maxWidth="md">
        <form method="POST" action="{{ route('admin.grade-levels.store') }}">
            @csrf
            
            <!-- Modal Header -->
            <div class="px-6 pt-6 pb-4">
                <h2 class="text-xl font-semibold text-neutral-900">Add Grade Level</h2>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700 mb-1.5">
                        Name
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        required 
                        placeholder="e.g. Level 1, Senior"
                        class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all placeholder-neutral-400"
                    >
                </div>
                <div>
                    <label for="basic_salary_range" class="block text-sm font-medium text-neutral-700 mb-1.5">
                        Salary Range (Optional)
                    </label>
                    <input 
                        type="text" 
                        id="basic_salary_range" 
                        name="basic_salary_range" 
                        placeholder="e.g. 100,000 - 150,000"
                        class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all placeholder-neutral-400"
                    >
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 flex justify-end gap-2 border-t border-neutral-100">
                <button 
                    type="button" 
                    @click="$dispatch('close')" 
                    class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 transition-colors"
                >
                    CANCEL
                </button>
                <button 
                    type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-neutral-900 rounded-md hover:bg-neutral-800 transition-all"
                >
                    CREATE
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>

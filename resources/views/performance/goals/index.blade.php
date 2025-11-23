<x-app-layout>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">My Goals (OKRs)</h1>
            <p class="mt-1 text-sm text-neutral-500">Track your objectives and key results</p>
        </div>
        <button onclick="document.getElementById('createGoalModal').showModal()" class="btn-primary">
            New Goal
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($goals as $goal)
        <div class="card p-6 flex flex-col h-full">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-semibold text-lg text-neutral-900">{{ $goal->title }}</h3>
                <span class="px-2 py-1 text-xs rounded-full {{ $goal->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-neutral-100 text-neutral-800' }}">
                    {{ ucfirst(str_replace('_', ' ', $goal->status)) }}
                </span>
            </div>
            <p class="text-sm text-neutral-500 mb-4 flex-grow">{{ $goal->description }}</p>
            
            <div class="space-y-2">
                <div class="flex justify-between text-xs text-neutral-500">
                    <span>Progress</span>
                    <span>{{ $goal->progress }}%</span>
                </div>
                <div class="w-full bg-neutral-100 rounded-full h-2">
                    <div class="bg-black h-2 rounded-full transition-all duration-500" style="width: {{ $goal->progress }}%"></div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-neutral-100 flex justify-between items-center">
                <span class="text-xs text-neutral-400">Due: {{ $goal->due_date ? $goal->due_date->format('M d, Y') : 'No date' }}</span>
                <button onclick="editGoal({{ $goal->id }}, '{{ $goal->status }}', {{ $goal->progress }})" class="text-sm text-neutral-600 hover:text-black font-medium">Update</button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 text-neutral-500 bg-white rounded-xl border border-neutral-200 border-dashed">
            No goals set yet. Create one to get started!
        </div>
        @endforelse
    </div>

    <!-- Create Modal -->
    <dialog id="createGoalModal" class="p-0 rounded-lg shadow-xl backdrop:bg-black/50 w-full max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-bold mb-4">Set New Goal</h3>
            <form method="POST" action="{{ route('performance.goals.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Title *</label>
                        <input type="text" name="title" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Due Date</label>
                        <input type="date" name="due_date" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('createGoalModal').close()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Create Goal</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Update Modal -->
    <dialog id="updateGoalModal" class="p-0 rounded-lg shadow-xl backdrop:bg-black/50 w-full max-w-md">
        <div class="p-6">
            <h3 class="text-lg font-bold mb-4">Update Progress</h3>
            <form method="POST" id="updateGoalForm">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                        <select name="status" id="goalStatus" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <option value="not_started">Not Started</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Progress (%)</label>
                        <input type="number" name="progress" id="goalProgress" min="0" max="100" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('updateGoalModal').close()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">Update</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function editGoal(id, status, progress) {
            document.getElementById('goalStatus').value = status;
            document.getElementById('goalProgress').value = progress;
            document.getElementById('updateGoalForm').action = `/performance/goals/${id}`;
            document.getElementById('updateGoalModal').showModal();
        }
    </script>
</x-app-layout>

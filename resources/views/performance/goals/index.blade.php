<x-app-layout>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Performance Dashboard</h1>
            <p class="mt-1 text-sm text-neutral-500">Track your Key Performance Indicators (KPIs) and Objectives</p>
        </div>
        <button onclick="document.getElementById('createGoalModal').showModal()" class="btn-primary">
            New KPI / Goal
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card p-6 border-l-4 border-l-black">
            <div class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Total Goals</div>
            <div class="text-3xl font-black">{{ $goals->total() }}</div>
        </div>
        <div class="card p-6 border-l-4 border-l-green-500">
            <div class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">On Track</div>
            <div class="text-3xl font-black text-green-600">{{ $goals->filter(fn($g) => $g->traffic_light === 'green')->count() }}</div>
        </div>
        <div class="card p-6 border-l-4 border-l-yellow-500">
            <div class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">At Risk</div>
            <div class="text-3xl font-black text-yellow-600">{{ $goals->filter(fn($g) => $g->traffic_light === 'yellow')->count() }}</div>
        </div>
        <div class="card p-6 border-l-4 border-l-red-500">
            <div class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Off Track</div>
            <div class="text-3xl font-black text-red-600">{{ $goals->filter(fn($g) => $g->traffic_light === 'red')->count() }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($goals as $goal)
        <div class="card flex flex-col h-full relative overflow-hidden group">
            <!-- Traffic Light Indicator strip -->
            <div class="h-1.5 w-full absolute top-0 left-0 {{ $goal->progress_color }} opacity-75"></div>

            <div class="p-6 flex flex-col h-full">
                <div class="flex justify-between items-start mb-2">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-neutral-100 text-neutral-600 uppercase tracking-wide">
                        {{ $goal->type === 'metric' ? 'KPI Metric' : 'Objective' }}
                    </span>
                    <div class="flex items-center gap-2">
                         @if($goal->traffic_light === 'green')
                            <span class="w-3 h-3 rounded-full bg-green-500 ring-2 ring-green-100"></span>
                        @elseif($goal->traffic_light === 'yellow')
                            <span class="w-3 h-3 rounded-full bg-yellow-500 ring-2 ring-yellow-100"></span>
                        @else
                            <span class="w-3 h-3 rounded-full bg-red-500 ring-2 ring-red-100"></span>
                        @endif
                    </div>
                </div>

                <h3 class="font-bold text-lg text-neutral-900 mb-2 leading-tight">{{ $goal->title }}</h3>
                <p class="text-sm text-neutral-500 mb-6 flex-grow">{{ Str::limit($goal->description, 100) }}</p>
                
                <!-- KPI Metrics -->
                @if($goal->type === 'metric')
                    <div class="mb-5">
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <span class="text-2xl font-black text-neutral-900">{{ number_format($goal->current_value) }}</span>
                                <span class="text-sm font-medium text-neutral-500">/ {{ number_format($goal->target_value) }} {{ $goal->unit }}</span>
                            </div>
                            <span class="text-lg font-bold {{ $goal->progress >= 100 ? 'text-green-600' : 'text-neutral-900' }}">
                                {{ $goal->calculateProgress() }}%
                            </span>
                        </div>
                        <div class="w-full bg-neutral-100 rounded-full h-2.5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000 {{ $goal->traffic_light === 'red' ? 'bg-red-500' : ($goal->traffic_light === 'yellow' ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                 style="width: {{ $goal->calculateProgress() }}%"></div>
                        </div>
                    </div>
                @else
                    <div class="space-y-2 mb-5">
                        <div class="flex justify-between text-xs font-semibold uppercase tracking-wide text-neutral-500">
                            <span>Progress</span>
                            <span>{{ $goal->progress }}%</span>
                        </div>
                        <div class="w-full bg-neutral-100 rounded-full h-2">
                            <div class="bg-black h-2 rounded-full transition-all duration-500" style="width: {{ $goal->progress }}%"></div>
                        </div>
                    </div>
                @endif

                <div class="pt-4 border-t border-neutral-100 flex justify-between items-center mt-auto">
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase font-bold text-neutral-400">Due Date</span>
                        <span class="text-xs font-medium text-neutral-600">{{ $goal->due_date ? $goal->due_date->format('M d, Y') : 'Ongoing' }}</span>
                    </div>
                    <div class="flex gap-2">
                        <button onclick='editGoal(@json($goal))' class="px-3 py-1.5 text-xs font-semibold bg-neutral-900 text-white rounded hover:bg-neutral-800 transition">Update</button>
                        <form action="{{ route('performance.goals.destroy', $goal) }}" method="POST" onsubmit="return confirm('Delete this goal?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-neutral-400 hover:text-red-600 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-white rounded-xl border-2 border-dashed border-neutral-200">
            <div class="w-16 h-16 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-neutral-900">No KPIs defined yet</h3>
            <p class="text-neutral-500 mt-1 mb-6 max-w-sm mx-auto">Set clear objectives and track key performance indicators to measure your success.</p>
            <button onclick="document.getElementById('createGoalModal').showModal()" class="btn-primary">
                Create First Goal
            </button>
        </div>
        @endforelse
    </div>
    <div class="mt-6">
        {{ $goals->links() }}
    </div>

    <!-- Create Modal -->
    <dialog id="createGoalModal" class="p-0 rounded-xl shadow-2xl backdrop:bg-black/40 w-full max-w-2xl open:animate-fade-in-up">
        <div class="bg-white">
            <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center">
                <h3 class="text-lg font-bold">New Performance Goal</h3>
                <button onclick="document.getElementById('createGoalModal').close()" class="text-neutral-400 hover:text-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('performance.goals.store') }}" class="p-6">
                @csrf
                
                <!-- Type Selector -->
                <div class="mb-6 flex p-1 bg-neutral-100 rounded-lg" x-data="{ type: 'text' }">
                    <label class="flex-1 text-center cursor-pointer">
                        <input type="radio" name="type" value="text" class="sr-only peer" @click="type = 'text'" checked>
                        <span class="block py-2 text-sm font-semibold rounded-md text-neutral-500 peer-checked:bg-white peer-checked:text-black peer-checked:shadow-sm transition-all">Basic Objective</span>
                    </label>
                    <label class="flex-1 text-center cursor-pointer">
                        <input type="radio" name="type" value="metric" class="sr-only peer" @click="type = 'metric'">
                        <span class="block py-2 text-sm font-semibold rounded-md text-neutral-500 peer-checked:bg-white peer-checked:text-black peer-checked:shadow-sm transition-all">KPI Metric</span>
                    </label>
                </div>

                <div class="space-y-5" x-data="{ type: 'text' }">
                    <!-- Sync x-data with radio buttons via event listener on parent is cleaner but for simplicity using simple js below -->
                    
                    <div>
                        <label class="block text-sm font-bold text-neutral-800 mb-1">Goal Title *</label>
                        <input type="text" name="title" required placeholder="e.g. Increase Customer Retention" class="w-full px-4 py-2 bg-neutral-50 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                    </div>

                    <div id="metric-fields" class="hidden grid-cols-2 gap-4 p-4 bg-neutral-50 rounded-lg border border-neutral-100">
                        <div class="col-span-2 text-xs font-bold uppercase tracking-wider text-neutral-500 mb-1">KPI Configuration</div>
                        <div>
                            <label class="block text-xs font-bold text-neutral-600 mb-1">Target Value</label>
                            <input type="number" step="0.01" name="target_value" placeholder="100" class="w-full px-3 py-2 bg-white border border-neutral-200 rounded-md focus:ring-black focus:border-black text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-neutral-600 mb-1">Unit</label>
                            <input type="text" name="unit" placeholder="%, USD, Users" class="w-full px-3 py-2 bg-white border border-neutral-200 rounded-md focus:ring-black focus:border-black text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 bg-neutral-50 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Due Date</label>
                            <input type="date" name="due_date" class="w-full px-4 py-2 bg-neutral-50 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">Priority (Weight)</label>
                            <select name="weight" class="w-full px-4 py-2 bg-neutral-50 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                                <option value="1">Low (1)</option>
                                <option value="3" selected>Medium (3)</option>
                                <option value="5">High (5)</option>
                                <option value="10">Critical (10)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-neutral-100">
                    <button type="button" onclick="document.getElementById('createGoalModal').close()" class="px-4 py-2 text-sm font-semibold text-neutral-600 hover:text-black transition">Cancel</button>
                    <button type="submit" class="btn-primary">Create Goal</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- UI Logic for Create Modal -->
    <script>
        document.querySelectorAll('input[name="type"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                const metricFields = document.getElementById('metric-fields');
                const targetInput = document.querySelector('input[name="target_value"]');
                if (e.target.value === 'metric') {
                    metricFields.classList.remove('hidden');
                    metricFields.classList.add('grid');
                    targetInput.setAttribute('required', 'required');
                } else {
                    metricFields.classList.add('hidden');
                    metricFields.classList.remove('grid');
                    targetInput.removeAttribute('required');
                }
            });
        });
    </script>

    <!-- Update Modal -->
    <dialog id="updateGoalModal" class="p-0 rounded-xl shadow-2xl backdrop:bg-black/40 w-full max-w-md open:animate-fade-in-up">
        <div class="bg-white">
             <div class="px-6 py-4 border-b border-neutral-100 flex justify-between items-center bg-neutral-50">
                <h3 class="text-lg font-bold">Update Progress</h3>
                <button onclick="document.getElementById('updateGoalModal').close()" class="text-neutral-400 hover:text-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" id="updateGoalForm" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <h4 id="updateGoalTitle" class="font-semibold text-lg mb-1"></h4>
                    <p class="text-xs text-neutral-500" id="updateGoalSubtitle"></p>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-neutral-700 mb-1">Status</label>
                        <select name="status" id="goalStatus" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-black focus:border-black">
                            <option value="not_started">Not Started</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Dynamic Input: Either raw progress % OR current metric value -->
                    <div id="metricUpdateField" class="hidden bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <label class="block text-sm font-bold text-blue-900 mb-1">Current Value</label>
                        <div class="flex items-center gap-2">
                            <input type="number" step="0.01" name="current_value" id="goalCurrentValue" class="flex-1 px-4 py-2 border border-blue-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <span id="goalUnitUpdate" class="font-medium text-blue-700"></span>
                        </div>
                        <p class="text-xs text-blue-600 mt-2">Target: <span id="goalTargetDisplay"></span></p>
                    </div>

                    <div id="textUpdateField">
                        <label class="block text-sm font-bold text-neutral-700 mb-1">Completion Percentage</label>
                        <div class="flex items-center gap-4">
                            <input type="range" name="progress" id="goalProgressRange" min="0" max="100" class="flex-1 h-2 bg-neutral-200 rounded-lg appearance-none cursor-pointer accent-black" oninput="document.getElementById('goalProgressNum').value = this.value">
                            <div class="relative w-16">
                                <input type="number" id="goalProgressNum" min="0" max="100" class="w-full px-2 py-1 text-center border border-neutral-300 rounded-md text-sm font-bold" oninput="document.getElementById('goalProgressRange').value = this.value">
                                <span class="absolute right-4 top-1.5 text-xs text-neutral-400 pointer-events-none">%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-neutral-100">
                    <button type="submit" class="btn-primary w-full justify-center">Save Updates</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function editGoal(goal) {
            document.getElementById('updateGoalForm').action = `/performance/goals/${goal.id}`;
            document.getElementById('updateGoalTitle').textContent = goal.title;
            document.getElementById('goalStatus').value = goal.status;
            
            const isMetric = goal.type === 'metric';
            const metricField = document.getElementById('metricUpdateField');
            const textField = document.getElementById('textUpdateField');

            if (isMetric) {
                metricField.classList.remove('hidden');
                textField.classList.add('hidden');
                document.getElementById('goalCurrentValue').value = goal.current_value;
                document.getElementById('goalUnitUpdate').textContent = goal.unit;
                document.getElementById('goalTargetDisplay').textContent = goal.target_value + ' ' + (goal.unit || '');
                document.getElementById('updateGoalSubtitle').textContent = 'Update the current numeric value of this KPI.';
            } else {
                metricField.classList.add('hidden');
                textField.classList.remove('hidden');
                document.getElementById('goalProgressRange').value = goal.progress;
                document.getElementById('goalProgressNum').value = goal.progress;
                document.getElementById('updateGoalSubtitle').textContent = 'Update the qualitative progress of this objective.';
            }
            
            document.getElementById('updateGoalModal').showModal();
        }
    </script>
</x-app-layout>

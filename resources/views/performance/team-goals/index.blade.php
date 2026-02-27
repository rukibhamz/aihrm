<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Team Goals & KPIs</h1>
            <p class="mt-1 text-sm text-neutral-500">Review and score completed objectives for your direct reports.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($goals as $goal)
        <div class="card flex flex-col h-full relative overflow-hidden group">
            <div class="h-1.5 w-full absolute top-0 left-0 {{ $goal->progress_color }} opacity-75"></div>

            <div class="p-6 flex flex-col h-full">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex items-center gap-3">
                        @if($goal->user->employee && $goal->user->employee->photo)
                            <img src="{{ Storage::url($goal->user->employee->photo) }}" class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-neutral-200 flex items-center justify-center text-xs font-bold text-neutral-600">
                                {{ substr($goal->user->name, 0, 2) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-xs font-bold text-neutral-900">{{ $goal->user->name }}</p>
                            <p class="text-[10px] text-neutral-500 uppercase">{{ $goal->cycle_name ?: 'No Cycle' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 mb-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-neutral-100 text-neutral-600 uppercase tracking-widest border border-neutral-200">
                        {{ $goal->type === 'metric' ? 'KPI' : 'Objective' }}
                    </span>
                    @if($goal->status === 'completed')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 uppercase tracking-widest border border-green-200 ml-1">
                            Completed
                        </span>
                    @endif
                </div>

                <h3 class="font-bold text-lg text-neutral-900 mb-2 leading-tight">{{ $goal->title }}</h3>
                <p class="text-sm text-neutral-500 mb-6 flex-grow">{{ Str::limit($goal->description, 100) }}</p>
                
                @if($goal->type === 'metric')
                    <div class="mb-5">
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <span class="text-xl font-black text-neutral-900">{{ number_format($goal->current_value) }}</span>
                                <span class="text-xs font-medium text-neutral-500">/ {{ number_format($goal->target_value) }} {{ $goal->unit }}</span>
                            </div>
                            <span class="text-sm font-bold {{ $goal->progress >= 100 ? 'text-green-600' : 'text-neutral-900' }}">
                                {{ $goal->calculateProgress() }}%
                            </span>
                        </div>
                        <div class="w-full bg-neutral-100 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full rounded-full {{ $goal->traffic_light === 'red' ? 'bg-red-500' : ($goal->traffic_light === 'yellow' ? 'bg-yellow-500' : 'bg-green-500') }}" 
                                 style="width: {{ $goal->calculateProgress() }}%"></div>
                        </div>
                    </div>
                @else
                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[10px] uppercase font-bold text-neutral-500 tracking-wider">Progress</span>
                            <span class="text-xs font-bold text-neutral-900">{{ $goal->progress }}%</span>
                        </div>
                        <div class="w-full bg-neutral-100 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full rounded-full bg-black" style="width: {{ $goal->progress }}%"></div>
                        </div>
                    </div>
                @endif

                <div class="pt-4 border-t border-neutral-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mt-auto">
                    @if($goal->status === 'completed')
                        @if($goal->manager_score !== null)
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-bold text-neutral-400 tracking-wider">Your Score</span>
                                <span class="text-sm font-black text-black">{{ $goal->manager_score }} / 5</span>
                            </div>
                            <button onclick='scoreGoal(@json($goal))' class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">Edit Score</button>
                        @else
                            <button onclick='scoreGoal(@json($goal))' class="w-full py-2 text-xs font-black bg-black text-white rounded hover:bg-neutral-800 transition uppercase tracking-widest">
                                Evaluate KPI
                            </button>
                        @endif
                    @else
                        <div class="flex flex-col w-full text-center py-1.5 bg-neutral-50 rounded border border-neutral-100">
                            <span class="text-[10px] uppercase font-bold text-neutral-400 tracking-wider">Awaiting Completion</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center bg-white rounded-xl border-2 border-dashed border-neutral-200">
            <div class="w-16 h-16 bg-neutral-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-neutral-900">No Team Goals Found</h3>
            <p class="text-neutral-500 mt-1 max-w-sm mx-auto">Your direct reports have not created any KPIs or objectives yet.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $goals->links() }}
    </div>

    <!-- Score Modal -->
    <dialog id="scoreGoalModal" class="p-0 rounded-xl shadow-2xl backdrop:bg-black/40 w-full max-w-md open:animate-fade-in-up">
        <div class="bg-white">
             <div class="px-6 py-4 border-b border-neutral-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-neutral-50">
                <h3 class="text-lg font-bold">Manager Evaluation</h3>
                <button onclick="document.getElementById('scoreGoalModal').close()" class="text-neutral-400 hover:text-black">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" id="scoreGoalForm" class="p-6">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <h4 id="scoreGoalTitle" class="font-bold text-lg mb-1 leading-tight text-black"></h4>
                    <p class="text-xs font-semibold text-neutral-500 uppercase tracking-widest" id="scoreGoalEmployee"></p>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-neutral-900 mb-3">Performance Score</label>
                        <div class="flex justify-between gap-2" x-data="{ currentScore: 0 }" id="scoreContainer">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="flex-1 text-center cursor-pointer group">
                                    <input type="radio" name="manager_score" value="{{ $i }}" class="sr-only peer" @click="currentScore = {{ $i }}" id="scoreRadio{{ $i }}">
                                    <div class="py-3 border-2 border-neutral-200 rounded-lg peer-checked:border-black peer-checked:bg-black peer-checked:text-white text-neutral-500 font-black text-lg transition-all group-hover:border-neutral-400"
                                         :class="currentScore === {{ $i }} ? 'shadow-md scale-105' : ''">
                                        {{ $i }}
                                    </div>
                                </label>
                            @endfor
                        </div>
                        <div class="flex justify-between text-[10px] font-bold text-neutral-400 uppercase tracking-widest mt-2 px-1">
                            <span>Needs Work (1)</span>
                            <span>Outstanding (5)</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-neutral-900 mb-1">Feedback & Comments</label>
                        <textarea name="manager_comment" id="managerComment" rows="4" placeholder="Provide constructive feedback on this KPI execution..." class="w-full px-4 py-3 bg-neutral-50 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all text-sm"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-neutral-100">
                    <button type="button" onclick="document.getElementById('scoreGoalModal').close()" class="px-4 py-2 text-sm font-semibold text-neutral-600 hover:text-black transition">Cancel</button>
                    <button type="submit" class="btn-primary">Submit Evaluation</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function scoreGoal(goal) {
            document.getElementById('scoreGoalForm').action = `{{ url('/performance/team-goals') }}/${goal.id}/score`;
            document.getElementById('scoreGoalTitle').textContent = goal.title;
            document.getElementById('scoreGoalEmployee').textContent = goal.user.name + ' - ' + (goal.cycle_name || '');
            
            // Set existing score if any
            if (goal.manager_score) {
                document.getElementById('scoreRadio' + goal.manager_score).click();
            } else {
                // Default unselected
                document.querySelectorAll('input[name="manager_score"]').forEach(r => r.checked = false);
                document.getElementById('scoreContainer').dispatchEvent(new CustomEvent('click')); // trigger x-data update if needed
            }
            
            document.getElementById('managerComment').value = goal.manager_comment || '';
            document.getElementById('scoreGoalModal').showModal();
        }
    </script>
</x-app-layout>

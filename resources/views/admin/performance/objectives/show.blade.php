<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.performance.objectives.index') }}" class="text-neutral-500 hover:text-neutral-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Objective Details</h1>
            </div>
            <p class="text-sm text-neutral-500 ml-8">View details and aligned goals for this company OKR.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.performance.objectives.edit', $objective) }}" class="btn-secondary">
                Edit Objective
            </a>
            @php
                $statusClasses = match($objective->status) {
                    'active' => 'bg-green-100 text-green-800 border bg-green-200',
                    'completed' => 'bg-blue-100 text-blue-800 border bg-blue-200',
                    'draft' => 'bg-neutral-100 text-neutral-800 border bg-neutral-200',
                    'cancelled' => 'bg-red-100 text-red-800 border bg-red-200',
                    default => 'bg-neutral-100 text-neutral-800'
                };
            @endphp
            <div class="px-4 py-2 font-medium text-sm rounded-md shadow-sm {{ $statusClasses }} flex items-center">
                Status: {{ ucfirst($objective->status) }}
            </div>
        </div>
    </div>

    <!-- Objective Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-2">
            <div class="card p-6 h-full">
                <h2 class="text-3xl font-extrabold text-neutral-900 mb-4">{{ $objective->title }}</h2>
                <div class="prose prose-sm text-neutral-600 max-w-none">
                    {!! nl2br(e($objective->description)) ?? '<em class="text-neutral-400">No description provided.</em>' !!}
                </div>
                
                <div class="mt-8 pt-6 border-t border-neutral-100 flex gap-12">
                    <div>
                        <span class="text-xs font-semibold text-neutral-500 tracking-wider uppercase block mb-1">Start Date</span>
                        <p class="text-neutral-900 font-medium">{{ $objective->start_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-neutral-500 tracking-wider uppercase block mb-1">End Date</span>
                        <p class="text-neutral-900 font-medium">{{ $objective->end_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-neutral-500 tracking-wider uppercase block mb-1">Aligned Goals</span>
                        <div class="flex items-center text-blue-600 font-medium">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                            {{ $objective->goals->count() }} Team/Individual Goal(s)
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="lg:col-span-1">
            <div class="card p-6 h-full flex flex-col justify-center bg-blue-50/50 border-blue-100">
                <h3 class="text-sm font-semibold text-neutral-700 uppercase tracking-wider text-center mb-6">Overall Progress</h3>
                
                <div class="relative w-48 h-48 mx-auto flex items-center justify-center">
                    <!-- SVG Donut Chart -->
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <!-- Background Circle -->
                        <path class="text-blue-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <!-- Progress Circle -->
                        <path class="{{ $objective->progress >= 70 ? 'text-green-500' : ($objective->progress >= 40 ? 'text-yellow-500' : 'text-blue-600') }}" stroke-dasharray="{{ $objective->progress }}, 100" stroke-width="4" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <!-- Percentage Text -->
                    <div class="absolute flex flex-col items-center justify-center text-center">
                        <span class="text-4xl font-extrabold {{ $objective->progress >= 70 ? 'text-green-700' : ($objective->progress >= 40 ? 'text-yellow-700' : 'text-blue-700') }}">{{ $objective->progress }}%</span>
                        <span class="text-xs text-neutral-500 mt-1">Completed</span>
                    </div>
                </div>
                
                <p class="text-xs text-center text-neutral-500 mt-6 px-4">Calculated from the weighted average of all {{ $objective->goals->count() }} aligned goals.</p>
            </div>
        </div>
    </div>

    <!-- Aligned Goals Table -->
    <div class="card overflow-hidden">
        <div class="p-6 border-b border-neutral-200">
            <h3 class="text-lg font-medium text-neutral-900">Aligned Individual & Team Goals</h3>
            <p class="text-sm text-neutral-500 mt-1">These employee goals contribute to the progress of this high-level company objective.</p>
        </div>
        
        <x-table>
            <x-slot name="head">
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Goal Title</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Owner</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Weight</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Progress</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
            </x-slot>

            <x-slot name="body">
                @forelse($objective->goals as $goal)
                    <tr class="hover:bg-neutral-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-neutral-900">{{ $goal->title }}</div>
                            <div class="text-xs text-neutral-500">{{ $goal->cycle_name }} &bull; Due {{ $goal->due_date->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-6 w-6 rounded-full bg-neutral-200 flex items-center justify-center font-semibold text-neutral-600 text-[10px]">
                                    {{ collect(explode(' ', $goal->user->name))->map(fn($s) => substr($s,0,1))->take(2)->join('') }}
                                </div>
                                <span class="ml-2 text-sm text-neutral-900">{{ $goal->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-neutral-100 text-neutral-800 border border-neutral-200">
                                {{ ucfirst($goal->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 font-medium">
                            <div class="flex items-center">
                                {{ $goal->weight }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $goalProgress = $goal->type === 'metric' ? $goal->calculateProgress() : $goal->progress;
                            @endphp
                            <div class="flex items-center">
                                <span class="text-sm font-medium mr-2 {{ $goalProgress >= 70 ? 'text-green-600' : ($goalProgress >= 40 ? 'text-yellow-600' : 'text-neutral-900') }}">{{ $goalProgress }}%</span>
                                <div class="w-16 bg-neutral-200 rounded-full h-1.5 hidden sm:block">
                                    <div class="h-1.5 rounded-full {{ $goalProgress >= 70 ? 'bg-green-500' : ($goalProgress >= 40 ? 'bg-yellow-500' : 'bg-blue-600') }}" style="width: {{ $goalProgress }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <a href="{{ route('performance.goals.show', $goal) }}" class="text-blue-600 hover:text-blue-900 transition-colors">View Goal</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-neutral-500">
                            <svg class="mx-auto h-12 w-12 text-neutral-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <p class="text-base font-medium text-neutral-900">No aligned goals yet</p>
                            <p class="text-sm mt-1 max-w-sm mx-auto">Employees can link their individual or team goals to this objective to automatically update its progress.</p>
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table>
    </div>
</x-app-layout>

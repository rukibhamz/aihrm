<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Company Objectives (OKRs)</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage high-level company goals to align employee performance.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.performance.objectives.create') }}" class="btn-primary">
                + Create Objective
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
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
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Objective</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Timeline</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Alignment</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Overall Progress</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
            </x-slot>

            <x-slot name="body">
                @forelse($objectives as $objective)
                    <tr class="hover:bg-neutral-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-neutral-900 mb-1">{{ $objective->title }}</div>
                            <div class="text-xs text-neutral-500 line-clamp-2 max-w-sm">{{ $objective->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            <div>{{ $objective->start_date->format('M d, Y') }}</div>
                            <div class="text-xs text-neutral-400">to {{ $objective->end_date->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = match($objective->status) {
                                    'active' => 'bg-green-100 text-green-800',
                                    'completed' => 'bg-blue-100 text-blue-800',
                                    'draft' => 'bg-neutral-100 text-neutral-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    default => 'bg-neutral-100 text-neutral-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClasses }}">
                                {{ ucfirst($objective->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600 border-l border-neutral-100">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-blue-500 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>{{ $objective->goals_count }} linked goal(s)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-medium {{ $objective->progress >= 70 ? 'text-green-600' : ($objective->progress >= 40 ? 'text-yellow-600' : 'text-neutral-900') }} mr-3 w-10 text-right">
                                    {{ $objective->progress }}%
                                </span>
                                <div class="w-24 bg-neutral-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full {{ $objective->progress >= 70 ? 'bg-green-500' : ($objective->progress >= 40 ? 'bg-yellow-500' : 'bg-blue-600') }}" style="width: {{ $objective->progress }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.performance.objectives.show', $objective) }}" class="text-blue-600 hover:text-blue-900 transition-colors">View</a>
                                <a href="{{ route('admin.performance.objectives.edit', $objective) }}" class="text-neutral-600 hover:text-neutral-900 transition-colors">Edit</a>
                                <form action="{{ route('admin.performance.objectives.destroy', $objective) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this objective?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" {{ $objective->goals_count > 0 ? 'disabled title="Cannot delete objective with linked goals"' : '' }}>Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-neutral-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-blue-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <p class="text-lg font-medium text-neutral-900">No Company Objectives</p>
                                <p class="text-sm mt-1 mb-4 max-w-md">Create high-level company objectives (OKRs) so employees can align their individual goals to the broader company vision.</p>
                                <a href="{{ route('admin.performance.objectives.create') }}" class="btn-primary">Create First Objective</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Resignation Brief') }}
        </h2>
    </x-slot>

<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="{{ route('admin.resignations.index') }}" class="text-sm text-gray-500 hover:text-black flex items-center gap-1 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Resignations
        </a>
        <h2 class="text-2xl font-bold text-gray-900">
            Resignation: {{ $resignation->user->name }}
        </h2>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Col: Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card p-6">
            <div class="flex justify-between items-start mb-4 pb-4 border-b border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Request Details</h3>
                    <p class="text-sm text-gray-500">Submitted on {{ $resignation->resignation_date->format('F d, Y') }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-bold rounded-full uppercase tracking-wide
                    {{ $resignation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $resignation->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $resignation->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $resignation->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ ucfirst($resignation->status) }}
                </span>
            </div>

            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Requested Last Working Day</span>
                    <span class="text-xl font-bold text-gray-900">{{ $resignation->last_working_day->format('l, F d, Y') }}</span>
                    <p class="text-xs text-gray-400 mt-1">
                        Notice Period: {{ $resignation->resignation_date->diffInDays($resignation->last_working_day) }} days
                    </p>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Reason for Leaving</span>
                    <p class="bg-gray-50 p-4 rounded-lg text-gray-700 border border-gray-100">
                        {{ $resignation->reason }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Asset Checklist (Integration Phase 11) -->
        <div class="card p-6 border-l-4 border-l-blue-500">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Asset Recovery Checklist
            </h3>
            
            @if($assets->count() > 0)
                <div class="space-y-3">
                    <p class="text-sm text-gray-600 mb-2">The following assets are currently assigned to this employee. Please ensure they are returned.</p>
                    @foreach($assets as $asset)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-3 bg-white border border-gray-200 rounded-lg">
                        <div>
                            <div class="font-bold text-sm text-gray-900">{{ $asset->name }}</div>
                            <div class="text-xs text-gray-500">S/N: {{ $asset->serial_number ?? 'N/A' }}</div>
                        </div>
                        <a href="{{ route('admin.assets.edit', $asset) }}" class="text-xs font-bold text-blue-600 hover:underline">
                            Process Return
                        </a>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="p-4 bg-green-50 text-green-700 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    No assets currently assigned.
                </div>
            @endif
        </div>
        <!-- Offboarding Tasks Checklist -->
        @if($resignation->status === 'approved' || $resignation->status === 'completed')
        <div class="card p-6 border-l-4 border-l-purple-500">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    Offboarding Tasks Checklist
                </h3>
                @php
                    $completedTasks = $resignation->offboardingTasks->where('is_completed', true)->count();
                    $totalTasks = $resignation->offboardingTasks->count();
                    $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                @endphp
                <span class="text-sm font-medium text-gray-600">{{ $completedTasks }} / {{ $totalTasks }} Completed</span>
            </div>
            
            @if($totalTasks > 0)
                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
                    <div class="bg-purple-600 h-2 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                </div>

                <div class="space-y-4">
                    @foreach($resignation->offboardingTasks as $taskLog)
                        <div class="p-4 bg-gray-50 border border-gray-100 rounded-lg {{ $taskLog->is_completed ? 'opacity-75' : '' }}">
                            <form action="{{ route('admin.resignations.offboarding.update', [$resignation, $taskLog]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            <label class="flex items-center gap-2 cursor-pointer group">
                                                <input type="hidden" name="is_completed" value="0">
                                                <input type="checkbox" name="is_completed" value="1" onchange="this.form.submit()" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 h-5 w-5 transition-colors" {{ $taskLog->is_completed ? 'checked' : '' }}>
                                                <span class="font-bold text-gray-900 {{ $taskLog->is_completed ? 'line-through text-gray-500' : '' }} transition-all">{{ $taskLog->task->title }}</span>
                                            </label>
                                        </div>
                                        @if($taskLog->task->description)
                                            <p class="text-sm text-gray-500 ml-8 mb-3">{{ $taskLog->task->description }}</p>
                                        @endif
                                        
                                        <!-- Comments Field -->
                                        <div class="ml-8 mt-2">
                                            <textarea name="comments" rows="1" class="w-full sm:w-2/3 text-sm px-3 py-1.5 border border-gray-200 rounded-md bg-white focus:ring-purple-500 focus:border-purple-500 transition-colors" placeholder="Internal notes or comments... (Press enter to save)" onkeydown="if(event.keyCode == 13 && !event.shiftKey) { event.preventDefault(); this.form.submit(); }">{{ $taskLog->comments }}</textarea>
                                        </div>

                                        @if($taskLog->is_completed && $taskLog->closedBy)
                                            <p class="text-xs text-green-600 font-medium ml-8 mt-2 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Completed by {{ $taskLog->closedBy->name }} on {{ $taskLog->completed_at->format('M d, Y') }}
                                            </p>
                                        @endif
                                    </div>
                                    <button type="submit" class="text-xs font-semibold text-purple-600 hover:text-purple-800 shrink-0 mt-1 opacity-0 group-hover:opacity-100 transition-opacity focus:opacity-100">
                                        Save Note
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-4 bg-yellow-50 text-yellow-800 rounded-lg text-sm border border-yellow-100 flex gap-3">
                     <svg class="w-5 h-5 shrink-0 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                     No offboarding tasks were defined when this resignation was approved. Wait for HR to define global template tasks, then you may need to manually seed them for this record via database.
                </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Right Col: Actions -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">HR Actions</h3>
            <form action="{{ route('admin.resignations.update', $resignation) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="pending" {{ $resignation->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="approved" {{ $resignation->status === 'approved' ? 'selected' : '' }}>Approved (Exit Process Started)</option>
                        <option value="completed" {{ $resignation->status === 'completed' ? 'selected' : '' }}>Completed (Offboarded)</option>
                        <option value="rejected" {{ $resignation->status === 'rejected' ? 'selected' : '' }}>Rejected (Retained)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">HR Comments / Internal Notes</label>
                    <textarea name="hr_comments" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">{{ $resignation->hr_comments }}</textarea>
                </div>
                
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Exit Interview Notes</label>
                    <textarea name="exit_interview_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Summary of exit interview...">{{ $resignation->exit_interview_notes }}</textarea>
                </div>

                <button type="submit" class="btn-primary w-full justify-center">Update Status</button>
            </form>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg text-xs text-gray-500 border border-gray-200">
            <h4 class="font-bold text-gray-700 mb-1">Employee Info</h4>
            <p><strong>Email:</strong> {{ $resignation->user->email }}</p>
            <p><strong>Department:</strong> {{ $resignation->user->employee->department->name ?? 'N/A' }}</p>
            <p><strong>Joined:</strong> {{ $resignation->user->employee->joining_date ? $resignation->user->employee->joining_date->format('M Y') : 'N/A' }}</p>
        </div>
    </div>
</div>
</x-app-layout>



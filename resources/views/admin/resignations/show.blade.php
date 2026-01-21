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
                    <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg">
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
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                        <option value="pending" {{ $resignation->status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="approved" {{ $resignation->status === 'approved' ? 'selected' : '' }}>Approved (Exit Process Started)</option>
                        <option value="completed" {{ $resignation->status === 'completed' ? 'selected' : '' }}>Completed (Offboarded)</option>
                        <option value="rejected" {{ $resignation->status === 'rejected' ? 'selected' : '' }}>Rejected (Retained)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">HR Comments / Internal Notes</label>
                    <textarea name="hr_comments" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">{{ $resignation->hr_comments }}</textarea>
                </div>
                
                <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Exit Interview Notes</label>
                    <textarea name="exit_interview_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black" placeholder="Summary of exit interview...">{{ $resignation->exit_interview_notes }}</textarea>
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

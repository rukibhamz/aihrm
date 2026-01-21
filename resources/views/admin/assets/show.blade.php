<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Asset Details') }}
        </h2>
    </x-slot>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.assets.index') }}" class="text-sm text-gray-500 hover:text-black flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Assets
            </a>
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                {{ $asset->name }}
                <span class="px-2.5 py-1 text-sm rounded-full 
                    {{ $asset->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $asset->status === 'assigned' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $asset->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $asset->status === 'retired' ? 'bg-gray-100 text-gray-800' : '' }}
                    {{ $asset->status === 'lost' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($asset->status) }}
                </span>
            </h2>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.assets.edit', $asset) }}" class="btn-secondary">Edit Asset</a>
        </div>
    </div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Asset Details Card -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">Asset Information</h3>
            <div class="grid grid-cols-2 gap-y-4 text-sm">
                <div>
                    <span class="block text-gray-500 mb-1">Type</span>
                    <span class="font-medium text-gray-900 uppercase tracking-wide">{{ $asset->type }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 mb-1">Serial Number</span>
                    <span class="font-medium text-gray-900 font-mono">{{ $asset->serial_number ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 mb-1">Purchase Date</span>
                    <span class="font-medium text-gray-900">{{ $asset->purchase_date ? $asset->purchase_date->format('M d, Y') : '-' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 mb-1">Purchase Cost</span>
                    <span class="font-medium text-gray-900">{{ $asset->purchase_cost ? 'USD ' . number_format($asset->purchase_cost, 2) : '-' }}</span>
                </div>
                <div>
                    <span class="block text-gray-500 mb-1">Warranty Expiry</span>
                    <span class="font-medium {{ $asset->warranty_expiry && $asset->warranty_expiry->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $asset->warranty_expiry ? $asset->warranty_expiry->format('M d, Y') : '-' }}
                    </span>
                </div>
                <div class="col-span-2">
                    <span class="block text-gray-500 mb-1">Notes</span>
                    <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $asset->notes ?? 'No notes recorded.' }}</p>
                </div>
            </div>
        </div>

        <!-- History Timeline -->
        <div class="card p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Asset History</h3>
            <div class="relative pl-4 border-l-2 border-gray-100 space-y-8">
                @forelse($asset->history as $history)
                <div class="relative">
                    <div class="absolute -left-[21px] top-1 h-3 w-3 rounded-full border-2 border-white 
                        {{ $history->action === 'created' ? 'bg-green-500' : '' }}
                        {{ $history->action === 'assigned' ? 'bg-blue-500' : '' }}
                        {{ $history->action === 'returned' ? 'bg-gray-500' : '' }}
                        {{ $history->action === 'status_change' ? 'bg-yellow-500' : '' }}">
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-bold text-gray-900 uppercase tracking-wide">
                                {{ str_replace('_', ' ', $history->action) }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $history->created_at->format('M d, Y • h:i A') }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">
                            {{ $history->notes }}
                        </p>
                        <div class="flex items-center gap-2 text-xs text-gray-400">
                            @if($history->performed_by)
                                <span>Action by: {{ $history->performer?->name }}</span>
                            @endif
                            @if($history->user_id)
                                <span>• Involved: <strong>{{ $history->user?->name }}</strong></span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 italic text-sm">No history records found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Assignment -->
    <div class="lg:col-span-1 space-y-6">
        <div class="card p-6 border-t-4 {{ $asset->status === 'assigned' ? 'border-t-blue-500' : 'border-t-gray-500' }}">
            <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4">Current Assignment</h3>
            
            @if($asset->assignedTo)
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full bg-black text-white flex items-center justify-center font-bold text-lg">
                        {{ substr($asset->assignedTo->name, 0, 1) }}
                    </div>
                    <div>
                        <a href="{{ route('admin.employees.show', $asset->assignedTo->employee ?? 0) }}" class="font-bold text-gray-900 hover:underline">
                            {{ $asset->assignedTo->name }}
                        </a>
                        <p class="text-sm text-gray-500">{{ $asset->assignedTo->employee->designation->title ?? 'Employee' }}</p>
                        <p class="text-xs text-gray-400">{{ $asset->assignedTo->email }}</p>
                    </div>
                </div>
                <div class="bg-blue-50 text-blue-800 text-sm p-3 rounded-lg border border-blue-100 text-center">
                    Assigned {{ $asset->history->where('action', 'assigned')->first()?->created_at->diffForHumans() ?? 'recently' }}
                </div>
            @else
                <div class="text-center py-6">
                     <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <p class="text-gray-900 font-medium mb-1">Not Assigned</p>
                    <p class="text-gray-500 text-sm mb-4">This asset is currently in inventory.</p>
                    <a href="{{ route('admin.assets.edit', $asset) }}" class="btn-primary w-full justify-center">Assign to Employee</a>
                </div>
            @endif
        </div>

        @if($asset->qr_code) 
        <!-- Placeholder for future QR Code feature -->
        <div class="card p-6 text-center">
             <img src="{{ $asset->qr_code }}" alt="QR Code" class="w-32 h-32 mx-auto mb-2">
             <p class="text-xs text-gray-500">Scan to view details</p>
        </div>
        @endif
    </div>
</div>
</x-app-layout>

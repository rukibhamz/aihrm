<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Asset Management') }}
        </h2>
    </x-slot>

    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-black">
            Asset Management
        </h2>
        <a href="{{ route('admin.assets.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add New Asset
        </a>
    </div>

<!-- Filters -->
<div class="card p-4 mb-6">
    <form action="{{ route('admin.assets.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or serial..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
        </div>
        <div class="w-full md:w-48">
            <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                <option value="">All Types</option>
                <option value="hardware" {{ request('type') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                <option value="software" {{ request('type') == 'software' ? 'selected' : '' }}>Software</option>
                <option value="license" {{ request('type') == 'license' ? 'selected' : '' }}>License</option>
                <option value="furniture" {{ request('type') == 'furniture' ? 'selected' : '' }}>Furniture</option>
                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                <option value="">All Statuses</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
            </select>
        </div>
        <button type="submit" class="btn-secondary">Filter</button>
    </form>
</div>

<!-- Assets Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial / Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchase Info</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($assets as $asset)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $asset->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-xs text-gray-500 mb-0.5 uppercase tracking-wide">{{ $asset->type }}</div>
                    <div class="text-sm text-gray-900">{{ $asset->serial_number ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $asset->status === 'available' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $asset->status === 'assigned' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $asset->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $asset->status === 'retired' ? 'bg-gray-100 text-gray-800' : '' }}
                        {{ $asset->status === 'lost' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($asset->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($asset->assignedTo)
                        <div class="flex items-center">
                            <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold mr-2">
                                {{ substr($asset->assignedTo->name, 0, 1) }}
                            </div>
                            <span class="text-sm text-gray-900">{{ $asset->assignedTo->name }}</span>
                        </div>
                    @else
                        <span class="text-sm text-gray-400 italic">Unassigned</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">
                        @if($asset->purchase_cost)
                            ${{ number_format($asset->purchase_cost, 2) }}
                        @else
                            -
                        @endif
                    </div>
                    @if($asset->purchase_date)
                    <div class="text-xs text-gray-500">{{ $asset->purchase_date->format('M Y') }}</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.assets.edit', $asset) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                    <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this asset? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    No assets found. <a href="{{ route('admin.assets.create') }}" class="text-indigo-600 font-medium">Create one now</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $assets->withQueryString()->links() }}
</div>
</x-app-layout>

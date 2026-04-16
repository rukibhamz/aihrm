<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-2">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Office Locations</h1>
            <p class="mt-1 text-sm text-neutral-500">Configure geofencing boundaries for clock-ins</p>
        </div>
        <a href="{{ route('admin.locations.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Add New Location
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-xl text-green-700 text-sm flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-neutral-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-neutral-50 text-neutral-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold border-b border-neutral-100">Location Name</th>
                        <th class="px-6 py-4 font-semibold border-b border-neutral-100">Coordinates</th>
                        <th class="px-6 py-4 font-semibold border-b border-neutral-100">Radius</th>
                        <th class="px-6 py-4 font-semibold border-b border-neutral-100">Status</th>
                        <th class="px-6 py-4 font-semibold border-b border-neutral-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-50">
                    @forelse($locations as $location)
                        <tr class="hover:bg-neutral-50/50 transition duration-150">
                            <td class="px-6 py-4">
                                <span class="font-medium text-neutral-900">{{ $location->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-neutral-500 font-mono">
                                    {{ number_format($location->latitude, 6) }}, {{ number_format($location->longitude, 6) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-neutral-600">{{ $location->radius_meters }}m</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($location->is_default)
                                    <span class="px-2 py-0.5 bg-indigo-50 text-primary text-[10px] font-bold uppercase tracking-wider rounded border border-indigo-100">Primary</span>
                                @else
                                    <span class="text-neutral-300 text-[10px] uppercase font-bold tracking-wider">Secondary</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.locations.edit', $location) }}" class="text-neutral-400 hover:text-primary transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" class="inline" onsubmit="return confirm('Delete this location?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-neutral-400 hover:text-red-600 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-neutral-400 italic">No office locations configured yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>


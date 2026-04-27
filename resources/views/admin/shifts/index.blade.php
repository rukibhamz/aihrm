<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-2">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-neutral-900">Shift Management</h1>
            <p class="mt-1 text-sm text-neutral-500">Configure working hours and grace periods</p>
        </div>
        <a href="{{ route('admin.shifts.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Create New Shift
        </a>
    </div>

    <x-flash-messages
        successClass="mb-6 p-4 bg-green-50 border border-green-100 rounded-xl text-green-700 text-sm"
        errorClass="mb-6 p-4 bg-red-50 border border-red-100 rounded-xl text-red-700 text-sm"
    />

    <div class="bg-white rounded-xl shadow-sm border border-neutral-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-neutral-50 text-neutral-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold border-b border-neutral-100">Shift Name</th>
                        <th class="px-6 py-4 font-semibold border-b border-neutral-100">Timing</th>
                        <th class="px-6 py-4 font-semibold border-b border-neutral-100">Grace Period</th>
                        <th class="px-6 py-4 font-semibold border-b border-neutral-100">Is Default</th>
                        <th class="px-6 py-4 font-semibold border-b border-neutral-100 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-50">
                    @forelse($shifts as $shift)
                        <tr class="hover:bg-neutral-50/50 transition duration-150">
                            <td class="px-6 py-4">
                                <span class="font-medium text-neutral-900">{{ $shift->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-sm text-neutral-600">
                                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-600">
                                {{ $shift->grace_period_minutes }} mins
                            </td>
                            <td class="px-6 py-4">
                                @if($shift->is_default)
                                    <span class="px-2 py-0.5 bg-indigo-50 text-primary text-[10px] font-bold uppercase tracking-wider rounded border border-indigo-100">Default</span>
                                @else
                                    <span class="text-neutral-300 text-[10px] uppercase font-bold tracking-wider">No</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.shifts.edit', $shift) }}" class="text-neutral-400 hover:text-primary transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.shifts.destroy', $shift) }}" method="POST" class="inline" onsubmit="return confirm('Delete this shift?')">
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
                            <td colspan="5" class="px-6 py-12 text-center text-neutral-400 italic">No shifts configured yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>


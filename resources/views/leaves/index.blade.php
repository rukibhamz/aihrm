<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Leave Requests</h1>
            <p class="mt-1 text-sm text-neutral-500">You can edit or cancel pending leave requests</p>
        </div>
        <a href="{{ route('leaves.create') }}" class="btn-primary whitespace-nowrap">Request Leave</a>
    </div>

    <x-flash-messages />

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse ($leaves as $leave)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">{{ $leave->leaveType->name ?? 'Leave' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            {{ $leave->start_date }} to {{ $leave->end_date }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            3 Days
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                   ($leave->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('leaves.show', $leave) }}" class="text-primary hover:text-black dark:hover:text-white">View</a>
                                @if($leave->status === 'pending')
                                    <a href="{{ route('leaves.edit', $leave) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <form method="POST" action="{{ route('leaves.destroy', $leave) }}" class="inline" onsubmit="return confirm('Are you sure you want to cancel this leave request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-neutral-500">No leave requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-neutral-200">
            {{ $leaves->links() }}
        </div>
    </div>
</x-app-layout>

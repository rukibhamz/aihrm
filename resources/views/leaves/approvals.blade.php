<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Leave Approvals</h1>
        <p class="mt-1 text-sm text-neutral-500">Review and approve pending leave requests from your team</p>
    </div>

    <x-flash-messages />

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Dates</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse ($requests as $request)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                            {{ $request->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            {{ $request->leaveType->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                            {{ $request->start_date }} to {{ $request->end_date }}
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-500 max-w-xs truncate">
                            {{ $request->reason }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2">
                                <a href="{{ route('leaves.show', $request) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                                    View
                                </a>
                                <form method="POST" action="{{ route('leaves.approve', $request) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('leaves.reject', $request) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-neutral-500">No pending leave requests.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-neutral-200">
            {{ $requests->links() }}
        </div>
    </div>
</x-app-layout>

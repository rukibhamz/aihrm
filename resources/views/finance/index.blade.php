<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Financial Requests</h1>
            <p class="mt-1 text-sm text-neutral-500">Submit and track expense claims and reimbursements</p>
        </div>
        <a href="{{ route('finance.create') }}" class="btn-primary whitespace-nowrap">New Request</a>
    </div>

    <x-flash-messages />

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse ($requests as $request)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">#{{ $request->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">{{ $request->category->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">₦{{ number_format($request->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-neutral-500 truncate max-w-xs">{{ $request->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $request->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                   ($request->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">{{ $request->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('finance.show', $request) }}" class="text-primary hover:text-black dark:hover:text-white">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-neutral-500">No financial requests found.</td>
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

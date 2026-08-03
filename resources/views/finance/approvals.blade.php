<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Financial Request Approvals</h1>
        <p class="mt-1 text-sm text-neutral-500">Review and action pending expense claims</p>
    </div>

    <x-flash-messages />

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
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
                            {{ $request->category->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                            ₦{{ number_format($request->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-500 max-w-xs truncate">
                            {{ $request->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $request->status === 'approved_manager' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex gap-2 flex-wrap">
                                @if($request->status === 'pending' && auth()->user()->hasRole(['Manager', 'Admin']))
                                    <form method="POST" action="{{ route('finance.approve.manager', $request) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs">
                                            Approve (Mgr)
                                        </button>
                                    </form>
                                @endif

                                @if($request->status === 'approved_manager' && auth()->user()->hasRole(['Finance', 'Admin']))
                                    <form method="POST" action="{{ route('finance.approve.finance', $request) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs">
                                            Approve (Fin)
                                        </button>
                                    </form>
                                @endif

                                @if($request->status === 'approved_finance' && auth()->user()->can('mark as paid'))
                                    <form method="POST" action="{{ route('finance.mark-paid', $request) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-xs">
                                            Mark Paid
                                        </button>
                                    </form>
                                @endif

                                @if(in_array($request->status, ['pending', 'approved_manager']))
                                    <form method="POST" action="{{ route('finance.reject', $request) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                            Reject
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-neutral-500">No pending financial requests.</td>
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

<x-app-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
        <div class="p-6 bg-white border-b border-gray-200">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <h2 class="text-2xl font-bold text-gray-900 mb-6">Financial Request Approvals</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($requests as $request)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $request->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->category->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ₦{{ number_format($request->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
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
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No pending financial requests.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <div x-data="{ openModal: false }" class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-neutral-900">My Loans</h1>
                <p class="mt-1 text-sm text-neutral-500">View your active loan deductions and apply for new requests</p>
            </div>
            <button @click="openModal = true" class="btn-primary">
                Apply for Loan
            </button>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-green-50 text-green-700 p-4 rounded-md border border-green-200">
            {{ session('success') }}
        </div>
        @endif
        
        @if(session('error'))
        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-md border border-red-200">
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 bg-red-50 text-red-700 p-4 rounded-md border border-red-200">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Date Requested</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Loan Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Monthly Deduction</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Remaining Balance</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-100">
                    @forelse ($loans as $loan)
                    <tr class="hover:bg-neutral-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900">
                            {{ \Carbon\Carbon::parse($loan->created_at)->format('M d, Y') }}<br>
                            <span class="text-xs text-neutral-500">Starts: {{ \Carbon\Carbon::parse($loan->start_date)->format('M Y') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-neutral-900">
                            ₦{{ number_format($loan->loan_amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                            -₦{{ number_format($loan->monthly_deduction, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $loan->remaining_balance > 0 ? 'text-blue-600' : 'text-emerald-600' }}">
                            ₦{{ number_format($loan->remaining_balance, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($loan->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Pending Approval</span>
                            @elseif($loan->status === 'active')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Active Deduction</span>
                            @elseif($loan->status === 'completed')
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Paid Off</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-neutral-500">
                            <svg class="mx-auto h-12 w-12 text-neutral-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            You have no loan history.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $loans->links() }}
        </div>

        <!-- Application Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-show="openModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity" 
                     aria-hidden="true" 
                     @click="openModal = false">
                    <div class="absolute inset-0 bg-neutral-900 opacity-50"></div>
                </div>

                <div x-show="openModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <form action="{{ route('my-loans.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-xl leading-6 font-bold text-neutral-900 mb-5">Apply for a Loan</h3>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700">Total Loan Amount (₦)</label>
                                        <input type="number" name="loan_amount" min="5000" step="500" required class="mt-1 form-input w-full" placeholder="e.g. 500000">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700">Monthly Deduction (₦)</label>
                                        <input type="number" name="monthly_deduction" min="1000" step="100" required class="mt-1 form-input w-full" placeholder="e.g. 50000">
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700">Deduction Start Date</label>
                                    <input type="date" name="start_date" min="{{ date('Y-m-d') }}" required class="mt-1 form-input w-full">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-neutral-700">Description / Reason</label>
                                    <textarea name="description" rows="3" class="mt-1 form-input w-full" placeholder="Please provide details for this loan request..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="bg-neutral-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-neutral-200">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-neutral-900 text-base font-medium text-white hover:bg-neutral-800 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Submit Request
                            </button>
                            <button type="button" @click="openModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-neutral-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-neutral-700 hover:bg-neutral-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

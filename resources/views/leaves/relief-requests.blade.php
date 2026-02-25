<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-neutral-900">Relief Officer Requests</h2>
            <p class="text-sm text-neutral-500">Manage leave requests where you've been assigned as a relief officer.</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-sm border border-neutral-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-wider">Leave Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-wider">Dates</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-neutral-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-neutral-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-100">
                        @forelse ($requests as $leave)
                        <tr class="hover:bg-neutral-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-neutral-100 flex items-center justify-center text-xs font-bold text-neutral-600">
                                        {{ substr($leave->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-neutral-900">{{ $leave->user->name }}</div>
                                        <div class="text-xs text-neutral-500">{{ $leave->user->employee->job_title ?? 'Employee' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-neutral-700">{{ $leave->leaveType->name }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-600 font-medium">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                </div>
                                <div class="text-xs text-neutral-400">
                                    {{ \App\Helpers\LeaveHelper::calculateWorkingDays($leave->start_date, $leave->end_date) }} working days
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                    {{ $leave->relief_officer_status === 'accepted' ? 'bg-green-100 text-green-800' : 
                                       ($leave->relief_officer_status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($leave->relief_officer_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($leave->relief_officer_status === 'pending')
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('leaves.relief-status', $leave) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="relief_officer_status" value="accepted">
                                        <button type="submit" class="px-3 py-1.5 bg-neutral-900 text-white rounded-lg text-xs font-bold hover:bg-black transition-colors shadow-sm">
                                            Accept
                                        </button>
                                    </form>
                                    <form action="{{ route('leaves.relief-status', $leave) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="relief_officer_status" value="rejected">
                                        <button type="submit" class="px-3 py-1.5 border border-neutral-200 text-neutral-600 rounded-lg text-xs font-bold hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-colors">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                                @else
                                <a href="{{ route('leaves.show', $leave) }}" class="text-neutral-400 hover:text-black transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-10 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-neutral-50 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <p class="text-neutral-500 font-medium">No relief requests found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
            <div class="px-6 py-4 border-t border-neutral-100">
                {{ $requests->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

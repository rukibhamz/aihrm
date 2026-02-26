<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-6 flex items-center justify-between">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('leaves.index') }}" class="inline-flex items-center text-sm font-medium text-neutral-500 hover:text-black">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to List
                        </a>
                    </li>
                </ol>
            </nav>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                    {{ $leaf->status === 'approved' ? 'bg-green-100 text-green-800' : 
                       ($leaf->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst($leaf->status) }}
                </span>
            </div>
        </div>

        <div class="bg-white shadow-sm border border-neutral-200 rounded-xl overflow-hidden">
            <div class="px-6 py-5 border-b border-neutral-100 bg-neutral-50/50 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-neutral-900">Leave Request Details</h3>
                    <p class="text-sm text-neutral-500">Submitted on {{ $leaf->created_at->format('M d, Y') }}</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Reference</span>
                    <p class="text-sm font-mono text-neutral-600">LR-{{ str_pad($leaf->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Basic Info -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-medium text-neutral-400 uppercase tracking-wider mb-1">Leave Type</label>
                            <p class="text-base font-semibold text-neutral-900">{{ $leaf->leaveType->name }}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-neutral-400 uppercase tracking-wider mb-1">Start Date</label>
                                <p class="text-base font-medium text-neutral-900">{{ \Carbon\Carbon::parse($leaf->start_date)->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-neutral-400 uppercase tracking-wider mb-1">End Date</label>
                                <p class="text-base font-medium text-neutral-900">{{ \Carbon\Carbon::parse($leaf->end_date)->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-neutral-400 uppercase tracking-wider mb-1">Duration</label>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-base font-semibold text-neutral-900">
                                    {{ \App\Helpers\LeaveHelper::calculateWorkingDays($leaf->start_date, $leaf->end_date) }} Working Days
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs font-medium text-neutral-400 uppercase tracking-wider mb-1">Employee</label>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-neutral-100 rounded-full flex items-center justify-center text-neutral-500 font-bold border border-neutral-200">
                                    {{ substr($leaf->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-neutral-900">{{ $leaf->user->name }}</p>
                                    <p class="text-xs text-neutral-500">{{ $leaf->user->employee->job_title ?? 'Employee' }}</p>
                                </div>
                            </div>
                        </div>

                        @if($leaf->reliefOfficer)
                        <div>
                            <label class="block text-xs font-medium text-neutral-400 uppercase tracking-wider mb-1">Relief Officer</label>
                            <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg border border-neutral-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-xs font-bold border border-neutral-200">
                                        {{ substr($leaf->reliefOfficer->name, 0, 1) }}
                                    </div>
                                    <p class="text-sm font-medium text-neutral-900">{{ $leaf->reliefOfficer->name }}</p>
                                </div>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full 
                                    {{ $leaf->relief_officer_status === 'accepted' ? 'bg-green-100 text-green-700' : 
                                       ($leaf->relief_officer_status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ ucfirst($leaf->relief_officer_status ?? 'pending') }}
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-neutral-100">
                    <label class="block text-xs font-medium text-neutral-400 uppercase tracking-wider mb-2">Reason for Leave</label>
                    <div class="bg-neutral-50 p-4 rounded-xl text-neutral-700 text-sm leading-relaxed border border-neutral-100 italic">
                        "{{ $leaf->reason }}"
                    </div>
                </div>

                @if($leaf->status === 'rejected' && $leaf->rejection_reason)
                <div class="mt-6 p-4 bg-red-50 border border-red-100 rounded-xl">
                    <label class="block text-xs font-bold text-red-600 uppercase tracking-wider mb-1">Rejection Remarks</label>
                    <p class="text-sm text-red-800">{{ $leaf->rejection_reason }}</p>
                </div>
                @endif
            </div>
        </div>

        @if($leaf->status === 'pending')
        <div class="mt-6 flex justify-end gap-3">
            <form method="POST" action="{{ route('leaves.destroy', $leaf) }}" onsubmit="return confirm('Are you sure you want to cancel this leave request? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 text-sm font-bold text-neutral-600 hover:text-red-600 transition-colors">
                    Cancel Request
                </button>
            </form>
            <a href="{{ route('leaves.edit', $leaf) }}" class="px-6 py-2 bg-neutral-900 text-white rounded-lg text-sm font-bold hover:bg-black transition-colors shadow-lg shadow-neutral-200">
                Edit Request
            </a>
        </div>
        @endif
    </div>
</x-app-layout>

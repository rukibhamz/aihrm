<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Resignation Requests') }}
        </h2>
    </x-slot>

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-neutral-800">
        Resignation Requests
    </h2>
    <div class="flex gap-2">
        <a href="{{ route('admin.resignations.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ !request('status') ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">All</a>
        <a href="{{ route('admin.resignations.index', ['status' => 'pending']) }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Pending</a>
        <a href="{{ route('admin.resignations.index', ['status' => 'approved']) }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ request('status') == 'approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Approved</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($resignations as $resignation)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                         <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold mr-3">
                            {{ substr($resignation->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $resignation->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $resignation->user->employee->designation->title ?? 'Employee' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-xs text-gray-500">Submitted: {{ $resignation->resignation_date->format('M d') }}</div>
                    <div class="text-sm font-medium text-gray-900">Last Day: {{ $resignation->last_working_day->format('M d, Y') }}</div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-500 truncate max-w-xs" title="{{ $resignation->reason }}">
                        {{ $resignation->reason }}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $resignation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $resignation->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $resignation->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $resignation->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                        {{ ucfirst($resignation->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('admin.resignations.show', $resignation) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Review</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                    No resignation requests found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $resignations->withQueryString()->links() }}
</div>
</x-app-layout>

<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Overtime Policies</h1>
            <p class="mt-1 text-sm text-neutral-500">Configure how overtime is calculated across the organization.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.overtime-policies.create') }}" class="btn-primary">
                + Create Policy
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card overflow-hidden">
        <x-table>
            <x-slot name="head">
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Policy Name</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Standard Daily Hours</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Weekday Mult.</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Weekend Mult.</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Holiday Mult.</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
            </x-slot>

            <x-slot name="body">
                @forelse($policies as $policy)
                    <tr class="hover:bg-neutral-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-neutral-900">
                            {{ $policy->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $policy->standard_daily_hours }} hrs
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $policy->weekday_multiplier }}x
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $policy->weekend_multiplier }}x
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $policy->holiday_multiplier }}x
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $policy->is_active ? 'bg-green-100 text-green-800' : 'bg-neutral-100 text-neutral-800' }}">
                                {{ $policy->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.overtime-policies.edit', $policy) }}" class="text-blue-600 hover:text-blue-900 transition-colors">Edit</a>
                                <form action="{{ route('admin.overtime-policies.destroy', $policy) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this policy?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-neutral-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-neutral-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-lg font-medium text-neutral-900">No overtime policies found</p>
                                <p class="text-sm mt-1 mb-4">Create a policy to start calculating overtime automatically.</p>
                                <a href="{{ route('admin.overtime-policies.create') }}" class="btn-primary">Create Policy</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table>
    </div>
</x-app-layout>

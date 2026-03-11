<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Tax Reliefs</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage tax reliefs that can be assigned to employees.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.tax-reliefs.create') }}" class="btn-primary">
                + Create Tax Relief
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
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Relief Name</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Assignments</th>
                <th class="px-6 py-4 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
            </x-slot>

            <x-slot name="body">
                @forelse($reliefs as $relief)
                    <tr class="hover:bg-neutral-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-neutral-900">{{ $relief->name }}</div>
                            <div class="text-xs text-neutral-500 truncate max-w-xs">{{ $relief->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ str_replace('_', ' ', Str::title($relief->type)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                            {{ $relief->type === 'fixed_amount' ? '₦'.number_format($relief->amount) : $relief->amount.'%' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $relief->is_active ? 'bg-green-100 text-green-800' : 'bg-neutral-100 text-neutral-800' }}">
                                {{ $relief->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $relief->employees()->count() }} employee(s)
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.tax-reliefs.edit', $relief) }}" class="text-blue-600 hover:text-blue-900 transition-colors">Edit</a>
                                <form action="{{ route('admin.tax-reliefs.destroy', $relief) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this tax relief? It will be removed from all assigned employees.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-neutral-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-neutral-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                </svg>
                                <p class="text-lg font-medium text-neutral-900">No tax reliefs found</p>
                                <p class="text-sm mt-1 mb-4">Get started by creating a new tax relief policy.</p>
                                <a href="{{ route('admin.tax-reliefs.create') }}" class="btn-primary">Create Tax Relief</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Salary Structures</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage employee compensation packages</p>
        </div>
        <a href="{{ route('admin.salary.create') }}" class="btn-primary">
            Assign Salary
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Base Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Allowances</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Deductions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Net Salary</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse($structures as $structure)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-neutral-900">{{ $structure->user->name }}</div>
                        <div class="text-xs text-neutral-500">{{ $structure->user->employee->designation->title ?? 'N/A' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                        {{ number_format($structure->base_salary, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                        +{{ number_format($structure->housing_allowance + $structure->transport_allowance + $structure->other_allowances, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                        -{{ number_format($structure->pension_employee + $structure->tax_paye, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-neutral-900">
                        {{ number_format($structure->net_salary, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.salary.edit', $structure) }}" class="text-neutral-600 hover:text-black">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-neutral-500">No salary structures found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $structures->links() }}
    </div>
</x-app-layout>

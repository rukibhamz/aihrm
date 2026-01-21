<x-app-layout>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Salary Structures</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage base salaries and allowances for employees</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Base Salary</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Net Salary (Est.)</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-100">
                @forelse ($users as $user)
                <tr class="hover:bg-neutral-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-neutral-900 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-semibold">{{ substr($user->name, 0, 2) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-neutral-900">{{ $user->name }}</div>
                                <div class="text-xs text-neutral-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                        {{ $user->employee->department->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-neutral-900">
                        {{ $user->salaryStructure ? number_format($user->salaryStructure->base_salary) : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-neutral-600">
                        {{ $user->salaryStructure ? number_format($user->salaryStructure->net_salary) : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.salary.edit', $user) }}" class="text-blue-600 hover:text-blue-900">Manage</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-neutral-500">
                        No employees found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-app-layout>

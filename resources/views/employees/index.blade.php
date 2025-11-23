<x-app-layout>
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Employees</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage your team members</p>
        </div>
        @can('create employees')
        <a href="{{ route('employees.create') }}" class="btn-primary inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Employee
        </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Employees Table -->
    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Designation</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-100">
                @forelse ($employees as $employee)
                <tr class="hover:bg-neutral-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-neutral-900 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-sm font-semibold">{{ substr($employee->user->name ?? 'U', 0, 2) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-neutral-900">{{ $employee->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-neutral-500">{{ $employee->user->email ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                        {{ $employee->department->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                        {{ $employee->designation->title ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-neutral-100 text-neutral-800' }}">
                            {{ ucfirst($employee->status ?? 'inactive') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('employees.show', $employee) }}" class="text-neutral-600 hover:text-black transition">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="text-neutral-400">
                            <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-sm font-medium">No employees found</p>
                            <p class="text-xs mt-1">Get started by adding your first employee</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>

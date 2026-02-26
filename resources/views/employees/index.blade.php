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
        <div class="overflow-x-auto">
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
                            <div class="flex items-center justify-end gap-3">
                                <button onclick="openResetModal('{{ $employee->id }}', '{{ $employee->user->name }}')" class="text-xs text-neutral-500 hover:text-red-600 transition">Reset Password</button>
                                <a href="{{ route('employees.show', $employee) }}" class="text-neutral-600 hover:text-black transition">View</a>
                            </div>
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
    </div>

    <!-- Reset Password Modal -->
    <div id="resetModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-neutral-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeResetModal()"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-neutral-100">
                <form id="resetForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="bg-white p-8">
                        <div class="text-center mb-8">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-rose-50 mb-4 border border-rose-100">
                                <svg class="h-8 w-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-neutral-900" id="modal-title">Reset Password</h3>
                            <p class="text-sm text-neutral-500 mt-2">
                                Enter a new secure password for <span id="employeeName" class="font-semibold text-neutral-900"></span>.
                            </p>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-neutral-700 uppercase tracking-wider mb-2">New Password</label>
                                <input type="password" name="password" required 
                                    class="w-full px-4 py-3 bg-neutral-50 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-neutral-900 focus:border-neutral-900 transition-all text-sm"
                                    placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-neutral-700 uppercase tracking-wider mb-2">Confirm New Password</label>
                                <input type="password" name="password_confirmation" required 
                                    class="w-full px-4 py-3 bg-neutral-50 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-neutral-900 focus:border-neutral-900 transition-all text-sm"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <div class="bg-neutral-50 px-8 py-6 flex flex-col sm:flex-row-reverse gap-3 border-t border-neutral-100">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-all shadow-lg shadow-rose-200">
                            Update Password
                        </button>
                        <button type="button" onclick="closeResetModal()" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-neutral-200 text-sm font-bold rounded-xl text-neutral-700 bg-white hover:bg-neutral-50 transition-all">
                            Keep Current
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openResetModal(id, name) {
            document.getElementById('resetModal').classList.remove('hidden');
            document.getElementById('employeeName').innerText = name;
            
            // Build absolute URL using Laravel's route helper
            const urlPattern = "{{ route('employees.reset-password', ':id') }}";
            document.getElementById('resetForm').action = urlPattern.replace(':id', id);
        }

        function closeResetModal() {
            document.getElementById('resetModal').classList.add('hidden');
        }
    </script>
</x-app-layout>

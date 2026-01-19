<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <!-- Personal Information -->
            <div class="card p-6">
                <h2 class="text-lg font-semibold mb-4">Personal Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-neutral-500">Email</div>
                        <div class="font-medium">{{ $employee->user->email ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-neutral-500">Phone</div>
                        <div class="font-medium">{{ $employee->phone ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-neutral-500">Date of Birth</div>
                        <div class="font-medium">{{ $employee->dob ? \Carbon\Carbon::parse($employee->dob)->format('M d, Y') : 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-neutral-500">Join Date</div>
                        <div class="font-medium">{{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('M d, Y') : 'N/A' }}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-sm text-neutral-500">Address</div>
                        <div class="font-medium">{{ $employee->address ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <!-- Employment Details -->
            <div class="card p-6">
                <h2 class="text-lg font-semibold mb-4">Employment Details</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-neutral-500">Department</div>
                        <div class="font-medium">{{ $employee->department->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-neutral-500">Designation</div>
                        <div class="font-medium">{{ $employee->designation->title ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-neutral-500">Status</div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-neutral-100 text-neutral-800' }}">
                                {{ ucfirst($employee->status ?? 'inactive') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-neutral-500">Manager</div>
                        <div class="font-medium">{{ $employee->manager->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Profile Photo -->
            <div class="card p-6 text-center">
                <div class="w-32 h-32 bg-neutral-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white text-4xl font-semibold">{{ substr($employee->user->name ?? 'U', 0, 2) }}</span>
                </div>
                <h3 class="font-semibold text-lg">{{ $employee->user->name ?? 'N/A' }}</h3>
                <p class="text-sm text-neutral-500">{{ $employee->user->email ?? 'N/A' }}</p>
            </div>

            <!-- Quick Actions -->
            <div class="card p-6">
                <h3 class="font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    @can('edit employees')
                    <a href="{{ route('employees.edit', $employee) }}" class="block w-full btn-secondary text-center">Edit Profile</a>
                    @endcan
                    <a href="{{ route('leaves.index') }}?user_id={{ $employee->user_id }}" class="block w-full btn-secondary text-center">Leave History</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

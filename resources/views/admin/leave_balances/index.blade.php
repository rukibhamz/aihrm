<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Leave Balances</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage employee leave balances</p>
        </div>
        
        <!-- Search -->
        <form method="GET" action="{{ route('admin.leave-balances.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Employee..." class="px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent">
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-900 rounded-md hover:bg-neutral-800 transition-all">Search</button>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-neutral-600">
                <thead class="bg-neutral-50 border-b border-neutral-200 font-medium text-neutral-900">
                    <tr>
                        <th class="px-6 py-4">Employee</th>
                        <th class="px-6 py-4">Department</th>
                        @foreach($leaveTypes as $type)
                            <th class="px-6 py-4">{{ $type->name }} (Used/Total)</th>
                        @endforeach
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse ($users as $user)
                    <tr class="hover:bg-neutral-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-neutral-900">{{ $user->name }}</div>
                            <div class="text-xs text-neutral-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            {{ $user->employee->department->name ?? 'N/A' }}
                        </td>
                        @foreach($leaveTypes as $type)
                            @php
                                $balance = $user->leaveBalances->where('leave_type_id', $type->id)->first();
                                $used = $balance ? $balance->used_days : 0;
                                $total = $balance ? $balance->total_days : $type->days_allowed;
                            @endphp
                            <td class="px-6 py-4">
                                <span class="{{ $used > $total ? 'text-red-600 font-bold' : '' }}">
                                    {{ $used }}
                                </span> / {{ $total }}
                            </td>
                        @endforeach
                        <td class="px-6 py-4 text-right">
                            <button x-data="" @click="$dispatch('open-modal', 'edit-balance-{{ $user->id }}')" class="text-neutral-600 hover:text-black transition font-medium">Adjust</button>

                            <!-- Edit Modal -->
                            <x-modal name="edit-balance-{{ $user->id }}" focusable maxWidth="md">
                                <form method="POST" action="{{ route('admin.leave-balances.update', $user) }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <!-- Modal Header -->
                                    <div class="px-6 pt-6 pb-4">
                                        <h2 class="text-xl font-semibold text-neutral-900">Adjust Balances</h2>
                                        <p class="mt-1 text-sm text-neutral-500">{{ $user->name }}</p>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="px-6 py-4 space-y-4 max-h-[60vh] overflow-y-auto">
                                        @foreach($leaveTypes as $index => $type)
                                            @php
                                                $balance = $user->leaveBalances->where('leave_type_id', $type->id)->first();
                                                $used = $balance ? $balance->used_days : 0;
                                                $total = $balance ? $balance->total_days : $type->days_allowed;
                                            @endphp
                                            <div class="border border-neutral-200 p-4 rounded-lg">
                                                <h3 class="font-medium text-neutral-900 mb-3">{{ $type->name }}</h3>
                                                <input type="hidden" name="balances[{{ $index }}][leave_type_id]" value="{{ $type->id }}">
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-xs font-medium text-neutral-500 mb-1">Total Days</label>
                                                        <input type="number" name="balances[{{ $index }}][total_days]" value="{{ $total }}" required class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-neutral-500 mb-1">Used Days</label>
                                                        <input type="number" name="balances[{{ $index }}][used_days]" value="{{ $used }}" required class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class="px-6 py-4 flex justify-end gap-2 border-t border-neutral-100">
                                        <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 transition-colors">CANCEL</button>
                                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-900 rounded-md hover:bg-neutral-800 transition-all">SAVE CHANGES</button>
                                    </div>
                                </form>
                            </x-modal>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($leaveTypes) + 3 }}" class="px-6 py-8 text-center text-neutral-500">No employees found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>

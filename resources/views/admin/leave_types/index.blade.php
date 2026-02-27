<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Leave Types</h1>
            <p class="mt-1 text-sm text-neutral-500">Manage leave types and policies</p>
        </div>
        <button x-data="" @click="$dispatch('open-modal', 'create-leave-type')" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Leave Type
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="card overflow-hidden">
        <table class="w-full text-left text-sm text-neutral-600">
            <thead class="bg-neutral-50 border-b border-neutral-200 font-medium text-neutral-900">
                <tr>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Days Allowed</th>
                    <th class="px-6 py-4">Paid</th>
                    <th class="px-6 py-4">Requests</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse ($leaveTypes as $type)
                <tr class="hover:bg-neutral-50 transition">
                    <td class="px-6 py-4 font-medium text-neutral-900">{{ $type->name }}</td>
                    <td class="px-6 py-4">{{ $type->days_allowed }}</td>
                    <td class="px-6 py-4">
                        @if($type->is_paid)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Yes</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">No</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $type->requests_count }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button x-data="" @click="$dispatch('open-modal', 'edit-leave-type-{{ $type->id }}')" class="text-neutral-600 hover:text-black transition">Edit</button>
                            <form action="{{ route('admin.leave-types.destroy', $type) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition">Delete</button>
                            </form>
                        </div>

                        <!-- Edit Modal -->
                        <x-modal name="edit-leave-type-{{ $type->id }}" focusable maxWidth="md">
                            <form method="POST" action="{{ route('admin.leave-types.update', $type) }}">
                                @csrf
                                @method('PUT')
                                
                                <!-- Modal Header -->
                                <div class="px-6 pt-6 pb-4">
                                    <h2 class="text-xl font-semibold text-neutral-900">Edit Leave Type</h2>
                                </div>

                                <!-- Modal Body -->
                                <div class="px-6 py-4 space-y-4">
                                    <div>
                                        <label for="edit-name-{{ $type->id }}" class="block text-sm font-medium text-neutral-700 mb-1.5">Name</label>
                                        <input type="text" id="edit-name-{{ $type->id }}" name="name" value="{{ $type->name }}" required class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                                    </div>
                                    <div>
                                        <label for="edit-days-{{ $type->id }}" class="block text-sm font-medium text-neutral-700 mb-1.5">Days Allowed (Per Year)</label>
                                        <input type="number" id="edit-days-{{ $type->id }}" name="days_allowed" value="{{ $type->days_allowed }}" required class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                                    </div>
                                    <div class="flex items-center pt-2">
                                        <input type="hidden" name="is_paid" value="0">
                                        <input type="checkbox" id="edit-paid-{{ $type->id }}" name="is_paid" value="1" {{ $type->is_paid ? 'checked' : '' }} class="h-4 w-4 text-black focus:ring-black border-neutral-300 rounded">
                                        <label for="edit-paid-{{ $type->id }}" class="ml-2 block text-sm text-neutral-900">Is Paid Leave?</label>
                                    </div>
                                    <div class="pt-2">
                                        <label class="block text-sm font-medium text-neutral-700 mb-1.5">Eligible Employment Statuses</label>
                                        <div class="grid grid-cols-2 gap-2 mt-2">
                                            @foreach($employmentStatuses as $status)
                                                <div class="flex items-center">
                                                    <input type="checkbox" id="edit-status-{{ $type->id }}-{{ $status->id }}" name="employment_statuses[]" value="{{ $status->id }}" {{ $type->employmentStatuses->contains('id', $status->id) ? 'checked' : '' }} class="h-4 w-4 text-black focus:ring-black border-neutral-300 rounded">
                                                    <label for="edit-status-{{ $type->id }}-{{ $status->id }}" class="ml-2 block text-sm text-neutral-700">{{ $status->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="pt-4 border-t border-neutral-100">
                                        <label class="block text-sm font-medium text-neutral-700 mb-2">Grade-Level Days Override (Optional)</label>
                                        <div class="grid grid-cols-2 gap-4">
                                            @foreach($gradeLevels as $grade)
                                                @php
                                                    $existingOverride = $type->grades->where('grade_level_id', $grade->id)->first();
                                                @endphp
                                                <div>
                                                    <label for="edit-grade-{{ $type->id }}-{{ $grade->id }}" class="block text-xs text-neutral-600 mb-1">{{ $grade->name }}</label>
                                                    <input type="number" id="edit-grade-{{ $type->id }}-{{ $grade->id }}" name="grade_levels[{{ $grade->id }}]" value="{{ $existingOverride ? $existingOverride->days_allowed : '' }}" min="0" placeholder="Default" class="w-full px-3 py-1.5 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="edit-desc-{{ $type->id }}" class="block text-sm font-medium text-neutral-700 mb-1.5">Description</label>
                                        <textarea id="edit-desc-{{ $type->id }}" name="description" rows="3" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">{{ $type->description }}</textarea>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="px-6 py-4 flex justify-end gap-2 border-t border-neutral-100">
                                    <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 transition-colors">CANCEL</button>
                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-900 rounded-md hover:bg-neutral-800 transition-all">UPDATE</button>
                                </div>
                            </form>
                        </x-modal>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-neutral-500">No leave types found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <x-modal name="create-leave-type" focusable maxWidth="md">
        <form method="POST" action="{{ route('admin.leave-types.store') }}">
            @csrf
            
            <!-- Modal Header -->
            <div class="px-6 pt-6 pb-4">
                <h2 class="text-xl font-semibold text-neutral-900">Add Leave Type</h2>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700 mb-1.5">Name</label>
                    <input type="text" id="name" name="name" required class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                </div>
                <div>
                    <label for="days_allowed" class="block text-sm font-medium text-neutral-700 mb-1.5">Days Allowed (Per Year)</label>
                    <input type="number" id="days_allowed" name="days_allowed" required class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                </div>
                <div class="flex items-center pt-2">
                    <input type="hidden" name="is_paid" value="0">
                    <input type="checkbox" id="is_paid" name="is_paid" value="1" checked class="h-4 w-4 text-black focus:ring-black border-neutral-300 rounded">
                    <label for="is_paid" class="ml-2 block text-sm text-neutral-900">Is Paid Leave?</label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Eligible Employment Statuses</label>
                    <div class="grid grid-cols-2 gap-2 mt-2">
                        @foreach($employmentStatuses as $status)
                            <div class="flex items-center">
                                <input type="checkbox" id="create-status-{{ $status->id }}" name="employment_statuses[]" value="{{ $status->id }}" checked class="h-4 w-4 text-black focus:ring-black border-neutral-300 rounded">
                                <label for="create-status-{{ $status->id }}" class="ml-2 block text-sm text-neutral-700">{{ $status->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-neutral-500 mt-1">If no statuses are selected, no one can apply for this leave type.</p>
                </div>
                
                <div class="pt-4 border-t border-neutral-100">
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Grade-Level Days Override (Optional)</label>
                    <p class="text-xs text-neutral-500 mb-3">Leave blank to use the default days allowed above.</p>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($gradeLevels as $grade)
                            <div>
                                <label for="create-grade-{{ $grade->id }}" class="block text-xs text-neutral-600 mb-1">{{ $grade->name }}</label>
                                <input type="number" id="create-grade-{{ $grade->id }}" name="grade_levels[{{ $grade->id }}]" min="0" placeholder="Default" class="w-full px-3 py-1.5 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-neutral-700 mb-1.5">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-2 focus:ring-black focus:border-transparent transition-all"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 flex justify-end gap-2 border-t border-neutral-100">
                <button type="button" @click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50 transition-colors">CANCEL</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-neutral-900 rounded-md hover:bg-neutral-800 transition-all">CREATE</button>
            </div>
        </form>
    </x-modal>
</x-app-layout>

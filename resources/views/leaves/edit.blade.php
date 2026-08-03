<x-app-layout>
    <div class="mb-8 max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Edit Leave Request</h1>
        <p class="mt-1 text-sm text-neutral-500">Update your pending leave request details</p>
    </div>

    <div class="max-w-2xl mx-auto card overflow-hidden">
        <div class="p-6">
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('leaves.update', $leaf) }}">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="block text-neutral-700 text-sm font-bold mb-2" for="leave_type_id">Leave Type</label>
                    <select name="leave_type_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-neutral-700 leading-tight focus:outline-none focus:shadow-outline bg-white">
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ (old('leave_type_id', $leaf->leave_type_id) == $type->id) ? 'selected' : '' }}>
                                {{ $type->name }} ({{ $type->days_allowed }} days)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-neutral-700 text-sm font-bold mb-2" for="start_date">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $leaf->start_date->format('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-neutral-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div>
                        <label class="block text-neutral-700 text-sm font-bold mb-2" for="end_date">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $leaf->end_date->format('Y-m-d')) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-neutral-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-neutral-700 text-sm font-bold mb-2" for="reason">Reason</label>
                    <textarea name="reason" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-neutral-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('reason', $leaf->reason) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-neutral-700 text-sm font-bold mb-2" for="handover_note">Handover Note</label>
                    <textarea name="handover_note" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-neutral-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('handover_note', $leaf->handover_note) }}</textarea>
                    <p class="text-xs text-neutral-500 mt-1">Provide key handover details for continuity while on leave.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-neutral-700 text-sm font-bold mb-2" for="relief_officer_id">Relief Officer (Optional)</label>
                    <select name="relief_officer_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-neutral-700 leading-tight focus:outline-none focus:shadow-outline bg-white">
                        <option value="">Select a Relief Officer</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (old('relief_officer_id', $leaf->relief_officer_id) == $user->id) ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-neutral-500 mt-1">The relief officer will be notified to accept your request.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-6">
                    <button type="submit" class="btn-primary">
                        Update Request
                    </button>
                    <a href="{{ route('leaves.index') }}" class="btn-secondary text-center mt-3 sm:mt-0 sm:ml-3">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

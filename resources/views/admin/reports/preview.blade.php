<x-app-layout>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Report Preview</h1>
            <p class="mt-1 text-sm text-neutral-500">Previewing {{ count($data) }} records for {{ ucfirst($entity) }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.reports.builder') }}" class="btn-secondary">Back to Builder</a>
            <form action="{{ route('admin.reports.generate') }}" method="POST">
                @csrf
                <input type="hidden" name="entity" value="{{ $entity }}">
                @foreach($fields as $field)
                    <input type="hidden" name="fields[]" value="{{ $field }}">
                @endforeach
                <!-- Re-pass other filters if needed, or store in session. For now, simple re-submission for export -->
                <!-- Ideally, we should persist the request parameters. For simplicity, we'll just use the back button or re-submit the form from the builder page for export. -->
                <!-- But wait, the user is on the preview page. They might want to export directly. -->
                <!-- Let's just make the "Export CSV" button on the builder page the primary way to export. -->
                <!-- Or we can embed the filters as hidden inputs here. -->
            </form>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @foreach($fields as $field)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ ucwords(str_replace('_', ' ', $field)) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($data as $row)
                        <tr>
                            @foreach($fields as $field)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @php
                                        // Logic duplicated from controller for view. Ideally should be a helper or model accessor.
                                        // For now, simple direct access or basic formatting.
                                        $value = $row->$field ?? 'N/A';
                                        
                                        // Handle specific fields
                                        if ($field === 'employee_name') {
                                            $value = $row->user->name ?? $row->name ?? 'N/A';
                                        } elseif ($field === 'department') {
                                            $value = $row->department->name ?? 'N/A';
                                        } elseif ($field === 'designation') {
                                            $value = $row->designation->title ?? 'N/A';
                                        } elseif ($field === 'leave_type') {
                                            $value = $row->leaveType->name ?? 'N/A';
                                        } elseif (in_array($field, ['net_salary', 'basic_salary'])) {
                                            $value = number_format($row->$field, 2);
                                        } elseif (in_array($field, ['date', 'join_date', 'start_date', 'end_date', 'payment_date'])) {
                                            $value = $row->$field ? \Carbon\Carbon::parse($row->$field)->format('M d, Y') : 'N/A';
                                        }
                                    @endphp
                                    {{ $value }}
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($fields) }}" class="px-6 py-4 text-center text-gray-500">No records found matching your criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

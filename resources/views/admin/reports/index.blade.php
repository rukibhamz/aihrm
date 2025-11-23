<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Whistleblowing Reports</h1>
        <p class="mt-1 text-sm text-neutral-500">Confidential reports submitted securely</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Reference ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse($reports as $report)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                        {{ $report->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                        {{ $report->subject }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-neutral-500">
                        {{ $report->anonymous_id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $report->status === 'new' ? 'bg-red-100 text-red-800' : 'bg-neutral-100 text-neutral-800' }}">
                            {{ ucfirst($report->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="viewReport('{{ $report->subject }}', '{{ e($report->description) }}')" class="text-neutral-600 hover:text-black">View Details</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-neutral-500">No reports found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- View Modal -->
    <dialog id="viewReportModal" class="p-0 rounded-lg shadow-xl backdrop:bg-black/50 w-full max-w-2xl">
        <div class="p-6">
            <h3 class="text-lg font-bold mb-4" id="modalSubject"></h3>
            <div class="bg-neutral-50 p-4 rounded-lg mb-6">
                <p class="text-sm text-neutral-700 whitespace-pre-wrap" id="modalDescription"></p>
            </div>
            <div class="flex justify-end gap-3">
                <button onclick="document.getElementById('viewReportModal').close()" class="btn-secondary">Close</button>
            </div>
        </div>
    </dialog>

    <script>
        function viewReport(subject, description) {
            document.getElementById('modalSubject').textContent = subject;
            document.getElementById('modalDescription').textContent = description;
            document.getElementById('viewReportModal').showModal();
        }
    </script>
</x-app-layout>

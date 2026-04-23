<x-app-layout>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Applications</h1>
            <p class="mt-1 text-sm text-neutral-500">Review and update recruitment applications.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.applications.kanban') }}" class="btn-secondary">Kanban Board</a>
            <a href="{{ route('admin.jobs.create') }}" class="btn-primary">Post Job</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="card overflow-hidden">
        <x-table>
            <x-slot name="head">
                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Candidate</th>
                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Job</th>
                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Status</th>
                <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Applied</th>
                <th class="px-6 py-4 text-right text-xs font-medium uppercase tracking-wider text-neutral-500">Actions</th>
            </x-slot>

            <x-slot name="body">
                @forelse($applications as $application)
                    <tr class="hover:bg-neutral-50/50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-neutral-900">{{ $application->candidate_name }}</div>
                            <div class="text-xs text-neutral-500">{{ $application->candidate_email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-700">
                            {{ $application->jobPosting->title ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.applications.status', $application) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="rounded-md border-neutral-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    @foreach(['applied', 'screening', 'interview', 'offer', 'hired', 'rejected'] as $status)
                                        <option value="{{ $status }}" {{ $application->status === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="text-xs font-medium text-blue-700 hover:text-blue-900">Save</button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm text-neutral-600">
                            {{ $application->created_at?->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.applications.show', $application) }}" class="text-sm text-blue-600 hover:text-blue-800">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-neutral-500">
                            No applications available.
                        </td>
                    </tr>
                @endforelse
            </x-slot>
        </x-table>
    </div>

    @if(method_exists($applications, 'links'))
        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    @endif
</x-app-layout>

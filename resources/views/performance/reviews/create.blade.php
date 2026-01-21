<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Start Review Cycle</h1>
        <p class="mt-1 text-sm text-neutral-500">Initiate a performance review for an employee</p>
    </div>

    <div class="card p-8 max-w-xl">
        <form method="POST" action="{{ route('performance.reviews.store') }}" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Employee *</label>
                <select name="reviewee_id" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    <option value="">Select Employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->user_id }}">{{ $emp->user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Period *</label>
                <input type="text" name="period" placeholder="e.g., Q4 2025" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Type *</label>
                <select name="type" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    <option value="manager">Manager Review</option>
                    <option value="peer">Peer Review</option>
                </select>
            </div>

            <div class="flex gap-3 pt-6 border-t border-neutral-200">
                <button type="submit" class="btn-primary w-full justify-center">Start Review</button>
            </div>
        </form>
    </div>
</x-app-layout>

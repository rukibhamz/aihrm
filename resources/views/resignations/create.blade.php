<x-app-layout>
    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 overflow-hidden">
            <div class="p-6 border-b border-neutral-100 bg-neutral-50">
                <h2 class="text-xl font-bold text-neutral-900 mb-1">Submit Resignation</h2>
                <p class="text-sm text-neutral-500">We're sorry to see you go. Please fill out the details below to initiate the exit process.</p>
            </div>
            
            <form action="{{ route('resignations.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Resignation Date</label>
                    <input type="date" name="resignation_date" value="{{ date('Y-m-d') }}" readonly class="w-full px-4 py-2 bg-neutral-100 border border-neutral-200 rounded-lg text-neutral-500 cursor-not-allowed">
                    <p class="text-xs text-neutral-400 mt-1">This is recorded as today's date.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Last Working Day *</label>
                    <input type="date" name="last_working_day" min="{{ date('Y-m-d') }}" required class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-black focus:border-black">
                    <p class="text-xs text-neutral-400 mt-1">Please consult your contract regarding notice period.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Reason for Leaving *</label>
                    <textarea name="reason" rows="4" required minlength="10" placeholder="Please provide a brief explanation..." class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-black focus:border-black"></textarea>
                </div>

                <div class="bg-blue-50 border border-blue-100 p-4 rounded-lg flex gap-3 items-start">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-blue-800">
                        Once submitted, your resignation will be sent to HR for review. You will be contacted for an exit interview and asset handover instructions.
                    </p>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="btn-primary bg-red-600 hover:bg-red-700 border-red-600 text-white" onclick="return confirm('Are you sure you want to submit your resignation? This action cannot be undone.')">
                        Submit Resignation
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

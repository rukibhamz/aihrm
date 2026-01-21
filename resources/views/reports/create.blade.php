<x-guest-layout>
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Secure Reporting Channel</h1>
        <p class="mt-2 text-sm text-neutral-500 max-w-md mx-auto">
            Submit anonymous reports regarding ethics violations, misconduct, or safety concerns. 
            Your identity is protected.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="card p-8 max-w-xl mx-auto">
        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Subject *</label>
                <input type="text" name="subject" required placeholder="e.g., Safety Violation at Warehouse" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Description *</label>
                <textarea name="description" rows="6" required placeholder="Please provide as much detail as possible..." class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Attachment (Optional)</label>
                <input type="file" name="attachment" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                <p class="mt-1 text-xs text-neutral-500">Images or documents supporting your report.</p>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg text-xs text-yellow-800 flex gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <p>This submission is encrypted and anonymous. We do not track your IP address or personal details unless you choose to provide them in the description.</p>
            </div>

            <button type="submit" class="btn-primary w-full justify-center">Submit Report</button>
        </form>
    </div>
    
    <div class="text-center mt-8">
        <a href="{{ route('login') }}" class="text-sm text-neutral-500 hover:text-black">Back to Login</a>
    </div>
</x-guest-layout>

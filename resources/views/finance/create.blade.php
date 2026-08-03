<x-app-layout>
    <div class="mb-8 max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">New Financial Request</h1>
        <p class="mt-1 text-sm text-neutral-500">Submit a new expense claim or reimbursement request</p>
    </div>

    <div class="max-w-2xl mx-auto card overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('finance.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-neutral-700 text-sm font-bold mb-2" for="category_id">
                        Category
                    </label>
                    <select name="category_id" id="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-neutral-700 leading-tight focus:outline-none focus:shadow-outline bg-white">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-neutral-700 text-sm font-bold mb-2" for="amount">
                        Amount (₦)
                    </label>
                    <input type="number" step="0.01" name="amount" id="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-neutral-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @error('amount') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-neutral-700 text-sm font-bold mb-2" for="description">
                        Description / Reason
                    </label>
                    <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-neutral-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                    @error('description') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-neutral-700 text-sm font-bold mb-2" for="attachment">
                        Attachment (Receipt/Invoice)
                    </label>
                    <input type="file" name="attachment" id="attachment" class="shadow appearance-none border rounded w-full py-2 px-3 text-neutral-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('attachment') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <button type="submit" class="btn-primary">
                        Submit Request
                    </button>
                    <a href="{{ route('finance.index') }}" class="btn-secondary text-center mt-3 sm:mt-0 sm:ml-3">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

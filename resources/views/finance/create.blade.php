<x-app-layout>
    <div class="max-w-2xl mx-auto bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mt-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">New Financial Request</h2>
            
            <form method="POST" action="{{ route('finance.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">
                        Category
                    </label>
                    <select name="category_id" id="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">
                        Amount (₦)
                    </label>
                    <input type="number" step="0.01" name="amount" id="amount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @error('amount') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Description / Reason
                    </label>
                    <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
                    @error('description') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="attachment">
                        Attachment (Receipt/Invoice)
                    </label>
                    <input type="file" name="attachment" id="attachment" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('attachment') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Submit Request
                    </button>
                    <a href="{{ route('finance.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

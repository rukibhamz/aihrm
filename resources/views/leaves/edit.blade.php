<x-app-layout>
    <div class="max-w-2xl mx-auto bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mt-6">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('leaves.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-black">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to List
                            </a>
                        </li>
                    </ol>
                </nav>
                <h2 class="text-2xl font-bold text-gray-900">Edit Leave Request</h2>
            </div>

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

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
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="leave_type_id">Leave Type</label>
                    <select name="leave_type_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white">
                        @foreach($leaveTypes as $type)
                            <option value="{{ $type->id }}" {{ $leaf->leave_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }} ({{ $type->days_allowed }} days)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">Start Date</label>
                        <input type="date" name="start_date" value="{{ $leaf->start_date }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="end_date">End Date</label>
                        <input type="date" name="end_date" value="{{ $leaf->end_date }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="reason">Reason</label>
                    <textarea name="reason" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ $leaf->reason }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="relief_officer_id">Relief Officer (Optional)</label>
                    <select name="relief_officer_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white">
                        <option value="">Select a Relief Officer</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $leaf->relief_officer_id == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">The relief officer will be notified to accept your request.</p>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <button type="submit" class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                        Update Request
                    </button>
                    <a href="{{ route('leaves.show', $leaf) }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

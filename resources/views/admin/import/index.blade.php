<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Data Import</h1>
        <p class="mt-1 text-sm text-neutral-500">Bulk import data into the system using CSV files.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Import Employees -->
        <div class="card p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-blue-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold">Import Employees</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6">
                Upload a CSV file containing employee details. Required columns: 
                <code class="bg-gray-100 px-1 py-0.5 rounded">name</code>, 
                <code class="bg-gray-100 px-1 py-0.5 rounded">email</code>, 
                <code class="bg-gray-100 px-1 py-0.5 rounded">department</code>, 
                <code class="bg-gray-100 px-1 py-0.5 rounded">designation</code>, 
                <code class="bg-gray-100 px-1 py-0.5 rounded">join_date</code>.
            </p>
            
            <form action="{{ route('admin.import.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="employees">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select CSV File</label>
                    <input type="file" name="file" accept=".csv" class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100
                    ">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">Upload & Import</button>
                </div>
            </form>
        </div>

        <!-- Future Imports (Placeholder) -->
        <div class="card p-6 opacity-50">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-gray-100 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold">Import Attendance</h3>
            </div>
            <p class="text-sm text-gray-500 mb-6">Coming soon. Bulk import attendance logs from biometric devices.</p>
            <button disabled class="btn-secondary w-full">Coming Soon</button>
        </div>
    </div>
</x-app-layout>

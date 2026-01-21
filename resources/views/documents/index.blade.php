<x-app-layout>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-black">
                    My Documents
                </h2>
                <p class="text-sm text-gray-500">Securely store and access your personal documents.</p>
            </div>
            <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="btn-primary">
                Upload Document
            </button>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Document Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($documents as $doc)
            <div class="group bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                        <!-- Icon based on type/extension generic -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100 text-gray-600 uppercase tracking-wide">
                        {{ ucfirst(str_replace('_', ' ', $doc->type)) }}
                    </span>
                </div>
                
                <h3 class="font-bold text-gray-900 truncate mb-1" title="{{ $doc->title }}">{{ $doc->title }}</h3>
                <p class="text-xs text-gray-500 mb-4">{{ $doc->created_at->format('M d, Y') }}</p>

                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                    <span class="text-xs text-gray-400">
                        {{-- File size logic if stored in DB would go here --}}
                        File
                    </span>
                    <a href="{{ route('documents.download', $doc) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
                        Download <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-12 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="font-medium">No documents found.</p>
                <p class="text-sm mt-1">Upload your ID proofs and certificates to keep them safe.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $documents->links() }}
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Upload Document</h3>
                <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Document Title</label>
                    <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black" placeholder="e.g. Passport Copy">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Document Type</label>
                    <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                        <option value="id_proof">ID Proof</option>
                        <option value="certificate">Certificate</option>
                        <option value="tax_form">Tax Form</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">File</label>
                    <input type="file" name="file" required accept=".pdf,.doc,.docx,.jpg,.png" class="w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-black file:text-white
                        hover:file:bg-gray-800
                    "/>
                    <p class="text-xs text-gray-500 mt-1">PDF, Images, Word docs up to 5MB.</p>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="btn-primary w-full justify-center">Upload</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

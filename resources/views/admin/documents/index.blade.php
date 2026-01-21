<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Document Center') }}
        </h2>
    </x-slot>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-800 to-black">
                Document Center
            </h2>
            <p class="text-sm text-gray-500">Manage employee contracts, offer letters, and compliance docs.</p>
        </div>
        <button onclick="document.getElementById('adminUploadModal').classList.remove('hidden')" class="btn-primary">
            Upload Document
        </button>
    </div>

<!-- Filters -->
<div class="mb-6 bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex gap-4 overflow-x-auto">
    <a href="{{ route('admin.documents.index') }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ !request('type') ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">All</a>
    <a href="{{ route('admin.documents.index', ['type' => 'contract']) }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ request('type') == 'contract' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Contracts</a>
    <a href="{{ route('admin.documents.index', ['type' => 'offer_letter']) }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ request('type') == 'offer_letter' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Offer Letters</a>
    <a href="{{ route('admin.documents.index', ['type' => 'id_proof']) }}" class="px-3 py-1.5 text-sm font-medium rounded-md {{ request('type') == 'id_proof' ? 'bg-black text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">ID Proofs</a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($documents as $doc)
            <tr>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8 rounded bg-gray-100 flex items-center justify-center text-gray-500 mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="text-sm font-medium text-gray-900 truncate max-w-xs" title="{{ $doc->title }}">
                            {{ $doc->title }}
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($doc->user)
                    <div class="flex items-center">
                        <div class="h-6 w-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold mr-2">
                            {{ substr($doc->user->name, 0, 1) }}
                        </div>
                        <div class="text-sm text-gray-900">{{ $doc->user->name }}</div>
                    </div>
                    @else
                    <span class="text-xs text-gray-400">System / Unknown</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-50 text-blue-800">
                        {{ ucfirst(str_replace('_', ' ', $doc->type)) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $doc->created_at->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if($doc->expiry_date)
                        <span class="{{ $doc->expiry_date->isPast() ? 'text-red-600 font-bold' : ($doc->expiry_date->diffInDays(now()) < 30 ? 'text-yellow-600 font-medium' : 'text-gray-500') }}">
                            {{ $doc->expiry_date->format('M d, Y') }}
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end gap-3">
                    <a href="{{ route('admin.documents.download', $doc) }}" class="text-blue-600 hover:text-blue-900" title="Download">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </a>
                    <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Delete this document permanently?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                    No documents found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $documents->withQueryString()->links() }}
</div>

<!-- Admin Upload Modal -->
<div id="adminUploadModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Upload Company Document</h3>
            <button onclick="document.getElementById('adminUploadModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign to Employee</label>
                <select name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                    <option value="">Select Employee...</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Document Title</label>
                    <input type="text" name="title" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black" placeholder="e.g. Contract 2026">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                        <option value="contract">Employment Contract</option>
                        <option value="offer_letter">Offer Letter</option>
                        <option value="nda">NDA</option>
                        <option value="visa">Visa / Permit</option>
                        <option value="policy">Policy Awareness</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date (Optional)</label>
                <input type="date" name="expiry_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-black focus:border-black">
                <p class="text-xs text-gray-500 mt-1">Leave blank if not applicable.</p>
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
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="btn-primary w-full justify-center">Upload & Assign</button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>

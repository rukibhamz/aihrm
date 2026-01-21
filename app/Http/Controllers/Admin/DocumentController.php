<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $query = Document::with('user');

        if (request('user_id')) {
            $query->where('user_id', request('user_id'));
        }
        
        if (request('type')) {
            $query->where('type', request('type'));
        }

        $documents = $query->latest()->paginate(15);
        $users = User::orderBy('name')->get();

        return view('admin.documents.index', compact('documents', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string', // Admin can define types freely or stick to enum
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB
            'expiry_date' => 'nullable|date',
        ]);

        $path = $request->file('file')->store('company_documents', 'public');

        Document::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
            'expiry_date' => $request->expiry_date,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Document uploaded for user.');
    }

    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }
        return Storage::disk('public')->download($document->file_path);
    }

    public function destroy(Document $document)
    {
        // Delete file
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        
        $document->delete();
        return redirect()->back()->with('success', 'Document deleted.');
    }
}

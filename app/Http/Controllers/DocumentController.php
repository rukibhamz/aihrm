<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::where('user_id', Auth::id())
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('documents.index', compact('documents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:id_proof,certificate,tax_form,other',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB max
        ]);

        $path = $request->file('file')->store('documents', 'public');

        Document::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Document uploaded successfully.');
    }

    public function download(Document $document)
    {
        // Security check: only own documents
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($document->file_path, $document->title . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION));
    }
}

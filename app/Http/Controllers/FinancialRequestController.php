<?php

namespace App\Http\Controllers;

use App\Models\FinancialRequest;
use App\Models\FinancialRequestCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FinancialRequestController extends Controller
{
    public function index()
    {
        $requests = FinancialRequest::with('category')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('finance.index', compact('requests'));
    }

    public function create()
    {
        $categories = FinancialRequestCategory::all();
        return view('finance.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:financial_request_categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('financial_attachments', 'public');
        }

        FinancialRequest::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'attachment_path' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('finance.index')->with('success', 'Request submitted successfully.');
    }

    public function show(FinancialRequest $financialRequest)
    {
        // Ensure user can only view their own requests
        if ($financialRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('finance.show', compact('financialRequest'));
    }
}

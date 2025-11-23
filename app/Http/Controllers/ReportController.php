<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('reports', 'public');
        }

        $report = Report::create([
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'attachment' => $path,
            'anonymous_id' => Str::random(10),
            'status' => 'new',
        ]);

        return back()->with('success', 'Report submitted securely. Your Reference ID is: ' . $report->anonymous_id);
    }

    public function index()
    {
        $reports = Report::latest()->paginate(15);
        return view('admin.reports.index', compact('reports'));
    }
}

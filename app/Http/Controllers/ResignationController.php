<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Resignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResignationController extends Controller
{
    public function create()
    {
        // Check if already resigned
        $existing = Resignation::where('user_id', Auth::id())->whereIn('status', ['pending', 'approved'])->first();
        if ($existing) {
            return redirect()->route('resignations.show');
        }

        return view('resignations.create');
    }

    public function store(Request $request)
    {
        $existing = Resignation::where('user_id', Auth::id())->whereIn('status', ['pending', 'approved'])->first();
        if ($existing) {
            return redirect()->route('resignations.show');
        }

        $validated = $request->validate([
            'resignation_date' => 'required|date',
            'last_working_day' => 'required|date|after_or_equal:resignation_date',
            'reason' => 'required|string|min:10',
        ]);

        $resignation = Resignation::create([
            'user_id' => Auth::id(),
            'resignation_date' => $validated['resignation_date'],
            'last_working_day' => $validated['last_working_day'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('resignations.show')->with('success', 'Resignation submitted successfully.');
    }

    public function show()
    {
        $resignation = Resignation::where('user_id', Auth::id())->latest()->first();
        
        if (!$resignation) {
            return redirect()->route('resignations.create');
        }

        return view('resignations.show', compact('resignation'));
    }
}

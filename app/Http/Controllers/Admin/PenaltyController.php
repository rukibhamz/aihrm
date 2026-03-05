<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penalty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenaltyController extends Controller
{
    public function index()
    {
        $penalties = Penalty::with(['user', 'creator'])->latest()->paginate(10);
        $employees = User::whereHas('roles', function($q) {
            $q->where('name', 'employee');
        })->get();
        
        return view('admin.penalties.index', compact('penalties', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:500',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024|max:2030',
        ]);

        Penalty::create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.penalties.index')->with('success', 'Penalty added successfully.');
    }

    public function destroy(Penalty $penalty)
    {
        $penalty->delete();
        return back()->with('success', 'Penalty deleted successfully.');
    }
}

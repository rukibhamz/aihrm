<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\User;
use Illuminate\Http\Request;
class BonusController extends Controller
{
    public function index()
    {
        $bonuses = Bonus::with(['user', 'creator'])->latest()->paginate(10);
        $employees = User::whereHas('roles', function($q) {
            $q->where('name', 'employee');
        })->get();
        
        return view('admin.bonuses.index', compact('bonuses', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:one-time,recurring,performance,sales',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2024|max:2030',
            'description' => 'nullable|string|max:500',
        ]);

        Bonus::create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.bonuses.index')->with('success', 'Bonus added successfully.');
    }

    public function destroy(Bonus $bonus)
    {
        $bonus->delete();
        return back()->with('success', 'Bonus deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resignation;
use App\Models\Asset;
use Illuminate\Http\Request;

class ResignationController extends Controller
{
    public function index()
    {
        $query = Resignation::with('user.employee.designation');

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $resignations = $query->latest()->paginate(10);

        return view('admin.resignations.index', compact('resignations'));
    }

    public function show(Resignation $resignation)
    {
        $resignation->load('user.employee.designation', 'user.employee.department');
        // Fetch assets assigned to this user
        $assets = Asset::where('user_id', $resignation->user_id)->get();
        
        return view('admin.resignations.show', compact('resignation', 'assets'));
    }

    public function update(Request $request, Resignation $resignation)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'hr_comments' => 'nullable|string',
            'exit_interview_notes' => 'nullable|string',
        ]);

        $resignation->update($validated);

        return redirect()->route('admin.resignations.show', $resignation)->with('success', 'Resignation status updated.');
    }

    public function assetsCheck(Resignation $resignation)
    {
        // Dedicated view just for assets if needed, but show view covers it.
        // Keeping this method if we need a specific JSON endpoint or print view later.
        $assets = Asset::where('user_id', $resignation->user_id)->get();
        return view('admin.resignations.assets', compact('resignation', 'assets'));
    }
}

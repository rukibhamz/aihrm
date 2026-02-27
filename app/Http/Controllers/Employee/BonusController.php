<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BonusController extends Controller
{
    /**
     * Display a listing of personal bonuses.
     */
    public function index()
    {
        $bonuses = Bonus::with('creator')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('employee.bonuses.index', compact('bonuses'));
    }
}

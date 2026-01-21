<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display audit logs
     */
    public function index(Request $request)
    {
        $query = Audit::with(['user'])->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by model
        if ($request->filled('auditable_type')) {
            $query->where('auditable_type', $request->auditable_type);
        }

        // Filter by event (created, updated, deleted)
        if ($request->filled('event')) {
            $query->where('event', $request->event);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $audits = $query->paginate(50);

        // Get unique models for filter
        $models = Audit::select('auditable_type')
            ->distinct()
            ->pluck('auditable_type')
            ->map(function($type) {
                return [
                    'value' => $type,
                    'label' => class_basename($type)
                ];
            });

        // Get users for filter
        $users = \App\Models\User::select('id', 'name')->get();

        return view('admin.audit-logs.index', compact('audits', 'models', 'users'));
    }

    /**
     * Show specific audit log details
     */
    public function show(Audit $audit)
    {
        $audit->load('user');
        return view('admin.audit-logs.show', compact('audit'));
    }
}

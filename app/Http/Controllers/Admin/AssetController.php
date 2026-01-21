<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    // ... index, create methods remain same

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:assets',
            'type' => 'required|string|in:hardware,software,license,furniture,other',
            'status' => 'required|string|in:available,assigned,maintenance,retired,lost',
            'user_id' => 'nullable|exists:users,id',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'warranty_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $asset = Asset::create($validated);

        // Log creation
        AssetHistory::create([
            'asset_id' => $asset->id,
            'action' => 'created',
            'performed_by' => Auth::id(),
            'notes' => 'Asset created in system',
        ]);

        // Log assignment if created with user
        if ($asset->user_id) {
            $asset->status = 'assigned'; // Ensure status is assigned
            $asset->save();
            
            AssetHistory::create([
                'asset_id' => $asset->id,
                'action' => 'assigned',
                'user_id' => $asset->user_id,
                'performed_by' => Auth::id(),
                'notes' => 'Initial assignment upon creation',
            ]);
        }

        return redirect()->route('admin.assets.index')->with('success', 'Asset created successfully.');
    }

    // ... show, edit methods remain same

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:assets,serial_number,' . $asset->id,
            'type' => 'required|string|in:hardware,software,license,furniture,other',
            'status' => 'required|string|in:available,assigned,maintenance,retired,lost',
            'user_id' => 'nullable|exists:users,id',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'warranty_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Track changes for history
        $oldUser = $asset->user_id;
        $oldStatus = $asset->status;

        $asset->fill($validated);

        // Auto-update status if user assigned/unassigned
        if ($asset->user_id && !$oldUser) {
            $asset->status = 'assigned';
        } elseif (!$asset->user_id && $oldUser) {
             // If unassigned and status wasn't manually changed to something else specific (like lost/retired), default to available
            if ($asset->status === 'assigned') {
                $asset->status = 'available';
            }
        }

        $asset->save();

        // Log Status Change
        if ($asset->status !== $oldStatus) {
            AssetHistory::create([
                'asset_id' => $asset->id,
                'action' => 'status_view',
                'performed_by' => Auth::id(),
                'notes' => "Status changed from {$oldStatus} to {$asset->status}",
            ]);
        }

        // Log Assignment
        if ($asset->user_id !== $oldUser) {
            if ($asset->user_id) {
                AssetHistory::create([
                    'asset_id' => $asset->id,
                    'action' => 'assigned',
                    'user_id' => $asset->user_id,
                    'performed_by' => Auth::id(),
                    'notes' => $request->notes ? 'Assigned: '.$request->notes : 'Asset assigned to employee',
                ]);
            } else {
                AssetHistory::create([
                    'asset_id' => $asset->id,
                    'action' => 'returned',
                    'user_id' => $oldUser, // The user who returned it
                    'performed_by' => Auth::id(),
                    'notes' => 'Asset returned/unassigned',
                ]);
            }
        }

        return redirect()->route('admin.assets.index')->with('success', 'Asset updated successfully.');
    }

    // ... destroy method remains same

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('admin.assets.index')->with('success', 'Asset deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'company_name' => Setting::get('company_name', 'AIHRM'),
            'company_email' => Setting::get('company_email', ''),
            'company_phone' => Setting::get('company_phone', ''),
            'company_address' => Setting::get('company_address', ''),
            'company_logo' => Setting::get('company_logo', ''),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string|max:500',
            'company_logo' => 'nullable|image|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('company_logo')) {
            // Delete old logo
            $oldLogo = Setting::get('company_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Store new logo
            $logoPath = $request->file('company_logo')->store('company', 'public');
            Setting::set('company_logo', $logoPath, 'file');
        }

        // Save other settings
        Setting::set('company_name', $validated['company_name']);
        Setting::set('company_email', $validated['company_email'] ?? '');
        Setting::set('company_phone', $validated['company_phone'] ?? '');
        Setting::set('company_address', $validated['company_address'] ?? '');

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
}

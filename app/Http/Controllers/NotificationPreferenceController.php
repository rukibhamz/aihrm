<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationPreference;
use Illuminate\Support\Facades\Auth;

class NotificationPreferenceController extends Controller
{
    // Define available notification types
    public const TYPES = [
        'payroll' => 'Payroll Updates',
        'leave' => 'Leave Requests',
        'bonus' => 'Bonus Awards',
        'loan' => 'Loan Approvals',
        'attendance' => 'Attendance Reminders',
        'performance' => 'Performance Reviews',
        'system' => 'System Alerts',
    ];

    public function index()
    {
        $user = Auth::user();
        $preferences = $user->notificationPreferences->keyBy('type');
        
        // Prepare data for view, ensuring all types have a default if not set
        $settings = [];
        foreach (self::TYPES as $key => $label) {
            $pref = $preferences->get($key);
            $settings[$key] = [
                'label' => $label,
                'email' => $pref ? $pref->email_enabled : true, // Default true
                'database' => $pref ? $pref->database_enabled : true, // Default true
            ];
        }

        return view('notifications.preferences', compact('settings'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->input('preferences', []);

        foreach (self::TYPES as $type => $label) {
            NotificationPreference::updateOrCreate(
                ['user_id' => $user->id, 'type' => $type],
                [
                    'email_enabled' => isset($data[$type]['email']),
                    'database_enabled' => isset($data[$type]['database']),
                ]
            );
        }

        return back()->with('success', 'Notification preferences updated successfully.');
    }
}

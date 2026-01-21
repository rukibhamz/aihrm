<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('attendance.index', compact('attendances'));
    }

    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Check if already clocked in
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($attendance) {
            return back()->with('error', 'You have already clocked in today.');
        }

        // Validate geolocation data
        $request->validate([
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        // Office coordinates (configurable via settings)
        $officeLatitude = 6.5244; // Example: Lagos, Nigeria
        $officeLongitude = 3.3792;
        $maxDistance = 0.5; // 500 meters radius

        $locationVerified = false;
        if ($request->latitude && $request->longitude) {
            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $officeLatitude,
                $officeLongitude
            );
            $locationVerified = $distance <= $maxDistance;
        }

        // Determine status (Late if after 9:00 AM)
        $now = Carbon::now();
        $status = $now->format('H:i') > '09:00' ? 'late' : 'present';

        Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'clock_in' => $now,
            'status' => $status,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'location_verified' => $locationVerified,
        ]);

        $message = 'Clocked in successfully at ' . $now->format('H:i');
        if (!$locationVerified && $request->latitude) {
            $message .= ' (Location not verified - outside office radius)';
        }

        return back()->with('success', $message);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Haversine formula to calculate distance in kilometers
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distance = $earthRadius * $c;

        return $distance;
    }

    public function clockOut()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return back()->with('error', 'You have not clocked in today.');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'You have already clocked out today.');
        }

        $attendance->update([
            'clock_out' => Carbon::now(),
        ]);

        return back()->with('success', 'Clocked out successfully at ' . Carbon::now()->format('H:i'));
    }

    public function qrView()
    {
        // Generate a signed payload for the QR code
        $payload = [
            'user_id' => Auth::id(),
            'timestamp' => now()->timestamp,
        ];
        
        // Use APP_KEY to sign the payload
        $token = hash_hmac('sha256', json_encode($payload), config('app.key'));
        $qrData = json_encode(['p' => $payload, 's' => $token]);

        return view('attendance.qr', compact('qrData'));
    }

    public function verifyQr(Request $request)
    {
        // This is usually called by an Admin/Gatekeeper scanning an employee's QR
        $data = json_decode($request->qr_data, true);
        
        if (!$data || !isset($data['p']) || !isset($data['s'])) {
            return response()->json(['success' => false, 'message' => 'Invalid QR Data'], 400);
        }

        $payload = $data['p'];
        $signature = $data['s'];

        // Verify signature
        $expectedSignature = hash_hmac('sha256', json_encode($payload), config('app.key'));
        if (!hash_equals($expectedSignature, $signature)) {
            return response()->json(['success' => false, 'message' => 'Signature mismatch'], 401);
        }

        // Verify timestamp (e.g., QR valid for 5 minutes)
        if (now()->timestamp - $payload['timestamp'] > 300) {
            return response()->json(['success' => false, 'message' => 'QR Code expired'], 403);
        }

        $userId = $payload['user_id'];
        $today = Carbon::today();

        // Standard attendance logic (reuse or refactor clockIn logic)
        $attendance = Attendance::where('user_id', $userId)
            ->where('date', $today)
            ->first();

        if ($attendance && $attendance->clock_out) {
            return response()->json(['success' => false, 'message' => 'Attendance already completed for today']);
        }

        if ($attendance) {
            // Clock Out
            $attendance->update(['clock_out' => Carbon::now()]);
            return response()->json(['success' => true, 'message' => 'Clocked OUT successful for ' . \App\Models\User::find($userId)->name, 'type' => 'out']);
        } else {
            // Clock In
            $now = Carbon::now();
            $status = $now->format('H:i') > '09:00' ? 'late' : 'present';
            
            Attendance::create([
                'user_id' => $userId,
                'date' => $today,
                'clock_in' => $now,
                'status' => $status,
                'location_verified' => true, // Verified by gatekeeper scan
            ]);
            
            return response()->json(['success' => true, 'message' => 'Clocked IN successful for ' . \App\Models\User::find($userId)->name, 'type' => 'in']);
        }
    }
}

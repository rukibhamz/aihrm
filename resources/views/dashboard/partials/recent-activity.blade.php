<!-- Recent Activity Feed -->
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
    
    <div class="space-y-4">
        @php
            $recentActivities = collect();
            
            // Get recent leave requests
            if(auth()->user()->hasRole('admin')) {
                $recentLeaves = \App\Models\LeaveRequest::with(['user', 'leaveType'])
                    ->latest()
                    ->take(5)
                    ->get()
                    ->map(function($leave) {
                        return [
                            'type' => 'leave',
                            'icon' => 'calendar',
                            'color' => 'blue',
                            'title' => $leave->user->name . ' requested ' . $leave->leaveType->name,
                            'time' => $leave->created_at,
                            'status' => $leave->status
                        ];
                    });
                $recentActivities = $recentActivities->merge($recentLeaves);
            }
            
            // Get recent payroll
            if(auth()->user()->hasRole('admin')) {
                $recentPayroll = \App\Models\Payroll::with('creator')
                    ->latest()
                    ->take(3)
                    ->get()
                    ->map(function($payroll) {
                        return [
                            'type' => 'payroll',
                            'icon' => 'cash',
                            'color' => 'green',
                            'title' => 'Payroll generated for ' . date('F Y', mktime(0, 0, 0, $payroll->month, 1, $payroll->year)),
                            'time' => $payroll->created_at,
                            'status' => 'completed'
                        ];
                    });
                $recentActivities = $recentActivities->merge($recentPayroll);
            }

            // Get recent user notifications
            $recentNotifications = auth()->user()->notifications()
                ->latest()
                ->take(5)
                ->get()
                ->map(function($notification) {
                    return [
                        'type' => 'notification',
                        'icon' => $notification->data['type'] ?? 'bell',
                        'color' => ($notification->data['type'] ?? 'info') === 'error' ? 'red' : (($notification->data['type'] ?? 'info') === 'success' ? 'green' : 'blue'),
                        'title' => $notification->data['message'] ?? $notification->data['title'] ?? 'New Notification',
                        'time' => $notification->created_at,
                        'status' => null
                    ];
                });
            $recentActivities = $recentActivities->merge($recentNotifications);
            
            // Sort by time
            $recentActivities = $recentActivities->sortByDesc('time')->take(10);
        @endphp

        @forelse($recentActivities as $activity)
            <div class="flex items-start gap-3 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                <div class="w-8 h-8 bg-{{ $activity['color'] }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    @if($activity['icon'] === 'calendar')
                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    @elseif($activity['icon'] === 'cash')
                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif($activity['icon'] === 'bell' || $activity['type'] === 'notification')
                        <svg class="w-4 h-4 text-{{ $activity['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activity['time']->diffForHumans() }}</p>
                </div>
                @if(isset($activity['status']))
                    <span class="px-2 py-1 text-xs rounded
                        {{ $activity['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $activity['status'] === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $activity['status'] === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $activity['status'] === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}
                    ">
                        {{ ucfirst($activity['status']) }}
                    </span>
                @endif
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm">No recent activity</p>
            </div>
        @endforelse
    </div>
</div>

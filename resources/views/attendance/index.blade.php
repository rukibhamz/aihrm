<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">My Attendance History</h1>
    </div>

    <!-- Clock In/Out Card -->
    <div class="card p-6 mb-6 bg-white border border-gray-200 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold mb-1 text-gray-900">Today's Attendance</h2>
                <p class="text-gray-500 text-base">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                @php
                    $todayAttendance = \App\Models\Attendance::where('user_id', Auth::id())
                        ->where('date', \Carbon\Carbon::today())
                        ->first();
                @endphp

                @if(!$todayAttendance)
                    <form id="clockInForm" action="{{ route('attendance.clockIn') }}" method="POST">
                        @csrf
                        <input type="hidden" name="latitude" id="latitude">
                        <input type="hidden" name="longitude" id="longitude">
                        
                        <button type="button" onclick="submitAttendance()" id="clockInBtn" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-bold text-base hover:bg-blue-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="flex items-center gap-2">
                                <svg id="syncIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span id="btnText">Clock In</span>
                            </span>
                        </button>
                    </form>

                    <script>
                        function submitAttendance() {
                            const btn = document.getElementById('clockInBtn');
                            const btnText = document.getElementById('btnText');
                            const icon = document.getElementById('syncIcon');
                            const form = document.getElementById('clockInForm');
                            
                            btn.disabled = true;
                            btnText.innerText = 'Locating...';
                            icon.classList.add('animate-spin');

                            const resetButtonState = () => {
                                btn.disabled = false;
                                btnText.innerText = 'Clock In';
                                icon.classList.remove('animate-spin');
                            };

                            const submitWithLocation = (position) => {
                                document.getElementById('latitude').value = position.coords.latitude;
                                document.getElementById('longitude').value = position.coords.longitude;
                                form.submit();
                            };

                            if (!navigator.geolocation) {
                                alert('Geolocation is not supported by this browser.');
                                form.submit(); // Fallback to non-geofenced
                                return;
                            }

                            navigator.geolocation.getCurrentPosition(
                                submitWithLocation,
                                (highAccuracyError) => {
                                    // Retry with relaxed settings, since strict GPS often times out on mobile.
                                    navigator.geolocation.getCurrentPosition(
                                        submitWithLocation,
                                        (error) => {
                                            let errorMessage = error.message;
                                            if (error.code === error.PERMISSION_DENIED) {
                                                errorMessage = 'Permission denied. Please allow location access in your browser settings.';
                                            } else if (error.code === error.TIMEOUT) {
                                                errorMessage = 'Unable to get your location in time. Turn on location/GPS and try again.';
                                            } else if (error.code === error.POSITION_UNAVAILABLE) {
                                                errorMessage = 'Location is unavailable on this device right now. Please try again.';
                                            }

                                            alert('Location error: ' + errorMessage);
                                            resetButtonState();
                                        },
                                        { enableHighAccuracy: false, timeout: 20000, maximumAge: 120000 }
                                    );
                                },
                                { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
                            );
                        }
                    </script>
                @elseif(!$todayAttendance->clock_out)
                    <div class="bg-blue-50 px-5 py-2 rounded-lg border border-blue-100">
                        <div class="text-sm text-blue-600 font-medium mb-1">Clocked in at:</div>
                        <div class="font-bold text-xl text-blue-900">{{ $todayAttendance->clock_in->format('H:i') }}</div>
                    </div>
                    <form action="{{ route('attendance.clockOut') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg font-bold text-base hover:bg-red-700 transition-all shadow-md hover:shadow-lg transform hover:scale-105">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Clock Out
                            </span>
                        </button>
                    </form>
                @else
                    <div class="bg-gray-50 px-5 py-3 rounded-lg border border-gray-200">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <div class="text-sm text-gray-500 font-medium mb-1">Clocked In</div>
                                <div class="font-bold text-lg text-gray-900">{{ $todayAttendance->clock_in->format('H:i') }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500 font-medium mb-1">Clocked Out</div>
                                <div class="font-bold text-lg text-gray-900">{{ $todayAttendance->clock_out->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    <button disabled class="px-6 py-3 bg-green-600 text-white rounded-lg font-bold text-base cursor-not-allowed opacity-90 shadow-sm">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Completed
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Attendance History -->
    <div class="card overflow-x-auto bg-white border border-gray-200 shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock In</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clock Out</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($attendances as $attendance)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $attendance->date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $attendance->clock_in ? $attendance->clock_in->format('H:i A') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $attendance->clock_out ? $attendance->clock_out->format('H:i A') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $attendance->duration > 0 ? number_format($attendance->duration, 1) . ' hrs' : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $attendance->status === 'late' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $attendance->status === 'absent' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No attendance records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $attendances->links() }}
        </div>
    </div>
</x-app-layout>

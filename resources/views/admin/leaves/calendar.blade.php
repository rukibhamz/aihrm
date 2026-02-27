<x-app-layout>
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Leave Calendar</h1>
            <p class="mt-1 text-sm text-neutral-500">Overview of approved leaves for {{ $date->format('F Y') }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.leaves.calendar', ['date' => $date->copy()->subMonth()->format('Y-m-d')]) }}" class="btn-secondary">
                &larr; Previous
            </a>
            <span class="font-bold text-lg w-32 text-center">{{ $date->format('F Y') }}</span>
            <a href="{{ route('admin.leaves.calendar', ['date' => $date->copy()->addMonth()->format('Y-m-d')]) }}" class="btn-secondary">
                Next &rarr;
            </a>
        </div>
    </div>

    <div class="card overflow-x-auto">
        <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
            <div class="py-3">Sun</div>
            <div class="py-3">Mon</div>
            <div class="py-3">Tue</div>
            <div class="py-3">Wed</div>
            <div class="py-3">Thu</div>
            <div class="py-3">Fri</div>
            <div class="py-3">Sat</div>
        </div>
        
        <div class="grid grid-cols-7 auto-rows-fr bg-gray-200 gap-px border-b border-gray-200">
            @php
                $startOfMonth = $date->copy()->startOfMonth();
                $endOfMonth = $date->copy()->endOfMonth();
                $startDayOfWeek = $startOfMonth->dayOfWeek;
                $daysInMonth = $startOfMonth->daysInMonth;
                $currentDay = 1;
                
                // Previous month padding
                for ($i = 0; $i < $startDayOfWeek; $i++) {
                    echo '<div class="bg-white min-h-[120px] p-2 text-gray-300"></div>';
                }

                // Days of month
                while ($currentDay <= $daysInMonth) {
                    $dateStr = $date->copy()->day($currentDay)->format('Y-m-d');
                    $isToday = $dateStr === now()->format('Y-m-d');
                    
                    echo '<div class="bg-white min-h-[120px] p-2 relative group hover:bg-gray-50 transition">';
                    echo '<span class="text-sm font-medium ' . ($isToday ? 'bg-blue-600 text-white w-6 h-6 rounded-full flex items-center justify-center' : 'text-gray-700') . '">' . $currentDay . '</span>';
                    
                    // Leaves for this day
                    foreach ($leaves as $leave) {
                        $start = \Carbon\Carbon::parse($leave->start_date);
                        $end = \Carbon\Carbon::parse($leave->end_date);
                        $current = \Carbon\Carbon::parse($dateStr);
                        
                        if ($current->between($start, $end)) {
                            $color = match($leave->leaveType->name) {
                                'Sick Leave' => 'bg-red-100 text-red-800 border-red-200',
                                'Annual Leave' => 'bg-green-100 text-green-800 border-green-200',
                                'Casual Leave' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                default => 'bg-blue-100 text-blue-800 border-blue-200'
                            };
                            
                            echo '<div class="mt-1 text-xs p-1 rounded border truncate ' . $color . '" title="' . $leave->user->name . ' - ' . $leave->leaveType->name . '">';
                            echo $leave->user->name;
                            echo '</div>';
                        }
                    }
                    
                    echo '</div>';
                    $currentDay++;
                }

                // Next month padding
                $remainingCells = 7 - (($startDayOfWeek + $daysInMonth) % 7);
                if ($remainingCells < 7) {
                    for ($i = 0; $i < $remainingCells; $i++) {
                        echo '<div class="bg-white min-h-[120px] p-2 text-gray-300"></div>';
                    }
                }
            @endphp
        </div>
    </div>
</x-app-layout>



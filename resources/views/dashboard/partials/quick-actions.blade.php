<!-- Quick Actions Widget -->
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
    
    <!-- Responsive Grid: 1 col mobile, 2 col tablet, 4 col desktop -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- Clock In/Out - Show for all users -->
        <a href="{{ route('attendance.index') }}" class="flex items-center gap-3 p-3 bg-green-50 hover:bg-green-100 rounded-lg transition">
            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="font-semibold text-sm">Clock In/Out</div>
                <div class="text-xs text-gray-600">Mark attendance</div>
            </div>
        </a>

        <!-- Request Leave -->
        <a href="{{ route('leaves.create') }}" class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <div class="font-semibold text-sm">Request Leave</div>
                <div class="text-xs text-gray-600">Apply for time off</div>
            </div>
        </a>

        <!-- View Payroll (Admin) OR Submit Expense (Non-Admin) -->
        @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('admin.payroll.index') }}" class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
            <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="font-semibold text-sm">View Payroll</div>
                <div class="text-xs text-gray-600">Salary information</div>
            </div>
        </a>
        @else
        <a href="{{ route('finance.create') }}" class="flex items-center gap-3 p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition">
            <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <div class="font-semibold text-sm">Submit Expense</div>
                <div class="text-xs text-gray-600">Financial claim</div>
            </div>
        </a>
        @endif

        <!-- Check Leave Balance -->
        <a href="{{ route('leaves.index') }}" class="flex items-center gap-3 p-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition">
            <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <div class="font-semibold text-sm">Leave Balance</div>
                <div class="text-xs text-gray-600">Check available days</div>
            </div>
        </a>
    </div>
</div>

<div class="card p-6 h-full flex flex-col">
    <div class="flex items-center gap-2 mb-6">
        <svg class="w-5 h-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <h3 class="font-semibold text-gray-900">Quick Actions</h3>
    </div>
    
    <div class="grid grid-cols-2 gap-4 flex-1">
        <!-- Clock In/Out -->
        <a href="{{ route('attendance.index') }}" class="group flex flex-col items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-all hover:scale-105 border border-green-100">
            <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-green-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-center">
                <div class="font-bold text-sm text-green-900">Clock In/Out</div>
                <div class="text-[10px] text-green-700 opacity-75 mt-0.5">Mark attendance</div>
            </div>
        </a>

        <!-- Request Leave -->
        <a href="{{ route('leaves.create') }}" class="group flex flex-col items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-all hover:scale-105 border border-blue-100">
            <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-blue-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="text-center">
                <div class="font-bold text-sm text-blue-900">Request Leave</div>
                <div class="text-[10px] text-blue-700 opacity-75 mt-0.5">Apply for time off</div>
            </div>
        </a>

        <!-- View Payroll / Submit Expense -->
        @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('admin.payroll.index') }}" class="group flex flex-col items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition-all hover:scale-105 border border-purple-100">
            <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-purple-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-center">
                <div class="font-bold text-sm text-purple-900">Payroll</div>
                <div class="text-[10px] text-purple-700 opacity-75 mt-0.5">Salary info</div>
            </div>
        </a>
        @else
        <a href="{{ route('finance.create') }}" class="group flex flex-col items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition-all hover:scale-105 border border-purple-100">
            <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-purple-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="text-center">
                <div class="font-bold text-sm text-purple-900">Expense</div>
                <div class="text-[10px] text-purple-700 opacity-75 mt-0.5">Submit claim</div>
            </div>
        </a>
        @endif

        <!-- Leave Balance -->
        <a href="{{ route('leaves.index') }}" class="group flex flex-col items-center justify-center p-4 bg-amber-50 hover:bg-amber-100 rounded-xl transition-all hover:scale-105 border border-amber-100">
            <div class="w-12 h-12 bg-amber-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-amber-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="text-center">
                <div class="font-bold text-sm text-amber-900">Balances</div>
                <div class="text-[10px] text-amber-700 opacity-75 mt-0.5">Check days</div>
            </div>
        </a>
    </div>
</div>

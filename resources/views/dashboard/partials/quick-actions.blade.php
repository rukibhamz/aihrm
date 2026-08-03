<div class="card p-6 h-full flex flex-col">
    <div class="flex items-center gap-2 mb-6">
        <svg class="w-5 h-5 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <h3 class="font-semibold text-zinc-900 dark:text-zinc-100">Quick Actions</h3>
    </div>
    
    <div class="grid grid-cols-2 gap-3 flex-1">
        <!-- Clock In/Out -->
        <a href="{{ route('attendance.index') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl transition-all border
            bg-emerald-50 border-emerald-100 hover:bg-emerald-100
            dark:bg-emerald-950/40 dark:border-emerald-800/60 dark:hover:bg-emerald-950/70">
            <div class="w-11 h-11 bg-emerald-600 dark:bg-emerald-500 rounded-xl flex items-center justify-center mb-3 shadow-sm dark:shadow-none">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-center">
                <div class="font-semibold text-sm text-emerald-950 dark:text-emerald-100">Clock In/Out</div>
                <div class="text-[10px] text-emerald-700 dark:text-emerald-300/80 mt-0.5">Mark attendance</div>
            </div>
        </a>

        <!-- Request Leave -->
        <a href="{{ route('leaves.create') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl transition-all border
            bg-sky-50 border-sky-100 hover:bg-sky-100
            dark:bg-sky-950/40 dark:border-sky-800/60 dark:hover:bg-sky-950/70">
            <div class="w-11 h-11 bg-sky-600 dark:bg-sky-500 rounded-xl flex items-center justify-center mb-3 shadow-sm dark:shadow-none">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="text-center">
                <div class="font-semibold text-sm text-sky-950 dark:text-sky-100">Request Leave</div>
                <div class="text-[10px] text-sky-700 dark:text-sky-300/80 mt-0.5">Apply for time off</div>
            </div>
        </a>

        <!-- View Payroll / Submit Expense -->
        @if(auth()->user()->hasRole('admin'))
        <a href="{{ route('admin.payroll.index') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl transition-all border
            bg-violet-50 border-violet-100 hover:bg-violet-100
            dark:bg-violet-950/40 dark:border-violet-800/60 dark:hover:bg-violet-950/70">
            <div class="w-11 h-11 bg-violet-600 dark:bg-violet-500 rounded-xl flex items-center justify-center mb-3 shadow-sm dark:shadow-none">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-center">
                <div class="font-semibold text-sm text-violet-950 dark:text-violet-100">Payroll</div>
                <div class="text-[10px] text-violet-700 dark:text-violet-300/80 mt-0.5">Salary info</div>
            </div>
        </a>
        @else
        <a href="{{ route('finance.create') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl transition-all border
            bg-violet-50 border-violet-100 hover:bg-violet-100
            dark:bg-violet-950/40 dark:border-violet-800/60 dark:hover:bg-violet-950/70">
            <div class="w-11 h-11 bg-violet-600 dark:bg-violet-500 rounded-xl flex items-center justify-center mb-3 shadow-sm dark:shadow-none">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="text-center">
                <div class="font-semibold text-sm text-violet-950 dark:text-violet-100">Expense</div>
                <div class="text-[10px] text-violet-700 dark:text-violet-300/80 mt-0.5">Submit claim</div>
            </div>
        </a>
        @endif

        <!-- Leave Balance -->
        <a href="{{ route('leaves.index') }}" class="group flex flex-col items-center justify-center p-4 rounded-xl transition-all border
            bg-amber-50 border-amber-100 hover:bg-amber-100
            dark:bg-amber-950/40 dark:border-amber-800/60 dark:hover:bg-amber-950/70">
            <div class="w-11 h-11 bg-amber-600 dark:bg-amber-500 rounded-xl flex items-center justify-center mb-3 shadow-sm dark:shadow-none">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="text-center">
                <div class="font-semibold text-sm text-amber-950 dark:text-amber-100">Balances</div>
                <div class="text-[10px] text-amber-700 dark:text-amber-300/80 mt-0.5">Check days</div>
            </div>
        </a>
    </div>
</div>

<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Dashboard</h1>
        <p class="mt-1 text-sm text-neutral-500">Welcome back, {{ Auth::user()->name }}</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Stat Card 1 -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-neutral-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-.1283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-sm font-medium text-neutral-500 mb-1">Total Employees</div>
            <div class="text-3xl font-bold tracking-tight">{{ $total_employees }}</div>
            <div class="mt-4">
                <a href="{{ route('employees.index') }}" class="text-sm font-medium hover:underline">View all →</a>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-neutral-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="text-sm font-medium text-neutral-500 mb-1">Pending Leaves</div>
            <div class="text-3xl font-bold tracking-tight">{{ $pending_leaves }}</div>
            <div class="mt-4">
                @can('approve leave')
                <a href="{{ route('leaves.approvals') }}" class="text-sm font-medium hover:underline">Review requests →</a>
                @else
                <a href="{{ route('leaves.index') }}" class="text-sm font-medium hover:underline">View all →</a>
                @endcan
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-neutral-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-sm font-medium text-neutral-500 mb-1">Pending Finance</div>
            <div class="text-3xl font-bold tracking-tight">₦{{ number_format($pending_finance_amount, 0) }}</div>
            <div class="mt-4">
                <a href="{{ route('finance.index') }}" class="text-sm font-medium hover:underline">View claims →</a>
            </div>
        </div>
    </div>

    <!-- Recruitment & AI Insights Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card p-6">
            <div class="text-sm font-medium text-neutral-500 mb-1">Open Job Roles</div>
            <div class="text-3xl font-black tracking-tight text-black">{{ $open_jobs }}</div>
            <div class="mt-4"><a href="{{ route('jobs.index') }}" class="text-xs font-bold uppercase tracking-widest hover:underline">Manage Jobs →</a></div>
        </div>
        <div class="card p-6">
            <div class="text-sm font-medium text-neutral-500 mb-1">New Applications (7d)</div>
            <div class="text-3xl font-black tracking-tight text-black">{{ $new_applications }}</div>
            <div class="mt-4 text-xs text-neutral-400">Steady volume</div>
        </div>
        <div class="card p-6 border-l-4 border-black">
            <div class="text-sm font-medium text-neutral-500 mb-1">Avg. AI Match Score</div>
            <div class="text-3xl font-black tracking-tight text-black">{{ round($avg_ai_score) }}%</div>
            <div class="mt-4 text-xs text-neutral-400">Talent quality metric</div>
        </div>
    </div>

    <!-- Analytics Charts Section (Admin Only) -->
    @role('Admin')
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-900 mb-6">Analytics & Insights</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Employee Growth Chart -->
            @include('components.charts.employee-growth')
            
            <!-- Leave Trends Chart -->
            @include('components.charts.leave-trends')
            
            <!-- Payroll Cost Chart -->
            @include('components.charts.payroll-cost')
        </div>
    </div>
    @endrole

    <!-- Enhanced Widgets Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Quick Actions Widget -->
        @include('dashboard.partials.quick-actions')

        <!-- Recent Activity Feed -->
        @include('dashboard.partials.recent-activity')
    </div>
</x-app-layout>

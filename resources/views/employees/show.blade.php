<x-app-layout>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Employee Profile</h1>
            <p class="mt-1 text-sm text-neutral-500 italic">Viewing details for {{ $employee->user->name ?? 'N/A' }}</p>
        </div>
        <div class="flex items-center gap-3">
            @can('edit employees')
            <a href="{{ route('employees.edit', $employee) }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Profile
            </a>
            @endcan
            <a href="{{ route('employees.index') }}" class="btn-secondary">Back to List</a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Profile Header Card -->
        <div class="card p-6 bg-white overflow-hidden relative">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <!-- Avatar Box -->
                <div class="w-24 h-24 bg-neutral-100 rounded-xl flex items-center justify-center border border-neutral-200 overflow-hidden">
                    <span class="text-neutral-400 text-3xl font-bold italic">{{ substr($employee->user->name ?? 'U', 0, 1) }}</span>
                </div>
                
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-3xl font-bold text-neutral-900 tracking-tight">{{ $employee->user->name ?? 'N/A' }}</h1>
                    <div class="flex flex-wrap justify-center md:justify-start items-center gap-3 mt-2">
                        <span class="text-sm px-2.5 py-0.5 rounded-md bg-neutral-100 text-neutral-700 font-medium">
                            {{ $employee->employee_id ?? 'EMP-' . $employee->id }}
                        </span>
                        <span class="text-neutral-300">•</span>
                        <span class="text-sm font-medium text-neutral-600">{{ $employee->designation->title ?? 'N/A' }}</span>
                        <span class="text-neutral-300">•</span>
                        <span class="text-sm font-medium text-neutral-600">{{ $employee->department->name ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="flex flex-col items-center md:items-end gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $employee->status === 'active' ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-neutral-50 text-neutral-600 border border-neutral-100' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $employee->status === 'active' ? 'bg-green-500' : 'bg-neutral-400' }}"></span>
                        {{ ucfirst($employee->status ?? 'inactive') }}
                    </span>
                    <p class="text-xs text-neutral-500 font-medium italic">Joined {{ $employee->join_date ? $employee->join_date->format('M d, Y') : 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Tabbed Navigation -->
        <div class="border-b border-neutral-200">
            <nav class="flex gap-8" aria-label="Tabs">
                <button onclick="switchTab('overview')" id="tab-overview" class="tab-btn active-tab pb-4 text-sm font-semibold border-b-2 border-transparent transition-all">
                    Overview
                </button>
                @if(auth()->user()->hasAnyRole(['Admin', 'HR']))
                <button onclick="switchTab('attendance')" id="tab-attendance" class="tab-btn pb-4 text-sm font-semibold border-b-2 border-transparent transition-all">
                    Attendance
                </button>
                @endif
                @if(auth()->user()->hasAnyRole(['Admin', 'HR', 'Finance']))
                <button onclick="switchTab('payslips')" id="tab-payslips" class="tab-btn pb-4 text-sm font-semibold border-b-2 border-transparent transition-all">
                    Payslips
                </button>
                @endif
                @if(auth()->user()->hasAnyRole(['Admin', 'Finance']))
                <button onclick="switchTab('financials')" id="tab-financials" class="tab-btn pb-4 text-sm font-semibold border-b-2 border-transparent transition-all">
                    Salary Details
                </button>
                @endif
            </nav>
        </div>

        <!-- Tab Content -->
        <!-- Overview Tab -->
        <div id="content-overview" class="tab-content transition-all duration-300">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Personal Information -->
                <div class="card p-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                        <h3 class="text-lg font-bold text-neutral-800">Personal Information</h3>
                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase tracking-wider block mb-1">Email Address</label>
                            <p class="text-neutral-900 font-semibold">{{ $employee->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase tracking-wider block mb-1">Phone Number</label>
                            <p class="text-neutral-900 font-semibold">{{ $employee->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase tracking-wider block mb-1">Date of Birth</label>
                            <p class="text-neutral-900 font-semibold">{{ $employee->dob ? $employee->dob->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase tracking-wider block mb-1">Address</label>
                            <p class="text-neutral-900 font-semibold">{{ $employee->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="card p-6">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                        <h3 class="text-lg font-bold text-neutral-800">Employment Information</h3>
                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase tracking-wider block mb-1">Manager</label>
                            <p class="text-neutral-900 font-semibold">{{ $employee->manager->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-neutral-500 uppercase tracking-wider block mb-1">Role in System</label>
                            <p class="text-neutral-900 font-semibold">{{ $employee->user->getRoleNames()->first() ?? 'Employee' }}</p>
                        </div>
                        <div class="col-span-1 sm:col-span-2 pt-4">
                            <a href="{{ route('leaves.index') }}?user_id={{ $employee->user_id }}" class="btn-secondary w-full flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                View Leave History
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Tab -->
        @if(auth()->user()->hasAnyRole(['Admin', 'HR']))
        <div id="content-attendance" class="tab-content hidden animate-in fade-in duration-300">
            <div class="card overflow-hidden">
                <div class="p-4 border-b border-neutral-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-neutral-50">
                    <h3 class="font-bold text-neutral-800 italic">Recent Attendance</h3>
                    <a href="{{ route('attendance.index') }}?user_id={{ $employee->user_id }}" class="text-xs font-semibold text-neutral-500 hover:text-black hover:underline transition">See all activity →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-4 text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-xs font-medium text-neutral-500 uppercase tracking-wider">Time In</th>
                                <th class="px-6 py-4 text-xs font-medium text-neutral-500 uppercase tracking-wider">Time Out</th>
                                <th class="px-6 py-4 text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 italic">
                            @forelse($employee->user->attendances->take(5) as $att)
                            <tr class="hover:bg-neutral-50/50 transition">
                                <td class="px-6 py-4 font-semibold text-neutral-900">{{ $att->date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-neutral-600">{{ $att->clock_in ? $att->clock_in->format('H:i') : '-' }}</td>
                                <td class="px-6 py-4 text-neutral-600">{{ $att->clock_out ? $att->clock_out->format('H:i') : '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $att->status === 'present' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                        {{ ucfirst($att->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-neutral-400 italic">No recent attendance records.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Payslips Tab -->
        @if(auth()->user()->hasAnyRole(['Admin', 'HR', 'Finance']))
        <div id="content-payslips" class="tab-content hidden animate-in fade-in duration-300">
            <div class="card overflow-hidden">
                <div class="p-4 border-b border-neutral-100 bg-neutral-50">
                    <h3 class="font-bold text-neutral-800 italic">Payroll History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-white">
                            <tr>
                                <th class="px-6 py-4 text-xs font-medium text-neutral-500 uppercase tracking-wider">Payment Month</th>
                                <th class="px-6 py-4 text-xs font-medium text-neutral-500 uppercase tracking-wider">Net Amount</th>
                                <th class="px-6 py-4 text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 italic">
                            @forelse($employee->user->payslips->take(5) as $payslip)
                            <tr class="hover:bg-neutral-50/50 transition">
                                <td class="px-6 py-4 font-semibold text-neutral-900">{{ date('F Y', mktime(0, 0, 0, $payslip->payroll->month, 10)) }}</td>
                                <td class="px-6 py-4 font-bold text-neutral-900">₦{{ number_format($payslip->net_salary, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold {{ $payslip->status === 'paid' ? 'bg-green-50 text-green-700' : 'bg-neutral-50 text-neutral-600' }}">
                                        {{ ucfirst($payslip->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('my-payslips.download', $payslip) }}" class="text-neutral-900 hover:text-black border border-neutral-200 px-3 py-1.5 rounded-md hover:bg-neutral-50 transition text-xs font-semibold inline-flex items-center gap-1.5 shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        PDF
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-neutral-400 italic">No payslips generated yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Financials Tab -->
        @if(auth()->user()->hasAnyRole(['Admin', 'Finance']))
        <div id="content-financials" class="tab-content hidden animate-in fade-in duration-300">
            <div class="card p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
                    <h3 class="text-lg font-bold text-neutral-800">Current Salary Structure</h3>
                    <a href="{{ route('admin.salary.edit', $employee->user_id) }}" class="btn-secondary text-xs font-semibold px-4 py-2 italic flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Update Structure
                    </a>
                </div>

                @if($employee->user->salaryStructure)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="p-5 bg-neutral-50 rounded-lg border border-neutral-100">
                        <label class="text-[10px] font-semibold text-neutral-500 uppercase tracking-widest block mb-1.5">Base Salary</label>
                        <p class="text-2xl font-bold text-neutral-900">₦{{ number_format($employee->user->salaryStructure->base_salary, 2) }}</p>
                    </div>
                    <div class="p-5 bg-neutral-50 rounded-lg border border-neutral-100">
                        <label class="text-[10px] font-semibold text-neutral-500 uppercase tracking-widest block mb-1.5">Gross Monthly</label>
                        <p class="text-2xl font-bold text-neutral-900">₦{{ number_format($employee->user->salaryStructure->gross_salary, 2) }}</p>
                    </div>
                    <div class="p-5 bg-neutral-50 rounded-lg border border-neutral-100">
                        <label class="text-[10px] font-semibold text-neutral-500 uppercase tracking-widest block mb-1.5">Total Deductions</label>
                        <p class="text-2xl font-bold text-red-600">₦{{ number_format($employee->user->salaryStructure->total_deductions, 2) }}</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-neutral-400 uppercase tracking-wider pb-2 border-b border-neutral-100">Monthly Allowances</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 italic">
                        <div>
                            <label class="text-xs font-medium text-neutral-500 block mb-1">Housing</label>
                            <p class="text-sm font-bold text-neutral-800">₦{{ number_format($employee->user->salaryStructure->housing_allowance, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-neutral-500 block mb-1">Transport</label>
                            <p class="text-sm font-bold text-neutral-800">₦{{ number_format($employee->user->salaryStructure->transport_allowance, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-neutral-500 block mb-1">Other Allowances</label>
                            <p class="text-sm font-bold text-neutral-800">₦{{ number_format($employee->user->salaryStructure->other_allowances, 2) }}</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="p-10 text-center bg-neutral-50 border border-dashed border-neutral-200 rounded-lg">
                    <p class="text-neutral-500 italic font-medium">No salary structure defined for this employee.</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <style>
        .active-tab {
            color: #000;
            border-bottom-color: #000 !important;
        }
        .tab-btn:not(.active-tab) {
            color: #737373;
        }
    </style>

    <script>
        function switchTab(tabId) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            // Show target content
            document.getElementById('content-' + tabId).classList.remove('hidden');
            
            // Update tab styles
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active-tab');
            });
            document.getElementById('tab-' + tabId).classList.add('active-tab');
        }
    </script>
</x-app-layout>


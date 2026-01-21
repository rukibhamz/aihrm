<x-app-layout>
    <div class="flex justify-between items-center mb-8">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold tracking-tight text-neutral-900">
                    Payroll: {{ date('F', mktime(0, 0, 0, $payroll->month, 10)) }} {{ $payroll->year }}
                </h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                    {{ $payroll->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ ucfirst($payroll->status) }}
                </span>
            </div>
            <p class="mt-1 text-sm text-neutral-500">Generated on {{ $payroll->created_at->format('M d, Y') }} by {{ $payroll->creator->name ?? 'System' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.payroll.index') }}" class="btn-secondary">Back</a>
            @if($payroll->status !== 'paid')
            <form action="{{ route('admin.payroll.status', $payroll) }}" method="POST" onsubmit="return confirm('Are you sure you want to mark this payroll as PAID? This cannot be undone.');">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action" value="mark_paid">
                <button type="submit" class="btn-primary bg-green-600 hover:bg-green-700 border-transparent text-white">
                    Mark as Paid
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card p-6">
            <h3 class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Headcount</h3>
            <p class="mt-2 text-3xl font-bold text-neutral-900">{{ $payroll->payslips->count() }}</p>
        </div>
        <div class="card p-6">
            <h3 class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Basic Salary</h3>
            <p class="mt-2 text-3xl font-bold text-neutral-900">₦{{ number_format($payroll->payslips->sum('basic_salary')) }}</p>
        </div>
        <div class="card p-6">
            <h3 class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Total Net Pay</h3>
            <p class="mt-2 text-3xl font-bold text-green-600">₦{{ number_format($payroll->total_cost) }}</p>
        </div>
        <div class="card p-6">
            <h3 class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Payment Date</h3>
             <p class="mt-2 text-xl font-bold text-neutral-900">{{ $payroll->payment_date ? $payroll->payment_date->format('M d, Y') : 'Pending' }}</p>
        </div>
    </div>

    <!-- Details Table -->
    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Basic</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Allowances</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Deductions</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Net Pay</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-100">
                @foreach ($payroll->payslips as $slip)
                <tr class="hover:bg-neutral-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-neutral-900">{{ $slip->user->name }}</div>
                        <div class="text-xs text-neutral-500">{{ $slip->user->employee->designation->title ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                        {{ $slip->user->employee->department->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-neutral-600">
                        {{ number_format($slip->basic_salary) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-neutral-600">
                        {{ number_format($slip->total_allowances) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-red-600">
                        -{{ number_format($slip->total_deductions) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-neutral-900">
                        {{ number_format($slip->net_salary) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                            {{ $slip->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-neutral-100 text-neutral-800' }}">
                            {{ ucfirst($slip->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>

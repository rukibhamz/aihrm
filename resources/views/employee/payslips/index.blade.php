<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">My Payslips</h1>
        <p class="mt-1 text-sm text-neutral-500">View and download your monthly salary slips</p>
    </div>

    <div class="card overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-neutral-600 uppercase tracking-wider">Month</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Gross Pay</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Deductions</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Net Pay</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-neutral-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-neutral-600 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-100">
                @forelse ($payslips as $slip)
                <tr class="hover:bg-neutral-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-neutral-900">
                            {{ date('F', mktime(0, 0, 0, $slip->payroll->month, 10)) }} {{ $slip->payroll->year }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-neutral-600">
                        ₦{{ number_format($slip->basic_salary + $slip->total_allowances) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-red-600">
                        -₦{{ number_format($slip->total_deductions) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-neutral-900">
                        ₦{{ number_format($slip->net_salary) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $slip->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($slip->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('my-payslips.download', $slip) }}" class="inline-flex items-center gap-1 text-black hover:underline" target="_blank">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download PDF
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-neutral-500">
                        No payslips found available yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $payslips->links() }}
    </div>
</x-app-layout>

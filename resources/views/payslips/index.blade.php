<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">My Payslips</h1>
        <p class="mt-1 text-sm text-neutral-500">View your payment history</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Basic Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Net Pay</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                @forelse($payslips as $payslip)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                        {{ \Carbon\Carbon::createFromDate($payslip->payroll->year, $payslip->payroll->month, 1)->format('F Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                        {{ number_format($payslip->basic_salary, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-neutral-900">
                        {{ number_format($payslip->net_salary, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payslip->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($payslip->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button class="text-neutral-600 hover:text-black">Download PDF</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-neutral-500">No payslips found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $payslips->links() }}
    </div>
</x-app-layout>

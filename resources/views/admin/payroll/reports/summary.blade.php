<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
                {{ __('Payroll Summary') }} - {{ date('F Y', mktime(0, 0, 0, $validated['month'], 1, $validated['year'])) }}
            </h2>
            <a href="{{ route('admin.payroll-reports.index') }}" class="text-sm text-gray-500 hover:text-black transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(!$data)
                <div class="bg-white p-12 text-center rounded-3xl border border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v10m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">No Data Found</h3>
                    <p class="text-gray-500 mt-1">No payroll records were found for the selected period.</p>
                </div>
            @else
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Employee Count</span>
                        <div class="text-3xl font-black text-gray-900 mt-1">{{ number_format($data['total_employees']) }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <span class="text-xs font-bold text-blue-400 uppercase tracking-widest">Total Basic</span>
                        <div class="text-3xl font-black text-gray-900 mt-1">{{ number_format($data['total_basic'], 2) }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <span class="text-xs font-bold text-red-400 uppercase tracking-widest">Total Deductions</span>
                        <div class="text-3xl font-black text-gray-900 mt-1">{{ number_format($data['total_deductions'], 2) }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 border-l-4 border-l-green-500">
                        <span class="text-xs font-bold text-green-500 uppercase tracking-widest">Net Payout</span>
                        <div class="text-3xl font-black text-gray-900 mt-1">{{ number_format($data['total_net'], 2) }}</div>
                    </div>
                </div>

                <!-- Detailed Table -->
                <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                        <h3 class="font-bold text-gray-900">Breakdown by Employee</h3>
                        <a href="{{ route('admin.payroll-reports.export', ['month' => $validated['month'], 'year' => $validated['year']]) }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">Export CSV</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="px-6 py-4">Employee</th>
                                    <th class="px-6 py-4">Basic</th>
                                    <th class="px-6 py-4">Allowances</th>
                                    <th class="px-6 py-4">Overtime</th>
                                    <th class="px-6 py-4">Bonuses</th>
                                    <th class="px-6 py-4">Deductions</th>
                                    <th class="px-6 py-4 text-right">Net Salary</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($data['payroll']->payslips as $payslip)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $payslip->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $payslip->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium">{{ number_format($payslip->basic_salary, 2) }}</td>
                                    <td class="px-6 py-4 text-green-600">+{{ number_format($payslip->total_allowances, 2) }}</td>
                                    <td class="px-6 py-4">{{ number_format($payslip->overtime_amount, 2) }}</td>
                                    <td class="px-6 py-4 text-blue-600">{{ number_format($payslip->bonus_amount, 2) }}</td>
                                    <td class="px-6 py-4 text-red-600">-{{ number_format($payslip->total_deductions, 2) }}</td>
                                    <td class="px-6 py-4 text-right font-black text-gray-900">{{ number_format($payslip->net_salary, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-900 text-white font-bold">
                                <tr>
                                    <td class="px-6 py-4">TOTAL</td>
                                    <td class="px-6 py-4">{{ number_format($data['total_basic'], 2) }}</td>
                                    <td class="px-6 py-4">{{ number_format($data['total_allowances'], 2) }}</td>
                                    <td class="px-6 py-4">{{ number_format($data['total_overtime'], 2) }}</td>
                                    <td class="px-6 py-4">{{ number_format($data['total_bonuses'], 2) }}</td>
                                    <td class="px-6 py-4">{{ number_format($data['total_deductions'], 2) }}</td>
                                    <td class="px-6 py-4 text-right">{{ number_format($data['total_net'], 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

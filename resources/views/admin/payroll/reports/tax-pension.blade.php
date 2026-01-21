<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
                {{ __('Tax & Pension Compliance') }} - {{ date('F Y', mktime(0, 0, 0, $validated['month'], 1, $validated['year'])) }}
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
                    <h3 class="text-lg font-bold text-gray-900">No Compliance Data</h3>
                    <p class="text-gray-500 mt-1">No tax or pension records found for this period.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Tax Card -->
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-10 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-red-50 blur-3xl rounded-full translate-x-12 -translate-y-12"></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center mb-6">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <h3 class="text-sm font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Total PAYE Tax</h3>
                            <div class="text-5xl font-black text-gray-900">{{ number_format($data['total_tax'], 2) }}</div>
                            <p class="text-xs font-bold text-gray-500 mt-6 leading-relaxed">
                                Calculated from {{ $data['employee_count'] }} active employee records. This amount should be remitted to the relevant tax authorities.
                            </p>
                        </div>
                    </div>

                    <!-- Pension Card -->
                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm p-10 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 blur-3xl rounded-full translate-x-12 -translate-y-12"></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-6">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="text-sm font-black uppercase tracking-[0.2em] text-gray-400 mb-2">Total Pension Fund</h3>
                            <div class="text-5xl font-black text-gray-900">{{ number_format($data['total_pension'], 2) }}</div>
                             <p class="text-xs font-bold text-gray-500 mt-6 leading-relaxed">
                                Combined employee and employer contributions. Amounts ready for PFA remittance.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Compliance Table -->
                <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                        <h3 class="font-bold text-gray-900">Compliance Log</h3>
                    </div>
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-400 font-bold uppercase tracking-wider text-[10px]">
                            <tr>
                                <th class="px-6 py-4">Employee</th>
                                <th class="px-6 py-4">Basic Pay</th>
                                <th class="px-6 py-4">PAYE Tax</th>
                                <th class="px-6 py-4">Pension</th>
                                <th class="px-6 py-4 text-right">Computed Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($data['payroll']->payslips as $payslip)
                                @php
                                    $breakdown = $payslip->breakdown;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $payslip->user->name }}</div>
                                        <div class="text-[10px] text-gray-500 font-mono">{{ $payslip->user->employee->tax_id ?? 'NO-TAX-ID' }}</div>
                                    </td>
                                    <td class="px-6 py-4">{{ number_format($payslip->basic_salary, 2) }}</td>
                                    <td class="px-6 py-4 text-red-600 font-bold">{{ number_format($breakdown['tax'] ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 text-green-600 font-bold">{{ number_format($breakdown['pension'] ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 text-right text-gray-400">{{ $payslip->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

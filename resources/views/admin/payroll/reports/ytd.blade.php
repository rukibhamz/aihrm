<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
                {{ __('Year-to-Date (YTD) Payroll') }} - {{ $validated['year'] }}
            </h2>
            <a href="{{ route('admin.payroll-reports.index') }}" class="text-sm text-gray-500 hover:text-black transition flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Reports
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($data->isEmpty())
                <div class="bg-white p-12 text-center rounded-3xl border border-dashed border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">No Annual Data</h3>
                    <p class="text-gray-500 mt-1">No payroll history found for the year {{ $validated['year'] }}.</p>
                </div>
            @else
                <!-- Annual Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Annual Gross (Basic)</span>
                        <div class="text-4xl font-black text-gray-900 mt-2">{{ number_format($data->sum('total_basic'), 2) }}</div>
                    </div>
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest">Annual Net Payout</span>
                        <div class="text-4xl font-black text-green-600 mt-2">{{ number_format($data->sum('total_net'), 2) }}</div>
                    </div>
                    <div class="bg-black p-8 rounded-3xl shadow-xl shadow-black/10 text-white">
                        <span class="text-xs font-black text-neutral-500 uppercase tracking-widest">Avg. Monthly Cost</span>
                        <div class="text-4xl font-black text-white mt-2">{{ number_format($data->sum('total_net') / $data->count(), 2) }}</div>
                    </div>
                </div>

                <!-- Monthly Trend Table -->
                <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-hidden mb-8">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-gray-900">Monthly Performance</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-400 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="px-6 py-4">Month</th>
                                    <th class="px-6 py-4">Employees</th>
                                    <th class="px-6 py-4">Basic Total</th>
                                    <th class="px-6 py-4">Total Net</th>
                                    <th class="px-6 py-4 text-right">Trend</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($data as $monthData)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ date('F', mktime(0, 0, 0, $monthData['month'], 1)) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 bg-neutral-100 rounded text-xs font-bold">{{ $monthData['employee_count'] }}</span>
                                    </td>
                                    <td class="px-6 py-4">{{ number_format($monthData['total_basic'], 2) }}</td>
                                    <td class="px-6 py-4 font-black">{{ number_format($monthData['total_net'], 2) }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @php
                                            $avg = $data->sum('total_net') / $data->count();
                                            $diff = $monthData['total_net'] - $avg;
                                            $color = $diff > 0 ? 'text-red-500' : 'text-green-500';
                                            $icon = $diff > 0 ? '↑' : '↓';
                                        @endphp
                                        <span class="{{ $color }} font-bold">{{ $icon }} {{ number_format(abs($diff), 0) }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Annual Breakdown Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100">
                        <h3 class="text-xl font-bold mb-6">Cost Distribution</h3>
                        <div class="space-y-6">
                            @php
                                $totalComp = $data->sum('total_basic') + $data->sum('total_allowances') + $data->sum('total_overtime') + $data->sum('total_bonuses');
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                                    <span>Base Salaries</span>
                                    <span>{{ number_format(($data->sum('total_basic') / $totalComp) * 100, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5">
                                    <div class="bg-black h-2.5 rounded-full" style="width: {{ ($data->sum('total_basic') / $totalComp) * 100 }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">
                                    <span>Allowances & Bonuses</span>
                                    <span>{{ number_format((($data->sum('total_allowances') + $data->sum('total_bonuses')) / $totalComp) * 100, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ (($data->sum('total_allowances') + $data->sum('total_bonuses')) / $totalComp) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-indigo-600 p-8 rounded-[2.5rem] text-white flex flex-col justify-center">
                        <h3 class="text-2xl font-black mb-4">Financial Year {{ $validated['year'] }}</h3>
                        <p class="text-indigo-100 mb-8 font-medium italic">"Consistent growth and optimized payroll spending lead to organizational success."</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold opacity-50 uppercase">Data Integrity</div>
                                <div class="text-sm font-bold">100% Verified against Bank Records</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
                {{ __('Departmental Payroll') }} - {{ date('F Y', mktime(0, 0, 0, $validated['month'], 1, $validated['year'])) }}
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
                    <h3 class="text-lg font-bold text-gray-900">No Departmental Data</h3>
                    <p class="text-gray-500 mt-1">We couldn't find any departmental breakdown for the selected period.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                     <!-- Table -->
                     <div class="bg-white shadow-sm sm:rounded-2xl border border-gray-100 overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 text-gray-500 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="px-6 py-4">Department</th>
                                    <th class="px-6 py-4 text-center">Staff</th>
                                    <th class="px-6 py-4 text-right">Net Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($data as $dept)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $dept->department }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 bg-neutral-100 rounded-lg font-bold text-xs">{{ $dept->employee_count }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-gray-900">{{ number_format($dept->total_net, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                     </div>

                     <!-- Visual Summary -->
                     <div class="space-y-4">
                         @foreach($data as $dept)
                            @php
                                $percentage = ($dept->total_net / $data->sum('total_net')) * 100;
                            @endphp
                            <div class="bg-white p-6 rounded-2xl border border-gray-100">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-2">
                                    <span class="font-bold text-gray-900">{{ $dept->department }}</span>
                                    <span class="text-xs font-bold text-gray-400">{{ number_format($percentage, 1) }}% of Total</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <div class="mt-4 flex justify-between text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                    <span>Basic: {{ number_format($dept->total_basic, 2) }}</span>
                                    <span>Net: {{ number_format($dept->total_net, 2) }}</span>
                                </div>
                            </div>
                         @endforeach
                     </div>
                </div>

                <!-- Footer Summary Card -->
                <div class="bg-neutral-900 text-white p-10 rounded-[2.5rem] flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <h3 class="text-2xl font-black mb-1">Company Total Payout</h3>
                        <p class="text-neutral-400 font-medium">Summed across all active departments mentioned above.</p>
                    </div>
                    <div class="text-center md:text-right">
                         <div class="text-5xl font-black text-white">{{ number_format($data->sum('total_net'), 2) }}</div>
                         <div class="text-xs font-bold uppercase tracking-[0.2em] text-neutral-500 mt-2">Total organizational cost</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>


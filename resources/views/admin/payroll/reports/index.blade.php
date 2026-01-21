<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Payroll Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Payroll Analytics</h1>
                <p class="mt-2 text-sm text-gray-600">Comprehensive overview of your organization's financial data.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Monthly Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-8">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Monthly Summary</h3>
                        <p class="text-sm text-gray-500 mb-8">Detailed breakdown of total earnings, deductions, and net payouts for any given month.</p>
                        
                        <form action="{{ route('admin.payroll-reports.summary') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <select name="month" required class="rounded-xl border-gray-200 text-sm focus:ring-black focus:border-black">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endforeach
                                </select>
                                <select name="year" required class="rounded-xl border-gray-200 text-sm focus:ring-black focus:border-black">
                                    @foreach(range(2024, 2030) as $y)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full btn-primary py-3 rounded-xl font-bold">Generate Report</button>
                        </form>
                    </div>
                </div>

                <!-- Departmental Report -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-8">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Departmental Analysis</h3>
                        <p class="text-sm text-gray-500 mb-8">Compare payroll costs across different departments to optimize budget allocation.</p>
                        
                        <form action="{{ route('admin.payroll-reports.department') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <select name="month" required class="rounded-xl border-gray-200 text-sm focus:ring-black focus:border-black">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endforeach
                                </select>
                                <select name="year" required class="rounded-xl border-gray-200 text-sm focus:ring-black focus:border-black">
                                    @foreach(range(2024, 2030) as $y)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full btn-primary py-3 rounded-xl font-bold bg-purple-600 hover:bg-purple-700">View Departmental</button>
                        </form>
                    </div>
                </div>

                <!-- Tax & Pension -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-8">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tax & Compliance</h3>
                        <p class="text-sm text-gray-500 mb-8">Summary of tax withholdings and pension contributions for regulatory filings.</p>
                        
                        <form action="{{ route('admin.payroll-reports.tax-pension') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <select name="month" required class="rounded-xl border-gray-200 text-sm focus:ring-black focus:border-black">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endforeach
                                </select>
                                <select name="year" required class="rounded-xl border-gray-200 text-sm focus:ring-black focus:border-black">
                                    @foreach(range(2024, 2030) as $y)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full btn-primary py-3 rounded-xl font-bold bg-green-600 hover:bg-green-700">Check Compliance</button>
                        </form>
                    </div>
                </div>

                <!-- Year-to-Date (YTD) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-8">
                        <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Annual Trends (YTD)</h3>
                        <p class="text-sm text-gray-500 mb-8">Track cumulative payroll expenses and patterns throughout the fiscal year.</p>
                        
                        <form action="{{ route('admin.payroll-reports.ytd') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-1 gap-4">
                                <select name="year" required class="rounded-xl border-gray-200 text-sm focus:ring-black focus:border-black">
                                    @foreach(range(2024, 2030) as $y)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full btn-primary py-3 rounded-xl font-bold bg-orange-600 hover:bg-orange-700">View Annual Stats</button>
                        </form>
                    </div>
                </div>

                <!-- Quick Export -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-8">
                        <div class="w-12 h-12 bg-neutral-100 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">CSV Export</h3>
                        <p class="text-sm text-gray-500 mb-8">Download complete raw payroll data for external accounting tools.</p>
                        
                        <form action="{{ route('admin.payroll-reports.export') }}" method="GET" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <select name="month" required class="rounded-xl border-gray-200 text-sm focus:ring-black focus:border-black">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                    @endforeach
                                </select>
                                <select name="year" required class="rounded-xl border-gray-200 text-sm focus:ring-black focus:border-black">
                                    @foreach(range(2024, 2030) as $y)
                                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-neutral-900 hover:bg-black text-white py-3 rounded-xl font-bold transition">Download CSV</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

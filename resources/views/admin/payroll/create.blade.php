<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Run Payroll</h1>
            <p class="mt-1 text-sm text-neutral-500">Generate payslips for all eligible employees</p>
        </div>
        <a href="{{ route('admin.salary.index') }}" class="btn-secondary">Back</a>
    </div>

    <div class="max-w-xl mx-auto">
        <form method="POST" action="{{ route('admin.payroll.store') }}" class="card p-8 space-y-6">
            @csrf
            
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-neutral-900">Select Payroll Period</h3>
                <p class="text-sm text-neutral-500">This will calculate salaries for all active employees with a defined structure.</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Month</label>
                    <select name="month" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Year</label>
                    <select name="year" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black">
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026" selected>2026</option>
                    </select>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <button type="submit" class="w-full btn-primary py-3 justify-center">
                    Generate Payroll
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

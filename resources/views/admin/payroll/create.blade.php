<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Run Payroll</h1>
        <p class="mt-1 text-sm text-neutral-500">Generate payslips for a specific month</p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-8 max-w-xl">
        <form method="POST" action="{{ route('admin.payroll.store') }}" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Month *</label>
                    <select name="month" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-2">Year *</label>
                    <select name="year" required class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                        @for($i = 2024; $i <= 2030; $i++)
                            <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="bg-neutral-50 p-4 rounded-lg text-sm text-neutral-600">
                <p class="mb-2"><strong>Note:</strong></p>
                <ul class="list-disc list-inside space-y-1">
                    <li>This will generate payslips for all active employees with a salary structure.</li>
                    <li>The payroll will be created in <strong>Draft</strong> status.</li>
                    <li>You can review and approve it before marking it as Paid.</li>
                </ul>
            </div>

            <div class="flex gap-3 pt-6 border-t border-neutral-200">
                <button type="submit" class="btn-primary w-full justify-center">Generate Payroll</button>
            </div>
        </form>
    </div>
</x-app-layout>

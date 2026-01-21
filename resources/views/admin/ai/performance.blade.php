<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('AI Performance Insights') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ loading: false, result: null, error: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-neutral-200">
                <div class="p-8">
                    <div class="max-w-2xl">
                        <h3 class="text-lg font-bold text-neutral-900 mb-2">Performance Intelligence</h3>
                        <p class="text-neutral-600 mb-6">
                            Analyze performance review comments and ratings across the organization. The AI identifies high-potential employees, hidden biases in reviews, and potential attrition risks.
                        </p>

                        <button 
                            @click="loading = true; error = null; 
                                fetch('{{ route('admin.ai.performance.analyze') }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if(data.error) error = data.error;
                                    else result = data;
                                    loading = false;
                                })
                                .catch(err => { error = 'Failed to analyze reviews'; loading = false; })"
                            :disabled="loading"
                            class="btn-primary flex items-center gap-2"
                        >
                            <span x-show="!loading">Analyze Performance Patterns</span>
                            <span x-show="loading">Scanning Reviews...</span>
                            <template x-if="loading">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                        </button>
                    </div>

                    <!-- Results Section -->
                    <div x-show="result" class="mt-12 space-y-10" x-transition>
                        <!-- Summary Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="card p-6 border-l-4" :class="result.bias_detected ? 'border-l-orange-500' : 'border-l-green-500'">
                                <p class="text-sm text-neutral-500 uppercase font-bold tracking-wider">Bias Detection</p>
                                <p class="text-xl font-bold mt-1 text-neutral-900" x-text="result.bias_detected ? 'SYSTEMIC BIAS DETECTED' : 'NO BIAS DETECTED'"></p>
                                <p x-show="result.bias_detected" class="text-sm text-neutral-600 mt-2" x-text="result.bias_details"></p>
                            </div>
                            <div class="card p-6 border-l-4 border-l-red-500">
                                <p class="text-sm text-neutral-500 uppercase font-bold tracking-wider">Attrition Risk</p>
                                <p class="text-xl font-bold mt-1 text-neutral-900" x-text="(result.attrition_risk ? result.attrition_risk.length : 0) + ' High-Risk Employees'"></p>
                            </div>
                        </div>

                        <!-- Detailed Findings -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <section>
                                <h4 class="font-bold text-neutral-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    High Performers to Watch
                                </h4>
                                <div class="bg-neutral-50 rounded-xl p-6 border border-neutral-100">
                                    <ul class="space-y-4">
                                        <template x-for="empId in result.high_performers" :key="empId">
                                            <li class="flex items-center gap-3">
                                                <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                                                <span class="text-neutral-800 font-medium" x-text="'Employee ID: ' + empId"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </section>

                            <section>
                                <h4 class="font-bold text-neutral-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Strategic Recommendations
                                </h4>
                                <ul class="space-y-3">
                                    <template x-for="rec in result.recommendations" :key="rec">
                                        <li class="bg-white border border-neutral-200 rounded-lg p-4 text-sm text-neutral-700 shadow-sm" x-text="rec"></li>
                                    </template>
                                </ul>
                            </section>
                        </div>
                    </div>

                    <div x-show="error" class="mt-8 p-4 bg-red-50 text-red-700 rounded-lg border border-red-100" x-text="error"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

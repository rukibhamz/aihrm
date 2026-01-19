<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Labor Law Compliance Audit') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ loading: false, result: null, error: null }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-neutral-200">
                <div class="p-8">
                    <div class="max-w-2xl">
                        <h3 class="text-lg font-bold text-neutral-900 mb-2">AI Compliance Officer</h3>
                        <p class="text-neutral-600 mb-6">
                            Run an automated audit of your HR records against Nigerian Labor Laws. The AI will analyze salary structures, leave policies, and work hours to identify potential risks.
                        </p>

                        <button 
                            @click="loading = true; error = null; 
                                fetch('{{ route('admin.ai.compliance.run') }}', {
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
                                .catch(err => { error = 'Failed to run audit'; loading = false; })"
                            :disabled="loading"
                            class="btn-primary flex items-center gap-2"
                        >
                            <span x-show="!loading">Start Compliance Audit</span>
                            <span x-show="loading">Auditing Records...</span>
                            <template x-if="loading">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </template>
                        </button>
                    </div>

                    <!-- Results Section -->
                    <div x-show="result" class="mt-12 space-y-8" x-transition>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="card p-6 border-l-4" :class="result.compliant ? 'border-l-green-500' : 'border-l-red-500'">
                                <p class="text-sm text-neutral-500 uppercase font-bold tracking-wider">Status</p>
                                <p class="text-2xl font-black mt-1" :class="result.compliant ? 'text-green-600' : 'text-red-600'" x-text="result.compliant ? 'COMPLIANT' : 'NON-COMPLIANT'"></p>
                            </div>
                            <div class="card p-6">
                                <p class="text-sm text-neutral-500 uppercase font-bold tracking-wider">Severity</p>
                                <p class="text-2xl font-black mt-1 text-neutral-900" x-text="(result.severity || 'N/A').toUpperCase()"></p>
                            </div>
                            <div class="card p-6">
                                <p class="text-sm text-neutral-500 uppercase font-bold tracking-wider">Issues Found</p>
                                <p class="text-2xl font-black mt-1 text-neutral-900" x-text="result.issues ? result.issues.length : 0"></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="font-bold text-neutral-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Issues Identified
                                </h4>
                                <ul class="space-y-3">
                                    <template x-for="issue in result.issues" :key="issue">
                                        <li class="bg-red-50 border border-red-100 rounded-lg p-4 text-sm text-red-700" x-text="issue"></li>
                                    </template>
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-bold text-neutral-900 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Recommendations
                                </h4>
                                <ul class="space-y-3">
                                    <template x-for="rec in result.recommendations" :key="rec">
                                        <li class="bg-green-50 border border-green-100 rounded-lg p-4 text-sm text-green-700 font-medium" x-text="rec"></li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div x-show="error" class="mt-8 p-4 bg-red-50 text-red-700 rounded-lg border border-red-100" x-text="error"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Audit Log Details') }}
        </h2>
    </x-slot>

    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.audit-logs.index') }}" class="p-2 rounded-full bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Audit Log Details</h2>
            <p class="text-sm text-gray-500">Event ID: #{{ $audit->id }}</p>
        </div>
    </div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Meta Info -->
    <div class="col-span-1 space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Event Info</h3>
            
            <div class="space-y-3">
                <div>
                    <span class="block text-xs text-gray-500">Event Type</span>
                    <span class="font-medium text-gray-900 capitalize">{{ $audit->event }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Date & Time</span>
                    <span class="font-medium text-gray-900">{{ $audit->created_at->format('M d, Y H:i:s') }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">IP Address</span>
                    <span class="font-mono text-sm text-gray-700">{{ $audit->ip_address }}</span>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">User Agent</span>
                    <span class="text-xs text-gray-600 break-words">{{ $audit->user_agent }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">User</h3>
            @if($audit->user)
                <div class="flex items-center mb-2">
                    <div class="h-10 w-10 rounded-full bg-neutral-900 text-white flex items-center justify-center font-bold text-lg mr-3">
                        {{ substr($audit->user->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="font-bold text-gray-900">{{ $audit->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $audit->user->email }}</div>
                    </div>
                </div>
                <div class="mt-2 text-xs text-gray-500">User ID: {{ $audit->user_id }}</div>
            @else
                <div class="flex items-center gap-2 text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="italic">System / Guest User</span>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-4">Target Model</h3>
            <div>
                <span class="block text-xs text-gray-500">Model Class</span>
                <code class="text-xs bg-gray-100 px-2 py-1 rounded block mt-1 overflow-x-auto">{{ $audit->auditable_type }}</code>
            </div>
            <div class="mt-3">
                <span class="block text-xs text-gray-500">Model ID</span>
                <span class="font-mono text-sm font-bold text-gray-900">#{{ $audit->auditable_id }}</span>
            </div>
        </div>
    </div>

    <!-- Changes -->
    <div class="col-span-1 lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">Modifications</h3>
                <span class="text-xs text-gray-500">Old vs New Values</span>
            </div>
            
            @php
                $old = $audit->old_values;
                $new = $audit->new_values;
                // Merge keys to show all fields involved
                $keys = array_unique(array_merge(array_keys($old), array_keys($new)));
            @endphp

            @if(empty($keys))
                <div class="p-8 text-center text-gray-500">
                    <p class="italic">No specific field changes recorded (e.g. strict boolean toggle, or creation without logging initial state).</p>
                    @if($audit->event == 'created' && !empty($new))
                         <p class="mt-2 text-xs">This was a creation event. See 'New Values' below.</p>
                    @endif
                </div>
                
                @if(!empty($new))
                <div class="border-t border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Field</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($new as $key => $val)
                                <tr>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $key }}</td>
                                    <td class="px-6 py-3 text-sm text-gray-600 break-words">{{ is_array($val) ? json_encode($val) : $val }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            @else
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Field</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Old Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">New Value</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($keys as $key)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                    {{ str_replace('_', ' ', ucfirst($key)) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-red-600 break-all bg-red-50 relative">
                                    <span class="absolute top-0 left-0 text-[10px] text-red-300 px-1">OLD</span>
                                    {{ isset($old[$key]) ? (is_array($old[$key]) ? json_encode($old[$key]) : $old[$key]) : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-green-600 break-all bg-green-50 relative">
                                    <span class="absolute top-0 left-0 text-[10px] text-green-300 px-1">NEW</span>
                                    {{ isset($new[$key]) ? (is_array($new[$key]) ? json_encode($new[$key]) : $new[$key]) : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
</x-app-layout>

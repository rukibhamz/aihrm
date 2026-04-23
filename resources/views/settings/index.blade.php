<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">System Settings</h1>
        <p class="mt-1 text-sm text-neutral-500">Configure your organization's global parameters, branding, and integrations.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

@php /** @var \Illuminate\Support\ViewErrorBag $errors */ @endphp
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li class="text-sm text-red-800 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div x-data="{ 
            activeTab: localStorage.getItem('settings_tab') || 'general',
            primaryColor: '{{ old('primary_color', $settings['primary_color'] ?? '#000000') }}',
            secondaryColor: '{{ old('secondary_color', $settings['secondary_color'] ?? '#171717') }}'
         }" 
         x-init="$watch('activeTab', value => localStorage.setItem('settings_tab', value))"
         class="space-y-6">
        
        <!-- Tab Navigation -->
        <div class="border-b border-neutral-200">
            <nav class="flex gap-8" aria-label="Tabs">
                <button @click="activeTab = 'general'" :class="activeTab === 'general' ? 'active-tab' : 'text-neutral-500 hover:text-neutral-700'" class="pb-4 text-sm font-semibold border-b-2 border-transparent transition-all outline-none">
                    General
                </button>
                <button @click="activeTab = 'branding'" :class="activeTab === 'branding' ? 'active-tab' : 'text-neutral-500 hover:text-neutral-700'" class="pb-4 text-sm font-semibold border-b-2 border-transparent transition-all outline-none">
                    Branding
                </button>
                <button @click="activeTab = 'email'" :class="activeTab === 'email' ? 'active-tab' : 'text-neutral-500 hover:text-neutral-700'" class="pb-4 text-sm font-semibold border-b-2 border-transparent transition-all outline-none">
                    Email (SMTP)
                </button>
                <button @click="activeTab = 'sso'" :class="activeTab === 'sso' ? 'active-tab' : 'text-neutral-500 hover:text-neutral-700'" class="pb-4 text-sm font-semibold border-b-2 border-transparent transition-all outline-none">
                    Authentication (SSO)
                </button>
                <button @click="activeTab = 'system'" :class="activeTab === 'system' ? 'active-tab' : 'text-neutral-500 hover:text-neutral-700'" class="pb-4 text-sm font-semibold border-b-2 border-transparent transition-all outline-none">
                    System & Workflows
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- General Tab -->
            <div x-show="activeTab === 'general'" x-cloak class="space-y-6 animate-in fade-in duration-300">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="card p-8">
                            <h3 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2 italic">
                                <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Company Information
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Company Name *</label>
                                    <input type="text" name="company_name" required value="{{ old('company_name', $settings['company_name']) }}"
                                        class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Public Email</label>
                                    <input type="email" name="company_email" value="{{ old('company_email', $settings['company_email']) }}"
                                        class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Phone Number</label>
                                    <input type="text" name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}"
                                        class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Physical Address</label>
                                    <textarea name="company_address" rows="3"
                                        class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">{{ old('company_address', $settings['company_address']) }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Currency Code</label>
                                    <input type="text" name="currency_code" value="{{ old('currency_code', $settings['currency_code'] ?? 'NGN') }}"
                                        placeholder="NGN"
                                        class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium uppercase">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Currency Symbol / Prefix</label>
                                    <input type="text" name="currency_symbol" value="{{ old('currency_symbol', $settings['currency_symbol'] ?? 'NGN') }}"
                                        placeholder="NGN or $"
                                        class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                                </div>
                            </div>
                        </div>

                        <div class="card p-8">
                            <h3 class="text-lg font-bold text-neutral-900 mb-2 flex items-center gap-2 italic">
                                <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                                Operations & Leave
                            </h3>
                            <p class="text-xs text-neutral-500 mb-6 italic">Define your typical working week to enable accurate leave calculations.</p>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                                @php
                                    $days = [1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'];
                                @endphp
                                
                                @foreach($days as $num => $name)
                                    <label class="flex flex-col items-center gap-2 p-3 border border-neutral-100 rounded-xl cursor-pointer hover:bg-neutral-50 hover:border-neutral-200 transition group">
                                        <input type="checkbox" name="working_days[]" value="{{ $num }}" 
                                            {{ in_array($num, old('working_days', $settings['working_days'])) ? 'checked' : '' }}
                                            class="w-4 h-4 text-black border-neutral-300 rounded focus:ring-primary">
                                        <span class="text-[10px] font-bold text-neutral-500 uppercase tracking-tighter group-hover:text-black transition">{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="card p-8">
                            <h3 class="text-lg font-bold text-neutral-900 mb-4 flex items-center gap-2 italic">
                                <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Performance
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Cycle Frequency</label>
                                    <select name="performance_cycle_frequency" class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                                        <option value="monthly" {{ old('performance_cycle_frequency', $settings['performance_cycle_frequency']) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('performance_cycle_frequency', $settings['performance_cycle_frequency']) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="half_yearly" {{ old('performance_cycle_frequency', $settings['performance_cycle_frequency']) == 'half_yearly' ? 'selected' : '' }}>Half-Yearly</option>
                                        <option value="annual" {{ old('performance_cycle_frequency', $settings['performance_cycle_frequency']) == 'annual' ? 'selected' : '' }}>Annually</option>
                                    </select>
                                    <p class="mt-2 text-[10px] text-neutral-400 italic">Determines the default period for goals and reviews.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Branding Tab -->
            <div x-show="activeTab === 'branding'" x-cloak class="space-y-6 animate-in fade-in duration-300">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="card p-8">
                        <h3 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2 italic">
                            <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Visual Identity
                        </h3>
                        
                        <div class="space-y-8">
                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-4">Organization Logo</label>
                                <div class="flex items-center gap-6">
                                    <div class="w-24 h-24 bg-neutral-50 rounded-2xl flex items-center justify-center border-2 border-dashed border-neutral-200 overflow-hidden group hover:border-black transition-colors">
                                        @if(isset($settings['company_logo']) && $settings['company_logo'])
                                            <img src="{{ Storage::url($settings['company_logo']) }}" alt="Logo" class="max-w-full max-h-full p-2 object-contain">
                                        @else
                                            <svg class="w-10 h-10 text-neutral-300 group-hover:text-neutral-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="company_logo" class="block w-full text-xs text-neutral-500
                                            file:mr-4 file:py-3 file:px-6
                                            file:rounded-xl file:border-0
                                            file:text-xs file:font-bold
                                            file:bg-neutral-900 file:text-white
                                            hover:file:bg-black transition cursor-pointer">
                                        <p class="mt-2 text-[10px] text-neutral-400 italic">SVG or transparent PNG recommended (256x256px).</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card p-8">
                        <h3 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2 italic">
                            <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17l.354-.354"/></svg>
                            Interface Theme
                        </h3>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-4">Primary Brand Color</label>
                                <div class="flex items-center gap-4">
                                    <input type="color" name="primary_color" x-model="primaryColor"
                                        class="w-16 h-16 border-0 rounded-2xl cursor-pointer p-0 overflow-hidden shadow-sm">
                                    <div class="flex-1">
                                        <input type="text" x-model="primaryColor" 
                                            class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-mono bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition">
                                        <p class="mt-2 text-[10px] text-neutral-400 italic">This color will be used for buttons, links, and highlights.</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-4">Secondary Brand Color</label>
                                <div class="flex items-center gap-4">
                                    <input type="color" name="secondary_color" x-model="secondaryColor"
                                        class="w-16 h-16 border-0 rounded-2xl cursor-pointer p-0 overflow-hidden shadow-sm">
                                    <div class="flex-1">
                                        <input type="text" x-model="secondaryColor" 
                                            class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-mono bg-white focus:ring-2 focus:ring-primary focus:border-transparent transition">
                                        <p class="mt-2 text-[10px] text-neutral-400 italic">This color will be used for the sidebar and major backgrounds.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Tab -->
            <div x-show="activeTab === 'email'" x-cloak class="space-y-6 animate-in fade-in duration-300">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 card p-8">
                        <h3 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2 italic">
                            <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Outbound Mail Server (SMTP)
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Host</label>
                                    <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings['smtp_host']) }}"
                                        placeholder="smtp.mailtrap.io"
                                        class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Port</label>
                                    <input type="number" name="smtp_port" value="{{ old('smtp_port', $settings['smtp_port']) }}"
                                        placeholder="587"
                                        class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Username</label>
                                <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings['smtp_username']) }}"
                                    class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Password</label>
                                <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings['smtp_password']) }}"
                                    class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Security</label>
                                <select name="smtp_encryption" class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                                    <option value="tls" {{ old('smtp_encryption', $settings['smtp_encryption']) == 'tls' ? 'selected' : '' }}>STARTTLS</option>
                                    <option value="ssl" {{ old('smtp_encryption', $settings['smtp_encryption']) == 'ssl' ? 'selected' : '' }}>SSL/TLS</option>
                                    <option value="none" {{ old('smtp_encryption', $settings['smtp_encryption']) == 'none' ? 'selected' : '' }}>None</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Sender Name</label>
                                <input type="text" name="smtp_from_name" value="{{ old('smtp_from_name', $settings['smtp_from_name']) }}"
                                    class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Sender Email Address</label>
                                <input type="email" name="smtp_from_address" value="{{ old('smtp_from_address', $settings['smtp_from_address']) }}"
                                    class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="card p-8 border border-neutral-200">
                            <h3 class="text-sm font-bold uppercase tracking-widest mb-4 italic text-neutral-900">Connection Test</h3>
                            <p class="text-xs text-neutral-500 mb-6 italic leading-relaxed">Ensure your credentials are correct by sending a diagnostic message.</p>
                            
                            <div class="space-y-4" x-data="{ testEmail: '' }">
                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Recipient Address</label>
                                    <input type="email" name="test_email" x-model="testEmail" placeholder="you@domain.com"
                                        class="w-full px-4 py-3 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition text-sm font-medium">
                                </div>
                                <button type="button" 
                                        @click="if(testEmail) { $el.closest('form').action = '{{ route('settings.test-email') }}'; $el.closest('form').submit(); }"
                                        class="w-full py-3 bg-white text-neutral-900 text-xs font-bold rounded-xl hover:bg-neutral-100 transition-all uppercase tracking-widest shadow-sm border border-neutral-200">
                                    Send Test Email
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SSO Tab -->
            <div x-show="activeTab === 'sso'" x-cloak class="space-y-6 animate-in fade-in duration-300">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div class="card p-8">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-lg font-bold text-neutral-900 flex items-center gap-3 italic">
                                    <svg class="h-5 w-5" viewBox="0 0 23 23"><rect width="11" height="11" fill="#F25022"/><rect x="12" width="11" height="11" fill="#7FBA00"/><rect y="12" width="11" height="11" fill="#00A4EF"/><rect x="12" y="12" width="11" height="11" fill="#FFB900"/></svg>
                                    Microsoft Entra ID
                                </h3>
                                <div class="flex items-center gap-2">
                                    <select name="sso_azure_enabled" class="text-xs font-bold border border-neutral-200 rounded-lg px-3 py-1.5 bg-neutral-50 transition focus:ring-1 focus:ring-primary">
                                        <option value="no" {{ ($settings['sso_azure_enabled'] ?? 'no') === 'no' ? 'selected' : '' }}>Disabled</option>
                                        <option value="yes" {{ ($settings['sso_azure_enabled'] ?? 'no') === 'yes' ? 'selected' : '' }}>Enabled</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Application (Client) ID</label>
                                    <input type="text" name="azure_client_id" value="{{ $settings['azure_client_id'] ?? '' }}" class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-mono lowercase" placeholder="00000000-0000-0000-0000-000000000000">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Client Secret</label>
                                    <input type="password" name="azure_client_secret" value="{{ $settings['azure_client_secret'] ?? '' }}" class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-mono" placeholder="••••••••••••••••••••">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Directory (Tenant) ID</label>
                                    <input type="text" name="azure_tenant_id" value="{{ $settings['azure_tenant_id'] ?? '' }}" class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-mono lowercase" placeholder="common">
                                </div>
                            </div>
                        </div>

                        <div class="card p-8">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-lg font-bold text-neutral-900 flex items-center gap-3 italic">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#C8202B"/><path d="M7 16.5l3-5.5h2l-3 5.5H7zm4.5-5.5h2l3 5.5h-2l-3-5.5z" fill="white"/></svg>
                                    Zoho Workspace
                                </h3>
                                <div class="flex items-center gap-2">
                                    <select name="sso_zoho_enabled" class="text-xs font-bold border border-neutral-200 rounded-lg px-3 py-1.5 bg-neutral-50 transition focus:ring-1 focus:ring-primary">
                                        <option value="no" {{ ($settings['sso_zoho_enabled'] ?? 'no') === 'no' ? 'selected' : '' }}>Disabled</option>
                                        <option value="yes" {{ ($settings['sso_zoho_enabled'] ?? 'no') === 'yes' ? 'selected' : '' }}>Enabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Client ID</label>
                                    <input type="text" name="zoho_client_id" value="{{ $settings['zoho_client_id'] ?? '' }}" class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-mono">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Client Secret</label>
                                    <input type="password" name="zoho_client_secret" value="{{ $settings['zoho_client_secret'] ?? '' }}" class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-mono">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="card p-8 border-l-4 border-l-amber-400">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-lg font-bold text-neutral-900 flex items-center gap-3 italic">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                                    Google Workspace
                                </h3>
                                <div class="flex items-center gap-2">
                                    <select name="sso_google_enabled" class="text-xs font-bold border border-neutral-200 rounded-lg px-3 py-1.5 bg-neutral-50 transition focus:ring-1 focus:ring-primary">
                                        <option value="no" {{ ($settings['sso_google_enabled'] ?? 'no') === 'no' ? 'selected' : '' }}>Disabled</option>
                                        <option value="yes" {{ ($settings['sso_google_enabled'] ?? 'no') === 'yes' ? 'selected' : '' }}>Enabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Google Client ID</label>
                                    <input type="text" name="google_client_id" value="{{ $settings['google_client_id'] ?? '' }}" class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-mono" placeholder="xxxx.apps.googleusercontent.com">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Google Client Secret</label>
                                    <input type="password" name="google_client_secret" value="{{ $settings['google_client_secret'] ?? '' }}" class="w-full px-4 py-3 border border-neutral-200 rounded-xl text-sm font-mono">
                                </div>
                            </div>
                        </div>

                        <div class="card p-8 bg-neutral-50 border-neutral-200">
                            <h4 class="text-sm font-bold text-neutral-900 mb-4 italic">Security & Policies</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2">Automated Registration</label>
                                    <select name="sso_allow_registration" class="w-full px-4 py-3 border border-neutral-200 rounded-xl bg-white text-sm font-medium">
                                        <option value="no" {{ ($settings['sso_allow_registration'] ?? 'no') === 'no' ? 'selected' : '' }}>Disabled (System Accounts Only)</option>
                                        <option value="yes" {{ ($settings['sso_allow_registration'] ?? 'no') === 'yes' ? 'selected' : '' }}>Enabled (New Users Allowed)</option>
                                    </select>
                                    <p class="mt-2 text-[10px] text-neutral-400 italic font-medium leading-relaxed">If enabled, the system will automatically create an employee profile upon a successful SSO handshake for unrecorded email addresses.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Tab -->
            <div x-show="activeTab === 'system'" x-cloak class="space-y-6 animate-in fade-in duration-300">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="card p-8">
                        <h3 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2 italic">
                            <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/></svg>
                            Core Workflows
                        </h3>
                        <p class="text-sm text-neutral-600 mb-6 leading-relaxed italic">Manage multi-stage approval levels for critical system actions like leave requests and financial disbursements.</p>
                        <a href="{{ route('admin.approval-chains.index') }}" 
                           class="inline-flex items-center gap-3 px-6 py-3 bg-neutral-900 text-white rounded-xl text-xs font-bold hover:bg-black transition-all shadow-lg shadow-neutral-200 uppercase tracking-widest">
                            Configuration Master
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>

                    <div class="card p-8">
                        <h3 class="text-lg font-bold text-neutral-900 mb-6 flex items-center gap-2 italic">
                            <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Runtime Environment
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-neutral-50">
                                <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Application Version</span>
                                <span class="text-sm font-bold text-neutral-900">1.0.4-LTS</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-neutral-50">
                                <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Laravel Framework</span>
                                <span class="text-sm font-bold text-neutral-900">{{ app()->version() }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-neutral-50">
                                <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider">PHP Version</span>
                                <span class="text-sm font-bold text-neutral-900">{{ PHP_VERSION }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Database Engine</span>
                                <span class="text-sm font-bold text-neutral-900 uppercase">MySQL Gen 8</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Save Bar -->
            <div class="sticky bottom-0 -mx-8 -mb-8 mt-12 px-8 py-6 bg-white/80 backdrop-blur-md border-t border-neutral-200 flex items-center justify-between z-20">
                <div>
                    <h4 class="text-xs font-bold text-neutral-900 uppercase tracking-widest">Configuration Management</h4>
                    <p class="text-[10px] text-neutral-500 mt-1 italic">Changes will be applied globally across the organization.</p>
                </div>
                <div class="flex items-center gap-4">
                    <button type="submit" 
                        @click="$el.closest('form').action = '{{ route('settings.update') }}'; $el.closest('form').submit();"
                        class="flex items-center gap-3 px-10 py-3.5 bg-neutral-900 text-white rounded-xl shadow-xl hover:bg-black hover:-translate-y-0.5 active:translate-y-0 transition-all focus:outline-none group">
                        <span class="font-bold text-xs uppercase tracking-widest">Sync Configurations</span>
                        <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <style>
        .active-tab {
            color: #000;
            border-bottom-color: #000 !important;
        }
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>


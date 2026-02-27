<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-neutral-900">Company Settings</h1>
        <p class="mt-1 text-sm text-neutral-500">Configure your company information and branding</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="list-disc list-inside text-sm text-red-800">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Form -->
        <div class="lg:col-span-2">
            <div class="card p-8">
                <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Company Information</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Company Name *</label>
                                <input type="text" name="company_name" required value="{{ old('company_name', $settings['company_name']) }}"
                                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Email</label>
                                <input type="email" name="company_email" value="{{ old('company_email', $settings['company_email']) }}"
                                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Phone</label>
                                <input type="text" name="company_phone" value="{{ old('company_phone', $settings['company_phone']) }}"
                                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Address</label>
                                <textarea name="company_address" rows="3"
                                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">{{ old('company_address', $settings['company_address']) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-neutral-200">
                        <h3 class="text-lg font-semibold mb-2">Working Days Configuration</h3>
                        <p class="text-sm text-neutral-500 mb-4">Select which days of the week are working days. This affects leave day calculations.</p>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @php
                                $days = [
                                    1 => 'Monday',
                                    2 => 'Tuesday',
                                    3 => 'Wednesday',
                                    4 => 'Thursday',
                                    5 => 'Friday',
                                    6 => 'Saturday',
                                    7 => 'Sunday'
                                ];
                            @endphp
                            
                            @foreach($days as $num => $name)
                                <label class="flex items-center gap-2 p-3 border border-neutral-200 rounded-lg cursor-pointer hover:bg-neutral-50 transition">
                                    <input type="checkbox" name="working_days[]" value="{{ $num }}" 
                                        {{ in_array($num, old('working_days', $settings['working_days'])) ? 'checked' : '' }}
                                        class="w-4 h-4 text-black border-neutral-300 rounded focus:ring-black">
                                    <span class="text-sm font-medium text-neutral-700">{{ $name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-6 border-t border-neutral-200">
                        <h3 class="text-lg font-semibold mb-2">Email Configuration (SMTP)</h3>
                        <p class="text-sm text-neutral-500 mb-4">Configure email settings for sending notifications and reports.</p>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">SMTP Host</label>
                                    <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings['smtp_host']) }}"
                                        placeholder="smtp.gmail.com"
                                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">SMTP Port</label>
                                    <input type="number" name="smtp_port" value="{{ old('smtp_port', $settings['smtp_port']) }}"
                                        placeholder="587"
                                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">SMTP Username</label>
                                    <input type="text" name="smtp_username" value="{{ old('smtp_username', $settings['smtp_username']) }}"
                                        placeholder="your-email@example.com"
                                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">SMTP Password</label>
                                    <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings['smtp_password']) }}"
                                        placeholder="••••••••"
                                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">Encryption</label>
                                    <select name="smtp_encryption" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                                        <option value="tls" {{ old('smtp_encryption', $settings['smtp_encryption']) == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ old('smtp_encryption', $settings['smtp_encryption']) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="none" {{ old('smtp_encryption', $settings['smtp_encryption']) == 'none' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-neutral-700 mb-2">From Name</label>
                                    <input type="text" name="smtp_from_name" value="{{ old('smtp_from_name', $settings['smtp_from_name']) }}"
                                        placeholder="AIHRM"
                                        class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">From Email Address</label>
                                <input type="email" name="smtp_from_address" value="{{ old('smtp_from_address', $settings['smtp_from_address']) }}"
                                    placeholder="noreply@example.com"
                                    class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-neutral-200">
                        <h3 class="text-lg font-semibold mb-2">Performance & Goals Configuration</h3>
                        <p class="text-sm text-neutral-500 mb-4">Set the default performance cycle length for the organization. This determines the cycle applied to newly created goals.</p>
                        
                        <div class="max-w-md">
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Goal Cycle Frequency</label>
                            <select name="performance_cycle_frequency" class="w-full px-4 py-2.5 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                                <option value="monthly" {{ old('performance_cycle_frequency', $settings['performance_cycle_frequency']) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ old('performance_cycle_frequency', $settings['performance_cycle_frequency']) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="half_yearly" {{ old('performance_cycle_frequency', $settings['performance_cycle_frequency']) == 'half_yearly' ? 'selected' : '' }}>Half-Yearly</option>
                                <option value="annual" {{ old('performance_cycle_frequency', $settings['performance_cycle_frequency']) == 'annual' ? 'selected' : '' }}>Annually</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-neutral-200">
                        <h3 class="text-lg font-semibold mb-4">SSO Configuration</h3>
                        
                        <!-- Azure AD -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-neutral-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10.5 0L0 0L0 21L10.5 21V0Z" fill="#F25022"/><path d="M21 0L10.5 0V21H21V0Z" fill="#00A4EF"/><path d="M10.5 0L0 0L0 10.5H10.5V0Z" fill="#7FBA00"/><path d="M21 0L10.5 0V10.5H21V0Z" fill="#FFB900"/><path d="M10.5 10.5H0V21H10.5V10.5Z" fill="#F25022"/><path d="M21 10.5H10.5V21H21V10.5Z" fill="#00A4EF"/></svg>
                                Microsoft Azure AD
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client ID</label>
                                    <input type="text" name="azure_client_id" value="{{ old('azure_client_id', $settings['azure_client_id']) }}"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-black focus:border-black">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client Secret</label>
                                    <input type="password" name="azure_client_secret" value="{{ old('azure_client_secret', $settings['azure_client_secret']) }}"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-black focus:border-black">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Tenant ID</label>
                                    <input type="text" name="azure_tenant_id" value="{{ old('azure_tenant_id', $settings['azure_tenant_id']) }}"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-black focus:border-black">
                                </div>
                            </div>
                        </div>

                        <!-- Google Workspace -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-neutral-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/></svg>
                                Google Workspace
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client ID</label>
                                    <input type="text" name="google_client_id" value="{{ old('google_client_id', $settings['google_client_id']) }}"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-black focus:border-black">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client Secret</label>
                                    <input type="password" name="google_client_secret" value="{{ old('google_client_secret', $settings['google_client_secret']) }}"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-black focus:border-black">
                                </div>
                            </div>
                        </div>

                        <!-- Zoho Workspace -->
                        <div>
                            <h4 class="text-sm font-medium text-neutral-900 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20.5 2h-17A1.5 1.5 0 002 3.5v17A1.5 1.5 0 003.5 22h17a1.5 1.5 0 001.5-1.5v-17A1.5 1.5 0 0020.5 2zM9 17H7v-4H5v-2h2V7h2v4h2v2H9v4zm6 0h-2V7h2v10zm4 0h-2v-4h-2v-2h2V7h2v4h2v2h-2v4z"/></svg>
                                Zoho Workspace
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client ID</label>
                                    <input type="text" name="zoho_client_id" value="{{ old('zoho_client_id', $settings['zoho_client_id']) }}"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-black focus:border-black">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client Secret</label>
                                    <input type="password" name="zoho_client_secret" value="{{ old('zoho_client_secret', $settings['zoho_client_secret']) }}"
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-black focus:border-black">
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Branding -->
                <div class="pt-6 border-t border-neutral-200">
                    <h3 class="text-lg font-semibold mb-4 text-neutral-800">Branding</h3>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Company Logo</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-neutral-100 rounded-lg flex items-center justify-center border border-dashed border-neutral-300 overflow-hidden">
                                @if(isset($settings['company_logo']) && $settings['company_logo'])
                                    <img src="{{ Storage::url($settings['company_logo']) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                @else
                                    <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="company_logo" class="block w-full text-sm text-neutral-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-black file:text-white
                                    hover:file:bg-neutral-800 transition">
                                <p class="mt-1 text-xs text-neutral-500">Recommended size: 200x200px. PNG or SVG preferred.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appearance -->
                <div class="pt-6 border-t border-neutral-200">
                    <h3 class="text-lg font-semibold mb-4 text-neutral-800">Appearance</h3>
                    <div class="max-w-xs">
                        <label class="block text-sm font-medium text-neutral-700 mb-2">Primary Theme Color</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="primary_color" value="{{ old('primary_color', $settings['primary_color'] ?? '#000000') }}"
                                class="w-12 h-10 border border-neutral-300 rounded-md cursor-pointer p-1">
                            <input type="text" value="{{ old('primary_color', $settings['primary_color'] ?? '#000000') }}" 
                                class="w-32 px-3 py-2 border border-neutral-300 rounded-lg text-sm bg-neutral-50" readonly>
                        </div>
                        <p class="mt-2 text-xs text-neutral-500">Choose the primary color used for buttons, links, and accents across the platform.</p>
                    </div>
                </div>

                <!-- SSO / Single Sign-On -->
                <div class="pt-6 border-t border-neutral-200">
                    <h3 class="text-lg font-semibold mb-1 text-neutral-800">Single Sign-On (SSO)</h3>
                    <p class="text-xs text-neutral-500 mb-4">Enable providers and manage credentials. See <a href="/SSO_SETUP.md" class="underline">setup guide</a> for instructions.</p>
                    
                    <div class="space-y-5">
                        <!-- Azure AD -->
                        <div class="p-4 border border-neutral-200 rounded-lg space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="h-5 w-5" viewBox="0 0 23 23"><rect width="11" height="11" fill="#F25022"/><rect x="12" width="11" height="11" fill="#7FBA00"/><rect y="12" width="11" height="11" fill="#00A4EF"/><rect x="12" y="12" width="11" height="11" fill="#FFB900"/></svg>
                                    <span class="text-sm font-medium">Microsoft Azure AD</span>
                                </div>
                                <select name="sso_azure_enabled" class="text-sm border border-neutral-300 rounded-md px-2 py-1">
                                    <option value="no" {{ ($settings['sso_azure_enabled'] ?? 'no') === 'no' ? 'selected' : '' }}>Disabled</option>
                                    <option value="yes" {{ ($settings['sso_azure_enabled'] ?? 'no') === 'yes' ? 'selected' : '' }}>Enabled</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client ID</label>
                                    <input type="text" name="azure_client_id" value="{{ $settings['azure_client_id'] ?? '' }}" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm" placeholder="Application (client) ID">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client Secret</label>
                                    <input type="password" name="azure_client_secret" value="{{ $settings['azure_client_secret'] ?? '' }}" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm" placeholder="••••••••">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Tenant ID</label>
                                    <input type="text" name="azure_tenant_id" value="{{ $settings['azure_tenant_id'] ?? '' }}" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm" placeholder="Directory (tenant) ID">
                                </div>
                            </div>
                        </div>

                        <!-- Google Workspace -->
                        <div class="p-4 border border-neutral-200 rounded-lg space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                                    <span class="text-sm font-medium">Google Workspace</span>
                                </div>
                                <select name="sso_google_enabled" class="text-sm border border-neutral-300 rounded-md px-2 py-1">
                                    <option value="no" {{ ($settings['sso_google_enabled'] ?? 'no') === 'no' ? 'selected' : '' }}>Disabled</option>
                                    <option value="yes" {{ ($settings['sso_google_enabled'] ?? 'no') === 'yes' ? 'selected' : '' }}>Enabled</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client ID</label>
                                    <input type="text" name="google_client_id" value="{{ $settings['google_client_id'] ?? '' }}" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm" placeholder="xxxx.apps.googleusercontent.com">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client Secret</label>
                                    <input type="password" name="google_client_secret" value="{{ $settings['google_client_secret'] ?? '' }}" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm" placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <!-- Zoho -->
                        <div class="p-4 border border-neutral-200 rounded-lg space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><rect width="24" height="24" rx="4" fill="#C8202B"/><path d="M7 16.5l3-5.5h2l-3 5.5H7zm4.5-5.5h2l3 5.5h-2l-3-5.5z" fill="white"/></svg>
                                    <span class="text-sm font-medium">Zoho Workspace</span>
                                </div>
                                <select name="sso_zoho_enabled" class="text-sm border border-neutral-300 rounded-md px-2 py-1">
                                    <option value="no" {{ ($settings['sso_zoho_enabled'] ?? 'no') === 'no' ? 'selected' : '' }}>Disabled</option>
                                    <option value="yes" {{ ($settings['sso_zoho_enabled'] ?? 'no') === 'yes' ? 'selected' : '' }}>Enabled</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client ID</label>
                                    <input type="text" name="zoho_client_id" value="{{ $settings['zoho_client_id'] ?? '' }}" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm" placeholder="Zoho client ID">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-neutral-500 mb-1">Client Secret</label>
                                    <input type="password" name="zoho_client_secret" value="{{ $settings['zoho_client_secret'] ?? '' }}" class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm" placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <!-- Registration Policy -->
                        <div class="p-4 bg-neutral-50 border border-neutral-200 rounded-lg">
                            <label class="flex items-start gap-3">
                                <select name="sso_allow_registration" class="text-sm border border-neutral-300 rounded-md px-2 py-1 mt-0.5 flex-shrink-0">
                                    <option value="no" {{ ($settings['sso_allow_registration'] ?? 'no') === 'no' ? 'selected' : '' }}>No</option>
                                    <option value="yes" {{ ($settings['sso_allow_registration'] ?? 'no') === 'yes' ? 'selected' : '' }}>Yes</option>
                                </select>
                                <div>
                                    <span class="text-sm font-medium text-neutral-800">Allow new user registration via SSO</span>
                                    <p class="text-xs text-neutral-500 mt-0.5">When disabled, only employees already in the system can log in via SSO. When enabled, new accounts are created automatically for SSO users.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                    <div class="flex gap-3 pt-6 border-t border-neutral-200">
                        <button type="submit" class="btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="space-y-6">
            <div class="card p-6">
                <h3 class="font-semibold mb-3">Test Email Configuration</h3>
                <p class="text-sm text-neutral-600 mb-4">
                    Send a test email to verify your SMTP settings are working correctly.
                </p>
                <form method="POST" action="{{ route('settings.test-email') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-neutral-500 mb-1">Test Email Address</label>
                        <input type="email" name="test_email" required placeholder="test@example.com"
                            class="w-full px-3 py-2 border border-neutral-300 rounded-md text-sm focus:ring-black focus:border-black">
                    </div>
                    <button type="submit" class="w-full btn-secondary text-sm">
                        Send Test Email
                    </button>
                </form>
            </div>
            
            <div class="card p-6">
                <h3 class="font-semibold mb-3">About Settings</h3>
                <p class="text-sm text-neutral-600">
                    Configure your company information here. This will be displayed throughout the system and in communications.
                </p>
            </div>

            <div class="card p-6">
                <h3 class="font-semibold mb-3">System Info</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Version</span>
                        <span class="font-medium">1.0.0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-500">Laravel</span>
                        <span class="font-medium">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-500">PHP</span>
                        <span class="font-medium">{{ PHP_VERSION }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

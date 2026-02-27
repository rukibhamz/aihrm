<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'company_name' => Setting::get('company_name', 'AIHRM'),
            'company_email' => Setting::get('company_email', ''),
            'company_phone' => Setting::get('company_phone', ''),
            'company_address' => Setting::get('company_address', ''),
            'company_logo' => Setting::get('company_logo', ''),
            
            // Working Days Configuration
            'working_days' => json_decode(Setting::get('working_days', '[1,2,3,4,5]'), true),
            
            // Performance & Goals
            'performance_cycle_frequency' => Setting::get('performance_cycle_frequency', 'annual'),
            
            // SMTP Settings
            'smtp_host' => Setting::get('smtp_host', ''),
            'smtp_port' => Setting::get('smtp_port', '587'),
            'smtp_username' => Setting::get('smtp_username', ''),
            'smtp_password' => Setting::get('smtp_password', ''),
            'smtp_encryption' => Setting::get('smtp_encryption', 'tls'),
            'smtp_from_address' => Setting::get('smtp_from_address', ''),
            'smtp_from_name' => Setting::get('smtp_from_name', 'AIHRM'),
            
            // SSO Settings
            'azure_client_id' => Setting::get('azure_client_id', ''),
            'azure_client_secret' => Setting::get('azure_client_secret', ''),
            'azure_tenant_id' => Setting::get('azure_tenant_id', ''),
            'google_client_id' => Setting::get('google_client_id', ''),
            'google_client_secret' => Setting::get('google_client_secret', ''),
            'zoho_client_id' => Setting::get('zoho_client_id', ''),
            'zoho_client_secret' => Setting::get('zoho_client_secret', ''),
            
            // Branding & Theme
            'primary_color' => Setting::get('primary_color', '#000000'),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:20',
            'company_address' => 'nullable|string|max:500',
            'company_logo' => 'nullable|image|max:2048',
            
            // Working Days Validation
            'working_days' => 'required|array|min:1',
            'working_days.*' => 'integer|min:1|max:7',

            // Performance Cycles
            'performance_cycle_frequency' => 'required|in:monthly,quarterly,half_yearly,annual',
            
            // SMTP Validation
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|in:tls,ssl,none',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
            
            // SSO Validation
            'azure_client_id' => 'nullable|string|max:255',
            'azure_client_secret' => 'nullable|string|max:255',
            'azure_tenant_id' => 'nullable|string|max:255',
            'google_client_id' => 'nullable|string|max:255',
            'google_client_secret' => 'nullable|string|max:255',
            'zoho_client_id' => 'nullable|string|max:255',
            'zoho_client_secret' => 'nullable|string|max:255',
            
            // Branding & Theme
            'primary_color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ]);

        // Handle logo upload
        if ($request->hasFile('company_logo')) {
            // Delete old logo
            $oldLogo = Setting::get('company_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            // Store new logo
            $logoPath = $request->file('company_logo')->store('company', 'public');
            Setting::set('company_logo', $logoPath, 'file');
        }

        // Save other settings
        Setting::set('company_name', $validated['company_name']);
        Setting::set('company_email', $validated['company_email'] ?? '');
        Setting::set('company_phone', $validated['company_phone'] ?? '');
        Setting::set('company_address', $validated['company_address'] ?? '');
        
        // Save Working Days
        Setting::set('working_days', json_encode($validated['working_days']));
        
        // Save Performance Cycle Frequency
        Setting::set('performance_cycle_frequency', $validated['performance_cycle_frequency']);
        
        // Save SMTP Settings
        Setting::set('smtp_host', $validated['smtp_host'] ?? '');
        Setting::set('smtp_port', $validated['smtp_port'] ?? '587');
        Setting::set('smtp_username', $validated['smtp_username'] ?? '');
        Setting::set('smtp_password', $validated['smtp_password'] ?? '');
        Setting::set('smtp_encryption', $validated['smtp_encryption'] ?? 'tls');
        Setting::set('smtp_from_address', $validated['smtp_from_address'] ?? '');
        Setting::set('smtp_from_name', $validated['smtp_from_name'] ?? 'AIHRM');
        
        // Update mail configuration dynamically
        $this->updateMailConfig();

        // Save SSO Settings
        Setting::set('azure_client_id', $validated['azure_client_id'] ?? '');
        Setting::set('azure_client_secret', $validated['azure_client_secret'] ?? '');
        Setting::set('azure_tenant_id', $validated['azure_tenant_id'] ?? '');
        Setting::set('google_client_id', $validated['google_client_id'] ?? '');
        Setting::set('google_client_secret', $validated['google_client_secret'] ?? '');
        Setting::set('zoho_client_id', $validated['zoho_client_id'] ?? '');
        Setting::set('zoho_client_secret', $validated['zoho_client_secret'] ?? '');

        // Save Branding & Theme
        Setting::set('primary_color', $validated['primary_color'] ?? '#000000');

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }
    
    /**
     * Update mail configuration dynamically
     */
    private function updateMailConfig(): void
    {
        $config = [
            'transport' => 'smtp',
            'host' => Setting::get('smtp_host'),
            'port' => Setting::get('smtp_port', 587),
            'encryption' => Setting::get('smtp_encryption', 'tls'),
            'username' => Setting::get('smtp_username'),
            'password' => Setting::get('smtp_password'),
            'from' => [
                'address' => Setting::get('smtp_from_address'),
                'name' => Setting::get('smtp_from_name', 'AIHRM'),
            ],
        ];
        
        config(['mail.mailers.smtp' => $config]);
        config(['mail.from.address' => $config['from']['address']]);
        config(['mail.from.name' => $config['from']['name']]);
    }
    
    /**
     * Test email configuration
     */
    public function testEmail(Request $request)
    {
        $validated = $request->validate([
            'test_email' => 'required|email',
        ]);
        
        try {
            // Update mail config first
            $this->updateMailConfig();
            
            // Send test email
            \Mail::raw('This is a test email from AIHRM. If you received this, your email configuration is working correctly!', function ($message) use ($validated) {
                $message->to($validated['test_email'])
                    ->subject('AIHRM - Test Email');
            });
            
            return back()->with('success', 'Test email sent successfully to ' . $validated['test_email']);
        } catch (\Exception $e) {
            return back()->withErrors(['test_email' => 'Failed to send test email: ' . $e->getMessage()]);
        }
    }
}

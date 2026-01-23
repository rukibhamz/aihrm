<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallController extends Controller
{
    public function fix(Request $request)
    {
        $results = [];

        try {
            // 1. Check/Create .env
            if (!File::exists(base_path('.env'))) {
                File::copy(base_path('.env.example'), base_path('.env'));
                Artisan::call('key:generate', ['--force' => true]);
                $results[] = '✅ .env file created and key generated.';
            } else {
                $results[] = 'ℹ️ .env file already exists.';
            }

            // 2. Storage Permissions (Best Effort)
            try {
                chmod(storage_path(), 0775);
                chmod(base_path('bootstrap/cache'), 0775);
                $results[] = '✅ Storage permissions set (0775).';
            } catch (\Exception $e) {
                $results[] = '⚠️ Could not specific permission levels (might be restricted).';
            }

            // 3. Clear Caches
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            $results[] = '✅ System caches cleared.';

            // 4. Run Migrations
            Artisan::call('migrate', ['--force' => true]);
            $results[] = '✅ Database migrations executed.';

            // 5. Seed Data
            Artisan::call('db:seed', ['--force' => true]);
            $results[] = '✅ Static data seeded (Roles, Settings).';

            return response()->json([
                'status' => 'success',
                'message' => 'System repair completed successfully.',
                'logs' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Installation failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}

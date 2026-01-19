<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class HealthCheckController extends Controller
{
    /**
     * Health check endpoint for monitoring
     */
    public function index(): JsonResponse
    {
        $status = 'healthy';
        $checks = [];

        // Database check
        try {
            DB::connection()->getPdo();
            $checks['database'] = 'ok';
        } catch (\Exception $e) {
            $checks['database'] = 'failed';
            $status = 'unhealthy';
        }

        // Cache check
        try {
            cache()->put('health_check', true, 10);
            $checks['cache'] = cache()->get('health_check') ? 'ok' : 'failed';
        } catch (\Exception $e) {
            $checks['cache'] = 'failed';
            $status = 'unhealthy';
        }

        // Storage check
        try {
            $checks['storage'] = is_writable(storage_path()) ? 'ok' : 'failed';
        } catch (\Exception $e) {
            $checks['storage'] = 'failed';
            $status = 'unhealthy';
        }

        return response()->json([
            'status' => $status,
            'timestamp' => now()->toIso8601String(),
            'checks' => $checks,
            'version' => config('app.version', '1.0.0'),
        ], $status === 'healthy' ? 200 : 503);
    }

    /**
     * Detailed system metrics
     */
    public function metrics(): JsonResponse
    {
        return response()->json([
            'database' => [
                'connection' => DB::connection()->getDatabaseName(),
                'tables_count' => count(DB::select('SHOW TABLES')),
            ],
            'cache' => [
                'driver' => config('cache.default'),
            ],
            'memory' => [
                'usage' => memory_get_usage(true),
                'peak' => memory_get_peak_usage(true),
            ],
            'php' => [
                'version' => PHP_VERSION,
            ],
        ]);
    }
}

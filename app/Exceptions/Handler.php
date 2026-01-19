<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Log to custom channel with context
            Log::channel('daily')->error($e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => request()->fullUrl(),
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Return JSON for API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                'error' => config('app.debug') ? get_class($e) : null,
            ], $this->getStatusCode($e));
        }

        // User-friendly error pages for production
        if (!config('app.debug')) {
            if ($this->isHttpException($e)) {
                return response()->view('errors.' . $e->getStatusCode(), [], $e->getStatusCode());
            }

            return response()->view('errors.500', [], 500);
        }

        return parent::render($request, $e);
    }

    /**
     * Get HTTP status code from exception
     */
    protected function getStatusCode(Throwable $e): int
    {
        if ($this->isHttpException($e)) {
            return $e->getStatusCode();
        }

        return 500;
    }
}

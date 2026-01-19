<?php

use App\Services\AIService;
use Illuminate\Support\Facades\Auth;

// Mock authentication for the test
$user = \App\Models\User::first();
if (!$user) {
    echo "No user found to test with.\n";
    exit(1);
}
Auth::login($user);

$service = new AIService();
try {
    echo "Testing Gemini API...\n";
    $response = $service->chatbotResponse("Hello, how can I apply for leave?");
    echo "Response received:\n" . $response . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

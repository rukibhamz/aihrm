<?php

namespace App\Http\Controllers;

use App\Models\AIChatMessage;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $messages = AIChatMessage::where('user_id', Auth::id())
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return view('chatbot.index', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Build context for AI
        $context = $this->buildContext();

        try {
            // Get AI response
            $response = $this->aiService->chatbotResponse($validated['message'], $context);

            // Save message
            $chatMessage = AIChatMessage::create([
                'user_id' => Auth::id(),
                'message' => $validated['message'],
                'response' => $response,
                'tokens_used' => 0, // Will be tracked by AIService
            ]);

            return response()->json([
                'success' => true,
                'message' => $chatMessage->message,
                'response' => $chatMessage->response,
                'timestamp' => $chatMessage->created_at->format('H:i'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Sorry, I encountered an error. Please try again.',
            ], 500);
        }
    }

    protected function buildContext(): array
    {
        $user = Auth::user();
        $employee = $user->employee;

        return [
            'user_name' => $user->name,
            'department' => $employee->department->name ?? 'N/A',
            'designation' => $employee->designation->title ?? 'N/A',
            'company_policies' => [
                'leave_policy' => 'Employees are entitled to 20 days annual leave per year.',
                'working_hours' => 'Standard working hours are 9 AM to 5 PM, Monday to Friday.',
                'remote_work' => 'Remote work is allowed up to 2 days per week with manager approval.',
            ],
        ];
    }
}

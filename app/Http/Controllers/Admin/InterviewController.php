<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function store(Request $request, \App\Models\Application $application)
    {
        $validated = $request->validate([
            'interviewer_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date|after:now',
            'location_or_link' => 'nullable|string|max:500',
            'round' => 'required|integer|min:1',
            'type' => 'required|in:phone,video,in_person,technical,panel',
            'notes' => 'nullable|string',
        ]);

        $application->interviews()->create($validated);

        // Auto-move application to interview stage if still in screening
        if (in_array($application->status, ['applied', 'screening'])) {
            $application->update(['status' => 'interview']);
        }

        return back()->with('success', 'Interview scheduled successfully.');
    }

    public function update(Request $request, \App\Models\Interview $interview)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,no_show',
            'notes' => 'nullable|string',
        ]);

        $interview->update($validated);

        return back()->with('success', 'Interview updated.');
    }

    public function submitScorecard(Request $request, \App\Models\Interview $interview)
    {
        $validated = $request->validate([
            'communication' => 'required|integer|min:1|max:5',
            'technical_skills' => 'required|integer|min:1|max:5',
            'problem_solving' => 'required|integer|min:1|max:5',
            'cultural_fit' => 'required|integer|min:1|max:5',
            'leadership' => 'required|integer|min:1|max:5',
            'recommendation' => 'required|in:strong_hire,hire,no_hire,strong_no_hire',
            'strengths' => 'nullable|string',
            'weaknesses' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        $criteriaScores = [
            'communication' => $validated['communication'],
            'technical_skills' => $validated['technical_skills'],
            'problem_solving' => $validated['problem_solving'],
            'cultural_fit' => $validated['cultural_fit'],
            'leadership' => $validated['leadership'],
        ];

        $totalScore = round(array_sum($criteriaScores) / count($criteriaScores) * 20); // Scale to 0-100

        $interview->scorecard()->updateOrCreate(
            ['interview_id' => $interview->id],
            [
                'criteria_scores' => $criteriaScores,
                'total_score' => $totalScore,
                'recommendation' => $validated['recommendation'],
                'strengths' => $validated['strengths'],
                'weaknesses' => $validated['weaknesses'],
                'comments' => $validated['comments'],
            ]
        );

        // Auto-mark interview completed
        if ($interview->status === 'scheduled') {
            $interview->update(['status' => 'completed']);
        }

        return back()->with('success', 'Scorecard submitted successfully.');
    }

    public function destroy(\App\Models\Interview $interview)
    {
        $interview->delete();
        return back()->with('success', 'Interview removed.');
    }
}

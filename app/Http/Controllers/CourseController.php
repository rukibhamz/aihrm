<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCompletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['creator', 'userCompletion'])->paginate(12);
        return view('lms.index', compact('courses'));
    }

    public function show(Course $course)
    {
        $course->load('lessons');
        
        // Track progress (start course if not started)
        CourseCompletion::firstOrCreate([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
        ]);

        return view('lms.show', compact('course'));
    }

    public function showLesson(Course $course, \App\Models\Lesson $lesson)
    {
        $course->load('lessons');
        return view('lms.show', compact('course', 'lesson'));
    }

    public function complete(Course $course)
    {
        CourseCompletion::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

        return redirect()->route('lms.index')->with('success', 'Course completed!');
    }

    public function downloadCertificate(Course $course)
    {
        $completion = CourseCompletion::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->firstOrFail();

        $user = Auth::user();
        
        $pdf = Pdf::loadView('lms.certificate', compact('course', 'user', 'completion'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download("certificate-{$course->id}.pdf");
    }
}

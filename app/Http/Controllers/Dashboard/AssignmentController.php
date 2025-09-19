<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use App\Notifications\AssignmentGraded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    // Show assignments for a course
    public function index(Course $course)
    {
        $instructor = Auth::user();

        // if ($instructor->id !== $course->user_id) {
        //     abort(403, 'Unauthorized');
        // }

        // Get assignments only for courses taught by this instructor
        $assignments = Assignment::with(['student', 'lesson.course'])
            ->whereHas('lesson.course', function ($query) use ($instructor) {
                $query->where('user_id', $instructor->id);
            })
            ->latest()
            ->get();

        return view('dashboard.assignments.index', compact('course', 'assignments'));
    }

    public function edit(Assignment $assignment)
    {
        $instructor = Auth::user();

        // Ensure only the course instructor can view
        if ($instructor->id !== $assignment->lesson->course->user_id) {
            abort(403, 'Unauthorized');
        }

        return view('dashboard.assignments.edit', compact('assignment'));
    }

    // Grade an assignment
    public function update(Request $request, Assignment $assignment)
    {
        $instructor = Auth::user();

        if ($instructor->id !== $assignment->lesson->course->user_id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $assignment->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'graded_at' => now(),
        ]);

        $assignment->student->notify(new AssignmentGraded($assignment));

        return redirect()->route('assignments.all')->with('success', 'Assignment graded successfully!');
    }
}

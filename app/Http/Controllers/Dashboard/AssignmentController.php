<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Models\Course;
use App\Notifications\AssignmentGraded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AssignmentController extends Controller
{
    // Show assignments for a course
    public function index(Course $course)
    {
        try {
            $instructor = Auth::user();

            // Get assignments only for courses taught by this instructor
            $assignments = Assignment::with(['student', 'lesson.course'])
                ->whereHas('lesson.course', function ($query) use ($instructor) {
                    $query->where('user_id', $instructor->id);
                })
                ->latest()
                ->get();

            return view('dashboard.assignments.index', compact('course', 'assignments'));
        } catch (\Exception $e) {
            Log::error('Error loading assignments: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to load assignments. Please try again.');
        }
    }

    public function edit(Assignment $assignment)
    {
        try {
            $instructor = Auth::user();

            // Ensure only the course instructor can view
            if ($instructor->id !== $assignment->lesson->course->user_id) {
                abort(403, 'Unauthorized');
            }

            return view('dashboard.assignments.edit', compact('assignment'));
        } catch (\Exception $e) {
            Log::error('Error loading assignment: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to load assignment. Please try again.');
        }
    }

    // Grade an assignment
    public function update(Request $request, Assignment $assignment)
    {
        try {
            if (Gate::denies('update-assignment', $assignment)) {
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
        } catch (\Exception $e) {
            Log::error('Error grading assignment: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to grade the assignment. Please try again.');
        }
    }
}

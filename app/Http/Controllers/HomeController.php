<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Notifications\CourseCompleted;
use App\Notifications\StudentEnrolled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Course $courses)
    {
        try {
            $courses = $courses->paginate(8);

            // Popular courses based on enrollments
            $popularCourses = Course::withCount('enrollments')
                ->orderByDesc('enrollments_count')
                ->take(5)
                ->get();

            $instructors = User::where('role', 'instructor')
                ->with(['courses' => function ($query) {
                    $query->withCount('enrollments'); // count enrollments for each course
                }])
                ->get()
                ->map(function ($instructor) {
                    // sum all enrollments of this instructor's courses
                    $instructor->total_enrollments = $instructor->courses->sum('enrollments_count');
                    return $instructor;
                })
                ->sortByDesc('total_enrollments') // order by total enrollments
                ->take(5);

            // Pass data to the view
            return view('home', compact('courses', 'popularCourses', 'instructors'));
        } catch (\Exception $e) {
            Log::error('Error loading courses: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load courses.');
        }
    }

    public function show(Course $course)
    {
        try {
            $user = Auth::user();
            $enrolled = false;

            if ($user && $user->role === 'student') {
                $user->load('enrolledCourses');
                $enrolled = $user->enrolledCourses->contains($course->id);
            }
            // dd($enrolled);
            return view('show', compact('course', 'enrolled'));
        } catch (\Exception $e) {
            Log::error('Error showing courses: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to show dashboard courses.');
        }
    }


    public function enroll(Course $course)
    {
        try {
            $student = Auth::user();

            // Ensure user is logged in
            if (!$student) {
                return redirect()->route('login')->with('error', 'You must be logged in to enroll.');
            }

            // Ensure user is a student
            if ($student->role !== 'student') {
                return back()->with('error', 'Only students can enroll in courses.');
            }

            // Use syncWithoutDetaching to prevent duplicates
            $student->enrolledCourses()->syncWithoutDetaching([
                $course->id => ['enrolled_at' => now()],
            ]);

            $instructor = $course->user;
            if ($instructor) {
                $instructor->notify(new StudentEnrolled($student, $course));
            }

            return back()->with('success', "You have successfully enrolled in {$course->title}");
        } catch (\Exception $e) {
            Log::error('Error enrolling courses: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to enroll to course.');
        }
    }

    public function assignmentStore(Request $request)
    {
        try {
            $request->validate([
                'lesson_id' => 'required|exists:lessons,id',
                'assignment_file' => 'required|file|mimes:pdf,doc,docx,zip,jpg,png|max:2048',
            ]);

            $exists = Assignment::where('user_id', Auth::id())
                ->where('lesson_id', $request->lesson_id)
                ->exists();

            if ($exists) {
                return back()->with('error', 'You have already submitted an assignment for this lesson.');
            }

            $path = $request->file('assignment_file')->store('assignments', 'public');

            Assignment::create([
                'assignment_file' => $path,
                'user_id' => Auth::id(),
                'lesson_id' => $request->lesson_id,
            ]);

            return back()->with('success', 'Assignment submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Error submiting courses: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to submit courses.');
        }
    }

    public function markComplete(Lesson $lesson)
    {
        try {
            $user = Auth::user(); // Get full User model, not just ID

            // Attach lesson to completed lessons without duplicates
            $user->completedLessons()->syncWithoutDetaching([$lesson->id]);

            // Check if student completed all lessons in the course
            $course = $lesson->course; // Make sure Lesson has 'course' relationship
            $totalLessons = $course->lessons()->count();
            $completedLessons = $user->completedLessons()
                ->where('course_id', $course->id)
                ->count();
            if ($totalLessons === $completedLessons) {
                // Notify student about course completion
                $user->notify(new CourseCompleted($course));
            }

            return back()->with('success', 'Lesson marked as complete!');
        } catch (\Exception $e) {
            Log::error('Error marking completion: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to mark completion.');
        }
    }
}

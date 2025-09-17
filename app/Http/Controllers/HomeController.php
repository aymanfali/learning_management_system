<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Course $courses)
    {
        $courses = $courses->paginate(10);
        return view("welcome", ["courses" => $courses]);
    }

    public function show(Course $course)
    {
        $user = Auth::user();
        $enrolled = false;

        if ($user && $user->role === 'student') {
            $user->load('enrolledCourses');
            $enrolled = $user->enrolledCourses->contains($course->id);
        }
        // dd($enrolled);
        return view('show', compact('course', 'enrolled'));
    }


    public function enroll(Course $course)
    {
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

        return back()->with('success', "You have successfully enrolled in {$course->title}");
    }

    public function assignmentStore(Request $request)
    {
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
    }
}

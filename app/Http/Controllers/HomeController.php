<?php

namespace App\Http\Controllers;

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
}

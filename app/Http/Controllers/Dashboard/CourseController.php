<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $data = Course::with('user')->get();
        return view('dashboard.courses.index', compact('data'));
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return view('courses.show', compact('course'));
    }
    public function destroy($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return redirect()->back()->with('error', 'course not found');
        }

        // Delete related cv and image files
        if ($course->cv && Storage::exists($course->cv)) {
            Storage::delete($course->cv);
        }
        if ($course->image && Storage::exists($course->image)) {
            Storage::delete($course->image);
        }

        $course->delete();
        return redirect()->back()->with('success', 'course deleted successfully');
    }
}

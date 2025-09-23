<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Course $courses)
    {
        $courses = $courses->paginate(8);
        return view('courses', compact('courses'));
    }
}

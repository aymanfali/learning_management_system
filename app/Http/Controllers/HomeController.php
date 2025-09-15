<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Course $courses)
    {
        $courses = $courses->paginate(10);
        return view("welcome", ["courses" => $courses]);
    }

    public function show(Course $course)
    {
        return view("show", ["course" => $course]);
    }
}

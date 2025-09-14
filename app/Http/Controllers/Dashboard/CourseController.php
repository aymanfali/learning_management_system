<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $data = $user->role === 'admin'
            ? Course::with('user')->get()
            : $user->courses()->with('user')->get();

        return view('dashboard.courses.index', compact('data'));
    }

    public function create()
    {
        return view('dashboard.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255',],
            'description' => ['required', 'string', 'max:2000',],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $validated['user_id'] = Auth::id();

        // Handle profile image upload
        if ($request->hasFile('image')) {
            // Store new image
            $validated['image'] = $request->file('image')->store('courses/images', 'public');
        }

        Course::create($validated);
        return redirect()->route('courses.all')->with('success', 'course created successfully');
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return view('dashboard.courses.show', compact('course'));
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('dashboard.courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }
            $validated['image'] = $request->file('image')->store('courses/images', 'public');
        }

        $course->update($validated);

        return redirect()->route('courses.all')->with('success', 'Course updated successfully');
    }

    public function destroy($id) {}
}

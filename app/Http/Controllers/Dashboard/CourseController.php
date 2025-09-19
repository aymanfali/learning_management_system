<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
            'lessons' => ['required', 'array', 'min:1'],
            'lessons.*.title' => ['required', 'string', 'max:255'],
            'lessons.*.content' => ['nullable', 'string'],
            'lessons.*.file' => ['nullable', 'file', 'max:10240'], // allow 10MB
        ]);


        // Handle profile image upload
        if ($request->hasFile('image')) {
            // Store new image
            $validated['image'] = $request->file('image')->store('courses/images', 'public');
        }

        $course = Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $request->file('image')?->store('courses/images', 'public'),
            'user_id' => Auth::id(),
        ]);

        // Handle lessons with optional files
        $lessonsData = [];
        foreach ($request->lessons as $lesson) {
            $filePath = null;
            if (isset($lesson['file'])) {
                $filePath = $lesson['file']->store('lessons/files', 'public');
            }
            $lessonsData[] = [
                'title'   => $lesson['title'],
                'content' => $lesson['content'] ?? null,
                'file'    => $filePath,
            ];
        }

        $course->lessons()->createMany($lessonsData);

        return redirect()->route('courses.all')->with('success', 'course created successfully');
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        return view('dashboard.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        if (Gate::denies('update-course', $course)) {
            abort(403, 'You are not authorized to update this course.');
        }

        return view('dashboard.courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'lessons' => ['required', 'array', 'min:1'],
            'lessons.*.id' => ['nullable', 'integer', 'exists:lessons,id'],
            'lessons.*.title' => ['required', 'string', 'max:255'],
            'lessons.*.content' => ['nullable', 'string'],
            'lessons.*.file' => ['nullable', 'file', 'max:10240'],
        ]);

        // Handle course image
        if ($request->hasFile('image')) {
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }
            $validated['image'] = $request->file('image')->store('courses/images', 'public');
        }

        $course->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $validated['image'] ?? $course->image,
        ]);

        $existingLessonIds = $course->lessons()->pluck('id')->toArray();
        $submittedLessonIds = [];

        foreach ($request->lessons as $lessonData) {
            // Update existing lesson
            if (isset($lessonData['id']) && in_array($lessonData['id'], $existingLessonIds)) {
                $lesson = $course->lessons()->find($lessonData['id']);
                $lesson->title = $lessonData['title'];
                $lesson->content = $lessonData['content'] ?? null;

                if (isset($lessonData['file'])) {
                    $lesson->file = $lessonData['file']->store('lessons/files', 'public');
                }

                $lesson->save();
                $submittedLessonIds[] = $lesson->id;
            } else {
                // Add new lesson
                $filePath = isset($lessonData['file']) ? $lessonData['file']->store('lessons/files', 'public') : null;
                $newLesson = $course->lessons()->create([
                    'title' => $lessonData['title'],
                    'content' => $lessonData['content'] ?? null,
                    'file' => $filePath,
                    'user_id' => Auth::id(),
                ]);
                $submittedLessonIds[] = $newLesson->id;
            }
        }

        // Delete removed lessons
        $toDelete = array_diff($existingLessonIds, $submittedLessonIds);
        if (!empty($toDelete)) {
            $course->lessons()->whereIn('id', $toDelete)->delete();
        }

        return redirect()->route('courses.all')->with('success', 'Course updated successfully');
    }


    public function destroy($id) {
        
    }
}

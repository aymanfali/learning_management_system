<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CourseController as GuestCourseController;
use App\Http\Controllers\Dashboard\AssignmentController;
use App\Http\Controllers\Dashboard\CourseController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'student.restrict', 'instructor.restrict'])->prefix('dashboard')->group(function () {

    Route::get('/', [AnalyticsController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.all');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/courses', [CourseController::class, 'index'])->name('courses.all');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses/store', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/edit/{course}', [CourseController::class, 'edit'])->name('course.edit');
    Route::put('/courses/update/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');

    Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.all');
    Route::get('/assignments/edit/{assignment}', [AssignmentController::class, 'edit'])
        ->name('dashboard.assignments.edit');

    Route::put('/assignments/{assignment}', [AssignmentController::class, 'update'])
        ->name('dashboard.assignments.update');

    Route::get('/notifications/{id}/read', function ($id) {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back();
    })->name('notifications.read');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses/{course}', [HomeController::class, 'show'])->name('course.show');

Route::middleware('auth')->group(function () {
    Route::post('/courses/{course}/enroll', [HomeController::class, 'enroll'])
        ->name('courses.enroll');
});

Route::post('/assignments', [HomeController::class, 'assignmentStore'])->name('assignments.store');
Route::post('/lessons/{lesson}/complete', [HomeController::class, 'markComplete'])->name('lessons.complete');


Route::get('/courses', [GuestCourseController::class, 'index'])->name('courses.list');

Route::get('/about', function () {
    return view('about');
});


require __DIR__ . '/auth.php';

<?php

use App\Http\Controllers\Dashboard\CourseController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CourseController::class, 'index']);

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->prefix('dashboard')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.all');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/courses', [CourseController::class, 'index'])->name('courses.all');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses/store', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/edit/{id}', [CourseController::class, 'edit'])->name('course.edit');
    Route::put('/courses/update/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courses/{course}', [HomeController::class, 'show'])->name('course.show');

Route::middleware('auth')->group(function () {
    Route::post('/courses/{course}/enroll', [HomeController::class, 'enroll'])
        ->name('courses.enroll');
});


require __DIR__ . '/auth.php';

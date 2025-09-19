<?php

namespace App\Providers;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 
    }

    protected $policies = [
        \App\Models\User::class => \App\Policies\UserPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        Gate::define('update-course', function ($user, Course $course) {
            return $user->id === $course->user_id;
        });

        Gate::define('update-assignment', function ($user, Assignment $assignment) {
            // Only the instructor who created the course can update
            return $user->id === $assignment->lesson->course->user_id
                && $user->role === 'instructor';
        });
    }
}

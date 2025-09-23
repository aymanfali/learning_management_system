<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'user_id', 'slug'];

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }


    public function assignments()
    {
        return $this->hasManyThrough(
            Assignment::class,   // final model
            Lesson::class,       // intermediate model
            'course_id',         // FK on lessons
            'lesson_id',         // FK on assignments
            'id',                // PK on courses
            'id'                 // PK on lessons
        );
    }


    protected static function booted()
    {
        static::creating(function ($course) {
            $baseSlug = Str::slug($course->name);
            $slug = $baseSlug;
            $count = 1;

            while (self::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }

            $course->slug = $slug;
        });
    }
}

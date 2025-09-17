<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['assignment_file', 'user_id', 'lesson_id'];

    public function lessons()
    {
        return $this->belongsTo(Lesson::class);
    }
}

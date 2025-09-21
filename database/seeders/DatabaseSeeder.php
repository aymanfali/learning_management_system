<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Course::factory(10)->create();
        Lesson::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'aymanfn.ali@gmail.com',
            'role' => 'admin',
        ]);
    }
}

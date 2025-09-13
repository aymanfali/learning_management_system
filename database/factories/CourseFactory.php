<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Support\Str;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'user_id' => User::where('role', 'instructor')->inRandomOrder()->first()->id,
            'slug' => function (array $attributes) {
                $baseSlug = Str::slug($attributes['name']);
                $slug = $baseSlug;
                $count = 1;

                // ensure uniqueness by incrementing if needed
                while (Course::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }

                return $slug;
            },
        ];
    }
}

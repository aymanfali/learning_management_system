<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $roles = ['student', 'instructor'];
        $role = fake()->randomElement($roles);
        $major = $role === 'instructor' ? fake()->name() : null;
        $bio = $role === 'instructor' ? fake()->paragraph() : null;
        $cv = $role === 'instructor' ? 'cvs/' . fake()->uuid() . '.pdf' : null;
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role'=> $role,
            'bio' => $bio,
            'major' => $major,
            'cv' => $cv,
            'birthdate' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
        ];
    }

    /**
     * State for student user
     */
    public function student(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'student',
            'bio' => null,
            'cv' => null,
            'birthdate' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
        ]);
    }

    /**
     * State for instructor user
     */
    public function instructor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'instructor',
            'major' => fake()->name(),
            'bio' => fake()->paragraph(),
            'cv' => 'cvs/' . fake()->uuid() . '.pdf',
            'birthdate' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

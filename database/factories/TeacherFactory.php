<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'nip' => fake()->unique()->numerify('################'),
            'name' => fake()->name(),
            'gender' => fake()->randomElement(['male', 'female']),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->date(),
            'religion' => 'Islam',
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'specialization' => fake()->word(),
            'status' => 'active',
            'joined_at' => fake()->date(),
        ];
    }
}

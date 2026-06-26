<?php

namespace Database\Factories;

use App\Domain\Enums\StudentStatus;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'class_id' => SchoolClass::factory(),
            'nis' => fake()->unique()->numerify('####'),
            'nisn' => fake()->unique()->numerify('##########'),
            'name' => fake()->name(),
            'gender' => fake()->randomElement(['male', 'female']),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->date(),
            'religion' => 'Islam',
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'status' => StudentStatus::Active,
            'enrolled_at' => now(),
        ];
    }
}

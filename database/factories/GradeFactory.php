<?php

namespace Database\Factories;

use App\Models\Assessment;
use App\Models\Grade;
use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Grade>
 */
class GradeFactory extends Factory
{
    protected $model = Grade::class;

    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'student_id' => Student::factory(),
            'assessment_id' => Assessment::factory(),
            'score' => fake()->numberBetween(60, 100),
        ];
    }
}

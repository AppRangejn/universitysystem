<?php

namespace Database\Factories;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Course ' . $this->faker->numberBetween(1, 4),
            'faculty_id' => Faculty::inRandomOrder()->first()?->id,
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Group ' . $this->faker->randomLetter() . $this->faker->numberBetween(1, 3),
            'course_id' => Course::inRandomOrder()->first()?->id,
        ];
    }
}

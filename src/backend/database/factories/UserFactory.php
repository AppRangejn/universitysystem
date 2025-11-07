<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $roles = ['admin', 'teacher', 'student', 'guest'];

        return [
            'name' => $this->faker->firstName(),
            'surname' => $this->faker->lastName(),
            'patronymic' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'password' => Hash::make('password'),
            'photo' => null,
            'role' => $this->faker->randomElement($roles),
            'group_id' => Group::inRandomOrder()->first()?->id,
        ];
    }
}

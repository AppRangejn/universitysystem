<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    public function definition(): array
    {
        $days = ['Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П’ятниця'];
        return [
            'group_id' => Group::inRandomOrder()->first()?->id,
            'day' => $this->faker->randomElement($days),
            'time' => $this->faker->time('H:i'),
            'subject' => $this->faker->randomElement(['Математика', 'Програмування', 'Фізика', 'Англійська', 'Бази даних']),
            'room' => $this->faker->numberBetween(101, 505),
            'teacher' => $this->faker->name(),
            'week' => $this->faker->randomElement(['Чисельник', 'Знаменник']),
            'pair_number' => $this->faker->numberBetween(1, 6),
        ];
    }
}

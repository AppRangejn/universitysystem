<?php

namespace Database\Seeders;

use App\Models\ScheduleSetting;
use Illuminate\Database\Seeder;

class ScheduleSettingSeeder extends Seeder
{
    public function run(): void
    {
        ScheduleSetting::create([
            'days' => ['Понеділок', 'Вівторок', 'Середа', 'Четвер', 'П’ятниця'],
            'pair_count' => 6,
        ]);
    }
}

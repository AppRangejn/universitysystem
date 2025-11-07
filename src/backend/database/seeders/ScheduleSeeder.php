<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        Group::all()->each(function ($group) {
            Schedule::factory(10)->create(['group_id' => $group->id]);
        });
    }
}

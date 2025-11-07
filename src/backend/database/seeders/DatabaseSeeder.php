<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FacultySeeder::class,
            CourseSeeder::class,
            GroupSeeder::class,
            UserSeeder::class,
            ScheduleSeeder::class,
            ScheduleSettingSeeder::class,
        ]);
    }
}

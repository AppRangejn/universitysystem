<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        Course::all()->each(function ($course) {
            Group::factory(2)->create(['course_id' => $course->id]);
        });
    }
}

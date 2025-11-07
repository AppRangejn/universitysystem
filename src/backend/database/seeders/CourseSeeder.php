<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        Faculty::all()->each(function ($faculty) {
            Course::factory(3)->create(['faculty_id' => $faculty->id]);
        });
    }
}

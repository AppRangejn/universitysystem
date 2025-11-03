<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;
use App\Models\Course;
use App\Models\Group;
use App\Models\Schedule;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $fit = Faculty::create(['name' => 'Факультет інформаційно-компʼютерних технологій']);

        $course1 = $fit->courses()->create(['name' => '1 курс']);
        $course2 = $fit->courses()->create(['name' => '2 курс']);

        $group = $course1->groups()->create(['name' => 'КН-25-1']);
        $group2 = $course1->groups()->create(['name' => 'ІПЗ-25-1']);

        Schedule::insert([
            [
                'group_id' => $group->id,
                'day' => 'Понеділок',
                'time' => '08:30–09:50',
                'subject' => 'Лінійна алгебра',
                'room' => 'ауд. 426',
                'teacher' => 'Головач Руслан Миколайович',
                'week' => '1',
            ],
            [
                'group_id' => $group->id,
                'day' => 'Понеділок',
                'time' => '10:00–11:20',
                'subject' => 'Математичний аналіз',
                'room' => 'ауд. 401',
                'teacher' => 'Семенюк Сергій Петрович',
                'week' => '1',
            ],
            [
                'group_id' => $group->id,
                'day' => 'П’ятниця',
                'time' => '10:00–11:20',
                'subject' => 'Технології документів',
                'room' => 'ОЦ 104',
                'teacher' => 'Лисенко Марія Сергіївна',
                'week' => '2',
            ],
        ]);
    }
}

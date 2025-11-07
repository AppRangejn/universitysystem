<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Group;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function actingAsAdmin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);
        return $admin;
    }

    protected function createGroup()
    {
        $faculty = Faculty::create(['name' => 'ФІТ']);
        $course = Course::create(['name' => 'Компʼютерні науки', 'faculty_id' => $faculty->id]);
        return Group::create(['name' => 'КН-23-1', 'course_id' => $course->id]);
    }

    /** @test */
    public function it_creates_a_schedule()
    {
        $this->actingAsAdmin();
        $group = $this->createGroup();

        $data = [
            'group_id' => $group->id,
            'day' => 'Понеділок',
            'pair_number' => 1,
            'time' => '08:30-09:50',
            'subject' => 'Математика',
            'room' => '101',
            'teacher' => 'Іваненко І.І.',
            'week' => 'A',
        ];

        $response = $this->postJson('/api/admin/schedules', $data);
        $response->assertStatus(201)
            ->assertJsonFragment(['subject' => 'Математика']);

        $this->assertDatabaseHas('schedules', ['subject' => 'Математика']);
    }

    /** @test */
    public function it_returns_all_schedules()
    {
        $group = $this->createGroup();
        Schedule::factory()->create(['group_id' => $group->id, 'subject' => 'Програмування']);

        $response = $this->getJson('/api/schedules');
        $response->assertStatus(200)
            ->assertJsonFragment(['subject' => 'Програмування']);
    }

    /** @test */
    public function it_filters_schedules_by_group()
    {
        $group1 = $this->createGroup();
        $group2 = $this->createGroup();

        Schedule::factory()->create(['group_id' => $group1->id, 'subject' => 'ОПП']);
        Schedule::factory()->create(['group_id' => $group2->id, 'subject' => 'Фізика']);

        $response = $this->getJson("/api/schedules?group_id={$group1->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['subject' => 'ОПП'])
            ->assertJsonMissing(['subject' => 'Фізика']);
    }

    /** @test */
    public function it_shows_single_schedule()
    {
        $group = $this->createGroup();
        $schedule = Schedule::factory()->create(['group_id' => $group->id, 'subject' => 'Алгебра']);

        $response = $this->getJson("/api/schedules/{$schedule->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['subject' => 'Алгебра']);
    }

    /** @test */
    public function it_updates_schedule()
    {
        $this->actingAsAdmin();
        $group = $this->createGroup();
        $schedule = Schedule::factory()->create(['group_id' => $group->id, 'subject' => 'Теорія ймовірностей']);

        $response = $this->putJson("/api/admin/schedules/{$schedule->id}", [
            'subject' => 'Оновлена теорія ймовірностей',
            'room' => '202',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['subject' => 'Оновлена теорія ймовірностей']);

        $this->assertDatabaseHas('schedules', ['room' => '202']);
    }

    /** @test */
    public function it_deletes_schedule()
    {
        $this->actingAsAdmin();
        $group = $this->createGroup();
        $schedule = Schedule::factory()->create(['group_id' => $group->id, 'subject' => 'Операційні системи']);

        $response = $this->deleteJson("/api/admin/schedules/{$schedule->id}");
        $response->assertStatus(200)
            ->assertJson(['message' => '✅ Розклад видалено успішно']);

        $this->assertDatabaseMissing('schedules', ['subject' => 'Операційні системи']);
    }
}

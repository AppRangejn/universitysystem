<?php

namespace Tests\Unit;

use App\Models\Faculty;
use App\Models\Course;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_identifies_roles_correctly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $teacher = User::factory()->create(['role' => 'teacher']);
        $student = User::factory()->create(['role' => 'student']);
        $guest = User::factory()->create(['role' => 'guest']);

        $this->assertTrue($admin->isAdmin());
        $this->assertTrue($teacher->isTeacher());
        $this->assertTrue($student->isStudent());
        $this->assertTrue($guest->isGuest());
    }

    #[Test]
    public function it_has_hidden_password_field(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret'),
        ]);

        $array = $user->toArray();
        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    #[Test]
    public function it_casts_email_verified_at_to_datetime(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }

    #[Test]
    public function it_belongs_to_a_group(): void
    {
        $faculty = Faculty::factory()->create(['name' => 'ФІКТ']);
        $course = Course::factory()->create([
            'name' => 'Комп’ютерні науки',
            'faculty_id' => $faculty->id,
        ]);
        $group = Group::factory()->create([
            'name' => 'КН-23-1',
            'course_id' => $course->id,
        ]);

        $user = User::factory()->create(['group_id' => $group->id]);

        $this->assertEquals('КН-23-1', $user->group->name);
        $this->assertInstanceOf(Group::class, $user->group);
    }
}

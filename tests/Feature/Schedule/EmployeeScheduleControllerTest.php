<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Schedule\EmployeeSchedule;
use PHPUnit\Framework\Attributes\Test;

class EmployeeScheduleControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_schedules_for_an_employee()
    {
        $employeeId = 123;
        EmployeeSchedule::factory()->create([
            'employee_id' => $employeeId,
            'date_effective' => '2025-08-15',
        ]);

        $response = $this->getJson("/api/employee-schedule?employee_id=$employeeId&date_effective=2025-08-15");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' => [['id', 'employee_id', 'date_effective']] // customize based on your schema
                 ]);
    }

    #[Test]
    public function it_returns_404_when_no_schedules_found()
    {
        $response = $this->getJson('/api/employee-schedule?employee_id=999&date_effective=2025-08-01');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Schedule not found.'
                 ]);
    }

    #[Test]
    public function it_can_store_and_return_schedule_records()
    {
        $payload = [
            'employee_id' => '456',
            'schedules' => [
                [
                    'start' => '09:00:00',
                    'end' => '18:00:00',
                    'day' => 'Monday',
                    'tardy_start' => '09:00:00',
                    'absent_start' => '12:00:00',
                    'early_dismiss' => '16:00:00',
                    'date_effective' => '2025-08-16'
                ]
            ]
        ];

        $response = $this->postJson('/api/employee-schedule', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Schedules processed.'
                 ]);

        $this->assertDatabaseHas('employee_schedules', [
            'employee_id' => '456',
            'start' => '09:00:00',
            'end' => '18:00:00',
            'day' => 'Monday',
            'tardy_start' => '09:00:00',
            'absent_start' => '12:00:00',
            'early_dismiss' => '16:00:00',
            'date_effective' => '2025-08-16'
        ]);
    }

    #[Test]
    public function it_returns_schedules_by_employee_id()
    {
        $employeeId = 789;

        EmployeeSchedule::factory()->count(2)->create([
            'employee_id' => $employeeId,
        ]);

        $response = $this->getJson("/api/employee-schedule/$employeeId");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         ['id', 'employee_id'] // etc.
                     ]
                 ]);
    }

    #[Test]
    public function it_returns_404_if_no_schedules_found_for_employee_id()
    {
        $response = $this->getJson("/api/employee-schedule/9999");

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Schedule not found.'
                 ]);
    }
}

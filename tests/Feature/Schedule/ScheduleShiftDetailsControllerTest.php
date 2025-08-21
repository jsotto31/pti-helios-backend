<?php

namespace Tests\Feature;

use App\Models\Schedule\ScheduleShift;
use App\Models\Schedule\ScheduleShiftDetails;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ScheduleShiftDetailsControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_store_new_schedule_details()
    {
        $shift = ScheduleShift::factory()->create();

        $payload = [
            'schedule_shift_id' => $shift->id,
            'schedules' => [
                [
                    'day' => 'Monday',
                    'start' => '09:00:00',
                    'end' => '17:00:00',
                    'tardy_start' => '17:00:00',
                    'absent_start' => '17:00:00',
                    'early_dismiss' => '17:00:00',
                ],
                [
                    'day' => 'Monday',
                    'start' => '09:00:00',
                    'end' => '17:00:00',
                    'tardy_start' => '17:00:00',
                    'absent_start' => '17:00:00',
                    'early_dismiss' => '17:00:00',
                ],
            ]
        ];

        $response = $this->postJson('/api/schedule-shift-details', $payload);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Schedules processed.',
                 ]);

        $this->assertDatabaseCount('schedule_shift_details', 1);
        $this->assertDatabaseHas('schedule_shift_details', [
            'schedule_shift_id' => $shift->id,
            'day' => 'Monday',
            'start' => '09:00:00',
            'end' => '17:00:00',
            'tardy_start' => '17:00:00',
            'absent_start' => '17:00:00',
            'early_dismiss' => '17:00:00'
        ]);
    }

    #[Test]
    public function it_returns_schedule_details_by_shift_id()
    {
        $shift = ScheduleShift::factory()->create();

        ScheduleShiftDetails::factory()->create([
            'schedule_shift_id' => $shift->id,
            'day' => 'Wednesday'
        ]);

        $response = $this->getJson("/api/schedule-shift-details/{$shift->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                 ])
                 ->assertJsonStructure([
                     'status',
                     'data' => [['id', 'schedule_shift_id', 'day', 'start', 'end']]
                 ]);
    }

    #[Test]
    public function it_returns_404_if_schedule_not_found()
    {
        $response = $this->getJson('/api/schedule-shift-details/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Schedule not found.',
                 ]);
    }
}

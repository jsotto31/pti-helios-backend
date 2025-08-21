<?php

namespace Tests\Unit\Schedule;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Schedule\ScheduleShift;
use App\Models\Schedule\ScheduleShiftDetails;
use PHPUnit\Framework\Attributes\Test;

class ScheduleShiftDetailsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_or_update_a_schedule_detail()
    {
        $shift = ScheduleShift::factory()->create();

        $data = [
            'schedule_shift_id' => $shift->id,
            'day' => 'Friday',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
        ];

        $created = ScheduleShiftDetails::createOrUpdateByUniqueKeys($data);
        $this->assertTrue($created->wasRecentlyCreated);

        $updatedData = array_merge($data, [
            'end_time' => '17:00:00',
        ]);

        $updated = ScheduleShiftDetails::createOrUpdateByUniqueKeys($updatedData);
        $this->assertFalse($updated->wasRecentlyCreated);
        $this->assertEquals('17:00:00', $updated->end_time);
    }
}

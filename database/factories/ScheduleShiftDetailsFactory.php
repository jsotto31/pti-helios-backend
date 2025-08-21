<?php

namespace Database\Factories;

use App\Models\Schedule\ScheduleShiftDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule\ScheduleShiftDetails>
 */
class ScheduleShiftDetailsFactory extends Factory
{
     protected $model = ScheduleShiftDetails::class;

    public function definition(): array
    {
        return [
            'schedule_shift_id' => ScheduleShiftDetails::factory(), // Automatically create related shift
            'start' => $this->faker->time('H:i:s'),
            'end' => $this->faker->time('H:i:s'),
            'tardy_start' => $this->faker->time('H:i:s'),
            'absent_start' => $this->faker->time('H:i:s'),
            'early_dismiss' => $this->faker->time('H:i:s'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

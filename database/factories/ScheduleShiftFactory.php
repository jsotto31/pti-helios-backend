<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Schedule\ScheduleShift;

class ScheduleShiftFactory extends Factory
{
    protected $model = ScheduleShift::class;

    public function definition()
    {
        return [
            'schedule_name' => $this->faker->words(3, true), // Generates a random schedule name
        ];
    }
}

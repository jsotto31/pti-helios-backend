<?php

namespace Database\Factories;

use App\Models\Schedule\EmployeeSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeScheduleFactory extends Factory
{
    protected $model = EmployeeSchedule::class;

    public function definition()
    {
        return [
            'employee_id' => $this->faker->numberBetween(1, 100),
            'day' => $this->faker->dayOfWeek, // e.g., Monday, Tuesday
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

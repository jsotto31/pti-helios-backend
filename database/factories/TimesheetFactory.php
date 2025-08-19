<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Timesheet>
 */
class TimesheetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Random date within last 30 days
        $workDate = $this->faker->dateTimeBetween('-30 days', 'now');

        // Random shift (e.g., between 8 AM - 6 PM)
        $timeIn = Carbon::instance($workDate)->setTime(rand(8, 10), rand(0, 59));
        $timeOut = (clone $timeIn)->addHours(rand(6, 10))->addMinutes(rand(0, 59));

        $totalHours = $timeOut->diffInMinutes($timeIn) / 60;

        return [
            'employee_id'     => User::factory(), // Each timesheet belongs to a user
            'work_date'   => $timeIn->toDateString(),
            'time_in'     => $timeIn->format('H:i:s'),
            'time_out'    => $timeOut->format('H:i:s'),
            'total_hours' => round($totalHours, 2),
            'status'      => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'notes'       => $this->faker->sentence(),
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyTimeRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::createFromDate(now()->year, 1, 1); // Jan 1 this year
        $endDate   = Carbon::today();

        $users = User::all();

        foreach ($users as $user) {
            $date = $startDate->copy();

            while ($date->lte($endDate)) {
                // Morning shift (8AM – 12PM)
                $timeInMorning  = $date->copy()->setTime(8, 0);
                $timeOutMorning = $date->copy()->setTime(12, 0);

                Timesheet::create([
                    'employee_id' => $user->employee_id,
                    'work_date'   => $date->toDateString(),
                    'time_in'     => $timeInMorning->format('H:i:s'),
                    'time_out'    => $timeOutMorning->format('H:i:s'),
                ]);

                // Afternoon shift (1PM – 5PM)
                $timeInAfternoon  = $date->copy()->setTime(13, 0);
                $timeOutAfternoon = $date->copy()->setTime(17, 0);

                Timesheet::create([
                    'employee_id' => $user->employee_id,
                    'work_date'   => $date->toDateString(),
                    'time_in'     => $timeInAfternoon->format('H:i:s'),
                    'time_out'    => $timeOutAfternoon->format('H:i:s'),
                ]);

                $date->addDay();
            }
        }
    }
}

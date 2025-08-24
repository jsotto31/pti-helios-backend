<?php

namespace Database\Seeders;

use App\Models\Schedule\EmployeeSchedule;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DailyTimeRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployeeSchedule::truncate();
        Timesheet::truncate();


        $this->createTimesheets();
        $this->createEmployeeSchedule();

        
    }

    private function createTimesheets(){
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

    private function createEmployeeSchedule(){
        $startDate = Carbon::createFromDate(now()->year, 1, 1); // Jan 1 this year
        
        $days = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
        ];

        $users = User::all();

        foreach ($users as $user) {
            foreach ($days as $day) {
                // Morning Schedule
                EmployeeSchedule::create([
                    'employee_id'   => $user->employee_id, // adjust if employee_id is not same as user id
                    'day'           => strtolower($day),
                    'start'         => $day == 'Saturday' || $day == 'Sunday' ? null : '08:00:00',
                    'end'           => $day == 'Saturday' || $day == 'Sunday' ? null : '12:00:00',
                    'tardy_start'   => $day == 'Saturday' || $day == 'Sunday' ? null : '08:15:00',
                    'absent_start'  => $day == 'Saturday' || $day == 'Sunday' ? null : '09:00:00',
                    'early_dismiss' => $day == 'Saturday' || $day == 'Sunday' ? null : '11:30:00',
                    'date_effective'=> $startDate,
                ]);

                // Afternoon Schedule
                EmployeeSchedule::create([
                    'employee_id'   => $user->employee_id,
                    'day'           => strtolower($day),
                    'start'         => $day == 'Saturday' || $day == 'Sunday' ? null : '13:00:00',
                    'end'           => $day == 'Saturday' || $day == 'Sunday' ? null : '17:00:00',
                    'tardy_start'   => $day == 'Saturday' || $day == 'Sunday' ? null : '13:15:00',
                    'absent_start'  => $day == 'Saturday' || $day == 'Sunday' ? null : '14:00:00',
                    'early_dismiss' => $day == 'Saturday' || $day == 'Sunday' ? null : '16:30:00',
                    'date_effective'=> $startDate,
                ]);
            }
        }
    }

    
}

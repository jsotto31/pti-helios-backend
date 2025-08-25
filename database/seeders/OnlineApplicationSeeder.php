<?php

namespace Database\Seeders;

use App\Models\OnlineApplication\LeaveApplication;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OnlineApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveApplication::truncate();
        $this->createLeaveApplication();
    }

    public function createLeaveApplication(){
        $leaveTypes = [
            'vacation-leave',
            'sick-leave',
            'birthday-leave',
            'emergency-leave',
        ];

        $users = User::all();

        foreach ($users as $user) {
            // Each user can have 1–3 random leave applications
            $count = rand(1, 3);

            for ($i = 0; $i < $count; $i++) {
                $dateFrom = now()->subDays(rand(1, 60));
                $dateTo = (clone $dateFrom)->addDays(rand(1, 5));

                LeaveApplication::create([
                    'employee_id'   => $user->employee_id,
                    'date_from'     => $dateFrom,
                    'date_to'       => $dateTo,
                    'number_of_days'=> $dateFrom->diffInDays($dateTo) + 1,
                    'type'          => $leaveTypes[array_rand($leaveTypes)],
                    'reason'        => fake()->sentence(),
                    'allow_approver'=> (bool) rand(0, 1),
                    'with_pay'      => (bool) rand(0, 1),
                    'status'        => collect(['pending', 'approved', 'disapproved'])->random(),
                    'created_by'    => $user->employee_id,
                ]);
            }
        }
    }
}

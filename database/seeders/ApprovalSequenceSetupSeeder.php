<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApprovalSequenceSetupSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('approval_sequence_setup_items')->truncate();

        $applicationTypes = [
            'leave_application',
            'overtime_application',
            'ob_application',
            'correction_application',
            'change_schedule_application',
        ];

        $approvers = User::orderBy('id', 'DESC')->limit(4)->get();

        $users = User::whereNotIn('id', $approvers->pluck('id'))->get();

        $data = [];

        foreach ($users as $user) {
            foreach ($applicationTypes as $type) {
                foreach ($approvers as $key => $approver) {
                    $data[] = [
                        'employee_id' => $user->employee_id,
                        'approver_id' => $approver->employee_id,
                        'type'        => $type,
                        'sequence'    => $key + 1,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ];
                }
            }
        }

        DB::table('approval_sequence_setup_items')->insert($data);
    }
}

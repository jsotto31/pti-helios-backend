<?php
    namespace App\Services\OnlineApplication;
    use App\Models\OnlineApplication\LeaveApplication;

    use Carbon\Carbon;

    class LeaveService
    {

        public function employeeLeave($employee_id, $date)
        {
            $onLeave = LeaveApplication::forEmployeeHasLeave($employee_id, $date)->first();

            return [
                'on_leave'     => (bool) $onLeave,
                'leave_type'   => optional($onLeave)->type,
                'leave_reason' => optional($onLeave)->reason,
            ];
        }


    }

?>
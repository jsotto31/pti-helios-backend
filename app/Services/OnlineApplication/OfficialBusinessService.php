<?php
    namespace App\Services\OnlineApplication;
    use App\Models\OnlineApplication\ObApplication;

    class OfficialBusinessService
    {

        public function employeeOfficialBusiness($employee_id, $date)
        {
            $hasOb = ObApplication::forEmployeeHasOb($employee_id, $date)->first();

            return [
                'on_ob'     => (bool) $hasOb,
                'ob_type'   => optional($hasOb)->type,
                'ob_reason' => optional($hasOb)->reason,
                'time_from' => optional($hasOb)->time_from
                        ? \Carbon\Carbon::parse($hasOb->time_from)->format('h:i A')
                        : null,
                'time_to' => optional($hasOb)->time_to
                        ? \Carbon\Carbon::parse($hasOb->time_to)->format('h:i A')
                        : null,
            ];
        }


    }

?>
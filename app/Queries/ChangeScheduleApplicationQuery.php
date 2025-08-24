<?php

namespace App\Queries;

use App\Models\OnlineApplication\ChangeScheduleApplication;

trait ChangeScheduleApplicationQuery
{
    public static function fetch($request)
    {
        $sortBy = urldecode($request->sortBy);
        $sortBy = json_decode($sortBy, true);

        $change_schedule_applications = ChangeScheduleApplication::query()
            ->select([
                "change_schedule_applications.*",
                "users.name"
            ])
            ->leftJoin('users', 'users.employee_id', '=', 'change_schedule_applications.employee_id')
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where("name", "like", "%$search%");
                });
            })
            ->with(['items'])
            ->when($request->date_from, fn($query) => $query->whereDate('change_schedule_applications.created_at', '>=', date("Y-m-d", strtotime($request->date_from))))
            ->when($request->date_to, fn($query) => $query->whereDate('change_schedule_applications.created_at', '<=', date("Y-m-d", strtotime($request->date_to))))
            ->when($request->type, fn($query) => $query->where('change_schedule_applications.type', $request->type))
            ->when($request->status, fn($query) => $query->where('change_schedule_applications.status', $request->status))
            ->when($request->employee_id, fn($query) => $query->where('change_schedule_applications.employee_id', $request->employee_id))
            ->when($sortBy, fn($q) => $q->orderBy($sortBy['key'], $sortBy['order']))
            ->paginate($request->itemPerPage ?? 10);

        $change_schedule_applications = $change_schedule_applications->toArray();

        $change_schedule_applications['headers'] = [
            [
                "title" => "Action",
                "key" => "action",
                "align" => "start",
                "sortable" => false,
            ],
            [
                "title" => "Employee ID",
                "key" => "employee_id",
                "align" => "start",
                "sortable" => true,
            ],
            [
                "title" => "Full name",
                "key" => "name",
                "align" => "start",
                "sortable" => true,
            ],
            [
                "title" => "Date",
                "key" => "date",
                "align" => "start",
                "sortable" => true,
            ],
            [
                "title" => "Type",
                "key" => "type",
                "align" => "start",
                "sortable" => true,
            ],
            [
                "title" => "Status",
                "key" => "status",
                "align" => "start",
                "sortable" => true,
            ],
            [
                "title" => "Details",
                "key" => "details",
                "align" => "start",
                "sortable" => true,
            ],
            [
                "title" => "Approving Authority",
                "key" => "approving_authority",
                "align" => "start",
                "sortable" => true,
            ],
            
        ];

        return $change_schedule_applications;
    }
}

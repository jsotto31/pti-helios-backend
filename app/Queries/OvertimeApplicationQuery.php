<?php

namespace App\Queries;

use App\Models\OnlineApplication\OvertimeApplication;

trait OvertimeApplicationQuery
{
    public static function fetch($request)
    {
        $sortBy = urldecode($request->sortBy);
        $sortBy = json_decode($sortBy, true);

        $overtime_application = OvertimeApplication::query()
            ->select([
                "overtime_applications.*",
                "users.name"
            ])
            ->leftJoin('users', 'users.employee_id', '=', 'overtime_applications.employee_id')
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where("name", "like", "%$search%");
                });
            })
            ->when($request->date_from, fn($query) => $query->whereDate('overtime_applications.created_at', '>=', date("Y-m-d", strtotime($request->date_from))))
            ->when($request->date_to, fn($query) => $query->whereDate('overtime_applications.created_at', '<=', date("Y-m-d", strtotime($request->date_to))))
            ->when($request->status, fn($query) => $query->where('overtime_applications.status', $request->status))
            ->when($request->employee_id, fn($query) => $query->where('overtime_applications.employee_id', $request->employee_id))
            ->when($request->type, fn($query) => $query->where('overtime_applications.type', $request->type))
            ->when($sortBy, fn($q) => $q->orderBy($sortBy['key'], $sortBy['order']))
            ->paginate($request->itemPerPage ?? 10);


        $overtime_application = $overtime_application->toArray();

        $overtime_application['headers'] = [
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
                "title" => "Time From",
                "key" => "time_from",
                "align" => "start",
                "sortable" => true,
            ],
            [
                "title" => "Time To",
                "key" => "time_to",
                "align" => "start",
                "sortable" => true,
            ],
            [
                "title" => "Duration",
                "key" => "duration",
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
                "title" => "Approving Authority",
                "key" => "approving_authority",
                "align" => "start",
                "sortable" => true,
            ],
            
        ];

        return $overtime_application;
    }
}

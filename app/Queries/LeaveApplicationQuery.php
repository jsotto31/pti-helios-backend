<?php

namespace App\Queries;

use App\Models\OnlineApplication\LeaveApplication;

trait LeaveApplicationQuery
{
    public static function fetch($request)
    {
        $sortBy = urldecode($request->sortBy);
        $sortBy = json_decode($sortBy, true);

        $leave_application = LeaveApplication::query()
            ->select([
                "leave_applications.*",
                "users.name"
            ])
            ->with(['approval_sequence_items.approver'])
            ->leftJoin('users', 'users.employee_id', '=', 'leave_applications.employee_id')
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where("name", "like", "%$search%");
                });
            })
            ->when($request->date_from, fn($query) => $query->whereDate('leave_applications.created_at', '>=', date("Y-m-d", strtotime($request->date_from))))
            ->when($request->date_to, fn($query) => $query->whereDate('leave_applications.created_at', '<=', date("Y-m-d", strtotime($request->date_to))))
            ->when($request->status, fn($query) => $query->where('leave_applications.status', $request->status))
            ->when($request->employee_id, fn($query) => $query->where('leave_applications.employee_id', $request->employee_id))
            ->when($request->type, fn($query) => $query->where('leave_applications.type', $request->type))
            ->when($sortBy, fn($q) => $q->orderBy($sortBy['key'], $sortBy['order']))
            ->paginate($request->itemPerPage ?? 10);


        $leave_application = $leave_application->toArray();

        $leave_application['headers'] = [
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
                "title" => "Leave Type",
                "key" => "type",
                "align" => "start",
                "sortable" => true,
            ],
            [
                "title" => "From Date",
                "key" => "date_from",
                "align" => "start",
                "sortable" => true,
            ],
            [
                "title" => "To Date",
                "key" => "date_to",
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
                "title" => "Date Created",
                "key" => "created_at",
                "align" => "start",
                "sortable" => true,
            ],
            
        ];

        return $leave_application;
    }
}

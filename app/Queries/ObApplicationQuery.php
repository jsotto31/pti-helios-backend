<?php

namespace App\Queries;

use App\Models\OnlineApplication\ObApplication;

trait ObApplicationQuery
{
    public static function fetch($request)
    {
        $sortBy = urldecode($request->sortBy);
        $sortBy = json_decode($sortBy, true);

        $ob_application = ObApplication::query()
            ->select([
                "ob_applications.*",
                "users.name"
            ])
            ->leftJoin('users', 'users.employee_id', '=', 'ob_applications.employee_id')
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where("name", "like", "%$search%");
                });
            })
            ->when($request->date_from, fn($query) => $query->whereDate('ob_applications.created_at', '>=', date("Y-m-d", strtotime($request->date_from))))
            ->when($request->date_to, fn($query) => $query->whereDate('ob_applications.created_at', '<=', date("Y-m-d", strtotime($request->date_to))))
            ->when($request->status, fn($query) => $query->where('ob_applications.status', $request->status))
            ->when($request->employee_id, fn($query) => $query->where('ob_applications.employee_id', $request->employee_id))
            ->when($request->type, fn($query) => $query->where('ob_applications.type', $request->type))
            ->when($sortBy, fn($q) => $q->orderBy($sortBy['key'], $sortBy['order']))
            ->paginate($request->itemPerPage ?? 10);


        $ob_application = $ob_application->toArray();

        $ob_application['headers'] = [
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
                "title" => "OB Type",
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

        return $ob_application;
    }
}

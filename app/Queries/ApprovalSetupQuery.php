<?php

namespace App\Queries;

use App\Models\User;

trait ApprovalSetupQuery
{
    public static function fetch($request)
    {
        $sortBy = urldecode($request->sortBy);
        $sortBy = json_decode($sortBy, true);

        $approval_sequence_setup_items = User::query()
            ->select(['users.*'])
            ->with(['approval_sequence_setup_items' => fn($query) => $query->where("type", $request->type)])
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where("name", "like", "%$search%");
                });
            })
            ->when(!$request->type, fn($query) => $query->whereRaw('1 != 1'))
            ->when($request->employee_id, fn($query) => $query->where('employee_id', $request->employee_id))
            ->when($sortBy, fn($q) => $q->orderBy($sortBy['key'], $sortBy['order']))
            ->paginate($request->itemPerPage ?? 10);
           
        $approval_sequence_setup_items = $approval_sequence_setup_items->toArray();

        $approval_sequence_setup_items['data'] =  collect($approval_sequence_setup_items['data'])->map(function($item){
            $approval_sequence_setup_items_array = array();

            for ($i=0; $i < 4; $i++) { 
                $setup_item = isset($item['approval_sequence_setup_items'][$i]) ? $item['approval_sequence_setup_items'][$i] : false;
                if($setup_item){
                    $approval_sequence_setup_items_array[$i] = $setup_item;
                }else{
                    $approval_sequence_setup_items_array[$i] = ['employee_id' => $item['employee_id'], 'approver_id' => null, 'sequence' => $i + 1];
                }
            }   

            $item['approval_sequence_setup_items'] = $approval_sequence_setup_items_array;

            return $item;
        });

        $approval_sequence_setup_items['headers'] = [
            [
                "title" => "Action",
                "key" => "action",
                "align" => "center",
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
                "title" => "First Approver",
                "key" => "first_approver",
                "align" => "center",
                "sortable" => false,
            ],
            [
                "title" => "Second Approver",
                "key" => "second_approver",
                "align" => "center",
                "sortable" => false,
            ],
            [
                "title" => "Third Approver",
                "key" => "third_approver",
                "align" => "center",
                "sortable" => false,
            ],
            [
                "title" => "Fourth Approver",
                "key" => "fourth_approver",
                "align" => "center",
                "sortable" => false,
            ],
            
        ];

        return $approval_sequence_setup_items;
    }
}

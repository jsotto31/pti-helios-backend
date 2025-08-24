<?php

namespace App\Traits;

use App\Models\OnlineApplication\ApprovalSequenceItem;
use App\Models\OnlineApplication\ApprovalSequenceSetupItem;

trait Approvable
{
    public function approval_sequence_items()
    {
        return $this->morphMany(ApprovalSequenceItem::class, 'application');
    }

    public static function hasApproverSetup($employee_id, $type)
    {

        return ApprovalSequenceSetupItem::where("employee_id", $employee_id)->where("type", $type)->exists();
    }

    public function createApproverSequence($employee_id, $type)
    {
        $approval_sequence_setup_items = ApprovalSequenceSetupItem::where("employee_id", $employee_id)->where("type", $type)->whereNotNull("approver_id")->get();

        $data = $approval_sequence_setup_items->map(function ($item, $key) use ($approval_sequence_setup_items, $employee_id) {
            return [
                "employee_id" => $employee_id,
                "can_approve" => $key == 0,
                "last_approver" => ($approval_sequence_setup_items->count() - 1) == $key,
            ];
        });

        $createdItems = $this->approval_sequence_items($employee_id, $type)->createMany($data);

        return $createdItems;
    }

    public function cancel(){
        $this->status = "cancelled";
        $this->save();

        $this->load('approval_sequence_items');
        
        $approval_sequence_items = $this->approval_sequence_items->map(function ($item) {
            return [
                "id" => $item->id,
                "application_type" => $item->application_type,
                "application_id" => $item->application_id,
                "employee_id" => $item->employee_id,
                "status" => $item->status,
                "can_approve" => 0,
                "last_approver" => $item->last_approver,
                "created_at" => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : null,
                "updated_at" => $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : null,
            ];
        })->toArray();

        $this->approval_sequence_items()->upsert($approval_sequence_items, ['id'], ['can_approve']);
    }
}

<?php

namespace App\Models;

class StaffOperationLog extends OperationLog
{
    protected $tableName = 'Staff';

    public static function add(Staff $staffIns)
    {
        $lanID = \App\Logic\LoginUser\LoginUserKeeper::getUser()->lanID;

        $log = new StaffOperationLog();
        $log->tableName = 'Staff';
        $log->tableID = $staffIns->id;
        $log->type = 'add';
        $log->to = $staffIns->toJson();
        $log->madeBy = $lanID;
        $log->checkedBy = $lanID;

        $log->save();
    }
}

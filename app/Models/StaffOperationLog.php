<?php

namespace App\Models;

use APP\Logic\LoginUser\LoginUserKeeper;

class StaffOperationLog extends OperationLog
{
    protected $tableName = 'Staff';

    public static function addLog(Staff $staffIns)
    {
        $lanID = LoginUserKeeper::getUser()->lanID;

        $log = new StaffOperationLog();
        $log->tableName = 'Staff';
        $log->tableID = $staffIns->id;
        $log->type = 'add';
        $log->to = $staffIns->toJson();
        $log->madeBy = $lanID;
        $log->checkedBy = $lanID;

        $log->save();
    }

    public static function updateLog(Staff $staffIns)
    {
        $lanID = LoginUserKeeper::getUser()->lanID;

        $log = new StaffOperationLog();
        $log->tableName = 'Staff';
        $log->tableID = $staffIns->id;
        $log->type = 'update';
        $log->from = json_encode($staffIns->getOriginal());
        $log->to = $staffIns->toJson();
        $log->madeBy = $lanID;
        $log->checkedBy = $lanID;

        $log->save();
    }
}

<?php

namespace App\Models;

use APP\Logic\LoginUser\LoginUserKeeper;

class StaffOperationLog extends OperationLog
{
    public static function logInsert(Staff $staffIns)
    {
        $lanID = LoginUserKeeper::getUser()->lanID;

        $log = new StaffOperationLog();
        $log->tableName = 'Staff';
        $log->tableID = $staffIns->id;
        $log->type = 'insert';
        $log->to = $staffIns->toJson();
        $log->madeBy = $lanID;
        $log->checkedBy = $lanID;
        $log->checkedResult = true;

        $log->save();
    }

    public static function logUpdate(Staff $staffIns)
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
        $log->checkedResult = true;

        $log->save();
    }

    public static function logDelete(Staff $staffIns)
    {
        $lanID = LoginUserKeeper::getUser()->lanID;

        $log = new StaffOperationLog();
        $log->tableName = 'Staff';
        $log->tableID = $staffIns->id;
        $log->type = 'delete';
        $log->from = $staffIns->toJson();
        $log->madeBy = $lanID;
        $log->checkedBy = $lanID;
        $log->checkedResult = true;

        $log->save();
    }
}

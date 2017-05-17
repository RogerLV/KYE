<?php

namespace App\Models;

use App\Logic\LoginUser\LoginUserKeeper;

class OccupationalRiskOperationLog extends OperationLog
{
    public static function logInsert($occupationalRiskInfo)
    {
        $lanID = LoginUserKeeper::getUser()->lanID;

        $log = new OperationLog();

        $log->tableName = 'OccupationalRisk';
        $log->type = 'insert';
        $log->to = json_encode($occupationalRiskInfo);
        $log->madeBy = $lanID;

        $log->save();
    }

    public static function logUpdate(OccupationalRisk $occupationalRiskIns, $updateInfo)
    {
        // get diff infos
        foreach ($updateInfo as $key => $value) {
            if ($value == $occupationalRiskIns->$key) {
                unset($updateInfo[$key]);
            }
        }

        if (!empty($updateInfo)) {
            $lanID = LoginUserKeeper::getUser()->lanID;

            $log = new OperationLog();

            $log->tableName = 'OccupationalRisk';
            $log->type = 'update';
            $log->from = $occupationalRiskIns->toJson();
            $log->to = json_encode($updateInfo);
            $log->madeBy = $lanID;

            $log->save();
        }


    }

    public static function logRemove(OccupationalRisk $occupationalRiskIns)
    {
        $lanID = LoginUserKeeper::getUser()->lanID;

        $log = new OperationLog();

        $log->tableName = 'OccupationalRisk';
        $log->type = 'remove';
        $log->from = $occupationalRiskIns->toJson();
        $log->madeBy = $lanID;

        $log->save();
    }

    public static function checkApprove()
    {

    }

    public static function checkReject()
    {

    }

    public static function listPendings()
    {
        return self::where('tableName', 'OccupationalRisk')
                    ->whereNull('checkedBy')
                    ->orderBy('type', 'created_at');
    }
}

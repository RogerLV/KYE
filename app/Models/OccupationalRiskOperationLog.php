<?php

namespace App\Models;

use App\Logic\LoginUser\LoginUserKeeper;

class OccupationalRiskOperationLog extends OperationLog
{
    public static function logInsert($occupationalRiskInfo)
    {
        $log = new OperationLog();

        $log->tableName = 'OccupationalRisk';
        $log->type = 'insert';
        $log->to = json_encode($occupationalRiskInfo);
        $log->madeBy = LoginUserKeeper::getUser()->lanID;

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
            $log = new OperationLog();

            $log->tableName = 'OccupationalRisk';
            $log->type = 'update';
            $log->from = $occupationalRiskIns->toJson();
            $log->to = json_encode($updateInfo);
            $log->madeBy = LoginUserKeeper::getUser()->lanID;

            $log->save();
        }
    }

    public static function logRemove(OccupationalRisk $occupationalRiskIns)
    {
        $log = new OperationLog();

        $log->tableName = 'OccupationalRisk';
        $log->type = 'remove';
        $log->from = $occupationalRiskIns->toJson();
        $log->madeBy = LoginUserKeeper::getUser()->lanID;

        $log->save();
    }

    public static function checkApprove($id)
    {
        $log = self::findOrFail($id);

        switch ($log->type) {
            case 'insert': 
                OccupationalRisk::insertIns($log->to);
                break;

            case 'update': 
                OccupationalRisk::updateIns($log->from->id, $log->to);
                break;

            case 'delete':
                OccupationalRisk::deleteIns($log->from->id);
                break;
        }

        $log->checkedBy = LoginUserKeeper::getUser()->lanID;
        $log->checkedResult = true;
        $log->save();
    }

    public static function checkReject($id)
    {
        $log = self::findOrFail($id);
        $log->checkedBy = LoginUserKeeper::getUser()->lanID;
        $log->checkedResult = false;
        $log->save();
    }

    public static function getAllPendings()
    {
        return self::where('tableName', 'OccupationalRisk')
                    ->whereNull('checkedBy')
                    ->get();
    }

    public static function listAllTypePendings()
    {
        $pendings = self::getAllPendings();

        return [
            'pendingAdd' => $pendings->where('type', 'insert'),
            'pendingUpdate' => $pendings->where('type', 'update'),
            'pendingRemove' => $pendings->where('type', 'remove'),
        ];
    }
}

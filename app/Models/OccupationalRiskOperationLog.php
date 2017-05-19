<?php

namespace App\Models;

use App\Logic\LoginUser\LoginUserKeeper;
use App\Exceptions\AppException;

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

        if (empty($updateInfo)) {
            return;
        }

        if (self::entryIsPending($occupationalRiskIns->id)) {
            throw new AppException('OCPTNRSKOPRTNLOGMDL001', ERROR_MESSAGE_ENTRY_IS_PENDING);
        }

        $log = new OperationLog();

        $log->tableName = 'OccupationalRisk';
        $log->tableID = $occupationalRiskIns->id;
        $log->type = 'update';
        $log->from = $occupationalRiskIns->toJson();
        $log->to = json_encode($updateInfo);
        $log->madeBy = LoginUserKeeper::getUser()->lanID;

        $log->save();
    }

    public static function logRemove(OccupationalRisk $occupationalRiskIns)
    {
        if (self::entryIsPending($occupationalRiskIns->id)) {
            throw new AppException('OCPTNRSKOPRTNLOGMDL002', ERROR_MESSAGE_ENTRY_IS_PENDING);
        }
        
        $log = new OperationLog();

        $log->tableName = 'OccupationalRisk';
        $log->tableID = $occupationalRiskIns->id;
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

            case 'remove':
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

    protected static function entryIsPending($occupationalRiskID)
    {
        $existing = self::where([
            ['tableName', '=', 'OccupationalRisk'],
            ['tableID', '=', $occupationalRiskID],
        ])->whereNull('checkedBy')
        ->whereNull('checkedResult')
        ->first();

        return !is_null($existing);
    }
}

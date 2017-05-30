<?php

namespace App\Models;

use App\Logic\LoginUser\LoginUserKeeper;
use App\Exceptions\AppException;

class OccupationalRiskOperationLog extends OperationLog
{
    protected static $tableName = 'OccupationalRisks';

    public function showRecord()
    {
        switch ($this->type) {
            case 'insert':
                $record = "Add ".$this->to->department." "
                        .$this->to->section." risk level: "
                        .$this->to->riskLevel.".";
                break;

            case 'remove':
                $record = "Remove ".$this->from->department." "
                        .$this->from->section." risk level: "
                        .$this->from->riskLevel.".";
                break;

            case 'update':
                $record = "Edit ".$this->from->department." "
                        .$this->from->section." risk level: "
                        .$this->from->riskLevel.", ";
                foreach ($this->to as $key => $value) {
                    if ($this->from->$key != $value) {
                        $record .= "update ".$key." from '".$this->from->$key."' to '".$value.";";
                    }
                }
                break;
        }

        return $record."<br>"
                ."Maker: ".$this->maker->getDualName()." at ".$this->created_at."<br>"
                ."Checker: ".$this->checker->getDualName()." at ".$this->updated_at;
    }

    public static function getLatest($no = null)
    {
        return self::getLatestRecords($no, self::$tableName);
    }

    public static function logInsert($occupationalRiskInfo)
    {
        $log = new OperationLog();

        $log->tableName = self::$tableName;
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

        $log->tableName = self::$tableName;
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

        $log->tableName = self::$tableName;
        $log->tableID = $occupationalRiskIns->id;
        $log->type = 'remove';
        $log->from = $occupationalRiskIns->toJson();
        $log->madeBy = LoginUserKeeper::getUser()->lanID;

        $log->save();
    }

    public static function checkApprove($id, $skipException)
    {
        $log = self::findOrFail($id);

        if (!self::canCheck($log)) {
            if ($skipException) {
                return false;
            } else {
                throw new AppException('OCPTNRSKOPRTNLOGMDL003', ERROR_MESSAGE_MAKER_CHECKER_SHOULD_BE_DIFFERENT);
            }
        }

        switch ($log->type) {
            case 'insert': 
                $riskIns = OccupationalRisk::insertIns($log->to);
                $log->tableID = $riskIns->id;
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

    public static function checkReject($id, $skipException)
    {
        $log = self::findOrFail($id);

        if (!self::canCheck($log)) {
            if ($skipException) {
                return false;
            } else {
                throw new AppException('OCPTNRSKOPRTNLOGMDL004', ERROR_MESSAGE_MAKER_CHECKER_SHOULD_BE_DIFFERENT);
            }
        }

        $log->checkedBy = LoginUserKeeper::getUser()->lanID;
        $log->checkedResult = false;
        $log->save();
    }

    public static function getAllPendings()
    {
        return self::where('tableName', self::$tableName)
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
            ['tableName', '=', self::$tableName],
            ['tableID', '=', $occupationalRiskID],
        ])->whereNull('checkedBy')
        ->whereNull('checkedResult')
        ->first();

        return !is_null($existing);
    }
}

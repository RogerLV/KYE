<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Exceptions\AppException;

class KYECaseOperationLog extends OperationLog
{
    protected static $tableName = 'KYECases';

    public function showRecord()
    {
        return $this->to->employNo." "
                .$this->to->name." "
                .$this->to->department." "
                .$this->to->section.". Overall Risk: "
                .$this->to->overallRisk.".<br> Maker: "
                .$this->maker->getDualName()." "
                .$this->created_at."<br> Checker: "
                .$this->checker->getDualName()." "
                .$this->updated_at.".";
    }

    public static function getLatest($no = null)
    {
        return self::getLatestRecords($no, self::$tableName);
    }

    public static function logInsert($KYECaseInfo)
    {
        $log = new OperationLog();

        $log->tableName = self::$tableName;
        $log->type = 'insert';
        $log->to = json_encode($KYECaseInfo);
        $log->madeBy = LoginUserKeeper::getUser()->lanID;

        $log->save();

        return $log;
    }

    public static function getAllPendings()
    {
        return self::where('tableName', self::$tableName)
                    ->whereNull('checkedBy')
                    ->whereNull('checkedResult')
                    ->get();
    }

    public static function checkApprove($id)
    {
        $log = self::findOrFail($id);

        if (!self::canCheck($log)) {
            throw new AppException('KYECSOPRTNLOGMDL001', ERROR_MESSAGE_MAKER_CHECKER_SHOULD_BE_DIFFERENT);
        }

        $caseIns = KYECase::logInsert($log);

        $log->tableID = $caseIns->id;
        $log->checkedBy = LoginUserKeeper::getUser()->lanID;
        $log->checkedResult = true;
        $log->save();

        return $log;
    }

    public static function checkReject($id)
    {
        $log = self::findOrFail($id);

        if (!self::canCheck($log)) {
            throw new AppException('KYECSOPRTNLOGMDL002', ERROR_MESSAGE_MAKER_CHECKER_SHOULD_BE_DIFFERENT);
        }

        $log->checkedBy = LoginUserKeeper::getUser()->lanID;
        $log->checkedResult = false;
        $log->save();

        return $log;
    }
}

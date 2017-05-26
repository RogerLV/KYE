<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Logic\LoginUser\LoginUserKeeper;
use App\Exceptions\AppException;

class KYECaseOperationLog extends OperationLog
{
    public static function logInsert($KYECaseInfo)
    {
        $log = new OperationLog();

        $log->tableName = 'KYECases';
        $log->type = 'insert';
        $log->to = json_encode($KYECaseInfo);
        $log->madeBy = LoginUserKeeper::getUser()->lanID;

        $log->save();
    }

    public static function getAllPendings()
    {
        return self::where('tableName', 'KYECases')
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
    }
}

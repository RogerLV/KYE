<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Logic\LoginUser\LoginUserKeeper;

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
}

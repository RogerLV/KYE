<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KYECase extends Model
{
    protected $table = 'KYECases';

    public $timestamps = false;

    public function log()
    {
        return $this->hasOne('App\Models\KYECaseOperationLog', 'id', 'logID');
    }

    public function staff()
    {
        return $this->hasOne('App\Models\Staff', 'employNo', 'employNo');
    }

    public static function logInsert(KYECaseOperationLog $logIns)
    {
        $caseInfo = $logIns->to;

        $case = new KYECase();
        $case->employNo = $caseInfo->employNo;
        $case->department = $caseInfo->department;
        $case->section = $caseInfo->section;
        $case->DowJonesFileID = $caseInfo->DowJonesFileID;
        $case->QuestnetFileID = $caseInfo->QuestnetFileID;
        $case->CreditBureauFileID = $caseInfo->CreditBureauFileID;
        $case->occupationalrisk = $caseInfo->occupationalRisk;
        $case->relationshipRisk = $caseInfo->relationshipRisk;
        $case->specialFactors = $caseInfo->specialFactors;
        $case->overallRisk = $caseInfo->overallRisk;
        $case->logID = $logIns->id;

        $case->save();

        return $case;
    }
}

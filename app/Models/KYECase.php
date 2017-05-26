<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KYECase extends Model
{
    protected $table = 'KYECases';

    public $timestamps = false;

    public static function logInsert($caseInfo)
    {

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

        $case->save();

        return $case;
    }
}

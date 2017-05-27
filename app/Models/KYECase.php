<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KYECase extends Model
{
    protected $table = 'KYECases';

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

    public function getElapsedTimeString()
    {
        $start_date = new \DateTime($this->updated_at);
        $end_date = new \DateTime('now');
        $dd = date_diff($start_date,$end_date);

        if ($dd->y > 1) return "$dd->y years ago";
        else if($dd->y == 1) return "1 year ago";

        if ($dd->m > 1) return "$dd->m months ago";
        else if($dd->m == 1) return "1 month ago";

        if ($dd->d > 1) return "$dd->d days ago";
        else if($dd->d == 1) return "1 day ago";

        if ($dd->h > 1) return "$dd->h hours ago";
        else if($dd->h == 1) return "1 hour ago";

        if ($dd->m > 1) return "$dd->m minutes ago";
        else if($dd->m == 1) return "1 minute ago";

        if ($dd->s > 1) return "$dd->s seconds ago";
        else if($dd->s == 1) return "1 second ago";
    }
}

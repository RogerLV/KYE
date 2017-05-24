<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OccupationalRisk extends Model
{
    use SoftDeletes;

    protected $table = 'OccupationalRisks';

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public static function insertIns($riskInfo)
    {
        $occupationalRiskIns = new OccupationalRisk();
        $occupationalRiskIns->department = $riskInfo->department;
        $occupationalRiskIns->section = $riskInfo->section;
        $occupationalRiskIns->description = $riskInfo->description;
        $occupationalRiskIns->riskLevel = $riskInfo->riskLevel;

        $occupationalRiskIns->save();

        return $occupationalRiskIns;
    }

    public static function updateIns($id, $updateInfo)
    {
        $ins = self::findOrFail($id);

        $newIns = new OccupationalRisk();
        $newIns->department = $ins->department;
        $newIns->section = $ins->section;
        $newIns->description = isset($updateInfo->description) ? $updateInfo->description : $ins->description;
        $newIns->riskLevel = isset($updateInfo->riskLevel) ? $updateInfo->riskLevel : $ins->riskLevel;

        $newIns->save();

        $ins->delete();

        return $newIns;
    }

    public static function deleteIns($id)
    {
        $ins = self::findOrFail($id);
        $ins->delete();
    }
}

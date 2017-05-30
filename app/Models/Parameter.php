<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    public $table = 'Parameters';

    public static $riskType = ['OccupationalRisk', 'RelationshipRisk', 'SpecialFactorRisk'];

    public static function getRiskSettings()
    {
        $rawData = self::whereIn('key1', self::$riskType)->get();

        $reviewPeriods = [];
        foreach ($rawData as $entry) {
            $reviewPeriods[$entry->key1][$entry->key2] = $entry->value;
        }

        return $reviewPeriods;
    }

    public static function getReviewPeriodOptions()
    {
        return [
            6 => 'Half Yearly', 
            12 => 'Annual', 
            24 => 'Biennial'
        ];
    }

    public static function updateRisk($riskInfo)
    {
        $ins = self::where([
            ['key1', '=', $riskInfo['category']],
            ['key2', '=', $riskInfo['level']],
        ])->first();

        if (is_null($ins)) {
            throw new \App\Exceptions\AppException('PRMTMDL001', ERROR_MESSAGE_DATA_ERROR);
        }

        if ($riskInfo['risk'] == $ins->value) {
            return;
        }

        UpdateLog::logUpdate($ins, ['value' => $riskInfo['risk']]);

        $ins->value = $riskInfo['risk'];
        $ins->save();
    }
}

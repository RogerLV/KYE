<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    public $table = 'Parameters';

    public static function getRiskSettings()
    {
    	$rawData = self::whereIn('key1', 
            ['OccupationalRisk', 'RelationshipRisk', 'SpecialFactorRisk']
        )->get();

        $reviewPeriods = [];
        foreach ($rawData as $entry) {
            $reviewPeriods[$entry->key1][$entry->key2] = $entry->value;
        }

        return $reviewPeriods;
    }

    public static function getReviewPeriodOptions()
    {
    	return ['Half Yearly', 'Annual', 'Biennial'];
    }
}

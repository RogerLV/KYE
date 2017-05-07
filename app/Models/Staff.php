<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    public $table = 'Staff';

    public $timestamps = false;

    protected $fillable = ['employNo', 'department', 'uEngName', 'section', 'joinDate'];

    public static function insertIns($staffInfo)
    {
        if (self::exists($staffInfo['employNo'])) {
            throw new AppException('STFMODEL004', 'Staff Already Exists.');
        }

        // add Staff
        $staffIns = new Staff($staffInfo);

        $staffIns->save();

        StaffOperationLog::add($staffIns);
    }

    private static function exists($employNo)
    {
        $result = self::where('employNo', $employNo)->first();

        return !is_null($result);
    }
}

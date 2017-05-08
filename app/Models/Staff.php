<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    public $table = 'Staff';

    public $timestamps = false;

    protected $fillable = ['employNo', 'department', 'uEngName', 'section', 'joinDate'];

    public static function updateIns($staffInfo)
    {
        $staffIns = self::where('employNo', $staffInfo['employNo'])->first();
        $staffIns->department = $staffInfo['department'];
        $staffIns->uEngName = $staffInfo['uEngName'];
        $staffIns->section = $staffInfo['section'];
        $staffIns->joinDate = $staffInfo['joinDate'];

        StaffOperationLog::updateLog($staffIns);
        $staffIns->save();
    }

    public static function insertIns($staffInfo)
    {
        if (self::exists($staffInfo['employNo'])) {
            throw new AppException('STFMODEL001', 'Staff Already Exists.');
        }

        // add Staff
        $staffIns = new Staff($staffInfo);

        $staffIns->save();

        StaffOperationLog::addLog($staffIns);
    }

    private static function exists($employNo)
    {
        $result = self::where('employNo', $employNo)->first();

        return !is_null($result);
    }
}

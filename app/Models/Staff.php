<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;

    public $table = 'Staff';

    public $timestamps = false;

    protected $fillable = ['employNo', 'department', 'uEngName', 'section', 'joinDate'];

    public function scopeInService($query)
    {
        return $query->whereNull('leaveDate');
    }

    public static function updateIns($staffInfo)
    {
        $staffIns = self::where('employNo', $staffInfo['employNo'])->first();
        $staffIns->department = $staffInfo['department'];
        $staffIns->uEngName = $staffInfo['uEngName'];
        $staffIns->section = $staffInfo['section'];
        $staffIns->joinDate = $staffInfo['joinDate'];

        StaffOperationLog::logUpdate($staffIns);
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

        StaffOperationLog::logInsert($staffIns);
    }

    public static function deleteIns($employNo, $leaveDate)
    {
        $staffIns = self::where('employNo', $employNo)->first();

        if (is_null($staffIns)) {
            throw new AppException('STFMODEL002', 'Staff do not exist.');
        }

        $staffIns->leaveDate = $leaveDate;
        $staffIns->save();

        $staffIns->delete();

        StaffOperationLog::logDelete($staffIns);
    }

    private static function exists($employNo)
    {
        $result = self::where('employNo', $employNo)->first();

        return !is_null($result);
    }
}

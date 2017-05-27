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

    public function pendingCaseLog()
    {
        return $this->hasOne('App\Models\KYECaseOperationLog', 'id', 'pendingCaseID');
    }

    public function kyeCases()
    {
        return $this->hasMany('App\Models\KYECase', 'employNo', 'employNo');
    }

    public function scopeInService($query)
    {
        return $query->whereNull('leaveDate');
    }

    public function scopeEx($query)
    {
        return $query->whereNotNull('leaveDate');
    }

    public static function updateIns($staffInfo)
    {
        $staffIns = self::getIns($staffInfo['employNo']);
        $staffIns->department = $staffInfo['department'];
        $staffIns->uEngName = $staffInfo['uEngName'];
        $staffIns->section = $staffInfo['section'];
        $staffIns->joinDate = $staffInfo['joinDate'];

        StaffOperationLog::logUpdate($staffIns);
        $staffIns->save();

        return $staffIns;
    }

    public static function insertIns($staffInfo)
    {
        if (self::exists($staffInfo['employNo'])) {
            throw new \App\Exceptions\AppException('STFMODEL001', 'Staff '.$staffInfo['employNo'].' Already Exists.');
        }

        // add Staff
        $staffIns = new Staff($staffInfo);

        $staffIns->save();

        StaffOperationLog::logInsert($staffIns);

        return $staffIns;
    }

    public static function deleteIns($employNo, $leaveDate)
    {
        $staffIns = self::getIns($employNo);

        $staffIns->leaveDate = $leaveDate;
        $staffIns->save();

        $staffIns->delete();

        StaffOperationLog::logDelete($staffIns);
    }

    public static function getIns($employNo)
    {
        $staffIns = self::where('employNo', $employNo)->first();

        if (is_null($staffIns)) {
            throw new \App\Exceptions\AppException('STFMODEL001', 'Staff '.$employNo.' does not exist.');
        }

        return $staffIns;
    }



    private static function exists($employNo)
    {
        $result = self::where('employNo', $employNo)->first();

        return !is_null($result);
    }
}

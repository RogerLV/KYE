<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationLog extends Model
{
    use SoftDeletes;

    protected static $tableName;

    protected $table = 'OperationLogs';

    protected $dates = ['deleted_at'];

    public function getToAttribute($value)
    {
        return json_decode($value);
    }

    public function getFromAttribute($value)
    {
        return json_decode($value);
    }

    public function maker()
    {
        return $this->hasOne('App\Models\User', 'lanID', 'madeBy');
    }

    public function checker()
    {
        return $this->hasOne('App\Models\User', 'lanID', 'checkedBy');
    }

    public static function remove($id)
    {
        $ins = self::findOrFail($id);
        $ins->delete();
    }

    protected static function canCheck(OperationLog $logIns)
    {
        return \App\Logic\LoginUser\LoginUserKeeper::getUser()->lanID != $logIns->madeBy;
    }

    protected static function getLatestRecords($nos = null, $tableName)
    {
        $query = self::with('maker', 'checker')
                    ->where('tableName', $tableName)
                    ->where('checkedResult', true)
                    ->orderBy('id', 'DESC');

        if (!is_null($nos)) {
            $query->take($nos);
        }

        return $query->get();
    }
}

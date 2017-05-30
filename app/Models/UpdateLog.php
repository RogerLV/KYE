<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateLog extends Model
{
    protected $table = 'UpdateLogs';
    protected $connection = 'basedata';

    public function editor()
    {
        return $this->hasOne('App\Models\User', 'lanID', 'editBy');
    }

    public function getNewValAttribute($value)
    {
        return json_decode($value);
    }

    public function getOldValAttribute($value)
    {
        return json_decode($value);
    }

    public function showRecord()
    {
        return $this->editor->getDualName(). " changed review period of "
                .$this->oldVal->key1." "
                .$this->oldVal->key2." level from "
                .$this->oldVal->value." to "
                .$this->newVal->value." at "
                .$this->created_at;
    }

    public static function logInsert($instance, $editBy=null)
    {
        $log = self::createIns($instance, $editBy);
        $log->newVal = $instance->toJson();
        $log->type = 'INSERT';
        $log->save();
    }

    public static function logUpdate($instance, $newVal, $editBy=null)
    {
        $log = self::createIns($instance, $editBy);
        $log->newVal = json_encode($newVal);
        $log->type = 'UPDATE';
        $log->oldVal = $instance->toJson();
        $log->save();
    }

    public static function logDelete($instance, $editBy=null)
    {
        $log = self::createIns($instance, $editBy);
        $log->oldVal = json_encode($instance->getOriginal());
        $log->type = 'DELETE';
        $log->save();
    }

    protected static function createIns($instance, $editBy=null)
    {
        $ins = new UpdateLog();
        $ins->app = env('APP_NAME');
        $ins->tableName = $instance->table;
        $ins->idInTable = $instance->id;
        $ins->editBy = is_null($editBy) ? \App\Logic\LoginUser\LoginUserKeeper::getUser()->lanID : $editBy;

        return $ins;
    }
}

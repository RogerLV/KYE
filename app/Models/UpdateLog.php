<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpdateLog extends Model
{
    protected $table = 'UpdateLogs';
    protected $connection = 'basedata';

    public function __construct($instance, $editBy=null)
    {
        parent::__construct();

        $this->app = env('APP_NAME');
        $this->tableName = $instance->table;
        $this->idInTable = $instance->id;
        $this->editBy = is_null($editBy) ? \App\Logic\LoginUser\LoginUserKeeper::getUser()->lanID : $editBy;
    }

    public static function logInsert($instance, $editBy=null)
    {
        $log = new UpdateLog($instance, $editBy);
        $log->newVal = $instance->toJson();
        $log->type = 'INSERT';
        $log->save();
    }

    public static function logUpdate($instance, $newVal, $editBy=null)
    {
        $log = new UpdateLog($instance, $editBy);
        $log->newVal = json_encode($newVal);
        $log->type = 'UPDATE';
        $log->oldVal = $instance->toJson();
        $log->save();
    }

    public static function logDelete($instance, $editBy=null)
    {
        $log = new UpdateLog($instance, $editBy);
        $log->oldVal = json_encode($instance->getOriginal());
        $log->type = 'DELETE';
        $log->save();
    }
}

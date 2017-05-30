<?php

namespace App\Models;

use APP\Logic\LoginUser\LoginUserKeeper;

class StaffOperationLog extends OperationLog
{
    protected static $tableName = 'Staff';

    public function showRecord()
    {
        switch ($this->type) {
            case 'insert':
                return $this->maker->getDualName()." added staff "
                        .$this->to->employNo." "
                        .$this->to->uEngName." "
                        .$this->to->department." "
                        .$this->to->section." at "
                        .$this->created_at;

            case 'delete':
                return $this->maker->getDualName()." removed staff "
                        .$this->from->employNo." "
                        .$this->from->uEngName." "
                        .$this->from->department." "
                        .$this->from->section." at "
                        .$this->created_at;

            case 'update':
                $record = $this->maker->getDualName()." edited staff "
                        .$this->from->employNo." "
                        .$this->from->uEngName." change ";
                foreach ($this->to as $key => $value) {
                    if ($this->from->$key != $value) {
                        $record .= $key." from ".$this->from->$key." to ".$value." ";
                    }
                }
                return $record;
        }
    }

    public static function getLatest($no = null)
    {
        return self::getLatestRecords($no, self::$tableName);
    }

    public static function logInsert(Staff $staffIns)
    {
        $lanID = LoginUserKeeper::getUser()->lanID;

        $log = new StaffOperationLog();
        $log->tableName = self::$tableName;
        $log->tableID = $staffIns->id;
        $log->type = 'insert';
        $log->to = $staffIns->toJson();
        $log->madeBy = $lanID;
        $log->checkedBy = $lanID;
        $log->checkedResult = true;

        $log->save();
    }

    public static function logUpdate(Staff $staffIns)
    {
        $lanID = LoginUserKeeper::getUser()->lanID;

        $log = new StaffOperationLog();
        $log->tableName = self::$tableName;
        $log->tableID = $staffIns->id;
        $log->type = 'update';
        $log->from = json_encode($staffIns->getOriginal());
        $log->to = $staffIns->toJson();
        $log->madeBy = $lanID;
        $log->checkedBy = $lanID;
        $log->checkedResult = true;

        $log->save();
    }

    public static function logDelete(Staff $staffIns)
    {
        $lanID = LoginUserKeeper::getUser()->lanID;

        $log = new StaffOperationLog();
        $log->tableName = self::$tableName;
        $log->tableID = $staffIns->id;
        $log->type = 'delete';
        $log->from = $staffIns->toJson();
        $log->madeBy = $lanID;
        $log->checkedBy = $lanID;
        $log->checkedResult = true;

        $log->save();
    }
}

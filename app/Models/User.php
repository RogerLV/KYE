<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'Users';
    protected $connection = 'basedata';

    public function getDualName()
    {
        return $this->uEngName.' '.$this->uCnName;
    }

    public static function getIns($lanID)
    {
        $ins = self::where('lanID', $lanID)->first();

        if (is_null($ins)) {
            throw new \App\Exceptions\AppException('USERMODEL001', 'Incorrect User Info.');
        }

        return $ins;
    }
}

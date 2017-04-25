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
}

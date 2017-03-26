<?php

namespace App\Logic\LoginUser;

use App\Models\SystemRole;

class LoginUser
{
    private $activeRole;

    public function __construct($lanID)
    {
        $this->activeRole = SystemRole::where('lanID', $lanID)->where('active', true)->first();

        if (is_null($this->activeRole)){
            $this->activeRole = SystemRole::initActiveRole($lanID);
        }
    }

    public function getActiveRole()
    {
        return $this->activeRole;
    }

    public function setActiveRole($id)
    {
        $lanID = $this->activeRole->lanID;
        $this->activeRole = SystemRole::setActiveRole($lanID, $id);
    }

    public function __get($attr)
    {
        return $this->activeRole->$attr;
    }
}
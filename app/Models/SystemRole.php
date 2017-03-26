<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\AppException;

class SystemRole extends Model
{
    use SoftDeletes;

    protected $table = 'RoleList';

    protected $dates = ['deleted_at'];

    public static function initActiveRole($lanID)
    {
        // find highest roleID map
        $ins = self::where('lanID', $lanID)
                    ->orderBy('roleID', 'DESC')
                    ->first();

        if (is_null($ins)) {
            throw new AppException('SYSTEMROLEMODEL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        self::setActiveRole($lanID, $ins->id);
        return $ins;
    }

    public static function setActiveRole($lanID, $mapID)
    {
        $roleIns = SystemRole::find($mapID);
        if (!self::activable($lanID, $roleIns))
        {
            throw new AppException('SYSTEMROLEMODEL002', 'The specified role cannot be activated.');
        }

        // first set original role inactive
        SystemRole::where('lanID', $lanID)
            ->where('active', true)
            ->update(['active' => false]);

        // then set new one active
        $roleIns->active = true;
        $roleIns->save();

        return $roleIns;
    }

    public static function isActive(SystemRole $roleIns)
    {
        $roleIns = SystemRole::find($roleIns->id);
        return !is_null($roleIns) && $roleIns->active;
    }

    private static function activable($lanID, SystemRole $roleIns)
    {
        return !is_null($roleIns)
                && $roleIns->lanID == $lanID
                && !$roleIns->active;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\AppException;

class UserRole extends Model
{
    use SoftDeletes;

    protected $table = 'UserRoles';

    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'lanID', 'lanID');
    }

    public function role()
    {
        return $this->hasOne('App\Models\Role', 'id', 'roleID');
    }

    public static function initActiveRole($lanID)
    {
        // find highest roleID map
        $ins = self::where('lanID', $lanID)
                    ->orderBy('roleID', 'DESC')
                    ->first();

        if (is_null($ins)) {
            throw new AppException('USERROLEMODEL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        self::setActiveRole($lanID, $ins->id);
        return $ins;
    }

    public static function setActiveRole($lanID, $mapID)
    {
        $roleIns = self::find($mapID);
        if (!self::activable($lanID, $roleIns))
        {
            throw new AppException('USERROLEMODEL002', 'The specified role cannot be activated.');
        }

        // first set original role inactive
        self::where('lanID', $lanID)
            ->where('active', true)
            ->update(['active' => false]);

        // then set new one active
        $roleIns->active = true;
        $roleIns->save();

        return $roleIns;
    }

    public static function isActive(UserRole $roleIns)
    {
        $roleIns = self::find($roleIns->id);
        return !is_null($roleIns) && $roleIns->active;
    }

    private static function activable($lanID, UserRole $roleIns)
    {
        return !is_null($roleIns)
                && $roleIns->lanID == $lanID
                && !$roleIns->active;
    }
}

<?php

namespace App\Logic\LoginUser;

use App\Exceptions\AppException;

class LoginUserKeeper
{
    private static $loginUser;

    public static function initUser()
    {
        if (!is_null($lanID = request()->input('LanID'))) {
            $lanID = strtoupper(base64_decode($lanID));
            session(['loginUser' => new LoginUser($lanID)]);
        }

        return self::getUser();
    }

    public static function getUser()
    {
        if (!(self::$loginUser instanceof LoginUser)) {
            self::getInstanceFromSession();
        }

        return self::$loginUser;
    }

    // The function will be run once and  only once during handling request
    // so that here is the idea place for recording footprints.
    private static function getInstanceFromSession()
    {
        // empty session check
        if (is_null($sessionUser = session('loginUser'))){
            throw new AppException('LOGINUSERKEEPER001', 'Session Time Out.');
        }

        // check if login user roleID is still valid
        if (!App\Models\SystemRole::isActive($sessionUser->getAciveRole())) {
            throw new AppException('LOGINUSERKEEPER002', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        // record footprints
        App\Models\Footprint::create([
            'lanID' => $sessionUser->lanID,
            'uri' => request()->path(),
            'app' => env('APP_NAME'),
        ]);

        self::$loginUser = $sessionUser;
    }
}
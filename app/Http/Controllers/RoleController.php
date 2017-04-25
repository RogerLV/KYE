<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;

class RoleController extends Controller
{
    public function select()
    {
        if (empty($mapID = request()->input('mapid'))) {
            throw new AppException('RLECTRL001', ERROR_MESSAGE_DATA_ERROR);
        }

        $this->loginUser->setActiveRole($mapID);

        return response()->json(['status' => 'good']);
    }

    public function listAll()
    {

    }
}

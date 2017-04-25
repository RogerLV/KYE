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
        $allUserRoles = \App\Models\UserRole::with('user')
                            ->join('Roles', 'Roles.id', '=', 'UserRoles.roleID')
                            ->where('Roles.hide', '=', false)
                            ->orderBy('roleID', 'DESC')
                            ->get();

        return view('roleList')
                ->with('allUserRoles', $allUserRoles)
                ->with('editable', $this->loginUser->roleID == ROLE_ID_APP_ADMIN);
    }
}

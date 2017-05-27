<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Models\UserRole;

class RoleController extends Controller
{
    public function select()
    {
        $paras = $this->checkParameters('mapid');

        $this->loginUser->setActiveRole($paras['mapid']);

        return response()->json(['status' => 'good']);
    }

    public function listAll()
    {
        $this->pageAccessible(__CLASS__, __FUNCTION__);

        $allUserRoles = UserRole::with('user')
                        ->join('Roles', 'Roles.id', '=', 'UserRoles.roleID')
                        ->select('*', 'UserRoles.id AS mapid')
                        ->where('Roles.hide', '=', false)
                        ->orderBy('roleID', 'DESC')
                        ->get();

        $editable = $this->loginUser->isAppAdmin();
        $roleOptions = $editable ? \App\Models\Role::where('hide', false)->get() : [];
        $candidates = $editable ? \App\Models\User::where([
            ['dept', '=', 'HRD'],
            ['inService', '=', true],
        ])->get() : [];

        $roleOptions = \App\Models\Role::where('hide', false)->get();

        return view('roleList')
                ->with('allUserRoles', $allUserRoles)
                ->with('editable', $editable)
                ->with('roleOptions', $roleOptions)
                ->with('candidates', $candidates);
    }

    public function remove()
    {
        $this->checkEditable();

        $paras = $this->checkParameters('mapid');

        UserRole::removeIns($paras['mapid']);

        return response()->json(['status' => 'good']);
    }

    public function add()
    {
        $this->checkEditable();

        $paras = $this->checkParameters(['lanid', 'roleid']);

        $dept = \App\Models\User::getIns($paras['lanid'])->dept;

        // add role
        UserRole::insertIns($paras['roleid'], $paras['lanid'], $dept);

        return response()->json(['status' => 'good']);
    }

    private function checkEditable()
    {
        if (!$this->loginUser->isAppAdmin()) {
            throw new AppException('RLECTRL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Models\Staff;

class StaffController extends Controller
{
    public function listAll($dept = 'all')
    {
        $this->pageAccessible(__CLASS__, __FUNCTION__);

        if ('all' == $dept) {
            $staff = Staff::inService()->paginate(50);
        } else {
            $staff = Staff::inService()->where('department', $dept)->paginate(50);
        }

        return view('staff.list')
                ->with('title', 'Staff List')
                ->with('editable', $this->editable())
                ->with('staff', $staff)
                ->with('deptOptions', Staff::select('department')->inService()->distinct()->get())
                ->with('selectedDept', $dept);
    }

    public function remove()
    {
        if (!$this->editable()) {
            throw new AppException('STFCTRL006', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['employno', 'leavedate']);

        Staff::deleteIns($paras['employno'], $paras['leavedate']);

        return response()->json(['status' => 'good']);
    }

    public function edit()
    {
        if (!$this->editable()) {
            throw new AppException('STFCTRL007', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['employno', 'name', 'department', 'section', 'joindate']);

        $staffIns = Staff::getIns($paras['employno']);

        // check if there is any changes
        if ($staffIns->uEngName == $paras['name']
            && $staffIns->department == $paras['department']
            && $staffIns->section = $paras['section']
            && $staffIns->joinDate = $paras['joindate']) {
            throw new AppException('STFCTRL008', 'No change has been made.');
        }

        // update by model
        $staffIns = Staff::updateIns([
            'employNo' => $paras['employno'],
            'uEngName' => $paras['name'],
            'department' => $paras['department'],
            'section' => $paras['section'],
            'joinDate' => $paras['joindate'],
        ]);

        return response()->json([
            'status' => 'good',
            'instance' => $staffIns,
        ]);
    }

    public function add()
    {
        if (!$this->editable()) {
            throw new AppException('STFCTRL009', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['employno', 'name', 'department', 'section', 'joindate']);

        // employ no has to be integer
        if (!is_numeric($paras['employno'])) {
            throw new AppException('STFCTRL010', 'Employ No has to be integer.');
        }

        // add Ins
        $staffIns = Staff::insertIns([
            'employNo' => $paras['employno'],
            'department' => $paras['department'],
            'uEngName' => $paras['name'],
            'section' => $paras['section'],
            'joinDate' => $paras['joindate'],
        ]);

        // return Ins
        return response()->json([
            'status' => 'good',
            'instance' => $staffIns,
        ]);
    }


    protected function editable()
    {
        return $this->loginUser->roleID == ROLE_ID_MAKER;
    }
}

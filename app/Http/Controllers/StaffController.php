<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Models\Staff;

class StaffController extends Controller
{
    public function listAll()
    {
        $this->pageAccessible(__CLASS__, __FUNCTION__);

        return view('staff.list')
                ->with('title', 'Staff List')
                ->with('editable', $this->editable())
                ->with('staff', Staff::inService()->get())
                ->with('deptOptions', Staff::select('department')->inService()->distinct()->get());
    }

    public function receiveStaffList()
    {
        if (!$this->editable()) {
            throw new AppException('STFCTRL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        if (empty($fileIns = request()->file('upload-staff-list'))) {
            throw new AppException('STFCTRL002', ERROR_MESSAGE_DATA_ERROR);
        }

        $staffList = array_map('str_getcsv', file($fileIns->getPathName()));

        // check file format
        if (sizeof($staffList) == 0) {
            throw new AppException('STFCTRL003', 'Incorrect Format');
        }
        if ($staffList[0][0] != 'EMP NO' 
            || $staffList[0][1] != 'EMP NAME'
            || $staffList[0][2] != 'DEPARTMENT'
            || $staffList[0][3] != 'SECTION'
            || $staffList[0][4] != 'JOIN DATE') {
            throw new AppException('STFCTRL004', 'Incorrect Format');
        }
        array_shift($staffList);

        // compare with staff in DB
        $existingStaff = Staff::all()->keyBy('employNo');

        define('INDEX_EMP_NO', 0);
        define('INDEX_EMP_NAME', 1);
        define('INDEX_DEPARTMENT', 2);
        define('INDEX_SECTION', 3);
        define('INDEX_JOIN_DATE', 4);

        $mappingAry = [
            INDEX_EMP_NO => 'employNo',
            INDEX_EMP_NAME => 'uEngName',
            INDEX_DEPARTMENT => 'department',
            INDEX_SECTION => 'section',
            INDEX_JOIN_DATE => 'joinDate',
        ];

        $toBeAdded = $toBeUpdated = [];
        foreach ($staffList as $staffEntry) {

            $timestamp = strtotime(str_replace('/', '-', $staffEntry[INDEX_JOIN_DATE]));
            $staffEntry[INDEX_JOIN_DATE] = date("Y-m-d", $timestamp);

            // add
            $existingStaffEntry = $existingStaff->get($staffEntry[INDEX_EMP_NO]);

            if (is_null($existingStaffEntry)) {
                $toBeAdded[$staffEntry[INDEX_EMP_NO]] = $staffEntry;
                continue;
            }

            // update
            $updatedCols = [0, 0, 0, 0];
            foreach (range(INDEX_EMP_NAME, INDEX_JOIN_DATE) as $index) {
                if ($staffEntry[$index] != $existingStaffEntry->$mappingAry[$index]) {
                    $updatedCols[$index] = 1;
                }
            }

            if (array_sum($updatedCols)) {
                $toBeUpdated[$staffEntry[INDEX_EMP_NO]] = [
                    'updatedCols' => $updatedCols,
                    'instance' => $staffEntry,
                ];
            }
        }
        
        // store uploaded file
        $fileIns->move(STORAGE_PATH_STAFF_LIST, date('Y_m_d_H_i_s').'.csv');

        return response()->json([
            'status' => 'good',
            'toBeAdded' => $toBeAdded,
            'toBeUpdated' => $toBeUpdated
        ]);
    }

    public function confirmStaffList()
    {
        if (!$this->editable()) {
            throw new AppException('STFCTRL005', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['tobeaddedstaff', 'tobeupdatedstaff']);

        // confirm add staff
        $toBeAddedStaff = json_decode($paras['tobeaddedstaff']);
        foreach ($toBeAddedStaff as $staffEntry) {
            Staff::insertIns([
                'employNo' => $staffEntry->empno,
                'department' => $staffEntry->dept,
                'uEngName' => $staffEntry->name,
                'section' => $staffEntry->section,
                'joinDate' => $staffEntry->joindate,
            ]);
        }

        // confirm update staff
        $toBeUpdatedStaff = json_decode($paras['tobeupdatedstaff']);
        foreach ($toBeUpdatedStaff as $staffEntry) {
            Staff::updateIns([
                'employNo' => $staffEntry->empno,
                'department' => $staffEntry->dept,
                'uEngName' => $staffEntry->name,
                'section' => $staffEntry->section,
                'joinDate' => $staffEntry->joindate,
            ]);
        }

        return response()->json(['status' => 'good']);
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


    private function editable()
    {
        return $this->loginUser->roleID == ROLE_ID_MAKER;
    }
}

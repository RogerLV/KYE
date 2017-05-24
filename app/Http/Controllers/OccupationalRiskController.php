<?php

namespace App\Http\Controllers;

use App\Models\OccupationalRisk;
use App\Models\OccupationalRiskOperationLog;
use App\Exceptions\AppException;

class OccupationalRiskController extends Controller
{
    public function listAll($dept = 'all')
    {
        $this->pageAccessible(__CLASS__, __FUNCTION__);

        if ('all' == $dept) {
            $entries = OccupationalRisk::all();
        } else {
            $entries = OccupationalRisk::where('department', $dept)->get();
        }

        return view('occupational.list')
                ->with('entries', $entries)
                ->with('editable', $this->editable())
                ->with('canCheck', $this->canCheck())
                ->with('title', 'Occupational Risk List')
                ->with('deptOptions', \App\Models\OccupationalRisk::select('department')->distinct()->get())
                ->with('selectedDept', $dept);
    }

    public function makerPage()
    {
        $this->pageAccessible(__CLASS__, __FUNCTION__);

        return view('occupational.maker', OccupationalRiskOperationLog::listAllTypePendings())
                ->with('title', 'Occupational Risk Maker Page');
    }

    public function checkerPage()
    {
        $this->pageAccessible(__CLASS__, __FUNCTION__);

        return view('occupational.checker', OccupationalRiskOperationLog::listAllTypePendings())
                ->with('title', 'Occupational Risk Checker Page')
                ->with('checkerLanID', \App\Logic\LoginUser\LoginUserKeeper::getUser()->lanID);
    }

    public function deletePending()
    {
        if (!$this->editable()) {
            throw new AppException('OCPTNRSKCTL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['pendingid']);

        OccupationalRiskOperationLog::remove($paras['pendingid']);

        return response()->json(['status' => 'good']);
    }

    public function checkerApprove()
    {
        if (!$this->canCheck()) {
            throw new AppException('OCPTNRSKCTL002', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters('pendingid');

        OccupationalRiskOperationLog::checkApprove($paras['pendingid'], false);

        return response()->json(['status' => 'good']);
    }

    public function checkerReject()
    {
        if (!$this->canCheck()) {
            throw new AppException('OCPTNRSKCTL003', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters('pendingid');

        OccupationalRiskOperationLog::checkReject($paras['pendingid'], false);

        return response()->json(['status' => 'good']);
    }

    public function add()
    {
        if (!$this->editable()) {
            throw new AppException('OCPTNRSKCTL004', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['department', 'section', 'risklevel'], ['description']);

        OccupationalRiskOperationLog::logInsert([
            'department' => $paras['department'],
            'section' => $paras['section'],
            'description' => $paras['description'],
            'riskLevel' => $paras['risklevel'],
        ]);

        return response()->json(['status' => 'good']);
    }

    public function delete()
    {
        if (!$this->editable()) {
            throw new AppException('OCPTNRSKCTL005', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters('entryid');

        $occupationalRiskIns = OccupationalRisk::findOrFail($paras['entryid']);

        OccupationalRiskOperationLog::logRemove($occupationalRiskIns);

        return response()->json(['status' => 'good']);
    }

    public function edit()
    {
        if (!$this->editable()) {
            throw new AppException('OCPTNRSKCTL005', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['entryid', 'department', 'section', 'risklevel'], ['description']);

        $occupationalRiskIns = OccupationalRisk::findOrFail($paras['entryid']);

        OccupationalRiskOperationLog::logUpdate($occupationalRiskIns, [
            'department' => $paras['department'],
            'section' => $paras['section'],
            'description' => $paras['description'],
            'riskLevel' => $paras['risklevel'],
        ]);
        
        return response()->json(['status' => 'good']);
    }

    protected function editable()
    {
        return $this->loginUser->roleID == ROLE_ID_MAKER;
    }

    protected function canCheck()
    {
        return $this->loginUser->roleID == ROLE_ID_CHECKER;
    }
}

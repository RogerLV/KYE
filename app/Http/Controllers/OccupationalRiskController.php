<?php

namespace App\Http\Controllers;

use App\Models\OccupationalRiskOperationLog;
use App\Exceptions\AppException;

class OccupationalRiskController extends Controller
{
    public function listAll()
    {
        $this->pageAccessible(__CLASS__, __FUNCTION__);

        return view('occupational.list');
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
                ->with('title', 'Occupational Risk Checker Page');
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

        OccupationalRiskOperationLog::checkApprove($paras['pendingid']);

        return response()->json(['status' => 'good']);
    }

    public function checkerReject()
    {
        if (!$this->canCheck()) {
            throw new AppException('OCPTNRSKCTL003', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters('pendingid');

        OccupationalRiskOperationLog::checkReject($paras['pendingid']);

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

<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\KYECaseOperationLog;
use App\Exceptions\AppException;

class KYECaseController extends Controller
{
    public function create($empNo)
    {
        $pageIns = $this->pageAccessible(__CLASS__, __FUNCTION__);

        $staffIns = \App\Models\Staff::getIns($empNo);
        $occupationalRisk = \App\Models\OccupationalRisk::where([
            ['department', '=', $staffIns->department],
            ['section', '=', $staffIns->section],
        ])->first();

        return view('kyecase.create')
                ->with('title', $pageIns->title)
                ->with('staff', \App\Models\Staff::getIns($empNo))
                ->with('occupationalRisk', $occupationalRisk);
    }

    public function listPending()
    {
        $pageIns = $this->pageAccessible(__CLASS__, __FUNCTION__);

        return view('kyecase.listpending')
                ->with('title', $pageIns->title)
                ->with('entries', KYECaseOperationLog::getAllPendings())
                ->with('isMaker', $this->editable())
                ->with('isChecker', $this->canCheck())
                ->with('userLanID', $this->loginUser->lanID);
    }

    public function checkerPage($logID)
    {
        $pageIns = $this->pageAccessible(__CLASS__, __FUNCTION__);

        $logIns = KYECaseOperationLog::findOrFail($logID);

        if (!is_null($logIns->checkedResult)) {
            throw new AppException('KYECSCTRL006', 'Case Closed.');
        }

        $creditBureauReport = null;
        if (0 != $logIns->to->CreditBureauFileID) {
            $creditBureauReport = Document::findOrFail($logIns->to->CreditBureauFileID);
        }

        return view('kyecase.checker')
                ->with('title', $pageIns->title)
                ->with('log', $logIns)
                ->with('dowJonesReport', Document::findOrFail($logIns->to->DowJonesFileID))
                ->with('questnetReport', Document::findOrFail($logIns->to->QuestnetFileID))
                ->with('creditBureauReport', $creditBureauReport)
                ->with('maker', \App\Models\User::getIns($logIns->madeBy));
    }

    public function make()
    {
        if (!$this->editable()) {
            throw new AppException('KYECSCTRL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['employno', 'occupationalrisk', 
                'relationshiprisk', 'specialfactor', 'overallrating']);

        if (!request()->hasFile('dowjonesreport') || !request()->hasFile('questnetreport')) {
            throw new AppException('KYECSCTRL002', ERROR_MESSAGE_DATA_ERROR);
        }

        $staffIns = \App\Models\Staff::getIns($paras['employno']);

        $dowJonesReport = request()->file('dowjonesreport');
        $dowJonesDoc = Document::saveFile($dowJonesReport, 'DowJones', $paras['employno']);

        $questnetReport = request()->file('questnetreport');
        $questnetDoc = Document::saveFile($questnetReport, 'Questnet', $paras['employno']);

        if (request()->hasFile('creditbureaureport')) {
            $creditBureauReport = request()->file('creditbureaureport');
            $creditBureauDoc = Document::saveFile($creditBureauReport, 'CreditBureau', $paras['employno']);
        }

        KYECaseOperationLog::logInsert([
            'employNo' => $paras['employno'],
            'name' => $staffIns->uEngName,
            'department' => $staffIns->department,
            'section' => $staffIns->section,
            'DowJonesFileID' => $dowJonesDoc->id,
            'QuestnetFileID' => $questnetDoc->id,
            'CreditBureauFileID' => isset($creditBureauDoc) ? $creditBureauDoc->id : 0,
            'occupationalRisk' => strtolower($paras['occupationalrisk']),
            'relationshipRisk' => strtolower($paras['relationshiprisk']),
            'specialFactors' => strtolower($paras['specialfactor']),
            'overallRisk' => strtolower($paras['overallrating']),
        ]);

        return response()->json(['status' => 'close']);
    }

    public function checkerApprove()
    {
        if (!$this->canCheck()) {
            throw new AppException('KYECSCTRL004', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['logid']);

        KYECaseOperationLog::checkApprove($paras['logid']);

        return response()->json(['status' => 'good']);
    }

    public function checkerReject()
    {
        if (!$this->canCheck()) {
            throw new AppException('KYECSCTRL005', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['logid']);

        KYECaseOperationLog::checkReject($paras['logid']);

        return response()->json(['status' => 'close']);
    }

    public function delete()
    {
        if (!$this->editable()) {
            throw new AppException('KYECSCTRL003', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['entryid']);

        KYECaseOperationLog::remove($paras['entryid']);

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

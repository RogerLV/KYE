<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\KYECaseOperationLog;
use App\Models\KYECase;
use App\Models\Staff;
use App\Exceptions\AppException;

class KYECaseController extends Controller
{
    public function view($caseID)
    {
        $pageIns = $this->pageAccessible(__CLASS__, __FUNCTION__);

        $caseIns = KYECase::findOrFail($caseID);

        $creditBureauReport = null;
        if (0 != $caseIns->CreditBureauFileID) {
            $creditBureauReport = Document::findOrFail($caseIns->CreditBureauFileID);
        }

        $checker = null;
        if (!is_null($caseIns->log->checkedBy)) {
            $checker = $caseIns->log->checker;
        }

        return view('kyecase.view')
                ->with('title', $pageIns->title)
                ->with('case', $caseIns)
                ->with('dowJonesReport', Document::findOrFail($caseIns->DowJonesFileID))
                ->with('questnetReport', Document::findOrFail($caseIns->QuestnetFileID))
                ->with('creditBureauReport', $creditBureauReport)
                ->with('maker', $caseIns->log->maker)
                ->with('checker', $checker);
    }

    public function create($empNo)
    {
        $pageIns = $this->pageAccessible(__CLASS__, __FUNCTION__);

        $staffIns = Staff::getIns($empNo);

        if (!is_null($staffIns->pendingCaseID)) {
            throw new AppException('KYECSCTRL007', 'Please check existing case before creating a new one.');
        }

        $occupationalRisk = \App\Models\OccupationalRisk::where([
            ['department', '=', $staffIns->department],
            ['section', '=', $staffIns->section],
        ])->first();

        return view('kyecase.create')
                ->with('title', $pageIns->title)
                ->with('staff', Staff::getIns($empNo))
                ->with('occupationalRisk', $occupationalRisk);
    }

    public function listPending()
    {
        $pageIns = $this->pageAccessible(__CLASS__, __FUNCTION__);

        return view('kyecase.listpending')
                ->with('title', $pageIns->title)
                ->with('entries', KYECaseOperationLog::getAllPendings())
                ->with('isMaker', $this->loginUser->isMaker())
                ->with('isChecker', $this->loginUser->isChecker())
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
                ->with('maker', $logIns->maker);
    }

    public function make()
    {
        if (!$this->loginUser->isMaker()) {
            throw new AppException('KYECSCTRL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['employno', 'occupationalrisk', 
                'relationshiprisk', 'specialfactor', 'overallrating']);

        if (!request()->hasFile('dowjonesreport') || !request()->hasFile('questnetreport')) {
            throw new AppException('KYECSCTRL002', ERROR_MESSAGE_DATA_ERROR);
        }

        $staffIns = Staff::getIns($paras['employno']);

        if (!is_null($staffIns->pendingCaseID)) {
            throw new AppException('KYECSCTRL008', 'Please check existing case before creating a new one.');
        }

        $dowJonesReport = request()->file('dowjonesreport');
        $dowJonesDoc = Document::saveFile($dowJonesReport, 'DowJones', $paras['employno']);

        $questnetReport = request()->file('questnetreport');
        $questnetDoc = Document::saveFile($questnetReport, 'Questnet', $paras['employno']);

        if (request()->hasFile('creditbureaureport')) {
            $creditBureauReport = request()->file('creditbureaureport');
            $creditBureauDoc = Document::saveFile($creditBureauReport, 'CreditBureau', $paras['employno']);
        }

        $logIns = KYECaseOperationLog::logInsert([
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

        // set pending case id
        $staffIns->pendingCaseID = $logIns->id;
        $staffIns->save();

        return response()->json(['status' => 'close']);
    }

    public function checkerApprove()
    {
        if (!$this->loginUser->isChecker()) {
            throw new AppException('KYECSCTRL004', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['logid']);

        $log = KYECaseOperationLog::checkApprove($paras['logid']);

        //unset pending case id in staff ins
        $staffIns = Staff::getIns($log->to->employNo);
        $staffIns->pendingCaseID = null;
        $staffIns->save();

        return response()->json([
            'status' => 'good',
            'url' => route('KYECaseView', ['case' => $log->tableID])
        ]);
    }

    public function checkerReject()
    {
        if (!$this->loginUser->isChecker()) {
            throw new AppException('KYECSCTRL005', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['logid']);

        $log = KYECaseOperationLog::checkReject($paras['logid']);

        //unset pending case id in staff ins
        $staffIns = Staff::getIns($log->to->employNo);
        $staffIns->pendingCaseID = null;
        $staffIns->save();

        return response()->json(['status' => 'close']);
    }

    public function delete()
    {
        if (!$this->loginUser->isMaker()) {
            throw new AppException('KYECSCTRL003', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['entryid']);

        KYECaseOperationLog::remove($paras['entryid']);

        return response()->json(['status' => 'good']);
    }
}

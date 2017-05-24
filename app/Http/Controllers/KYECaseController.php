<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\KYECaseOperationLog;

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

    public function make()
    {
        if (!$this->editable()) {
            throw new AppException('KYECSCTRL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['staffid', 'department', 'section', 'occupationalrisk', 
                'relationshiprisk', 'specialfactor', 'overallrating']);

        if (!request()->hasFile('dowjonesreport') || !request()->hasFile('questnetreport')) {
            throw new AppException('KYECSCTRL002', ERROR_MESSAGE_DATA_ERROR);
        }

        $dowJonesReport = request()->file('dowjonesreport');
        $dowJonesDoc = Document::saveFile($dowJonesReport, 'DowJones', $paras['staffid']);

        $questnetReport = request()->file('questnetreport');
        $questnetDoc = Document::saveFile($questnetReport, 'Questnet', $paras['staffid']);

        if (request()->hasFile('creditbureaureport')) {
            $creditBureauReport = request()->file('creditbureaureport');
            $creditBureauDoc = Document::saveFile($creditBureauReport, 'CreditBureau', $paras['staffid']);
        }

        KYECaseOperationLog::logInsert([
            'staffID' => $paras['staffid'],
            'department' => $paras['department'],
            'section' => $paras['section'],
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

    protected function editable()
    {
        return $this->loginUser->roleID == ROLE_ID_MAKER;
    }
}

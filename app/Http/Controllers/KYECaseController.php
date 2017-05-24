<?php

namespace App\Http\Controllers;

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
}

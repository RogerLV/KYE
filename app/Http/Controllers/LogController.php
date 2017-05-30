<?php

namespace App\Http\Controllers;

use App\Models\OperationLog;
use DB;

class LogController extends Controller
{
    public function view()
    {
        $pageIns = $this->pageAccessible(__CLASS__, __FUNCTION__);

        $paraLogs = \App\Models\UpdateLog::with('editor')->where([
            ['tableName', '=', 'Parameters'],
            ['app', '=', env('APP_NAME')],
        ])->orderBy('id', 'DESC')
        ->take(50)
        ->get();

        return view('logview')
                ->with('title', $pageIns->title)
                ->with('staffLogs', \App\Models\StaffOperationLog::getLatest(50))
                ->with('KYECaseLogs', \App\Models\KYECaseOperationLog::getLatest(50))
                ->with('riskLogs', \App\Models\OccupationalRiskOperationLog::getLatest(50))
                ->with('paraLogs', $paraLogs);
    }
}

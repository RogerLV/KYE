<?php

namespace App\Http\Controllers;

use DB;

class WelcomeController extends Controller
{
    protected $loginUser;

    public function __construct()
    {
        // As of start of the application, override parent
        // constructor preventing user initialization.
    }

    public function welcome()
    {
        // get user and role info
        $this->loginUser = \App\Logic\LoginUser\LoginUserKeeper::initUser();
        $userInfo = \App\Models\User::where('lanID', $this->loginUser->lanID)->first();
        $roleList = \App\Models\UserRole::with('role')->where('lanID', $this->loginUser->lanID)
                        ->orderBy('roleID', 'DESC')
                        ->get();

        $pages = \App\Models\Page::join('RolePages', 'Pages.id', '=', 'RolePages.pageID')
                    ->where('Pages.showInEntrance', '=', true)
                    ->where('RolePages.roleID', '=', $this->loginUser->roleID)
                    ->get();

        $alertInfo = null;
        if ($this->loginUser->isMaker()) {
            $alertInfo = $this->getMakerAlertInfo();
        } elseif ($this->loginUser->isChecker()) {
            $alertInfo = $this->getCheckerAlertInfo();
        }

        return view('welcome')
                ->with('userInfo', $userInfo)
                ->with('roleList', $roleList)
                ->with('selectedMapID', $this->loginUser->id)
                ->with('pages', $pages)
                ->with('isMaker', $this->loginUser->isMaker())
                ->with('isChecker', $this->loginUser->isChecker())
                ->with('alertInfo', $alertInfo);
    }


    protected function getMakerAlertInfo()
    {
        $checkLine = date('Y-m-d H:i:s', strtotime('now - 5 months'));

        $staff = DB::table('Staff')
                ->leftJoin('KYECases', 'Staff.employNo', '=', 'KYECases.employNo')
                ->select(
                    'Staff.employNo', 
                    'Staff.uEngName', 
                    'KYECases.occupationalRisk',
                    'KYECases.relationshipRisk',
                    'KYECases.specialFactors',
                    DB::raw('MAX(KYECases.created_at) AS lastConduct'))
                ->where('KYECases.created_at', '<', $checkLine)
                ->orWhereNull('created_at')
                ->whereNull('Staff.leaveDate')
                ->whereNull('Staff.deleted_at')
                ->groupBy('Staff.employNo')
                ->orderBy('lastConduct')
                ->get();

        // parameter preparation
        $now = date('Y-m-d H:i:s', strtotime('now'));
        $thisWeek = date('Y-m-d H:i:s', strtotime('+1 week'));
        $thisMonth = date('Y-m-d H:i:s', strtotime('+1 month'));

        $riskNameCaseToPara = [
            'occupationalRisk' => 'OccupationalRisk',
            'relationshipRisk' => 'RelationshipRisk',
            'specialFactors' => 'SpecialFactorRisk',
        ];
        $intervalMap = array_flip(\App\Models\Parameter::getReviewPeriodOptions());

        $riskSettings = \App\Models\Parameter::getRiskSettings();

        $alertInfo = [
            'expired' => [],
            'withinThisMonth' => [],
            'withinThisWeek' => [],
        ];

        // loop to classify all kinds of staff
        foreach ($staff as $entry) {

            if (is_null($entry->lastConduct)) {
                $alertInfo['expired'][] = $entry;
                continue;
            } 

            // find interval and deadline
            $staffIntervals = [];

            foreach ($riskNameCaseToPara as $key => $value) {
                $staffIntervals[] = $intervalMap[$riskSettings[$value][$entry->$key]];
            }

            $deadline = date('Y-m-d H:i:s', strtotime($entry->lastConduct."+".min($staffIntervals)." months"));

            // classify
            if ($deadline > $thisMonth) {
                continue;
            } elseif ($deadline <= $thisMonth && $deadline > $thisWeek) {
                $alertInfo['withinThisMonth'][] = $entry;
            } elseif ($deadline <= $thisWeek && $deadline > $now) {
                $alertInfo['withinThisWeek'][] = $entry;
            } else {
                $alertInfo['expired'][] = $entry;
            }
        }
        return $alertInfo;
    }

    protected function getCheckerAlertInfo()
    {
        return [
                'hasPendingRisk' => \App\Models\OccupationalRiskOperationLog::hasPending(),
                'hasPendingKYE' => \App\Models\KYECaseOperationLog::hasPending(),
            ];
    }
}

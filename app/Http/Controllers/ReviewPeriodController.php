<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use DB;

class ReviewPeriodController extends Controller
{
    public function view()
    {
        $pageIns = $this->pageAccessible(__CLASS__, __FUNCTION__);

        return view('reviewperiod.view')
                ->with('title', $pageIns->title)
                ->with('reviewPeriods', Parameter::getRiskSettings())
                ->with('isAdmin', $this->loginUser->isAppAdmin());
    }

    public function edit()
    {
        $pageIns = $this->pageAccessible(__CLASS__, __FUNCTION__);

        return view('reviewperiod.edit')
                ->with('title', $pageIns->title)
                ->with('reviewPeriods', Parameter::getRiskSettings())
                ->with('options', Parameter::getReviewPeriodOptions());
    }

    public function update()
    {
        if (!$this->loginUser->isAppAdmin()) {
            throw new \App\Exceptions\AppException('RVWPRDCTRL001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        $paras = $this->checkParameters(['category', 'level', 'risk']);

        Parameter::updateRisk($paras);

        return response()->json(['status' => 'good']);
    }
}

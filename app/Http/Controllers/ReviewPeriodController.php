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
}

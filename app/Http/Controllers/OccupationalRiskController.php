<?php

namespace App\Http\Controllers;

use App\Models\OccupationalRiskOperationLog;

class OccupationalRiskController extends Controller
{
    public function listAll()
    {
    	
    }

    public function makerPage()
    {
		$this->pageAccessible(__CLASS__, __FUNCTION__);

        $pendings = OccupationalRiskOperationLog::listPendings();

		return view('occupational.maker')
                ->with('pendingAdd', $pendings->where('type', 'insert')->get())
                ->with('pendingUpdate', $pendings->where('type', 'update')->get())
                ->with('pendingRemove', $pendings->where('type', 'remove')->get());
    }

    public function deletePending()
    {
        $paras = $this->checkParameters(['pendingid']);

        OccupationalRiskOperationLog::remove($paras['pendingid']);

        return response()->json(['status' => 'good']);
    }

    protected function editable()
    {
        return $this->loginUser->roleID == ROLE_ID_MAKER;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Staff;

class ExStaffController extends Controller
{
    public function listAll($dept='all')
    {
        $this->pageAccessible(__CLASS__, __FUNCTION__);

        if ('all' == $dept) {
            $staff = Staff::ex()->paginate(50);
        } else {
            $staff = Staff::ex()->where('department', $dept)->paginate(50);
        }

        return view('staff.ex')
                ->with('title', 'Ex-Staff List')
                ->with('staff', $staff)
                ->with('deptOptions', Staff::select('department')->ex()->distinct()->get())
                ->with('selectedDept', $dept);
    }
}

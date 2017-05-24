<?php

namespace App\Http\Controllers;

use App\Models\OccupationalRisk;
use App\Models\OccupationalRiskOperationLog;

class OccupationalRiskBatchController extends OccupationalRiskController
{
    public function receive()
    {
        if (!$this->editable()) {
            throw new AppException('OCPRSKBAH001', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        if (empty($fileIns = request()->file('upload-occupation-list'))) {
            throw new AppException('OCPRSKBAH002', ERROR_MESSAGE_DATA_ERROR);
        }

        $handle = fopen($fileIns, "r"); 
        if (($data = fgetcsv($handle)) == false) {
            throw new AppException('OCPRSKBAH003', 'Incorrect Format');
        }

        define('INDEX_DEPARTMENT', 0);
        define('INDEX_SECTION', 1);
        define('INDEX_DESCRIPTION', 2);
        define('INDEX_RISK_LEVEL', 3);

        if ($data[INDEX_DEPARTMENT] != 'Department' 
            || $data[INDEX_SECTION] != 'Section'
            || $data[INDEX_DESCRIPTION] != 'Description'
            || $data[INDEX_RISK_LEVEL] != 'Risk Level') {
            throw new AppException('OCPRSKBAH004', 'Incorrect Format');
        }

        while ($entry = fgetcsv($handle)) {

            $data = [
                'department' => utf8_encode($entry[INDEX_DEPARTMENT]),
                'section' => utf8_encode($entry[INDEX_SECTION]),
                'description' => utf8_encode($entry[INDEX_DESCRIPTION]),
                'riskLevel' => utf8_encode($entry[INDEX_RISK_LEVEL]),
            ];

            $existingIns = OccupationalRisk::where([
                ['department', '=', $data['department']],
                ['section', '=', $data['section']],
            ])->first();

            if (is_null($existingIns)) {
                OccupationalRiskOperationLog::logInsert($data);
            } else {
                OccupationalRiskOperationLog::logUpdate($existingIns, $data);
            }
        }

        return response()->json(['status' => 'good']);
    }

    public function approveAll()
    {
        if (!$this->canCheck()) {
            throw new AppException('OCPRSKBAH005', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        foreach (OccupationalRiskOperationLog::getAllPendings() as $entry) {
            OccupationalRiskOperationLog::checkApprove($entry->id, true);
        }

        return response()->json(['status' => 'good']);
    }

    public function rejectAll()
    {
        if (!$this->canCheck()) {
            throw new AppException('OCPRSKBAH006', ERROR_MESSAGE_NOT_AUTHORIZED);
        }

        foreach (OccupationalRiskOperationLog::getAllPendings() as $entry) {
            OccupationalRiskOperationLog::checkReject($entry->id, true);
        }

        return response()->json(['status' => 'good']);
    }
}

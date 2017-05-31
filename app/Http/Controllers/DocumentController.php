<?php

namespace App\Http\Controllers;

use App\Models\Document;

class DocumentController extends Controller
{
    public function display($docID, $name)
    {
        $docIns = Document::findOrFail($docID);

        return response()->file($docIns->getPath());
    }

    public function errorLog($logName)
    {
        return response()->file(storage_path('logs/'.$logName));
    }
}

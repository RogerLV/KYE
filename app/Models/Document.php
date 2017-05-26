<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Document extends Model
{
    protected $table = 'Documents';

    public $timestamps = false;

    public static function saveFile(UploadedFile $fileIns, $type, $employNo)
    {
        $ins = new Document();
        $tempName = 't'.str_random(16).'.'.$fileIns->clientExtension();
        $ins->type = $type;
        $ins->origName = $fileIns->getClientOriginalName();
        $ins->subAddr = '/'.$type.'/'.$employNo.'/'.$tempName;

        $ins->save();

        $dest = STORAGE_PATH_KYE_CASE.'/'.$type.'/'.$employNo;
        $fileIns->move($dest, $tempName);

        return $ins;
    }

    public function getUrl()
    {
        return route('DocDisplay', [
            'docID' => $this->id,
            'name' => $this->origName
        ]);
    }

    public function getPath()
    {
        return STORAGE_PATH_KYE_CASE.'/'.$this->subAddr;
    }
}

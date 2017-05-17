<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperationLog extends Model
{
    use SoftDeletes;

    protected $table = 'OperationLogs';

    protected $dates = ['deleted_at'];

    public function getToAttribute($value)
    {
    	return json_decode($value);
    }

    public function getFromAttribute($value)
    {
    	return json_decode($value);
    }

    public static function getIns($id)
    {
        $instance = self::find($id);

        if (is_null($instance)) {
            throw new AppException('OPRTNLOGMDL001', 'Incorrect Role Info.');
        }

        return $instance;
    }

    public static function remove($id)
    {
    	$ins = self::getIns($id);
    	$ins->delete();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OccupationalRisk extends Model
{
    use SoftDeletes;

    protected $table = 'OccupationalRisk';

    protected $dates = ['deleted_at'];
}

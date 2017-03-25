<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleList extends Model
{
    use SoftDeletes;

    public $table = 'RoleList';

    protected $dates = ['deleted_at'];
}

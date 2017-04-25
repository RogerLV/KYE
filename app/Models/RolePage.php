<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePage extends Model
{
    protected $table = 'RolePages';

    public $timestamps = false;

    public function page()
    {
    	return $this->hasOne('App\Models\Page', 'id', 'pageID');
    }

    public function role()
    {
    	return $this->hasOne('App\Models\Role', 'id', 'roleID');
    }
}

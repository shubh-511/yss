<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleModule extends Model
{
    protected $table = 'map_module_roles';
    public function role()
    {
        return $this->belongsTo('App\Role','role_id','id');
    }
    public function module()
    {
        return $this->belongsTo('App\Module','module_id','id');
    }
}

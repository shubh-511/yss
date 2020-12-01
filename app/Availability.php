<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
	use SoftDeletes;
    /*public function availaibleHours
    {
    	return $this->hasMany('App\AvailaibleHours','availability_id','id');
    }*/

    protected $fillable = [
        'user_id', 
        'availaible_days', 
        'breaks',
    ];
}

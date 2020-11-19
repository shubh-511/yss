<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoChannel extends Model
{
    /*public function availaibleHours
    {
    	return $this->hasMany('App\AvailaibleHours','availability_id','id');
    }*/

    protected $fillable = [
        'from_id', 
        'to_id', 
        'channel_id',
        'timing',
        'uid',
        'status',
    ];
}

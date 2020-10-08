<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
	protected $fillable = [
        'user_id', 
        'package_name',  
        'package_description',
        'session_minutes',
        'session_hours',
        'amount',
    ];

    public function user()
    {
    	return $this->belongsTo('App/User','user_id','id');
    }
}

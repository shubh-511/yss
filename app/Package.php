<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use SoftDeletes;
	protected $fillable = [
        'user_id', 
        'package_name',  
        'package_description',
        'session_minutes',
        'session_hours',
        'no_of_slots',
        'amount',
    ];

    public function user()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }
}

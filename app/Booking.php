<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use SoftDeletes;
    public function counsellor()
    {
    	return $this->belongsTo('App\User','counsellor_id','id');
    }

    public function user()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }

    public function package()
    {
    	return $this->belongsTo('App\Package','package_id','id');
    }
}

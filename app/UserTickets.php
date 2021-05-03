<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTickets extends Model
{
	use SoftDeletes;

    public function user()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }

    public function booking()
    {
    	return $this->belongsTo('App\Booking','booking_id','id');
    }
}

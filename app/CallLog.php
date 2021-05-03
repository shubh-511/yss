<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    public function init_by()
    {
    	return $this->belongsTo('App\User','init_by','id');
    }
    public function pick_by()
    {
    	return $this->belongsTo('App\User','pick_by','id');
    }
    public function cut_by()
    {
    	return $this->belongsTo('App\User','cut_by','id');
    }

    public function initiated_by()
    {
        return $this->belongsTo('App\User','init_by','id');
    }
    public function picked_by()
    {
        return $this->belongsTo('App\User','pick_by','id');
    }
    public function cutted_by()
    {
        return $this->belongsTo('App\User','cut_by','id');
    }
    public function booking()
    {
    	return $this->belongsTo('App\Booking','booking_id','id');
    }
}

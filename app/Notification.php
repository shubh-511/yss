<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public function sender()
    {
    	return $this->belongsTo('App\User','sender_id','id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\User','receiver_id','id');
    }

}

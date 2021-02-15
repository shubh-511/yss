<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	protected $table = 'notifications';
    public function sender()
    {
    	return $this->belongsTo('App\User','sender','id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\User','receiver','id');
    }

}

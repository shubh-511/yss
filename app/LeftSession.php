<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeftSession extends Model
{
	protected $table = 'user_left_sessions';

    protected $fillable = [
        'user_id', 
        'package_id',  
        'booking_id',
        'left_sessions',
        
    ];

    public function user()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }
    public function package()
    {
        return $this->belongsTo('App\Package','package_id','id');
    }

    public function last_payment()
    {
        return $this->belongsTo('App\Payment','payment_id','id');
    }
}

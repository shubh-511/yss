<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = 'refunds';
    public function user()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }
    
    public function payment_detail()
    {
        return $this->belongsTo('App\Payment','payment_id','id');
    }
}

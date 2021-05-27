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
}

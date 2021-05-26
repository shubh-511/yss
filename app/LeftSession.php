<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeftSession extends Model
{
    protected $fillable = [
        'user_id', 
        'package_id',  
        'booking_id',
        'left_sessions',
        
    ];
}

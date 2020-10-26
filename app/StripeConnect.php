<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeConnect extends Model
{
    
    protected $fillable = [
        'user_id', 
        'stripe_id', 
        'breaks',
    ];
}

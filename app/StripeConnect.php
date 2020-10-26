<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripeConnect extends Model
{
    protected $table = 'stripe_connect';
    protected $fillable = [
        'user_id', 
        'stripe_id', 
        'breaks',
    ];
}

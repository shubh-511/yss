<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingReview extends Model
{
    protected $fillable =['user_id','review','rating'];
}

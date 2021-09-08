<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingReview extends Model
{
    protected $table = 'listing_reviews';
    protected $fillable =['user_id','listing_id','review','rating'];

    public function user()
    {
    	return $this->belongsTo('App\User','user_id','id');
    }

    public function listing()
    {
    	return $this->belongsTo('App\Listing','listing_id','id');
    }
}

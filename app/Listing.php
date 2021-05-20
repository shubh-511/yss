<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    public function listing_category()
    {
    	return $this->belongsTo('App\ListingCategory','listing_category','id');
    }

    public function listing_label()
    {
    	return $this->belongsTo('App\ListingLabel','listing_label','id');
    }

    public function listing_region()
    {
    	return $this->belongsTo('App\ListingRegion','listing_region','id');
    }

    public function category()
    {
        return $this->belongsTo('App\ListingCategory','listing_category','id');
    }

    public function label()
    {
        return $this->belongsTo('App\ListingLabel','listing_label','id');
    }

    public function region()
    {
        return $this->belongsTo('App\ListingRegion','listing_region','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function gallery()
    {
        return $this->hasMany('App\ListingGallery','listing_id','id');
    }

 public function multilabel()
    {
        return $this->hasMany('App\multilabel','listing_id','id');
    }
    
}

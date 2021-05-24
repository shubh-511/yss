<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class multilabel extends Model
{
    protected $fillable = [
        'listing_id', 
        'label_id',  
    ];
    public function multilable()
    {
        return $this->belongsTo('App\ListingLabel','label_id','id');
    }
}

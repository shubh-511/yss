<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ListingCategory extends Model
{
	use SoftDeletes;
    /*public function counsellor()
    {
    	return $this->belongsTo('App\User','counsellor_id','id');
    }*/

    
}

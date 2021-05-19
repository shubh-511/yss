<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class multilabel extends Model
{
    protected $fillable = [
        'listing_id', 
        'label_id',  
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoChannel extends Model
{
    public function user
    {
    	return $this->belongsTo('App\User','from_id','id');
    }

    public function counsellor
    {
        return $this->belongsTo('App\User','to_id','id');
    }

    protected $fillable = [
        'from_id', 
        'to_id', 
        'channel_id',
        'timing',
        'uid',
        'status',
    ];
}

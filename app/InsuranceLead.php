<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceLead extends Model
{
	use SoftDeletes;
    protected $fillable = ['first_name','last_name', 
        'email','phone','insurance_provider','country','state','city'];
}

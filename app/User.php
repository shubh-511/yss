<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasApiTokens;

    public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  public function getJWTCustomClaims()
  {
    return [];
  }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'first_name',
        'middle_name',
        'last_name',
        'country_code',
        'phone',
        'otp',
        'country_code',
        'country_id',
        'registered_date',
        'last_login_date',
        'email_verified_at',
        'user_nicename',
        'display_name',
        'account_enabled',
        'migrated_id',
        'user_type',
        'avatar_id',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles(){
        return $this->belongsTo(Role::class, 'role_id','id');
    }
}

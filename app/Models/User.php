<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    
    use HasApiTokens,HasFactory, Notifiable;

    public const GOOGLE=0;
    public const FACEBOOK=1;
    public const APPLE=2;

    public const ACTIVE=1;
    public const UNACTIVE=0;

   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function convertNameProvider(int $name){
        switch($name){
            case self::GOOGLE: return "google";
            case self::FACEBOOK: return "facebook";
            case self::APPLE: return "apple";
        }
    }

    public function playlist(){
        return $this->hasMany('App\Models\Playlist','user_id','id');
    }

    public function license(){
        return $this->hasMany('App\Models\License','user_id','id');
    }
}

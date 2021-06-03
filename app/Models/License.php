<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    public const UNACTIVE=0;
    public const EXPIRE=1;
    public const ACTIVE=2;

    protected $fillable=['code','active_time'];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function convertStatusLicense(int $status){ 
        switch ($status) {
            case self::UNACTIVE: return "Chưa kích hoạt";
            case self::EXPIRE: return "Đã hết hạn";
            case self::ACTIVE: return "Đã kích hoạt";
            default:return "____";  
        }
    }
}

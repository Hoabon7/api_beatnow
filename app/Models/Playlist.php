<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable=['name','thumbnail','song_number','user_id','listsongs','id'];
    protected $casts = ['id' => 'string'];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function song(){
        return $this->hasMany('App\Models\Song','playlist_id','id');
    }
}

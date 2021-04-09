<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;
    protected $fillable=['name','url','thumbnail','playlist_id','song_id'];

    public function playlist(){
        return $this->belongsTo('App\Models\Playlist','playlist_id','id');
    }
}

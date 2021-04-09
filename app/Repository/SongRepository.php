<?php 
    namespace App\Repository;

use App\Models\Song;
use App\Models\Playlist;
use App\Interfaces\BaseSongInterface;

class SongRepository implements BaseSongInterface{
    protected $song;
    protected $playList;
    public function __construct(Song $song,Playlist $playList)
    {
        $this->song=$song;
        $this->playList=$playList;

    }

    public function delete($idSong){
        $checkSong=$this->song->where('id',$idSong)->first();
        if($checkSong==null) return false;
        else return $this->song->destroy($idSong);
    }

    
}
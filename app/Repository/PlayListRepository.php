<?php

namespace App\Repository;

use App\Models\Song;
use App\Models\User;
use App\Models\Playlist;
use App\Interfaces\BasePlayListInterface;

class PlayListRepository implements BasePlayListInterface{

    protected $playlist;
    protected $user;

    public function __construct(Playlist $playlist,User $user)
    {
        $this->playlist=$playlist;   
        $this->user=$user;
    }
    /**
     * add playlist to user specifile
     * @param data ;//data of playlist 
     */
    public function add($idUser,array $data){
        $user=$this->user->where('id',$idUser)->first();
        if($user==null) return false;
        else{
            $dataPlayList=$user->playlist()->save(new Playlist($data));
            return $dataPlayList;
        }
        
    }
    /**
     * check playlist exit
     * return playlist
     * @param $idPlaylist
     */
    public function edit(int $idPlayList){
        $dataPlayList=$this->playlist->findOrFail($idPlayList);
        return $dataPlayList;
    }
    /**
     * update info playlist
     * @param $idPlaylist
     * @param data of playlist from app send
     * 
     * return dataPlaylist updated
     */

    public function store($idPlayList,array $data){
        $dataPlayList=$this->playlist->where('id',$idPlayList)->first();
        if($dataPlayList==null){
            return false;
        }
        return $dataPlayList->update($data);
        //$this->playlist->update($data);
    }

    public function delete(string $idPlayList){
        $checkCancel=$this->playlist->where('id',$idPlayList)->first();
        //return $checkCancel;
        if($checkCancel==null){
            return false;
        }
        return $this->playlist->destroy($checkCancel->id);
    }
    /**
     * get all song from playlist
     */

    public function getAllSong($idPlayList){
        return $this->playlist->where('id',$idPlayList)->with('song')->get();
    }

    public function getAllPlayList($idUser){
        return User::where('id',$idUser)->with('playlist')->first();
    }

    public function createSong($idPlayList,$dataSong){
        $playList=$this->playlist->find($idPlayList);
        //check song da co trong playlist chua
        $checkIdSong=$this->playlist->where('id',$idPlayList)->whereHas('song',function($query) use($dataSong){
            $query->where('song_id',$dataSong['song_id']);
        })->get();

        if($checkIdSong->count()==0){
            return $playList->song()->save(new Song($dataSong));
        }
        return false;
        
    }
}
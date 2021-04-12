<?php

namespace App\Http\Controllers\Api;

use App\Models\Playlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repository\PlayListRepository;
use App\Http\Requests\EditPlayListRequest;
use App\Http\Requests\CreatePlayListRequest;

class PlaylistController extends Controller
{
    protected $playListRepository;
    protected $playlist;

    public function __construct(PlayListRepository $playListRepository,Playlist $playlist)
    {
        $this->playListRepository=$playListRepository;
        $this->playlist=$playlist;
    }

    public function getAllPlayList(){
        $idUser=Auth::user()->id;
        return $this->playListRepository->getAllPlayList($idUser);
    }

    public function createPlayList(CreatePlayListRequest $request){
        $idUser=Auth::user()->id;
        $playlistId=$request->playlist_id;
        $checkPlayListExit=$this->playlist->where('playlist_id',$playlistId)->get()->count();
        if($checkPlayListExit==0){
            $checkCreatePlayList= $this->playListRepository->add($idUser,$request->all());
            if($checkCreatePlayList==null) return $this->responseFail('can not create playlist!');
            else return $this->responseSuccess($checkCreatePlayList);
        }elseif($checkPlayListExit>0){
            return $this->responseFail('can not create playlist!');
        }
        
        
    }

    public function getInfoPlayList(Request $request){
        $idPlayList=$request->id;
        return $this->playListRepository->edit($idPlayList);
    }

    public function editPlayList($idPlayList,EditPlayListRequest $request){
        $dataPlayList=$this->playListRepository->store($idPlayList,$request->all());
        if($dataPlayList==false) return $this->responseFail("can not edit");
        return $this->responseSuccess($dataPlayList);
    }

    public function deletePlayList(int $idPlayList){
        $checkCancel=$this->playListRepository->delete($idPlayList);
        if($checkCancel==false){
            return $this->responseBadRequest('can not delete!');
        }
        return $this->responseSuccess($checkCancel);
    }

    public function getAllSong(int $idPlayList){
        return $this->playListRepository->getAllSong($idPlayList);
    }

    public function createSong($idPlayList,Request $request){
        $dataSong=$request->all();
        $checkCreateSong=$this->playListRepository->createSong($idPlayList,$dataSong);
        if($checkCreateSong==false){
            return $this->responseBadRequest('song have been in playlist');
        }
        return $checkCreateSong;
    }


}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePlayListRequest;
use App\Http\Requests\EditPlayListRequest;
use Illuminate\Support\Facades\Auth;
use App\Repository\PlayListRepository;

class PlaylistController extends Controller
{
    protected $playListRepository;

    public function __construct(PlayListRepository $playListRepository)
    {
        $this->playListRepository=$playListRepository;
    }

    public function getAllPlayList(){
        $idUser=Auth::user()->id;
        return $this->playListRepository->getAllPlayList($idUser);
    }

    public function createPlayList(CreatePlayListRequest $request){
        $idUser=Auth::user()->id;
        $checkCreatePlayList= $this->playListRepository->add($idUser,$request->all());
        if($checkCreatePlayList==null) return $this->responseFail('can not create playlist!');
        else return $this->responseSuccess($checkCreatePlayList);
        
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

<?php

namespace App\Http\Controllers\Api;

use App\Models\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\SongRepository;

class SongController extends Controller
{
    protected $song;
    protected $songRepository;
    public function __construct(Song $song,SongRepository $songRepository)
    {
        $this->song = $song;
        $this->songRepository = $songRepository;
    }

    public function deleteSong($idSong){
        $checkDeleteSong = $this->songRepository->delete($idSong);
        if($checkDeleteSong == false) return $this->responseFail('Can not delete song!');
        return $this->responseSuccess($checkDeleteSong);
    }
}

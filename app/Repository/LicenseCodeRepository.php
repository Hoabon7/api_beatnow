<?php 

namespace App\Repository;

use Carbon\Carbon;
use App\Models\User;
use App\Models\License;
use App\Interfaces\LicenseCodeInterface;

class LicenseCodeRepository implements LicenseCodeInterface{
    /**
     * gender ra code_license
     */
    protected $license;
    protected $user;

    public function __construct(License $license,User $user)
    {
        $this->license=$license;
        $this->user=$user;
    }

    public function createLicense($idUser,$date,$codeLicense){
        $dataUser=$this->user->where('id',$idUser)->first();

        $dataLicense=$dataUser->license()->save(new License([
            'active_time'=>$date,
            'code'=> $codeLicense
        ]));

        if($dataLicense==null) return false;
        return $dataLicense;
    }

    
    public function checkCodeSame($code){
        $check=$this->license::where('code',$code)->first();
        if($check==null) return false;
        return true;
    }

    public function getUserExpire($idUser){
        return $this->user->where('id',$idUser)->select('expire_time')->get()[0]->expire_time;
    }

    public function checkLicenseIsset($idUser,$license){
        $license=$this->user->where('id',$idUser)->whereHas('license',function($query) use($license){
            $query->where('code',$license);
        })->first();

        if($license==null) return false;
        return $license;
    }

    public function checkCodeExpire($license){
        $timeCreated=$this->license->where('code',$license)->select('created_at','active_time')->first();
        if($timeCreated==null) return false;
        else{
            $timeCode=strtotime($timeCreated->created_at. ' +'.$timeCreated->active_time.'days');
            $check=$timeCode-strtotime(Carbon::now());
            //return $check;
            if($check>=0) return true;//chua het han
            else return false;//het han
        }
    }

}
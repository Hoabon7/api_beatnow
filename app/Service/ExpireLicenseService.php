<?php 
namespace App\Service;

use Carbon\Carbon;
use App\Models\License;

class ExpireLicenseService{
   
    public function createLicenseCode(){
        $characters = '123456789ABCDEFGHIJKLMNPQRSTVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $licenseCode=$randomString;
        return $licenseCode;
    }

    public function checkExpireLicense($expireTime){
        $timeNow=strtotime(Carbon::now());
        if($expireTime==0)       return License::UNACTIVE;
        if($expireTime<$timeNow) return License::EXPIRE;
        if($expireTime>=$timeNow) return License::ACTIVE;
    }



}
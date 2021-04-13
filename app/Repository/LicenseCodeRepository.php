<?php 

namespace App\Repository;

use Carbon\Carbon;
use App\Models\User;
use App\Models\License;
use Illuminate\Support\Facades\Log;
use App\Interfaces\LicenseCodeInterface;

class LicenseCodeRepository{
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
    /**
     * create license
     * @param idUser
     * @param date (so ngay)
     * @param code_license
     * return null or dataLicense
     */
    
    public function createLicense($codeLicense){
        $check=$this->license->where('code',$codeLicense)->first();
        //$count
        
        $license=$this->license->where('id','>',0)->first();
        try {
            $count=$this->license->where('id','>',0)->first()->count();
            if($check==null){
                //neu mã nhập vào khác mã trong db thì update
                $this->license->where('id',$license->id)->update([
                    'code'=>$codeLicense
                ]);
                return true;
            }else{
                return false;//da co thi thôi
            }
        } catch (\Throwable $th) {
            Log::debug($th->getMessage());
            $this->license->create([
                'code'=>$codeLicense
            ]);
            return true;
        }
       
        
            
          
        // if($dataLicense==null) return false;
        // return $dataLicense;
    }
    /**
     * check code license in table license exit
     * @param code
     * 
     * return boolen
     */
    
   
    /**
     * check time_expire of user expire or still in version pro
     * @param idUser
     * return expire_time
     */

    public function getUserExpire($idUser){
        return $this->user->where('id',$idUser)->select('expire_time')->get()[0]->expire_time;
    }
    /**
     * check license exit by check code license
     * @param idUser
     * @param code_license
     * return false or return user has license
     */
    public function checkLicenseIsset($idUser,$license){
        $license=$this->user->where('id',$idUser)->whereHas('license',function($query) use($license){
            $query->where('code',$license);
        })->first();

        if($license==null) return false;
        return $license;
    }
   


}
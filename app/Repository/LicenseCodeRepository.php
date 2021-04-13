<?php 

namespace App\Repository;

use Carbon\Carbon;
use App\Models\User;
use App\Models\License;
use App\Models\Log_user;
use Illuminate\Support\Facades\Log;
use App\Interfaces\LicenseCodeInterface;

class LicenseCodeRepository{
    /**
     * gender ra code_license
     */
    protected $license;
    protected $user;
    protected $logUser;

    public function __construct(License $license,User $user,Log_user $logUser)
    {
        $this->license=$license;
        $this->user=$user;
        $this->logUser=$logUser;
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
            $this->license->create(['code'=>$codeLicense]);
            return true;
        }
    }

    public function logActive($idUser,$time,$code){

        $data=$this->logUser->create([
            'user_id'=>$idUser,
            'code'=>$code,
            'time_active'=>$time
        ]);

        return $data;
    }
  
    
   
    

    
   


}
<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Service\ExpireLicenseService;
use App\Repository\LicenseCodeRepository;
use App\Http\Requests\checkLicenseRequest;
use App\Http\Requests\createCodeLicenseRequest;

class LicenseController extends Controller
{
    /**
     * service
     */
    protected $expireLicenseService;
    protected $license;
    protected $user;
    /**
     * repository
     */
    protected $licenseCodeRepository;
    public function __construct(ExpireLicenseService $expireLicenseService,
                                LicenseCodeRepository $licenseCodeRepository,
                                License $license,
                                User $user)
    {
        $this->user=$user;
        $this->license=$license;
        $this->expireLicenseService=$expireLicenseService;
        $this->licenseCodeRepository=$licenseCodeRepository;
    }

    public function createCodeLicense(createCodeLicenseRequest $request){
        $idUser=Auth::user()->id;
        $date=$request->day;
        $checkCreateCode=false;
        do{
            try {
                //generate code;
                $codeLicense=$this->expireLicenseService->createLicenseCode();
                $dataCodeLicense=$this->licenseCodeRepository->createLicense($idUser,$date,$codeLicense);
                $checkCreateCode=false;
                return $dataCodeLicense->code;
               
            } catch (\Throwable $th) {
                Log::debug($th->getMessage());
                $checkCreateCode=true;
            }
        }while($checkCreateCode==true);
    }

    public function checkLicense(checkLicenseRequest $request){
        $license=$request->license;
        $idUser=Auth::user()->id;

        $checkCodeIsset= $this->licenseCodeRepository->checkLicenseIsset($idUser,$license);
        if($checkCodeIsset==false) return $this->responseFail('license out date');
        else{
            $checkCodeExpire= $this->licenseCodeRepository->checkCodeExpire($license);
            $activeTime= $this->license->where('code',$license)->first()->active_time;
            //return $activeTime;
            $checkUserExpire=$this->expireLicenseService->checkExpireLicense(Auth::user()->expire_time);
            if($checkCodeExpire==true) {
                //code chua het han==> check user xem het han su dung hay chua
                $getUserExpire=$this->licenseCodeRepository->getUserExpire($idUser);
                if($checkUserExpire==License::UNACTIVE||$checkUserExpire==License::EXPIRE){
                    $this->user->where('id',$idUser)->update([
                        'expire_time'=>strtotime(Carbon::now() . '+ '.$activeTime.'days')
                    ]);
                }else{
                    $dateExpireUser=$this->expireLicenseService->convertFromIntToDate($getUserExpire);
                    //return $dateExpireUser;
                    $dataUser=$this->user->where('id',$idUser)->update([
                        //check
                        'expire_time'=>strtotime($dateExpireUser. '+ '.$activeTime.'days')
                    ]);
                    return $this->responseSuccess($dataUser);
                }
                
            }else{
                //code het han
                return $this->responseFail('license out date');
            }
        }
    }

}

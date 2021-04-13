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
       $checkCreate= $this->licenseCodeRepository->createLicense($request->code);
       if($checkCreate==true) return $this->responseSuccess($checkCreate);
       else return $this->responseFail('code exited');
    }

    public function checkLicense(checkLicenseRequest $request){
        $license=$request->license;
        //return $license;
        $idUser=Auth::user()->id;
        try {
            $checkLicense=$this->license->where('code',$license)->first()->count();

            if($checkLicense==1){
                //active cho user vinh vien
                $this->user->where('id',$idUser)->update([
                    'active'=>1
                ]);
                $timeActive=$this->expireLicenseService->convertTimeToInteger(Carbon::now());
                //return $timeActive;
                $this->licenseCodeRepository->logActive($idUser,$timeActive,$license);
                return $this->responseSuccess(null);
            }
           
        } catch (\Throwable $th) {
            Log::debug($th->getMessage());
            return $th->getMessage();
            return $this->responseFail('License not true');
        }
        
    }

    public function getCodeLicense(){
        if($this->license->first()!=null)
            return $this->license->first()->code;
        return $this->responseFail("License empty");
    }

}

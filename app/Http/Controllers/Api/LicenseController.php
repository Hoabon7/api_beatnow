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
        $this->user = $user;
        $this->license = $license;
        $this->expireLicenseService = $expireLicenseService;
        $this->licenseCodeRepository = $licenseCodeRepository;
    }

    // public function createCodeLicense(createCodeLicenseRequest $request){
    //    $checkCreate = $this->licenseCodeRepository->createLicense($request->code);
    //    if($checkCreate == true) return $this->responseSuccess($checkCreate);
    //    else return $this->responseFail('code exited');
    // }

    public function isLicenseDisable($license){
        return $this->license->where('code', $license)->first()->status;
    }

    public function checkLicense(checkLicenseRequest $request){
        $license = $request->license;
        //return $license;
        $idUser = Auth::user()->id;
        try {
           if($this->isLicenseDisable($license) != License::ACTIVE) return $this->responseFail('License Expire!');
           else{
                $this->user->where('id', $idUser)->update([
                    'active' => user::ACTIVE
                ]);
                $timeActive = $this->expireLicenseService->convertTimeToInteger(Carbon::now());
                //return $timeActive;
                $dataLogActive = $this->licenseCodeRepository->logActive($idUser, $timeActive, $license);
                //return $dataLogActive;
                return $this->responseSuccess($dataLogActive);
           }
           
        } catch (\Throwable $th) {
            Log::debug($th->getMessage());
            return $th->getMessage();
            return $this->responseFail('License not true');
        }
        
    }
    //sua lai
    public function getCodeLicense(){
        if(Auth::user()->active !== User::ACTIVE) {
            if($this->license->where('status',License::ACTIVE)->first() != null)
                return $this->license->first()->code;
            return $this->responseFail("License empty");
        }else{
            return $this->responseFail("User was active!");
        }
        
    }


    public function checkUserActive(){
         $idUser = Auth::user()->id;
         $user = Auth::user();
        if($user->active == user::ACTIVE) {
            return $this->response(200, true, "success", $idUser);
        }
        else {
            return $this->response(200, false, "false", $idUser);
        }
    }

}

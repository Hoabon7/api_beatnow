<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repository\UserBaseRepository;
use App\Service\NormalUserService;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    protected $userBaseRepository;
    protected $userService;
    public function __construct(UserBaseRepository $userBaseRepository,
                                NormalUserService $userService)
    {
        $this->userBaseRepository=$userBaseRepository;
        $this->userService=$userService;
    }
    /**
     * check google hay facebook
     */
    public function checkLogin(Request $request){
        $token=$request->token;
        $provider=$request->provider;
        if($provider==User::FACEBOOK) $url="https://graph.facebook.com/v6.0/me?fields=id,name,email,first_name,middle_name,last_name,birthday,gender,picture&access_token=$token";
        if($provider==User::GOOGLE) $url="https://www.googleapis.com/oauth2/v3/userinfo?access_token=$token";
        $checkToken=true;
        try {
            $response = file_get_contents($url);
            $data=json_decode($response);
        } catch (\Throwable $th) {
            Log::debug($th->getMessage());
            $checkToken=false;
        }
        if($checkToken==false) 
            return response()->json([
                    "success"=>"bad request"
            ],400);
        return $this->login($data,$provider);
    }
    /**
     * login
     */
    public function login($data,$provider){
     
        if(User::GOOGLE==$provider){
            $data=$this->loginWithGoogle($data);
            return $data;
        }
        if(User::FACEBOOK==$provider){
            $data=$this->loginWithFaceBook($data);
            return $data;
        }
    }
   /**
    * login with facebook
    */
   public function loginWithFaceBook($dataUser){
        $user = User::where('provider_id', '=', $dataUser->id)->first();
        if(!isset($user) ){
            $data=$this->userBaseRepository->insertUserFacebook($dataUser);
            return $this->userService->createUserToken($data);
        }
        return $this->userService->createUserToken($user);
        
   }
    /**
     * login with google
     */
   public function loginWithGoogle($dataUser){
        $user = User::where('provider_id', '=', $dataUser->sub)->first();
        if(!isset($user) ){
            $data=$this->userBaseRepository->insertUserGoogle($dataUser);
            return $this->userService->createUserToken($data);
        }
        return $this->userService->createUserToken($user);
        
   }
  /**
   * logout
   */
   public function logout(Request $request)
   {
       $request->user()->token()->revoke();
       return response()->json([
           'message' => 'Successfully logged out'
       ]);
   }
}

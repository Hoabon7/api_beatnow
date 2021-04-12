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
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;

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
     * check login of App,use JWT
     */
    public function checkLoginApple($token,$provider){
        // return ($token);
        //return $provider;
        $checkToken=true;
        $keySet=config('global.KEY_SET');
        try {
            //convert keySet from json to array
            $convertKeySet = json_decode($keySet,true); //READ_FROM_APPLE_KEYSET_URL & CONVERT_TO_ARRAY
            $dataUser=JWT::decode($token, JWK::parseKeySet($convertKeySet), [ 'RS256' ]);
            $user['email']=$dataUser->email;
            $user['id']=$dataUser->sub;

            
        } catch (\Throwable $th) {
            Log::debug($th->getMessage());
            $checkToken=false;
        }
        if($checkToken==false) return $this->responseFail("Token khong duoc truy cap!");
        return $this->login($user,$provider);
    }
    /**
     * check google hay facebook
     */
    public function checkLogin(Request $request){
        $token=$request->token;
        $provider=$request->provider;
        if($this->userService->checkProvider($provider)){
            if($provider==User::FACEBOOK) $url=config('global.URL_FACEBOOK').$token;
            if($provider==User::GOOGLE) $url=config('global.URL_GOOGLE').$token;
            if($provider==User::APPLE) return $this->checkLoginApple($token,$provider);
            $checkToken=true;
            try {
                $response = file_get_contents($url);
                $dataUser=json_decode($response);
            } catch (\Throwable $th) {
                Log::debug($th->getMessage());
                $checkToken=false;
            }
            if($checkToken==false) 
                $this->responseBadRequest("Bad request!");
            return $this->login($dataUser,$provider);
        }else{
            return $this->responseBadRequest("provider khong duoc truy cap!");
        }
    }
    /**
     * login
     */
    public function login($dataUser,$provider){
        try {
            if(User::GOOGLE==$provider){
                $user=$this->loginWithGoogle($dataUser);
                return $user;
            }
            if(User::FACEBOOK==$provider){
                $user=$this->loginWithFaceBook($dataUser);
                return $user;
            }
            if(User::APPLE==$provider){
                $user=$this->loginWithApple($dataUser);
                return $user;
            }
        } catch (\Throwable $th) {
            Log::debug($th->getMessage());
            return $this->responseFail("provider không được phép 2 !");
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
    * login with apple
    */

   public function loginWithApple($dataUser){
        //return $dataUser['email'];
        $user = User::where('provider_id', '=', $dataUser['id'])->first();
        if(!isset($user)){
            $data=$this->userBaseRepository->insertUserApple($dataUser);
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

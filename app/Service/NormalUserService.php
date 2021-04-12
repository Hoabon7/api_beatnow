<?php
namespace App\Service;

use Carbon\Carbon;
use App\Models\User;


class NormalUserService {

    public function createUserToken($dataUser){
        //return $dataUser;
        $tokenResult = $dataUser->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'user_info'=>$dataUser,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
   }
   /**
    * check provider in arrange allow:google,face,apple
    */
   public function checkProvider($provider){
        if($provider==User::FACEBOOK||$provider==User::APPLE||$provider==User::GOOGLE) return true;
        else return false;

   }
}
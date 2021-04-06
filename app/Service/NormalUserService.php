<?php
namespace App\Service;

use Carbon\Carbon;

class NormalUserService{
    public function createUserToken($dataUser){
        $tokenResult = $dataUser->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
   }
}
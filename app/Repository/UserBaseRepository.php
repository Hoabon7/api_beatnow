<?php

namespace App\Repository;

use App\Models\User;
use App\Interfaces\UserQueryInterface;


class UserBaseRepository implements UserQueryInterface{

    /**
     * insert info user to db
     * @param dataUser get from token
     * return user
     */
    public function insertUserFacebook($dataUser){
        $user=new User;
            $user->name=$dataUser->name;
            if(isset($dataUser->email)){
                $user->email=$dataUser->email;
                $user->provider_id=$dataUser->id;
                $user->provider=User::FACEBOOK;
                $user->avatar=$dataUser->picture->data->url;
                $user->save();
            } 
            else {
                $user->provider_id=$dataUser->id;
                $user->provider=User::FACEBOOK;
                $user->avatar=$dataUser->picture->data->url;
                $user->save();
            }
       return $user;
    }
   

    public function insertUserGoogle($dataUser){
        $user=new User;
            $user->name=$dataUser->name;
            $user->email=$dataUser->email;
            $user->provider_id=$dataUser->sub;
            $user->provider=User::GOOGLE;
            $user->avatar=$dataUser->picture;
            $user->save();
        return $user;
    }
    public function insertUserApple($dataUser){
        $user=new User;
            $user->email=$dataUser['email'];
            $user->provider_id=$dataUser['id'];
            $user->provider=User::APPLE;
            $user->save();
        return $user;
    }
    public function getUser($id){

    }
   
}
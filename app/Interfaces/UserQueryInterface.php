<?php
namespace App\Interfaces;

interface UserQueryInterface{

    public function insertUserGoogle( $data);

    public function insertUserFacebook( $data);

    public function insertUserApple( $data);

    public function getUser($id);

    
}


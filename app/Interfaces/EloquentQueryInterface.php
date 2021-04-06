<?php
namespace App\Interfaces;

interface EloquentQueryInterface{

    public function insertUserGoogle($data);

    public function insertUserFacebook($data);

    public function getUser($id);


}


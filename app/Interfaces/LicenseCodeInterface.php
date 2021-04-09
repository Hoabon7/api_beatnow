<?php 

namespace App\Interfaces;


interface LicenseCodeInterface{

    public function createLicense($idUser,$date,$codeLicense);

    public function getUserExpire($idUser);

    public function checkCodeSame($code);

    public function checkCodeExpire($license);

}
<?php

use Carbon\Carbon;
use App\Models\User;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


 Route::get('test_date',function(){

       // echo strtotime(Carbon::now())."<br>";
//        $token="12fdjfdkfdjfd";
//        echo config('global.URL_FACEBOOK').$token;
        // $datatest_reserve=1649929450;
        // //1624983557
        // //1631895557
        // echo date('Y-m-d H:i:s',$datatest_reserve);

        //echo Carbon::now();
        //$date = "2020-11-17 18:47:00";
//     //echo date('Y-m-d H:i:s', strtotime($date. ' + 6 days'));
        //echo strtotime($date);

//     $characters = '123456789ABCDEFGHIJKLMNPQRSTVWXYZ';
//     $charactersLength = strlen($characters);
//     $randomString = '';
//     for ($i = 0; $i < 6; $i++) {
//         $randomString .= $characters[rand(0, $charactersLength - 1)];
//     }
//     $licenseCode=$randomString;
//     return $licenseCode;
 });
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);

// Facebook login
Route::get('login/facebook', [App\Http\Controllers\Auth\LoginController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('login/facebook/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleFacebookCallback']);

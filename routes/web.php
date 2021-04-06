<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;

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


Route::get('test',function(){

    $data1=config('global.KEY_SET');
    //return $data1;
    $kSet = json_decode($data1,true); //READ_FROM_APPLE_KEYSET_URL & CONVERT_TO_ARRAY
    $token = "eyJraWQiOiI4NkQ4OEtmIiwiYWxnIjoiUlMyNTYifQ.eyJpc3MiOiJodHRwczovL2FwcGxlaWQuYXBwbGUuY29tIiwiYXVkIjoiY29tLmJlYXRub3cueW91dHViZW1wMy5pcGhvbmUiLCJleHAiOjE2MTc3NjA3MzAsImlhdCI6MTYxNzY3NDMzMCwic3ViIjoiMDAwODgwLjMwMzMyZjI2MDJjYzRkODE4Mzc0ODQ0MTVmY2JhMWYwLjAxMzkiLCJjX2hhc2giOiIzVGlhV2EyNUFheG9FdGs1aTRvRUhRIiwiZW1haWwiOiJ2YW5ob2FwdDAxQGdtYWlsLmNvbSIsImVtYWlsX3ZlcmlmaWVkIjoidHJ1ZSIsImF1dGhfdGltZSI6MTYxNzY3NDMzMCwibm9uY2Vfc3VwcG9ydGVkIjp0cnVlfQ.LMklUb23sNX3n8fqB6x5dqQSSwWK8D9SPNaC1EXdIMDk7lztKN1G0QYVTPZ_cUUZyKdzOOsfioqZuEyVjsdkNN58paj4mrGuAjP9e5BLCaBgWMaC8J4TmzaOkr1ofJJGhKVbevqHsqgbcKtB_KPh0fAbxOxZjuE3d2S5OE6-lLAh6AzP8Nk7VM16iMFOQlb110hZQQ3B-kHYXf2YAP1vktHdrNX-t-lL2t6d_gn34J8DVEMNEnr88U7YUWOHoDxsimxI51xRXooC4iVnwSdNkKCqTn_edJn2q7RNoyVVFgIrEGUcstealKyexKNATPl9tsgouXxrdRhAS1iNILd8-g";
    $data=JWT::decode($token, JWK::parseKeySet($kSet), [ 'RS256' ]);
    dd($data);
    return $data->email;
    
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);

// Facebook login
Route::get('login/facebook', [App\Http\Controllers\Auth\LoginController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('login/facebook/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleFacebookCallback']);

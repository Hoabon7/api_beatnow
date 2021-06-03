<?php

use App\Http\Controllers\AdminLoginController;
use Carbon\Carbon;
use App\Models\User;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\ManagingLicenseController;

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



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);

// Facebook login
Route::get('login/facebook', [App\Http\Controllers\Auth\LoginController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('login/facebook/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleFacebookCallback']);


//admin login 
Route::group(['prefix' => 'license', 'as' => 'license.', 'middleware' => ['is_admin']], function(){
    Route::get('/all', [ManagingLicenseController::class,'all'])->name('all');
    Route::get('/create', [ManagingLicenseController::class,'create'])->name('create');
    Route::post('/store', [ManagingLicenseController::class,'store'])->name('store');
    Route::get('/{id}/is_active/{status}', [ManagingLicenseController::class,'isActive'])->name('is_active');
});


// Route::get('/login', [AdminLoginController::class, 'login'])->name('login');
// Route::post('/login', [AdminLoginController::class, 'checkLogin'])->name('login.check');
// Route::get('/logout', [AdminLoginController::class, 'logout'])->name('logout');



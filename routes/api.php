<?php

use App\Http\Controllers\Api\LicenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PlaylistController;
use App\Http\Controllers\Api\SongController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'mobile'],function(){
    Route::post('login',[LoginController::class,'checkLogin']);

    Route::group(['middleware'=>['auth:api']],function(){
        //playlist
        Route::group(['prefix'=>'playlists','as'=>'playlists.'],function(){
            Route::get('/',[PlaylistController::class,'getAllPlayList'])->name('getall');//xong
            Route::post('/update',[PlaylistController::class,'createPlayList'])->name('create');//xong
            Route::delete('/{id}/delete',[PlaylistController::class,'deletePlayList'])->name('delete');//xong
            //Route::get('/{id}',[PlaylistController::class,'getInfoPlayList'])->name('info');//xong
            //Route::post('/{id}/edit',[PlaylistController::class,'editPlayList'])->name('edit');//xong
           
            // Route::get('/{id}/songs',[PlaylistController::class,'getAllSong'])->name('getAll');//xong
            // Route::post('/{id}/songs/create',[PlaylistController::class,'createSong'])->name('create_song');
        });
        //song
        Route::group(['prefix'=>'songs','as'=>'songs.'],function(){
            Route::delete('/{id}/delete',[SongController::class,'deleteSong'])->name('delete');//xong
        });

        //Route::post('license/update',[LicenseController::class,'createCodeLicense'])->name('createcode');
        Route::get('license/getcode',[LicenseController::class,'getCodeLicense'])->name('getcode');
        Route::post('license/active',[LicenseController::class,'checkLicense'])->name('checklicense');
        Route::get('license/check-active-user',[LicenseController::class,'checkUserActive']);
        
       
    });
    
});

Route::get('get',[LicenseController::class,'getLicenseActive']);





<?php

use App\Http\Controllers\CctvController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function (){

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // data user
    Route::resource('data-user', UserController::class);

    Route::resource('data-kecamatan', KecamatanController::class);

    // data cctv
    Route::resource('/data-cctv', CctvController::class);
    Route::get('/peta-cctv', [CctvController::class, 'showMap'])->name('data-cctv.peta');
    Route::post('/cctv/{id}/upload-fotos', [CctvController::class, 'uploadPhotoDetail'])->name('data-cctv.uploadFotos');
    Route::post('/cctv/status/{id}', [CctvController::class, 'storeStatusCctv'])->name('data-cctv.storeStatus');
    
});

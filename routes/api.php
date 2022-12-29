<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;// manggil controller sesuai bawaan laravel 8
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\DetailPesananController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\ChatController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('/password/email', 'AuthController@sendPasswordResetLinkEmail')->middleware('throttle:5,1')->name('password.email');
Route::get('/password/reset', 'AuthController@resetPassword')->name('password.reset');
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('profile', function(Request $request) {
        return response()->json(['status' =>'success','msg' => 'succes fetch data','content' => auth()->user(),]);
    });
    Route::resource('produk', \ProdukController::class);
    Route::resource('pesanan', \PesananController::class);
    Route::resource('detail-pesanan', \DetailPesananController::class);
    Route::resource('kategori-produk', \KategoriProdukController::class);
    Route::resource('chat', \ChatController::class);
    // manggil controller sesuai bawaan laravel 8
    Route::post('logout', [AuthController::class, 'logout']);
    // manggil controller dengan mengubah namespace di RouteServiceProvider.php biar bisa kayak versi2 sebelumnya
    Route::post('logoutall', 'AuthController@logoutall');

});